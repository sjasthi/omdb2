<?php
require_once __DIR__ . '/database.php';

function catalog_text(array $input, string $key, int $limit, bool $required = false): string {
    $value = $input[$key] ?? '';
    $label = preg_replace('/\d+$/','',str_replace('_',' ', $key));
    $label = ['native name'=>'native title','english name'=>'English title','title'=>'song title'][$label] ?? $label;
    if (!is_string($value) || !mb_check_encoding($value, 'UTF-8')) { throw new InvalidArgumentException('Enter plain text for the ' . $label . '.'); }
    $value = trim($value);
    if ($required && $value === '') { throw new InvalidArgumentException('Enter the ' . $label . '.'); }
    if (mb_strlen($value, 'UTF-8') > $limit) { throw new InvalidArgumentException(ucfirst($label) . ' must be ' . $limit . ' characters or fewer.'); }
    return $value;
}
function catalog_id($value): int {
    $id = filter_var($value, FILTER_VALIDATE_INT);
    if (!$id || $id < 1) { throw new InvalidArgumentException('Select a valid record.'); }
    return $id;
}

// Call inside db_transaction(): the movie and its derived numbers are one operation.
function catalog_insert_movie(mysqli $db, array $input): int {
    $native = catalog_text($input, 'native_name', 180, true);
    $english = catalog_text($input, 'english_name', 180, true);
    $year = filter_var($input['year'] ?? '', FILTER_VALIDATE_INT);
    if (!$year || $year < 1901 || $year > min(2155, (int)date('Y') + 10)) {
        throw new InvalidArgumentException('Enter a release year from 1901 to ' . min(2155, (int)date('Y') + 10) . '.');
    }
    $id = db_insert($db, 'INSERT INTO movies (native_name, english_name, year_made) VALUES (?, ?, ?)', [$native, $english, $year]);
    // Graphemes keep Telugu combining marks together and make imports work offline.
    preg_match_all('/\X/u', preg_replace('/\s+/u', '', mb_strtolower($native, 'UTF-8')), $characters);
    db_query($db, 'INSERT INTO movie_numbers (movie_id, length, base_chars) VALUES (?, ?, ?)',
        [$id, count($characters[0]), implode(', ', $characters[0])]);
    return $id;
}
function catalog_insert_person(mysqli $db, array $input): int {
    $values = [];
    foreach (['stage_name'=>30, 'first_name'=>20, 'middle_name'=>20, 'last_name'=>20, 'gender'=>6, 'image_name'=>30] as $key=>$limit) {
        $values[] = catalog_text($input, $key, $limit, $key === 'stage_name');
    }
    return db_insert($db, 'INSERT INTO people (stage_name, first_name, middle_name, last_name, gender, image_name) VALUES (?, ?, ?, ?, ?, ?)', $values);
}
function catalog_insert_song(mysqli $db, array $input): int {
    return db_insert($db, 'INSERT INTO songs (title, lyrics, theme) VALUES (?, ?, ?)', [
        catalog_text($input, 'title', 20, true), catalog_text($input, 'lyrics', 500), catalog_text($input, 'theme', 20)]);
}

