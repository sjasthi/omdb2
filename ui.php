<?php
require_once __DIR__ . '/functions.php';

function ui_page_info(): array {
    $pages = [
        'index.php'=>['Welcome to OMDB','Explore the movies, people, and songs in your catalog.'],
        'movies.php'=>['Movies','Browse the catalog, filter the table, or open a movie for its full credits.'],
        'people.php'=>['People','Find cast members, directors, and other contributors.'],
        'songs.php'=>['Songs','Browse song titles, lyrics, themes, and credits.'],
        'advance_movie_search.php'=>['Find movies','Combine cast, director, and title filters to narrow your results.'],
        'search.php'=>['Search results','Results from the movies, people, and songs stored in OMDB.'],
        'search_data.php'=>['Search the catalog','Choose a search category and enter the details you know.'],
        'advance_people_search.php'=>['Find people','Search the people and credits stored in OMDB.'],
        'advance_song_search.php'=>['Find songs','Search songs by title or contributor.'],
        'help.php'=>['Help & getting started','A guide to browsing, searching, and managing your catalog.'],
        'about.php'=>['About OMDB','A shared catalog of movies, people, songs, and the connections between them.'],
        'login.php'=>['Sign in','Use the account provided by your administrator.'],
        'log_out.php'=>['Sign out','End your current OMDB session.'],
        'setup.php'=>['Import data','Add records from a CSV file. Imports are available to administrators.'],
        'sign_up.php'=>['Create user account','Create a regular member account. Administrator access is managed separately.'],
        'create_movie.php'=>['Create movie','Enter the titles and release year. Add credits and other details after saving.'],
        'createTheMovie.php'=>['Create movie','Review your details and try saving again.'],
        'create_people.php'=>['Create person','A stage name is required. Other personal details are optional.'],
        'create_song.php'=>['Create song','Enter a title, then add optional lyrics and a theme.'],
        'modify.php'=>['Edit movie','Update the movie and its related information.'],
        'modify_movie.php'=>['Edit movie','Update the movie information.'],
        'modify_people.php'=>['Edit person','Update the person’s name and profile details.'],
        'post_modify_people.php'=>['Edit person','Review your details and try saving again.'],
        'modify_song.php'=>['Edit song','Update the title, lyrics, and theme.'],
        'create_Data.php'=>['Add movie details','Add optional plot, media, keywords, trivia, and other information.'],
        'add_people.php'=>['Manage movie credits','Choose people and their roles for the selected movie.'],
        'add_song.php'=>['Manage movie songs','Attach songs from the library or remove an existing connection.'],
        'songs_people_list.php'=>['Manage song credits','Choose people and their roles for the selected song.'],
        'add_movie_posters.php'=>['Upload posters','Select image files for movies in the catalog.'],
        'delete_movie.php'=>['Delete movie','Review the record before confirming its deletion.'],
        'delete_people.php'=>['Delete person','Review the record before confirming its deletion.'],
        'delete_song.php'=>['Delete song','Review the record before confirming its deletion.'],
        'movie_info.php'=>['Movie details','Titles, credits, songs, and additional movie information.'],
        'people_info.php'=>['Person details','Profile information and the person’s movie and song credits.'],
        'song_info.php'=>['Song details','Song information and its connections to movies and people.'],
        'movie_more_info.php'=>['Movie information','Explore the selected movie’s related records.'],
        'people_more_info.php'=>['Person information','Explore the selected person’s related records.'],
        'songs_more_info.php'=>['Song information','Explore the selected song’s related records.'],
        'movies_data.php'=>['Movie data','Additional details recorded for movies in the catalog.'],
        'movies_keywords.php'=>['Movie keywords','Browse keywords associated with movies.'],
        'movies_media.php'=>['Movie media','Browse media associated with movies.'],
        'movies_numbers.php'=>['Movie numbers','Browse running times, budgets, and title statistics.'],
        'movies_quotes.php'=>['Movie quotes','Browse quotations recorded for movies.'],
        'movies_trivia.php'=>['Movie trivia','Browse trivia recorded for movies.'],
        'movies_people.php'=>['Movie credits','People and their roles in movies.'],
        'movies_songs.php'=>['Movie songs','Songs connected to movies.'],
        'people_movies.php'=>['People & movies','Movie credits for the selected person.'],
        'people_songs.php'=>['People & songs','Song credits for the selected person.'],
        'people_trivia.php'=>['People trivia','Browse trivia recorded for people.'],
        'songs_keyword.php'=>['Song keywords','Browse keywords associated with songs.'],
        'songs_media.php'=>['Song media','Browse media associated with songs.'],
        'songs_trivia.php'=>['Song trivia','Browse trivia and open the related song.'],
        'songs_movie_list.php'=>['Songs & movies','Movies connected to the selected song.'],
        'reports.php'=>['Movies by year','Choose a year to browse its movies.'],
        'reports_actors.php'=>['Actor report','Explore movies associated with a cast member.'],
        'reports_actors_actresses.php'=>['Shared cast report','Explore movies associated with two cast members.'],
        'reports_lyricist.php'=>['Lyricist report','Explore movies and songs associated with a lyricist.'],
        'reports_lyricists.php'=>['Lyricist report','Explore movies and songs associated with a lyricist.'],
        'query_movies.php'=>['Movies by person','Find movie credits for a person in the catalog.'],
        'query_songs.php'=>['Songs by person','Find song credits for a person in the catalog.'],
        'games.php'=>['Movie games','Explore word games based on titles stored in OMDB.'],
        'games_base_chars_user.php'=>['Base characters','Find movies using characters from their titles.'],
        'games_base_chars_admin.php'=>['Manage base characters','Review and update the stored title characters.'],
        'movies_anagram.php'=>['Manage anagrams','Review anagrams stored for movie titles.'],
        'createTheAnagram.php'=>['Add anagram','Create an anagram for a movie title.'],
        'anagrammer.php'=>['Anagram game','Explore anagrams of movie titles.'],
        'movie_in_movies.php'=>['Movie in movies','Guess the movie hidden in another title.']
    ];
    $script = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
    return $pages[$script] ?? [ucwords(str_replace('_',' ',basename($script,'.php'))), 'Explore your OMDB catalog.'];
}

