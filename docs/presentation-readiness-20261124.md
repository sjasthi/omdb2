# Presentation readiness — November 24, 2026

The local full-catalog installation passed the following checks against application commit `34def21bc8d8c093d45dc65e27aca259e86ff36f`. Its 232 tracked PHP/JavaScript/CSS files matched the source checkout after normalizing line endings. This verification did not change application code or catalog records.

| Verification | Expected | Actual | Result |
|---|---|---|---|
| Complete regression | All six suites pass in disposable databases | 62 backend + 273 combined search + 307 security HTTP + 134 UI HTTP + 361 matrix + 4 installation sessions = 1,141 | PASS |
| Installed Apache site | Guest/member/admin pages, assets and protected files give intended responses without technical errors | 130/130 checks | PASS |
| Original logins | Existing administrator/member credentials work with the intended role | Both verified; member has no Admin menu and direct admin-page access is denied | PASS |
| Browser workflows | Catalog filters, details, empty search, create/import forms, Help and themes work | 24 recorded observations | PASS |
| Responsive layout | No horizontal page overflow at tested widths | 390-pixel phone, 768-pixel tablet and 1,366-pixel desktop | PASS |
| Syntax and source hygiene | Maintained source parses and no rejected configuration/credential material is tracked | 123 PHP files, 3 maintained JavaScript files, secret scan and whitespace check | PASS |
| Catalog preservation | Every table's count, complete row-multiset hash and schema hash remain unchanged | All 23 tables matched before/after; 17 foreign keys present | PASS |
| Launchers | Full-catalog and fictional rehearsal preflights pass | Both ready after repairing private runtime paths | PASS |
| Presentation backups | Slide archive is intact; local backup materials exist | 8-slide PPTX ZIP integrity passed; PDF header, video and offline walkthrough checked | PASS |

The 361 matrix cases are part of the 1,141 regression total. The installed-site checks and browser observations are separate. These are recorded checks, not a claim that every possible device/input has been tested. The disposable regression databases were removed afterward; CRUD/import/delete tests did not target the live catalog.

## Preserved full catalog

The original installation still contained **3,997 movies, 15 people, 3 songs, 138 movie credits and 4 accounts** after verification. Complete table-row and definition comparisons include accounts, optional details, relationships and legacy/archive tables. Account values and hashes stay in the private comparison files; the public evidence reports only counts and check outcomes.

## Reliable search examples

- Cast member **Brad Pitt**, other filters blank: 84 stored matches across two results pages.
- Cast member **Brad Pitt** plus movie title **Fight Club**: one movie; its detail shows the Tyler Durden credit.
- Cast member **Brad Pitt** plus director **Steven Spielberg**: zero stored matches, with a clear explanation. The catalog lacks those Spielberg credits; the system does not fetch them from an external provider.
- The separate fictional sample workflow uses **Avery Stone** and **Morgan River** after importing [the sample CSVs and attaching credits](../examples/README.md). Do not expect fictional examples to exist automatically in the restored full catalog.

Some original movies have blank English-title/metadata fields. Search their title using the table's global filter or the populated native-title field. Six original poster references already lacked image files at restoration; their detail pages correctly use the existing placeholder. These data limitations were preserved rather than filled with invented records/artwork.

## Startup issue resolved

The private Windows shortcuts referred to a Codex runtime version removed by an update. The local full-catalog and rehearsal shortcuts now discover an installed PowerShell 7 runtime, then call the repository's existing `tools/start-demo.ps1`. Both preflights passed. No execution policy, account password or database schema was changed. Machine-specific shortcuts and connection files remain outside Git.

For another installation, follow [README setup and preflight](../README.md#8-check-startup-before-a-demonstration) and the [portable startup instructions](presentation/startup.md). The ordinary full-catalog preflight omits `--require-demo`; use that option only when the fictional sample records and credits exist.

## Evidence and scope

[Machine-readable readiness results](presentation-readiness-20261124.json) include suite totals, all browser observations, preservation outcomes and every installed HTTP expected/actual result. Raw local snapshots, application passwords, account-row hashes, connection settings, server logs and launchers are excluded.

The [November 9 fresh-installation record](fresh-installation.md) remains the independent GitHub clone/schema/bootstrap proof. This November 24 check verified the currently installed full catalog and presentation readiness; it did not repeat a fresh installation. Broader production-hosting, dependency, load and accessibility limitations remain in [known limitations](known-limitations.md).
