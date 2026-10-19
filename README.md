# OMDB2 — online movie directory

PHP 8.0+ with `mysqli`/mysqlnd, `mbstring`, and `fileinfo`; MariaDB 10.4+ or MySQL 8.0; Apache with `.htaccess` enabled. XAMPP's local URL is http://localhost/omdb/ when installed in `C:\xampp\htdocs\omdb`.

## New installation

1. Create an empty database named `omdb` with `utf8mb4` / `utf8mb4_unicode_ci`. Import **sql/schema.sql** into that database using phpMyAdmin's Import tab or the MySQL client: `mysql -u YOUR_USER -p omdb < sql/schema.sql` (use Command Prompt or a Unix shell for `<` redirection).
2. Copy `db_credentials.example.php` to the ignored `db_credentials.php` and fill in your own server, database, username, and password. Alternatively set `OMDB_DB_SERVER`, `OMDB_DB_USER`, `OMDB_DB_PASS`, and `OMDB_DB_NAME`. Never commit the local file. Use a dedicated database account in deployment; the migration account needs DDL privileges, while the application account only needs SELECT/INSERT/UPDATE/DELETE.
3. Create the first administrator using `php tools/create-admin.php your-email@example.com`. Supply a private password of at least 12 characters through `OMDB_ADMIN_PASSWORD` in the process environment, then clear that variable. This CLI bootstrap only runs when `users` is empty. There are no default accounts or seeded passwords.
4. Open the app, log in, and import movie/people/song CSV files through the administrator Setup page. The schema contains no movie data. Optional existing poster files belong in `posters/`; allow the PHP process to write there for uploads.

Normal users browse and play user games. ADMIN and SUPER-ADMIN accounts manage database records, relationships, posters, and user accounts. Administrators create additional USER accounts using `sign_up.php`.

## Upgrade an existing installation

Do **not** import the fresh schema over an existing database. Back up the entire database and `posters/`, stop web requests and other database writers, and keep the backup outside the repository/web root.

```sh
php tools/migrate.php --check
php tools/migrate.php --apply
```

The preflight reports counts without printing account data. It rejects duplicate primary IDs, incompatible existing keys, duplicate emails, and non-InnoDB tables. Resolve ambiguous records explicitly before retrying. For the known legacy defects only, use:

```sh
php tools/migrate.php --apply --repair-legacy
php tools/migrate.php --check
```

`--repair-legacy` preserves original rows in `omdb_migration_archive`: it assigns generated IDs when **all** existing anagram IDs are the placeholder 0, and moves orphan child rows out of active tables before adding foreign keys. It never deletes or reassigns movie, people, song, or account IDs. Mixed/duplicate nonzero anagram IDs require explicit correction. The archive may contain private application data; keep it in the database, outside exported source files.

[DDL implicitly commits in MySQL](https://dev.mysql.com/doc/refman/8.0/en/implicit-commit.html). The runner preflights first, uses a migration lock, and skips completed DDL steps on a retry; the backup is the recovery path for an unrecoverable failure. A migration lock does not stop ordinary application writes: keep the site offline until migration and code deployment are complete. `sql/migrations/007_database_cleanup.sql` records the DDL; execute it through the runner so preflight, archival repairs, email uniqueness, and version tracking run too. Deploy the matching PHP files before reopening the site. Accounts and password hashes are preserved.

## Database and backend design

See [docs/database.md](docs/database.md) for keys, ownership, deletion behavior, transactions, legacy tables, and retired handlers. New records use AUTO_INCREMENT and the connection's insert ID. Imports no longer call an external service: native title length/base characters use local Unicode graphemes, including Telugu combining marks. Existing stored values are preserved until their title is edited.

CSV imports are UTF-8, contain no header, and have at most 10,000 records / 5 MB. Quoted commas and line breaks are supported. An invalid record rolls back the entire upload. Columns: movies = native name, English name, year; people = stage name, first, middle, last, gender; songs = title, lyrics, theme. Existing movie native-title/year pairs are skipped; people and songs are imported as new records.

## Validation

`php tests/backend.php` creates randomly named isolated databases ending in `_test`, tests fresh setup and a schema-only legacy fixture, and drops only its own databases. Its database user must be able to create/drop test databases; set `OMDB_TEST_DB_SERVER`, `OMDB_TEST_DB_USER`, and `OMDB_TEST_DB_PASSWORD` if needed. Application configuration must also be present, as above. Never point HTTP tests at a live database: `tests/security_http_test.py` requires a separate configured app/database ending in `_test` and test login values through environment variables.

GitHub Actions runs PHP syntax checks, the secret scanner, and backend regression tests against MariaDB 10.4 and MySQL 8.0. Enable local hooks with `git config core.hooksPath .githooks`, ensure PHP is on PATH, and run `php tools/check-secrets.php --all` before uploading. Only the reviewed schema, migration, and schema-only test fixture are exempt from the SQL dump ignore rule. See [SECURITY.md](SECURITY.md) for access controls and historical credential follow-up.