function ui_page_header(): void {
    [$title,$description] = ui_page_info(); ?>
    <header class="page-heading"><h1><?php echo h($title); ?></h1><p><?php echo h($description); ?></p></header>
<?php }

function ui_notice(string $message, string $type = 'info'): string {
    $type = in_array($type,['success','error','info'],true) ? $type : 'info';
    return '<div class="notice notice--'.$type.'" role="'.($type === 'error' ? 'alert' : 'status').'">'.h($message).'</div>';
}

function ui_feedback(): void {
    $notice = $_SESSION['ui_notice'] ?? null; unset($_SESSION['ui_notice']);
    if (is_array($notice)) { echo ui_notice($notice['message'],$notice['type']); return; }
    $legacy = show_flash_message();
    if (is_string($legacy) && $legacy !== '') { echo ui_notice($legacy); return; }
    foreach (['create'=>'Record created.','updated'=>'Changes saved.','delete'=>'Record deleted.'] as $key=>$message) {
        if (($_GET[$key] ?? '') === 'Success') { echo ui_notice($message,'success'); return; }
        if (($_GET[$key] ?? '') === 'Failed') { echo ui_notice('Your changes could not be saved. Please review the details and try again.','error'); return; }
    }
}

function ui_set_notice(string $message, string $type = 'success'): void {
    $_SESSION['ui_notice'] = ['message'=>$message,'type'=>$type];
}

function ui_section_nav(string $section, string $selected): void {
    $sections = [
        'MOVIES'=>[['MOVIES','movies.php','All movies'],['DATA','movies_data.php','Movie data'],['KEYWORD','movies_keywords.php','Keywords'],['MEDIA','movies_media.php','Media'],['NUMBERS','movies_numbers.php','Numbers'],['QUOTES','movies_quotes.php','Quotes'],['TRIVIA','movies_trivia.php','Trivia'],['SEARCH','advance_movie_search.php','Find movies'],['ANAGRAM','movies_anagram.php','Manage anagrams',true]],
        'PEOPLE'=>[['PEOPLE','people.php','All people'],['TRIVIA','people_trivia.php','Trivia']],
        'SONGS'=>[['SONGS','songs.php','All songs'],['TRIVIA','songs_trivia.php','Trivia'],['KEYWORD','songs_keyword.php','Keywords'],['MEDIA','songs_media.php','Media'],['SEARCH','query_songs.php','Find song credits']],
        'REPORTS'=>[['REPORTS','reports.php','Movies by year'],['ACTORS','reports_actors.php','Actor report'],['ACTORS/ACTRESSES','reports_actors_actresses.php','Shared cast'],['LYRICIST','reports_lyricist.php','Lyricist report']],
        'GAMES'=>[['BASECHARSUSER','games_base_chars_user.php','Base characters'],['ANAGRAMUSER','anagrammer.php','Anagrams'],['MOVIE_IN_MOVIES','movie_in_movies.php','Movie in movies'],['BASECHARSADMIN','games_base_chars_admin.php','Manage characters',true],['ANAGRAMADMIN','movies_anagram.php','Manage anagrams',true]],
        'ADMIN'=>[['IMPORT','setup.php','Import data'],['ACCOUNTS','sign_up.php','Create user account'],['POSTERS','add_movie_posters.php','Upload posters'],['ANAGRAM','movies_anagram.php','Manage anagrams'],['BASECHARSADMIN','games_base_chars_admin.php','Manage characters']],
        'SEARCH'=>[['MOVIES','advance_movie_search.php','Movies'],['SONGS','search_data.php?type=songs','Songs'],['PEOPLE','search_data.php?type=people','People']]
    ];
    if ($section === 'ADMIN' && !is_admin()) { return; } ?>
    <nav id="menu-left" aria-label="<?php echo h(ucfirst(strtolower($section))); ?> navigation">
    <?php foreach ($sections[$section] ?? [] as $entry) {
        [$key,$href,$label] = $entry;
        if (($entry[3] ?? false) && !is_admin()) { continue; } ?>
        <a href="<?php echo h($href); ?>"<?php echo $key === $selected ? ' aria-current="page" class="menu-left-current-page"' : ''; ?>><?php echo h($label); ?></a>
    <?php } ?>
    </nav>
<?php }