// Stream CSV records (including quoted newlines); either every new record commits or none do.
function catalog_import_csv(mysqli $db, string $table, $handle): array {
    if (!in_array($table, ['movies', 'people', 'songs'], true) || !is_resource($handle)) {
        throw new InvalidArgumentException('Select a valid CSV import.');
    }
    return db_transaction($db, function () use ($db, $table, $handle): array {
        $counts = ['imported'=>0, 'skipped'=>0];
        $rowNumber = 0;
        while (($row = fgetcsv($handle)) !== false) {
            if ($row === [null]) { continue; }
            if (++$rowNumber > 10000) { throw new InvalidArgumentException('Upload at most 10,000 records.'); }
            if ($rowNumber === 1) { $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', $row[0]); }
            if (count($row) !== ($table === 'people' ? 5 : 3)) {
                throw new InvalidArgumentException('CSV column count is incorrect at record ' . $rowNumber . '. No records were imported.');
            }
            if ($table === 'movies') {
                // Retain the existing importer policy: skip an existing native title/year.
                $exists = db_query($db, 'SELECT movie_id FROM movies WHERE native_name = ? AND year_made = ? LIMIT 1', [trim($row[0]), trim($row[2])])->num_rows;
                if ($exists) { $counts['skipped']++; continue; }
                catalog_insert_movie($db, ['native_name'=>$row[0], 'english_name'=>$row[1], 'year'=>$row[2]]);
            } elseif ($table === 'people') {
                catalog_insert_person($db, array_combine(['stage_name','first_name','middle_name','last_name','gender'], $row));
            } else {
                catalog_insert_song($db, array_combine(['title','lyrics','theme'], $row));
            }
            $counts['imported']++;
        }
        if (!feof($handle)) { throw new RuntimeException('CSV could not be read.'); }
        return $counts;
    });
}

// Match selected row suffixes rather than pairing independent arrays by position.
function catalog_relationships(mysqli $db, string $kind, int $parent, array $input): void {
    $specs = [
        'movie_people'=>['movie_id', 'people_id', ['role'=>20, 'screen_name'=>30]],
        'song_people'=>['song_id', 'people_id', ['role'=>15]],
        'movie_song'=>['movie_id', 'song_id', []]
    ];
    if (!isset($specs[$kind])) { throw new InvalidArgumentException('Invalid relationship.'); }
    [$parentColumn, $childColumn, $fields] = $specs[$kind];
    db_transaction($db, function () use ($db, $kind, $parent, $input, $parentColumn, $childColumn, $fields): void {
        foreach ($input as $key=>$value) {
            if (!preg_match('/^' . $childColumn . '(\d+)$/D', $key, $match)) { continue; }
            $columns = [$parentColumn, $childColumn];
            $values = [$parent, catalog_id($value)];
            foreach ($fields as $field=>$limit) {
                $columns[] = $field;
                $values[] = catalog_text($input, $field . $match[1], $limit, $field === 'role');
            }
            if (isset($input['delete'])) {
                $where = implode(' AND ', array_map(function ($c) { return "`$c` = ?"; }, $columns));
                db_query($db, "DELETE FROM `$kind` WHERE $where", $values);
            } elseif (isset($input['add'])) {
                $names = implode('`,`', $columns);
                $placeholders = implode(',', array_fill(0, count($values), '?'));
                // Only a duplicate key is a no-op; bad FKs/invalid data must fail the batch.
                db_query($db, "INSERT INTO `$kind` (`$names`) VALUES ($placeholders) ON DUPLICATE KEY UPDATE `$parentColumn` = VALUES(`$parentColumn`)", $values);
            }
        }
    });
}

function catalog_update_person(mysqli $db, array $input): void {
    $id = catalog_id($input['people_id'] ?? $input['id'] ?? null);
    db_query($db, 'UPDATE people SET stage_name=?, first_name=?, middle_name=?, last_name=?, gender=? WHERE people_id=?', [
        catalog_text($input,'stage_name',30,true), catalog_text($input,'first_name',20),
        catalog_text($input,'middle_name',20), catalog_text($input,'last_name',20), catalog_text($input,'gender',6), $id]);
    if (isset($input['image_name'])) { db_query($db, 'UPDATE people SET image_name=? WHERE people_id=?', [catalog_text($input,'image_name',30),$id]); }
    catalog_update_children($db, 'people', $id, $input);
    foreach ($input as $key=>$value) {
        if (preg_match('/^movie_id(\d+)$/D',$key,$m)) {
            $i=$m[1];
            db_query($db, 'UPDATE movie_people SET role=?, screen_name=? WHERE people_id=? AND movie_id=? AND role=? AND screen_name=?', [
                catalog_text($input,'role_update'.$i,20,true),catalog_text($input,'screen_name_update'.$i,30),$id,catalog_id($value),
                catalog_text($input,'original_role'.$i,20,true),catalog_text($input,'original_screen_name'.$i,30)]);
        } elseif (preg_match('/^song_id(\d+)$/D',$key,$m)) {
            $i=$m[1];
            db_query($db,'UPDATE song_people SET role=? WHERE people_id=? AND song_id=? AND role=?',[
                catalog_text($input,'song_role_update'.$i,15,true),$id,catalog_id($value),catalog_text($input,'original_song_role'.$i,15,true)]);
        }
    }
}
function catalog_update_song(mysqli $db, array $input): void {
    $id=catalog_id($input['song_id'] ?? null);
    db_query($db,'UPDATE songs SET title=?, lyrics=?, theme=? WHERE song_id=?',[
        catalog_text($input,'title',20,true),catalog_text($input,'lyrics',500),catalog_text($input,'theme',20),$id]);
    catalog_update_children($db,'song',$id,$input);
}
// Child IDs and original keyword values identify individual rows within their parent.
function catalog_update_children(mysqli $db, string $kind, int $id, array $input): void {
    $specs = [
        'movie'=>[
            ['movie_anagrams','anagram_id',['anagram'=>'anagram_update'],[180]],
            ['movie_trivia','movie_trivia_id',['movie_trivia_name'=>'movie_trivia_name'],[300]],
            ['movie_quotes','movie_quote_id',['movie_quote_name'=>'movie_quote_name'],[300]],
            ['movie_media','movie_media_id',['m_link'=>'m_link_update','m_link_type'=>'m_link_type_update'],[100,10]]
        ],
        'people'=>[['people_trivia','people_trivia_id',['people_trivia_name'=>'people_trivia_name_update'],[300]]],
        'song'=>[
            ['song_trivia','song_trivia_id',['song_trivia_name'=>'song_trivia_name_update'],[300]],
            ['song_media','song_media_id',['s_link'=>'s_link_update','s_link_type'=>'s_link_type_update'],[100,10]]
        ]
    ];
    if (!isset($specs[$kind])) { throw new InvalidArgumentException('Invalid child type.'); }
    $parent = $kind === 'people' ? 'people_id' : $kind . '_id';
    foreach ($specs[$kind] as [$table,$pk,$fields,$limits]) {
        foreach ($input as $key=>$value) {
            if (!preg_match('/^'.$pk.'(\d+)$/D',$key,$match)) { continue; }
            $sets=[]; $values=[]; $index=0;
            foreach ($fields as $column=>$prefix) {
                $sets[]="`$column`=?";
                $values[]=catalog_text($input,$prefix.$match[1],$limits[$index++]);
            }
            $values[]=$id; $values[]=catalog_id($value);
            db_query($db,"UPDATE `$table` SET ".implode(',',$sets)." WHERE `$parent`=? AND `$pk`=?",$values);
        }
    }
    if ($kind === 'movie' || $kind === 'song') {
        $prefix=$kind === 'movie' ? 'keyword_update' : 'song_keyword_update';
        foreach ($input as $key=>$value) {
            if (preg_match('/^'.$prefix.'(\d+)$/D',$key,$match)) {
                db_query($db,"UPDATE `{$kind}_keywords` SET keyword=? WHERE `$parent`=? AND keyword=?",[
                    catalog_text($input,$key,15,true),$id,catalog_text($input,'original_keyword'.$match[1],15,true)]);
            }
        }
    }
}
function catalog_update_movie(mysqli $db, array $input): void {
    $id=catalog_id($input['movie_id'] ?? null);
    $year=filter_var($input['year_update'] ?? '',FILTER_VALIDATE_INT);
    if (!$year || $year<1901 || $year>min(2155,(int)date('Y')+10)) { throw new InvalidArgumentException('Enter a release year from 1901 to ' . min(2155,(int)date('Y')+10) . '.'); }
    db_query($db,'UPDATE movies SET english_name=?,year_made=? WHERE movie_id=?',[
        catalog_text($input,'english_name_update',180,true),$year,$id]);
    if (isset($input['native_name_update'])) {
        $native=catalog_text($input,'native_name_update',180,true);
        db_query($db,'UPDATE movies SET native_name=? WHERE movie_id=?',[$native,$id]);
        preg_match_all('/\X/u',preg_replace('/\s+/u','',mb_strtolower($native,'UTF-8')),$characters);
        db_query($db,'INSERT INTO movie_numbers (movie_id,length,base_chars) VALUES (?,?,?) ON DUPLICATE KEY UPDATE length=VALUES(length),base_chars=VALUES(base_chars)',[$id,count($characters[0]),implode(', ',$characters[0])]);
    }
    if (isset($input['language_update'],$input['country_update'],$input['genre_update'],$input['plot_update'],$input['tag_line_update'])) {
        $values=[$id];
        foreach (['language'=>10,'country'=>20,'genre'=>15,'plot'=>500,'tag_line'=>100] as $key=>$limit) { $values[]=catalog_text($input,$key.'_update',$limit); }
        db_query($db,'INSERT INTO movie_data (movie_id,language,country,genre,plot,tag_line) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE language=VALUES(language),country=VALUES(country),genre=VALUES(genre),plot=VALUES(plot),tag_line=VALUES(tag_line)',$values);
    }
    if (isset($input['box_office_update'],$input['budget_update'],$input['running_time_update'])) {
        $values=[];
        foreach (['box_office_update','budget_update','running_time_update'] as $key) {
            $v=$input[$key] === '' ? null : filter_var($input[$key],FILTER_VALIDATE_INT);
            if ($v === false || ($v !== null && ($v<0 || $v>2147483647))) { throw new InvalidArgumentException('Enter valid nonnegative whole numbers.'); }
            $values[]=$v;
        }
        db_query($db,'UPDATE movie_numbers SET box_office=?,budget=?,running_time=? WHERE movie_id=?',array_merge($values,[$id]));
    }
    catalog_update_children($db,'movie',$id,$input);
}
