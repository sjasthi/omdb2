# Final static-analysis and functional-test report

The [November 24 presentation-readiness check](presentation-readiness-20261124.md) reran all **1,141 regression checks** and added **130 installed-site HTTP observations** against the full catalog. All passed. It also verified both login roles, themes, responsive layouts, served-source parity, launchers and preservation of all 23 database tables. This is a new readiness check of the existing installation; the November 9 independent fresh-install evidence below retains its original scope.

The [November 17 full-catalog restoration](full-catalog-restoration.md) preserves all 23 original database tables and verifies the current code with **1,141 functional checks**, including four installation-session checks and two missing-poster checks added after the original 1,135-check suite. Historical results below retain their original scope.

The [November 17 presentation rehearsal](presentation/verification.md) reran the complete functional suite after the small presentation/UI additions. The Week 10 installation results below retain their original date and scope.

Verification date: **November 9, 2026**. Application source: fresh GitHub clone at `240e3f64305706546160abc01711721b0b9552b8`, independent of the previously deployed app. Environment and install observations are recorded in [fresh-installation.md](fresh-installation.md). This is the current report; weekly reports retain their original dates/results as historical evidence.

## Results

| Verification | Expected | Actual | Result |
|---|---|---|---|
| PHP syntax | Every tracked PHP file parses | 122/122 | PASS |
| Shared JavaScript syntax | Both maintained shared scripts parse | 2/2 | PASS |
| Package-builder Python syntax | Builder parses | 1/1 | PASS |
| Tracked-source secret scan | No rejected private configuration, dumps or credential values | tools/check-secrets.php --all passed | PASS |
| Documentation links | Every local reference/image target exists | 55/55 | PASS |
| Whitespace check | No diff formatting errors | git diff --check passed | PASS |
| Backend/schema/migration regression | Fresh/legacy schema, IDs, keys, transactions and cascades work | 62/62 | PASS |
| Combined search regression | Filter combinations, roles, escaping, read-only behavior and pagination work | 273/273 | PASS |
| HTTP security regression | Access guards, CSRF, safe SQL/output and authorized CRUD work | 307/307 | PASS |
| HTTP UI regression | Role-specific actions, labels, messages, validation and missing records work | 132/132 | PASS |
| Final edge-case matrix | Expected result matches observed result for each case | 361/361 | PASS |
| Fresh-install acceptance | New clone/schema/config/bootstrap/import/workflows operate independently | 38/38 | PASS |
| Browser verification | Representative pages render; no horizontal page overflow at desktop/phone widths; no console warnings/errors | 1366 × 900 and 390 × 844; 9 screenshots; zero warnings/errors | PASS |

Functional total: **1,135 PASS / 0 FAIL**, plus **38 PASS / 0 FAIL** installation acceptance checks. Syntax/browser checks are reported separately, not added to that functional count. Tests used synthetic data and removed their disposable databases.

## Evidence and method

- [Final results JSON](final-test-results.json): suite counts, environment, source hashes, static file results, browser geometry and all 38 installation observations.
- [Final 361-case CSV matrix](final-test-matrix.csv): scenario, expected result, actual result and PASS/FAIL, suitable for review in Excel.
- [Fresh-install expected/actual results](fresh-install-results.json) and [screenshot walkthrough](screenshots.md).
- [Week 9 defect/retest record](week9-defect-retests.json): original reproducible defects and their passing retests; the full matrix was rerun for this final report.

PHP checks use `php -l`; shared JavaScript uses `node --check`; Python syntax is parsed before executing the package builder. These are syntax/review checks, not a PHPStan/Sonar scan. `tools/check-secrets.php --all` scans the staged/tracked delivery, and `git diff --check` checks whitespace before publication. The final upload also runs the repository's GitHub Actions checks. The release builder verifies tracked source entries and emits a SHA-256/commit/tree manifest; the delivery ZIP is a source package, not a database backup.

## Coverage

The matrix covers empty/whitespace/array inputs, invalid and missing IDs, duplicate/case-variant accounts, bad URLs, apostrophes, Unicode, markup, long values/boundary lengths, SQL/XSS attack strings, logged-out access, USER access to all 37 ADMIN routes, write methods/CSRF, repeated operations, relationship batches, deletion cascades, zero/one/many search results, pagination and CSV validation/rollback/duplicate policies.

The installation proof additionally verifies no default accounts, a private configuration file, the first-admin CLI, member creation, real CSV/relationship forms under Apache, protected filesystem URLs and restricted database privileges. The system-table corruption encountered on the pre-existing database instance is documented rather than misreported as an OMDB defect or silently repaired.

## Reproduction and limits

Run the documented XAMPP install and `python tests/run_week9.py` command in README. Use a separate database account with CREATE/DROP privileges for tests; use a restricted account for the app. For the isolated port-3307 verification, the test PHP process used `mysqli.default_port=3307` through a separate CLI-only INI scan directory and the MySQL client used `MYSQL_TCP_PORT=3307`. The ordinary port-3306 test command needs neither override. The installed app explicitly used `127.0.0.1:3307` in its local configuration.

The new PHP/application source was not changed for Week 10; this delivery documents and packages the tested system. Older GitHub runs already passed the full suite on MariaDB 10.4 and MySQL 8.0; the documentation/package pull request runs those checks again. Known limits, historical credential follow-up, dependency modernization and production-hosting work are listed in [known-limitations.md](known-limitations.md) and [SECURITY.md](../SECURITY.md). This evidence does not constitute a full penetration, dependency-vulnerability, load or accessibility audit.
