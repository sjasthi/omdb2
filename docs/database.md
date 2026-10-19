# Database and backend maintenance

The schema and migration support the Week 6 database with the existing `users(id,email,password,first_name,last_name,role)` layout. The migration preserves entity IDs, account details, and content. Every application table uses InnoDB. Fresh setup and upgrade are compared by column definitions, indexes, and foreign-key deletion rules in `tests/backend.php`.

| Table group | Key and ownership |
|---|---|
| `movies`, `people`, `songs` | Existing integer IDs become AUTO_INCREMENT primary keys. |
| `users` | Existing unsigned AUTO_INCREMENT `id` and unique email stay in place. No other table owns account records. |
| `movie_data`, `movie_numbers` | `movie_id` is both primary key and FK: zero or one details/numbers row per movie. |
| `movie_anagrams`, `movie_media`, `movie_quotes`, `movie_trivia` | Generated integer primary IDs; `movie_id` FK. |
| `people_trivia` | Generated integer primary ID; `people_id` FK. |
| `song_media`, `song_trivia` | Generated integer primary IDs; `song_id` FK. |
| `movie_keywords`, `song_keywords` | Composite primary key: owner ID plus keyword. |
| `movie_song` | Composite primary key: movie ID plus song ID; both are FKs. |
| `movie_people` | Composite primary key: movie ID, person ID, role, screen name; both IDs are FKs. A person may hold multiple credits. |
| `song_people` | Composite primary key: song ID, person ID, role; both IDs are FKs. |

All 17 FKs use `ON DELETE CASCADE`. Deleting a movie removes its owned data and associations, preserving the people and songs themselves. Deleting a person removes their trivia and credits, preserving movies/songs. Deleting a song removes its keywords, trivia, media metadata, and associations, preserving movies/people. FKs reject missing parent references. Composite keys prevent identical repeated relationships.

`mpr_test_data`, `person_matches`, and `song_matches` have no PHP callers. They are retained for compatibility and to preserve existing data, with primary keys added. Their free-text values are not references to entity IDs. Removing these archival tables is a separate data-retention decision. `omdb_migration_archive` preserves original repaired/quarantined rows; `omdb_schema_migrations` records successful upgrades.

`database.php` provides bound queries, generated insert IDs, and `db_transaction()`. `catalog.php` holds shared creation, import, relationship, and edit operations. Movie creation plus number creation, CSV imports, batches of relationships, multi-table edits, related-data creation, and parent deletions commit together or roll back. `catalog_insert_movie()` and the multi-table edit helpers run inside the caller's transaction; this keeps a complete CSV upload inside one transaction. Do not nest `db_transaction()` calls.

Credit form values match by their numeric suffix, including sparse selections. Edits include the original relationship/keyword key; child updates are scoped by both parent ID and child key. Duplicate relationship additions are no-ops, while invalid FKs and other errors fail the whole batch. `INSERT IGNORE` is avoided because it can hide invalid data.

Poster files are outside MySQL transactions. Movie deletion captures metadata, commits the cascaded database delete, then removes validated local files. Poster upload removes its newly written file if its metadata insert fails. A filesystem cleanup failure is logged privately; backups include both database and poster directory. Song-media links remain external metadata; song deletion does not delete remote media.

`add_movie_people.php` delegates to `add_people.php`; `delete_song1.php` remains a guarded POST compatibility entry point and all current links use `delete_song.php`. Unreferenced broken processors (`add_the_song.php`, `attach_song.php`, `add_movie_people_post.php`, `song_people_list_post.php`, `delete_movie_people.php`, `delete_song_people.php`) return HTTP 410 after the same admin/POST/CSRF checks. Placeholder history/timeline/trend pages are removed. Simple and extended song/person forms share their update implementations. Unused/broken external API helpers are removed; remaining read-only puzzle calls use HTTPS, a timeout, response checks, and local fallbacks.

The CI matrix runs the database contract on MariaDB 10.4 and MySQL 8.0. Test fixtures are synthetic and contain no production rows or passwords. For a new schema change, update the fresh schema, add a versioned migration, and add a regression that exercises both paths.
