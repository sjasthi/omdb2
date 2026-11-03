# Week 9 — Serious testing and edge cases

Executed November 3, 2026 on Windows/XAMPP, PHP 8.0.30 and MariaDB 10.4.32. All **361 matrix cases passed** after fixes. The four existing suites also passed: **1,135 functional checks total**.

## Evidence

- [Executed matrix](week9-test-matrix.md): each case has its input/scenario, expected outcome, actual observation, and PASS/FAIL.
- [CSV for Excel](week9-test-matrix.csv) and [JSON](week9-test-matrix.json): the same complete 361-case matrix.
- [Defect retests](week9-defect-retests.json): original failing observations and the matching passing retest. The initial account-creation failure was a consequence of the preceding null-password test creating the account; it was not a separate role-policy defect.
- [Run metadata](week9-run.json): environment, base commit, tested source-file hashes, suite exit codes, and database cleanup. Hashes identify the tested working source, including the fixes on top of the base commit.
- [Deployed Apache smoke checks](week9-apache-smoke.json): 23 additional read-only checks passed against the updated XAMPP app, including safe error responses and blocked credentials, SQL, tools, tests, and repository paths.

## Final matrix

| Area | Cases | What was verified | Actual result |
|---|---:|---|---|
| Login, roles, methods, CSRF, logout | 172 | Guest GET/POST to all 37 admin routes redirects to login; USER GET/POST is forbidden; POST-only actions reject GET; invalid CSRF cannot write; logout/replay revokes access | 172 PASS; blocked requests left tables unchanged |
| Empty, malformed and long fields | 28 | Empty/whitespace/array fields, invalid years, over-limit values, and Unicode text exactly at movie/person/song limits | 28 PASS; rejected values saved no data; boundary-length values saved intact |
| IDs, URLs and saved forms | 58 | Missing/negative/non-numeric/overflow/array IDs, unknown URLs, CLI-only fixture, deleted records, invalid edit URLs, and valid admin forms | 58 PASS; invalid requests safely rejected; valid forms usable |
| Accounts | 16 | Empty fields, invalid/duplicate/case-variant emails, mismatched/short/long/null-containing passwords, role override, password hashing and injection attempts at login | 16 PASS; uniqueness and USER creation policy preserved |
| CRUD and escaping | 6 | Create movies, people and songs with apostrophes/markup; retrieve stored text through detail pages | 6 PASS; exact data stored and HTML escaped |
| Search | 22 | Zero/one/many matches, combined filters, Unicode/apostrophes, SQL/XSS strings, literal wildcards, malformed filters, repeated reads, and 51-result pagination | 22 PASS; correct counts/pages and read-only execution |
| Relationships and retries | 5 | Repeat a credit addition, incomplete role, missing parent/person, and a batch with one invalid child | 5 PASS; no duplicate credit, no orphan, and failed batch rolled back |
| Deletes | 32 | Person/song/movie deletes with owned child records; repeated deletes; invalid IDs; surviving related entities | 32 PASS; children cleaned up and unrelated parents preserved |
| CSV uploads | 22 | Valid movies/people/songs; BOM/CRLF/Unicode/apostrophes/commas/multiline/escaped quotes; missing/empty/wrong-format/oversized/over-10,000-row files; invalid later rows; duplicate policies and repeat imports | 22 PASS; valid data preserved, invalid uploads atomic |
| **Total** | **361** | Expected and actual outcomes recorded per case | **361 PASS / 0 FAIL** |

## Reproduced defects and fixes

| Reproduction | Before | After / fix |
|---|---|---|
| Submit edits for nonexistent movie/person/song IDs | HTTP 302 with a success destination despite updating nothing | HTTP 400 with a useful missing-record message; parent existence checked inside the transaction |
| Add credits for a missing movie/person, including an invalid child after a valid selection | HTTP 500; transaction did roll back | HTTP 400 with an understandable message; parent/child existence checked under transaction locks; rollback preserved |
| Create an account with a null character in the password | Account was accepted by the local PHP/bcrypt implementation | HTTP 400 before hashing/insertion; valid UTF-8 and no null characters required |
| Upload an empty or blank-only CSV | HTTP 200, misleading zero-record success | HTTP 400, explanation that at least one record is needed |
| Upload an existing native-title/year with a blank English title | Invalid row skipped before validation | HTTP 400; validate all movie fields before applying the duplicate-skip policy |
| Open saved legacy edit/detail/relationship form URLs for deleted records or SQL text as an ID | Blank forms, or MySQL coercion treating `1 OR 1=1` as ID 1 in two legacy forms | HTTP 404 for deleted records; malformed IDs rejected before rendering; existing form URLs still work |

Changes are limited to those observed defects and the testing/evidence infrastructure. Existing SQL parameterization, admin authorization, email uniqueness, transaction behavior, and deletion cascades remain covered. No schema migration or live catalog/account change was needed.

## Regression suites

| Suite | Passing checks |
|---|---:|
| Fresh schema, legacy migration, keys, transactions, cascades and import backend | 62 |
| Combined movie search combinations, roles and pagination | 273 |
| HTTP security and authorized CRUD | 307 |
| HTTP UI/role controls, messages and validation | 132 |
| Week 9 matrix | 361 |
| **Total** | **1,135** |

All 122 tracked PHP files passed syntax checks. The staged secret scanner and whitespace checks passed. The HTTP server log contained no PHP warnings/fatal errors or uncaught application exceptions in the final local run. Every disposable database was removed. The verified PHP fixes, test entrypoint, and evidence documents were copied to the local XAMPP app; the original files were backed up outside the web root. Live accounts and catalog data were not changed.

Hosted verification also passed all five suites on both MariaDB 10.4 and MySQL 8.0 in [GitHub Actions](https://github.com/sjasthi/omdb2/actions/runs/41692400675). The first hosted run exposed two test-harness portability issues: the PHP development server fell back to the home page for nonexistent URLs, and the MariaDB client returned text that the test reader could not decode as UTF-8. The test server now uses an explicit missing-file router; round-trip text assertions compare stored UTF-8 bytes through HEX rather than relying on client output encoding. Failed-suite diagnostics are printed with generated login passwords redacted. These adjustments do not change production Apache routing or relax the expected results.

## Run it again

Start a local database server. Use a database account that can create/drop disposable databases, and install PHP 8.0+ with mysqli/mysqlnd, mbstring and fileinfo, Python 3.12+, and the MySQL client.

From the repository:

```sh
python tests/run_week9.py --output /path/outside/the/repository/week9-results
```

On XAMPP/Windows, if PHP/MySQL are not on PATH:

```powershell
python tests/run_week9.py --php C:/xampp/php/php.exe --mysql C:/xampp/mysql/bin/mysql.exe --output C:/omdb-test-results/week9
```

Optional environment settings are `OMDB_TEST_DB_SERVER`, `OMDB_TEST_DB_USER`, and `OMDB_TEST_DB_PASSWORD`. Supply private values only through the environment. The runner generates random passwords, copies source to a temporary directory without local credentials, exclusively creates a randomly named `omdb_week9_<random>_test` database, starts a loopback PHP server on an available port, runs all five suites, and cleans up in `finally`. It refuses to reuse an existing database and keeps results outside the app's web root. No persistent test account or credential file is created.

Results include `matrix.csv`, `matrix.md`, `matrix.json`, the security/UI JSON results, per-suite logs, and `run.json`. A failed case exits nonzero. The workflow repeats this matrix for MariaDB 10.4 and MySQL 8.0 and shows expected/actual results in each GitHub job summary. The checked-in results capture the local MariaDB run; hosted results are available through the linked Actions run.
