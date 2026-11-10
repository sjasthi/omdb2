# Changes from the original sjasthi/omdb2

Comparison baseline: commit [`ccee862`](https://github.com/sjasthi/omdb2/commit/ccee862), immediately before this project's Week 5 hardening. The application was built on the existing OMDB2 project and preserves its original contributors/credits. This document summarizes the final implementation, rather than claiming the original project as new work.

| Area | Original baseline | Final project |
|---|---|---|
| Installation | Short README referred to historical dumps and inconsistent database names | One complete XAMPP walkthrough, schema-only setup, private local configuration, CLI first-admin creation, examples, screenshots, deployment package and independent install evidence |
| Authorization | Permissions and write guards varied between PHP pages | Shared login/ADMIN policy; USER accounts cannot perform catalog CRUD; role refresh takes effect during existing sessions |
| Request/output safety | Repeated request/query/output patterns across handlers | Bound SQL values, escaped HTML/JSON/table output, POST + session CSRF on writes, guarded uploads, safe error responses and blocked internal files |
| Credentials | Tracked local configuration, dumps and a previous report contained account material | Those artifacts removed from current source; example configuration and secret checks added; historical exposure/rotation follow-up remains explicit |
| Database IDs and keys | Application-generated IDs and incomplete relational constraints | Generated entity/child IDs, reviewed primary/composite keys, 17 cascading FKs, unique email, schema/migration equivalence checks |
| Multi-table writes | Related changes could be handled independently | Transactions for create/edit/delete/import/relationship operations, rollback tests, consistent Unicode derivation and local helpers |
| Search | Large duplicated movie-search condition branches | Shared parameterized AND/EXISTS movie search; cast plus director, partial/stage/full names, optional credit/title filters, shareable URLs and pagination |
| Layout | Separate page styles and inconsistent controls | Shared navigation, page headings, notices, forms and table rendering; responsive layout; admin controls omitted for members |
| Usability | Duplicated list URLs, broken links/scripts and outdated help | Canonical redirects, corrected entity actions, repaired report/game cases, clear validation, current role-aware Help |
| Testing | No single reproducible final evidence set | 361 expected/actual/pass/fail matrix cases plus four regression suites, 1,135 functional checks, multi-database CI, fresh-install acceptance results and static checks |
| Week 9 edge cases | False success on missing edits, server errors for missing credits, invalid CSV/password cases, permissive saved form IDs | Reproduced cases fixed and retested; original observations preserved in the defect/retest record |
| Documentation/package | Installation depended on local knowledge/data | Repository is the source of the install guide, schema reference, limitations, evidence, sample data and clean-commit ZIP builder |

Implementation history: [security PR #7](https://github.com/sjasthi/omdb2/pull/7), [database/responsive PR #10](https://github.com/sjasthi/omdb2/pull/10), [UI/search PR #12](https://github.com/sjasthi/omdb2/pull/12), and [testing PR #13](https://github.com/sjasthi/omdb2/pull/13). Closed PR #11 was not merged; the final combined search was included in PR #12.

To inspect the code-level comparison from a Git clone:

```sh
git diff --stat ccee862 HEAD
git diff ccee862 HEAD -- database.php security.php catalog.php movie_search.php sql/schema.sql
```

Old commits may still contain sensitive historical material. The delivered source/package contains schema-only SQL, not those historical database dumps.
