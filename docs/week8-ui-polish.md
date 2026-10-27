# Week 8 — UI, admin, and usability polish

This version builds on the combined cast/director search and brings the catalog pages, admin workflows, and Help onto shared UI components.

## What changed

- Shared page headings, navigation, role badges, buttons, form panels, notices, and table styles replace mixed page-specific presentation. The home page provides catalog counts and clear starting points.
- Members and guests receive view-only catalog actions. Admin links are omitted on the server; direct admin endpoints still enforce login, role, method, and CSRF checks. Admin tools include imports and member-account creation.
- Person actions now use person endpoints, song trivia opens its related song, and old duplicate list URLs redirect to maintained catalog pages.
- Movie/person/song creation, person/song editing, login, account creation, and imports use clearer labels and validation. Main create forms retain safe entered values after server validation errors. Passwords are never repopulated. Optional movie details validate lengths before database writes.
- Create/edit success messages, relationship feedback, validation summaries, empty table results, and page-load failures have consistent presentation. Missing detail records return a useful 404. Error pages do not expose database diagnostics or filesystem paths.
- Removed unused wizard scripts and duplicate classroom headings. Table actions use visible labels; column filters have accessible names. Wide tables scroll inside their own region and menu controls work with the keyboard.
- Help documents the actual catalog, combined search, permissions, smaller-screen behavior, messages, and admin workflows. Admin-only guide links are shown only to admins.
- Repaired the shared-cast report’s empty table and removed broken game scripts. Anagram clues now come from the database, and members never receive the admin contribution control. Movie-in-Movies uses local Unicode helpers so generating clues does not wait on repeated external API requests.

Release-year inputs now match the existing MySQL YEAR column: 1901 through the lesser of 2155 and the current year plus ten. This is validation only; database structure, stored records, and existing accounts were not migrated.

## Main implementation files

`ui.php` owns shared page metadata, notices, navigation, and basic entity forms. `styles/ui.css` supplies the common components after the existing responsive stylesheet. `scripts/catalog-ui.js` renders catalog tables using the role-specific action configuration supplied by PHP. `scripts/layout.js` supplies navigation, table scrolling, accessible names, and table-loading feedback.

`help.php` is the user guide. `tests/ui_http_test.py` checks rendered role controls, page headings, form validation, missing records, and unchanged database contents during read-only/error requests. It requires an isolated application/database ending in `_test` and fixture login values supplied through environment variables. Never run HTTP integration tests against the user's live database.

## Demo checklist

1. Sign in as a member. Open Movies, People, and Songs: only View actions appear, and the Admin menu is absent.
2. Sign in as an admin. Show Create, Edit, Delete, movie/song credit controls, and Admin tools. Open a form and use Cancel without changing data.
3. Show an empty required field and a descriptive length error. Correct the field and save a disposable record only in an isolated demo database.
4. Search by cast plus director. Every filled filter must match the same movie; missing people or credits can produce zero matches.
5. Resize the browser to 390px, 768px, and a desktop width. Show the collapsed menu, stacked forms, and locally scrolling tables.
6. Open Help and show the relevant role-specific guide and troubleshooting instructions.

Local verification results and screenshots are saved in the task's output folder. The final task response records the completed checks.

Verified locally on October 27, 2026: 307 security HTTP checks, 132 UI HTTP checks, 62 backend assertions, and 273 combined-search assertions passed on MariaDB 10.4. Browser checks covered 48 member/admin page-width cases at 320, 390, 768, 1024, 1366, and 1920 pixels. The repaired report and games ran without JavaScript errors. PHP and shared JavaScript syntax checks passed. Local secret checks were run before saving the final source snapshot.
