<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

// These keys and role aliases are application-owned; request values are always bound.
function movie_search_fields(): array {
    return [
        'movie_search' => 'Movie title', 'cast_member' => 'Cast member', 'director' => 'Director',
        'lead_actor' => 'Lead actor', 'lead_actress' => 'Lead actress',
        'producer' => 'Producer', 'music_director' => 'Music director'
    ];
}

function movie_search_value(array $input, string $key, int $limit = 180): string {
    $value = $input[$key] ?? '';
    if (!is_string($value) || !preg_match('/^.{0,' . $limit . '}$/us', $value)) {
        throw new InvalidArgumentException('Enter plain text within the search field length limit.');
    }
    return trim(preg_replace('/\s+/u', ' ', $value));
}

function movie_search_filters(array $input): array {
    $filters = [];
    foreach (movie_search_fields() as $key => $label) {
        $filters[$key] = movie_search_value($input, $key);
    }
    return $filters;
}

// Recognize a small, predictable phrase grammar. Ordinary title searches stay unchanged.
function movie_search_phrase(string $text): ?array {
    $prefix = '(?:(?:find|show(?: me)?)\s+)?(?:movies|films)\s+';
    $cast = '(?:with|starring|featuring|that (?:have|feature))\s+';
    $join = '\s+(?:and\s+)?(?:(?:is|are|was|were)\s+)?';
    if (preg_match('/^' . $prefix . $cast . '(.+?)' . $join . 'directed by\s+(.+?)\s*[?.]?$/iu', $text, $m)) {
        $names = [$m[1], $m[2]];
    } elseif (preg_match('/^' . $prefix . 'directed by\s+(.+?)\s+(?:and\s+)?' . $cast . '(.+?)\s*[?.]?$/iu', $text, $m)) {
        $names = [$m[2], $m[1]];
    } else {
        return null;
    }
    return movie_search_filters(['cast_member' => trim($names[0], " \t\n\r\0\x0B\""),
        'director' => trim($names[1], " \t\n\r\0\x0B\"")]);
}

function movie_search_url(array $filters, int $page = 1): string {
    $params = array_filter($filters, static function ($value) { return $value !== ''; });
    if ($page > 1) { $params['page'] = $page; }
    return 'advance_movie_search.php' . ($params ? '?' . http_build_query($params) : '');
}

function movie_search_like(string $value): string {
    return '%' . strtr($value, ['!' => '!!', '%' => '!%', '_' => '!_']) . '%';
}

function movie_search_where(array $filters): array {
    $clauses = []; $params = [];
    $roles = [
        'cast_member' => ['actor', 'actress', 'cast', 'lead actor', 'lead actress', 'leading actor',
            'leading actress', 'supporting actor', 'supporting actress', 'voice actor', 'voice actress', 'cameo'],
        'lead_actor' => ['lead actor', 'leading actor'], 'lead_actress' => ['lead actress', 'leading actress'],
        'director' => ['director'], 'producer' => ['producer', 'executive producer', 'co producer', 'co-producer'],
        'music_director' => ['music director']
    ];
    foreach (movie_search_fields() as $key => $label) {
        $value = $filters[$key] ?? '';
        if ($value === '') { continue; }
        $like = movie_search_like($value);
        if ($key === 'movie_search') {
            $clauses[] = "(m.native_name LIKE ? ESCAPE '!' OR m.english_name LIKE ? ESCAPE '!')";
            array_push($params, $like, $like);
            continue;
        }
        $role = "LOWER(REPLACE(TRIM(mp.role), '_', ' '))";
        $roleSql = $role . ' IN (' . implode(',', array_fill(0, count($roles[$key]), '?')) . ')';
        // Legacy imports sometimes combine directing with another credit in this column.
        if ($key === 'director') {
            $roleSql = "($roleSql OR $role LIKE 'director and %' OR $role LIKE 'director,%')";
        }
        // Separate EXISTS clauses allow the actor and director to be different people.
        $clauses[] = "EXISTS (SELECT 1 FROM movie_people mp JOIN people p ON p.people_id = mp.people_id
            WHERE mp.movie_id = m.movie_id AND $roleSql AND (
                p.stage_name LIKE ? ESCAPE '!'
                OR CONCAT_WS(' ', NULLIF(TRIM(p.first_name), ''), NULLIF(TRIM(p.middle_name), ''),
                    NULLIF(TRIM(p.last_name), '')) LIKE ? ESCAPE '!'))";
        array_push($params, ...$roles[$key]);
        array_push($params, $like, $like);
    }
    return ['sql' => $clauses ? implode(' AND ', $clauses) : '1 = 1', 'params' => $params];
}

function movie_search_results(mysqli $db, array $filters, int $page = 1): array {
    $where = movie_search_where($filters);
    $count = (int)db_query($db, 'SELECT COUNT(*) FROM movies m WHERE ' . $where['sql'], $where['params'])->fetch_row()[0];
    $pages = max(1, (int)ceil($count / 50));
    $page = max(1, min($page, $pages));
    $params = array_merge($where['params'], [50, ($page - 1) * 50]);
    $rows = db_query($db, 'SELECT m.movie_id, m.native_name, m.english_name, m.year_made FROM movies m WHERE '
        . $where['sql'] . ' ORDER BY m.year_made DESC, m.english_name, m.native_name, m.movie_id LIMIT ? OFFSET ?', $params)->fetch_all(MYSQLI_ASSOC);
    return ['count' => $count, 'pages' => $pages, 'page' => $page, 'rows' => $rows];
}

function movie_search_form(array $filters): void {
    $fields = movie_search_fields(); ?>
    <form class="movie-search-form" method="get" action="advance_movie_search.php" aria-label="Movie filters">
        <div class="movie-search-fields">
        <?php foreach (['cast_member', 'director', 'movie_search'] as $key) { ?>
            <div class="form-group">
                <label for="<?php echo h($key); ?>"><?php echo h($fields[$key]); ?><?php echo $key === 'movie_search' ? ' (optional)' : ''; ?></label>
                <input class="form-control" type="search" id="<?php echo h($key); ?>" name="<?php echo h($key); ?>" maxlength="180"
                    value="<?php echo h($filters[$key]); ?>" placeholder="<?php echo h(['cast_member' => 'e.g. Brad Pitt', 'director' => 'e.g. Steven Spielberg', 'movie_search' => 'Native or English title'][$key]); ?>">
            </div>
        <?php } ?>
        </div>
        <details<?php echo array_filter(array_intersect_key($filters, array_flip(['lead_actor', 'lead_actress', 'producer', 'music_director']))) ? ' open' : ''; ?>>
            <summary>More credit filters</summary>
            <div class="movie-search-fields">
            <?php foreach (['lead_actor', 'lead_actress', 'producer', 'music_director'] as $key) { ?>
                <div class="form-group">
                    <label for="<?php echo h($key); ?>"><?php echo h($fields[$key]); ?></label>
                    <input class="form-control" type="search" id="<?php echo h($key); ?>" name="<?php echo h($key); ?>" maxlength="180" value="<?php echo h($filters[$key]); ?>">
                </div>
            <?php } ?>
            </div>
        </details>
        <div class="movie-search-actions">
            <button class="btn btn-primary" type="submit">Find movies</button>
            <a class="btn btn-default" href="advance_movie_search.php">Clear filters</a>
        </div>
    </form>
<?php }