function ui_field_value(array $values, string $key): string {
    return isset($values[$key]) && is_string($values[$key]) ? $values[$key] : '';
}

function ui_entity_form(string $kind, string $action, array $values, array $errors = [], bool $editing = false): void {
    $fields = [
        'movie'=>[['native_name','Native title',180,true],['english_name','English title',180,true],['year','Release year',4,true,'number']],
        'person'=>[['stage_name','Stage name',30,true],['first_name','First name',20,false],['middle_name','Middle name',20,false],['last_name','Last name',20,false]],
        'song'=>[['title','Song title',20,true],['lyrics','Lyrics',500,false,'textarea'],['theme','Theme',20,false]]
    ];
    $cancel = ['movie'=>'movies.php','person'=>'people.php','song'=>'songs.php'][$kind];
    echo display_errors($errors); ?>
    <form class="form-panel" method="post" action="<?php echo h($action); ?>">
        <?php echo csrf_field(); ?>
        <?php if ($editing) { $idKey = $kind === 'person' ? 'id' : 'song_id'; ?>
            <input type="hidden" name="<?php echo h($idKey); ?>" value="<?php echo h($values[$idKey]); ?>">
        <?php } ?>
        <p class="form-help">Fields marked <span aria-hidden="true">*</span> are required.</p>
        <?php foreach ($fields[$kind] as $field) { [$key,$label,$limit,$required] = $field; $type=$field[4] ?? 'text'; ?>
            <div class="form-group"><label for="<?php echo h($key); ?>"><?php echo h($label); ?><?php echo $required ? ' <span aria-hidden="true">*</span>' : ' (optional)'; ?></label>
            <?php if ($type === 'textarea') { ?>
                <textarea class="form-control" id="<?php echo h($key); ?>" name="<?php echo h($key); ?>" maxlength="<?php echo h($limit); ?>" rows="5" aria-describedby="<?php echo h($key); ?>-hint"><?php echo h(ui_field_value($values,$key)); ?></textarea>
            <?php } else { ?>
                <input class="form-control" id="<?php echo h($key); ?>" name="<?php echo h($key); ?>" type="<?php echo h($type); ?>"
                    <?php echo $type === 'number' ? 'min="1901" max="'.h(min(2155,(int)date('Y')+10)).'" step="1"' : 'maxlength="'.h($limit).'"'; ?>
                    value="<?php echo h(ui_field_value($values,$key)); ?>"<?php echo $required ? ' required' : ''; ?> aria-describedby="<?php echo h($key); ?>-hint">
            <?php } ?>
                <small class="form-help" id="<?php echo h($key); ?>-hint"><?php echo $type === 'number' ? 'A whole year from 1901 to '.h(min(2155,(int)date('Y')+10)).'.' : 'Up to '.h($limit).' characters.'; ?></small>
            </div>
        <?php } ?>
        <?php if ($kind === 'person') { ?>
            <div class="form-group"><label for="gender">Gender (optional)</label><select class="form-control" id="gender" name="gender">
                <?php $currentGender=ui_field_value($values,'gender'); if (!in_array($currentGender,['','male','female'],true)) { ?><option value="<?php echo h($currentGender); ?>" selected><?php echo h($currentGender); ?></option><?php } ?>
                <?php foreach ([''=>'Not specified','male'=>'Male','female'=>'Female'] as $value=>$label) { ?>
                    <option value="<?php echo h($value); ?>"<?php echo ui_field_value($values,'gender') === $value ? ' selected' : ''; ?>><?php echo h($label); ?></option>
                <?php } ?>
            </select></div>
        <?php } ?>
        <div class="form-actions"><button class="btn btn-primary" name="submit" type="submit"><?php echo $editing ? 'Save changes' : 'Create '.h($kind); ?></button><a class="btn btn-default" href="<?php echo h($cancel); ?>">Cancel</a></div>
    </form>
<?php }
