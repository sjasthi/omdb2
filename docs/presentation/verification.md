# Presentation rehearsal verification

Date: **November 17, 2026**. Base: merged Week 10 commit `60a8c008f039f7f6774b6280457febb2f1023029`, with the presentation changes on local branch `codex/presentation-kit`. The installed demonstration is the separate `omdb-final` app and clean MariaDB instance on port 3307. The older local installation remains separate.

| Check | Expected | Observed | Result |
|---|---|---|---|
| Backend/schema regression | Generated IDs, keys, transactions and cascades work | 62 checks passed | PASS |
| Combined movie search regression | Every filled filter applies to the same movie | 273 checks passed | PASS |
| HTTP security regression | Role, CSRF, SQL/output and write controls work | 307 checks passed | PASS |
| HTTP UI regression | Role controls, validation and missing records work | 132 checks passed | PASS |
| Edge-case matrix | Expected matches actual for every case | 361 cases passed | PASS |
| Filter reset in browser | Clear global and native-title filters | Both inputs cleared and three demo rows returned | PASS |
| Prepared search in browser | Avery Stone plus Morgan River | One match, Harbor Lights | PASS |
| Member access to setup | Server denies administrator page | Styled administrator-access-required response | PASS |
| Admin catalog | Create and edit controls available | Controls visible for the admin account | PASS |
| Phone home | No page overflow at 390 × 844 | Home fits viewport | PASS |
| Browser console | No JavaScript errors during rehearsal | Zero errors observed | PASS |
| Startup preflight | Read schema, roles and demo search without modifying data | All seven CLI checks and the HTTP sign-in/token check passed | PASS |
| Cold database startup | Start the configured stopped local instance | Database became available and preflight passed | PASS |
| Presentation | Eight slides with notes and editable evidence tables | Eight slide/note pages and three native tables | PASS |
| Offline backup | View without a server | Nine captured screens, eight-page PDF and 82-second silent video | PASS |

Functional total: **1,135 PASS / 0 FAIL**. The 361 matrix cases are included in that total. The five browser behavior checks, launcher checks and static/media checks are separate. The **38 installation checks** in the [Week 10 final report](../final-test-report.md) remain historical installation evidence, rather than a new installation claim for this rehearsal.

The PDF is a static image backup of the reviewed slides. The PowerPoint contains editable text/tables and speaker notes. The video is a screenshot walkthrough with optional captions, not a full-motion recording of CRUD operations. Browser captures intentionally avoid password entry and permanent database changes. The existing isolated functional suite verifies the write/deletion behavior.

The offline HTML's script syntax and all local image references passed checks. The in-app browser blocks `file:` URLs, so its interactive local-file navigation was not browser-tested there. Open `offline-demo.html` from the delivery folder in your normal browser during rehearsal.

The full suite ran with PHP 8.0.30 and MariaDB 10.4.32 on the clean port-3307 instance. It used disposable test databases and removed them after completion. The startup script was tested after the prior database process fully exited; attempting an immediate restart during shutdown can fail while the old process still holds database files.

Local environment observation: the Windows MariaDB process lingered after a shutdown request during the restart rehearsal. The isolated fictional-data instance needed manual process cleanup before restart. Startup and schema/data checks passed afterward. The launcher starts/checks servers and does not implement a shutdown or force-stop function. Its private logs avoid inherited terminal output handles, but a clean shutdown of this older local MariaDB build remains an environment limitation.

The small final changes add recently added movies, a catalog filter reset, corresponding Help text, and CLI-only rehearsal tools. No schema, authentication or write endpoint changes were needed for those UI features. Private login notes and local launchers remain outside Git. The ignore rules, staged secret scan and release builder reject `.private.` files.

See [machine-readable rehearsal results](verification.json) and the [rerun matrix](rehearsal-matrix.csv). This is evidence for the listed checks, not a penetration, dependency, load or complete accessibility audit.
