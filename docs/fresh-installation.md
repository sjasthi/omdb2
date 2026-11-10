# Fresh installation from GitHub

Verified November 9, 2026. The application source was cloned directly from `https://github.com/sjasthi/omdb2.git`, main commit `240e3f64305706546160abc01711721b0b9552b8`. It was installed in a new `C:/xampp/htdocs/omdb-final` folder. No code, credentials, database rows or poster files were copied from the existing `C:/xampp/htdocs/omdb` installation.

## Installation conditions

| Item | Observed result |
|---|---|
| PHP / database | PHP 8.0.30 / MariaDB 10.4.32 |
| Web server | Existing XAMPP Apache serving the newly cloned folder, with `.htaccess` enabled |
| New database | `omdb_final`, created empty, schema imported from the fresh clone |
| Clean server instance | Separate newly initialized MariaDB data directory on loopback port 3307 |
| Local configuration | New ignored `db_credentials.php`, with the new database and restricted application account |
| First administrator | Created with `tools/create-admin.php`; no seeded/shared password |
| Member | Created through the admin account form; USER role confirmed |
| Data | 3 fictional movies, 3 fictional people, 1 fictional song imported through the real CSV form; credits added through real relationship forms |
| Original application | Its web folder and catalog were not installation inputs |

The first attempt against the existing port-3306 server found corruption in MariaDB's `mysql.db` permission table when granting a new application account. The schema itself imported correctly. No repair of that existing server was attempted. A separate clean MariaDB instance was initialized using the installed XAMPP `mysql_install_db.exe`, bound to `127.0.0.1:3307`, then the installation was performed there. This is an environment issue, not an application-schema defect. On a clean XAMPP installation, README's normal port-3306 steps apply; its non-default-port note explains the only connection difference.

## Acceptance results

All **38** independent installation checks passed. [Machine-readable expected/actual results](fresh-install-results.json) cover:

- GitHub source identity; new empty database; 23 schema tables, zero accounts and 17 cascading FKs.
- Private configuration ignored by Git and empty catalog served by Apache.
- CLI administrator bootstrap; refusal of a second bootstrap; read-only schema preflight.
- Admin/member login, member account role and denied Admin access.
- All three sample imports, movie/person/song associations and zero/one/many search results.
- Create/edit/delete of a disposable movie, including child cleanup.
- HTTP protection for credentials, SQL, tooling, tests, docs and Git paths; missing-page 404.
- Restricted application account denied CREATE TABLE while ordinary application operations succeeded.

Then `tests/run_week9.py` was run **from this fresh clone**, using separate disposable databases on the clean instance. All **1,135 functional checks passed** and the disposable databases were removed. The final delivery adds documentation, samples and a source-package builder; the PHP application/schema used for these results are the GitHub version identified above. Source hashes are included in [final-test-results.json](final-test-results.json).

## Repeat the process

Follow [README.md](../README.md) in a new folder/database. Record `git rev-parse HEAD`; import only `sql/schema.sql`; supply your own private database connection and first-admin password; import the optional examples once; add their relationships; verify permissions and the sample combined search. Run the five-suite test entrypoint with a separate test database account. Use [the screenshots](screenshots.md) as visual examples and [deployment.md](deployment.md) for packaging or upgrades.

The verification app remains a synthetic local demo. Its generated credentials are kept privately outside Git and the web root; they are not repository defaults. Another installer creates their own accounts. A local server's backup/data directory is not part of the source delivery.
