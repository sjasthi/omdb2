# Week 7 validation — October 19, 2026

Completed on the local XAMPP application and MariaDB 10.4.32:

- 62 backend integration checks passed: fresh setup, legacy migration/preflight, generated IDs across independent connections, unique email, invalid parent rejection, transaction rollback, CSV parsing/rollback, individual child edits, cascading deletes, preserved accounts, equivalent schema contracts, and repeat migration.
- 291 HTTP security checks passed against an isolated database and temporary app server, using synthetic accounts supplied through process environment variables. These cover authentication/roles, CSRF, hostile text, CRUD, relationship forms, uploads, generic errors, and session demotion.
- 8 Apache page/private-path smoke checks passed, and all 118 deployed PHP files (including the ignored local configuration) passed syntax checks.
- Staged and complete tracked-file secret scans passed. No local configuration, real user rows, supplied account passwords, or database backups are included in this change.

Before applying the live local migration, a full private database backup was saved outside Git and the web root. A trial upgrade on a complete database copy preserved checksums for all movie, people, song, and account rows. The local upgrade then preserved those same checksums. All 11 legacy anagrams received distinct generated IDs without losing text; their original rows and one invalid person relationship were preserved in the migration archive (12 archived rows). Post-migration preflight reports no blockers or repairs.

Fresh installs and upgrades enforce 17 cascading foreign keys. Existing movie, people, song, and account IDs, login passwords/hashes, and account roles were preserved. The original 3,997 movies, 15 people, 3 songs, and 4 accounts remain.

GitHub Actions also runs the backend regression suite on MariaDB 10.4 and MySQL 8.0; the pull request's checks show the remote results. This report records the local test results and does not claim production deployment verification.

The remaining owner work (historical credential rotation, history review, production database permissions and server configuration) is tracked separately in [issue #9](https://github.com/sjasthi/omdb2/issues/9). No passwords were changed as part of this migration.
