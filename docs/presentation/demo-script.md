# Ten-minute speaking and demo plan

## Slides: seven minutes

| Slide | Time | What to say |
|---|---|---|
| 1. OMDB2 | 0:00–0:30 | “This is OMDB2, our Group 1 capstone project. It builds on the original sjasthi/omdb2 repository. My work focused on making the existing application safer, consistent, testable and reproducible.” |
| 2. Catalog and connected search | 0:30–1:15 | “The system stores movies, people, songs and their credits. Users can find movies where a cast member and a director worked together. Every filled filter must match the same movie. The data shown here is fictional demonstration data.” |
| 3. Contributions to the original project | 1:15–2:15 | “I centralized permission and write checks, replaced repeated backend logic, improved the database design, and standardized the responsive UI. I added systematic testing and a complete installation/package handoff. These changes improve an existing project rather than represent an entirely new codebase.” |
| 4. Database reliability | 2:15–3:10 | “The schema has 23 tables and 17 cascading foreign keys. Generated IDs avoid MAX(id) + 1 races. Composite keys prevent repeated relationships. Transactions keep multi-table writes together. Deleting a movie removes its owned rows and associations while preserving related people and songs.” |
| 5. Permissions and safe writes | 3:10–4:05 | “Members can read the catalog. Administrators perform CRUD and manage relationships. The server checks permissions even when someone types an admin URL directly. Writes require POST and a session CSRF token. Queries bind values separately from SQL, and output escapes database text.” |
| 6. Test evidence | 4:05–5:10 | “The existing full suite has 1,135 functional checks, including 361 matrix cases with expected results, actual results and pass/fail. There are also 38 installation checks. I fixed reproducible defects such as false success for missing records and invalid CSV behavior. These totals describe specific recorded checks, not proof that every possible bug is absent.” |
| 7. Fresh installation and handoff | 5:10–6:05 | “I cloned GitHub into a new folder and used a fresh database. The README covers requirements, configuration and creating your own administrator. Screenshots, a schema dictionary, limitations and a deployment ZIP help another person reproduce it. A corrupt system permission table in the old XAMPP server led me to verify against a separate clean instance.” |
| 8. Demonstration and remaining work | 6:05–7:00 | “I will show the member experience, combined search and administrator workflow. The catalog currently relies on manually entered data. Password reset, newer UI libraries and large-catalog performance are future work. The current delivery is a verified local XAMPP application.” |

The 1,135 total includes the 361 matrix cases. Do not add those numbers together as separate tests. Presentation-specific checks added afterward have their own result record.

## Live demonstration: three minutes

| Time | Action | Expected result / speaking cue |
|---|---|---|
| 7:00–7:20 | Sign in as the private demo member. Open Movies. | “The member sees View actions. The admin menu is absent.” Briefly filter the table and choose Clear filters to restore all three examples. |
| 7:20–7:45 | Open Search. Cast: Avery Stone. Director: Morgan River. | One match: Harbor Lights. “This applies both criteria to the same movie.” |
| 7:45–8:00 | Open Harbor Lights, then return to Movies. | Show existing credits and the attached song. “Relationships connect existing records.” |
| 8:00–8:15 | As the member, open `setup.php` directly. | The app denies access. “Hiding controls is only one part. The server also checks the role.” |
| 8:15–8:35 | Sign out, then sign in as the private administrator. Open Movies. | Management actions appear. Use the private login file before the presentation, not on the projector. |
| 8:35–9:15 | Create a movie named **Presentation Check**, English title the same, year **2026**. Filter Movies to find it. Attach Avery Stone as Actor using People. | The create succeeds and the credit appears. Use only this disposable record. Do not create a second copy if the first operation already succeeded. |
| 9:15–9:35 | Edit that movie's English title to **Presentation Check edited**. | Confirm the update in the table. |
| 9:35–9:55 | Delete that same disposable movie through its confirmation page. | The movie and its derived/association rows disappear. Avery Stone remains in People. |
| 9:55–10:00 | Return Home. | “The catalog is back to the prepared examples, and the documented source and test results are ready for review.” |

If time is short, show the permission difference and combined search first, then describe the CRUD flow using the tested evidence rather than rushing into a destructive click.

## If the live demonstration fails

Pause, state what failed, and open the offline walkthrough or PDF. Say: “These are captured screens of the tested local application. I can use them to explain the same workflow.” Do not claim the backup is a working live server. The silent video is a screenshot walkthrough, not a full-motion capture of every database operation.

## Rehearsal cleanup

Confirm the disposable Presentation Check record is gone and the three original sample movies remain. Run the preflight again. Do not reset the whole database, delete accounts, or reimport the people/song CSV files to clean up a rehearsal.
