# Known limitations and remaining work

These limits describe the delivered system and its tested scope.

| Area | Current behavior / limitation |
|---|---|
| Catalog content | Fresh setup is empty. Optional CSV examples are fictional. There is no automatic download from IMDb, OMDb, or another movie provider. Credits must be entered before relationship searches can return them. |
| Search | Combined movie search supports cast/director and the documented optional credit filters with 50 results per page. The main-box phrase parser handles supported cast/director phrases; it is not a general natural-language query engine. Legacy People/Song search remains separate. |
| Field limits | Movie titles: 180 characters; years: 1901 through the lesser of 2155 and current year + 10. Stage names: 30 characters; first/middle/last names: 20 each. Song title/theme: 20; lyrics: 500. See the schema dictionary for child limits. |
| CSV imports | No header, UTF-8, at most 10,000 records and 5 MB. PHP/server upload limits may be lower. An invalid record rolls back the batch. Movie title/year duplicates are skipped; repeated people/song imports intentionally add rows. CSV does not import accounts or relationships. |
| Accounts | No public self-registration, email verification, password-reset flow, or role-promotion UI. The first administrator is bootstrapped by CLI; the admin form creates USER accounts. SUPER-ADMIN currently uses the same management policy as ADMIN. |
| Passwords and history | Current source/package seeds no passwords. Old repository history contained credentials/user dumps; removal from the working tree does not erase history. Owner-managed credential rotation/history cleanup remains documented in SECURITY.md. |
| Deletion | Confirmed deletes are permanent and cascade to owned/association rows. Database transactions cannot atomically include filesystem deletion of poster files; a cleanup failure is logged. Back up both the database and posters. |
| Data / encoding | Core catalog tables use utf8mb4. Some legacy account/archive tables retain their original utf8/latin1 definitions for compatibility; this iteration did not migrate all legacy character sets. |
| Scale | Catalog tables still load their result data into the browser for DataTables filtering. Large-catalog load/performance testing and server-side catalog pagination are future work. |
| UI dependencies | The project retains Bootstrap 3.3.7, DataTables 1.10.12 and jQuery 3.2.1 with local assets/license notices. Modernizing these libraries and a dedicated dependency security review remain future work. |
| Hosting | Verified on local Windows/XAMPP and in Linux database CI. Public production hosting, HTTPS/domain configuration, mail, monitoring, automated backups and restore rehearsals need deployment-specific work. Apache protection rules need equivalent configuration on another web server. |
| Verification | Automated tests cover the recorded cases; browser screenshots cover representative desktop/phone pages. This is not a load test, a complete accessibility audit, or proof that every possible vulnerability is absent. |

The retained legacy tables `mpr_test_data`, `person_matches` and `song_matches` have no current PHP callers. Several obsolete write endpoints return HTTP 410 after authorization checks. They are documented in [database.md](database.md), rather than represented as finished user features.
