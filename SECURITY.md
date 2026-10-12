# OMDB security hardening

Normal users can browse, search, view reports, and play user games. ADMIN and SUPER-ADMIN users can create, modify, delete, manage relationships, upload posters, import CSV data, and create user accounts. Account creation always creates a USER account; submitted role values are ignored.

All administrative pages use the shared authorization check. Every database-changing form uses POST and a session CSRF token. The database helper checks authorization, method, and token again before preparing a write. Login verifies credentials and upgrades only the authenticated account's legacy password hash; this authentication-maintenance operation is the sole non-admin database write.

Queries bind data separately from SQL. Dynamic table and column identifiers are limited to explicit server-side choices. HTML values use `htmlspecialchars(..., ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')`; AJAX tables use DataTables' text renderer. Technical exceptions are logged outside the response and produce generic messages.

Keep local configuration in the ignored `db_credentials.php` file, or provide `OMDB_DB_SERVER`, `OMDB_DB_USER`, `OMDB_DB_PASS`, and `OMDB_DB_NAME` in the server environment. Never commit database dumps, login lists, or real passwords. `.htaccess` denies private configuration, include files, database dumps, tooling, and directory listings. Preserve equivalent rules when deploying with another web server.

Enable the local commit check with `git config core.hooksPath .githooks` and ensure PHP is on PATH. Run `php tools/check-secrets.php --staged` before committing, or `--all` to scan every tracked file. The included GitHub workflow runs syntax and secret checks. Local hooks must be enabled in each clone; GitHub CI detects mistakes after a push and does not prevent the initial disclosure.

If updating the existing GitHub repository, remove its already-tracked private files from Git. Ignore rules do not untrack existing files:

```sh
git rm --cached --ignore-unmatch db_credentials.php sql/omdb_db.sql sql/import_movies_stored_procedure.sql sql/omdb_06_03_2022.sql omdb_06_03_2022.sql
git config core.hooksPath .githooks
git update-index --chmod=+x .githooks/pre-commit
php tools/check-secrets.php --all
```

The source repository contained a database dump with user records. Removing it from the current version does not remove historical commits. Review affected accounts and change any credentials that were exposed; coordinate any Git history cleanup with the repository owner. The supplied test-account logins are absent from this package.

The older FP3 Word report also contained the supplied test-account logins and is removed from this version. Change those accounts' passwords because the old report remains in historical commits.

Manual ID allocation and the older schema remain; migrating to unique AUTO_INCREMENT keys is a separate database change. External puzzle services and the production hosting configuration were not assessed. Security tests reduce the obvious issues checked here; they do not establish that every possible vulnerability has been eliminated.

Implementation references: [PHP HTML escaping](https://www.php.net/manual/en/function.htmlspecialchars.php), [PHP prepared statements](https://www.php.net/manual/en/mysqli.quickstart.prepared-statements.php), and [OWASP CSRF guidance](https://cheatsheetseries.owasp.org/cheatsheets/Cross-Site_Request_Forgery_Prevention_Cheat_Sheet.html).
