# Optional synthetic demonstration data

These are fictional titles, people and credits for demonstrating OMDB2. They contain no user accounts, passwords, or production catalog data. They are UTF-8 CSV files with no header row.

Sign in as the administrator you created during installation. Open **Admin**, choose the matching record type, and import each file once:

| File | Record type | Expected on an empty catalog |
|---|---|---|
| `movies.csv` | Movies | 3 imported, 0 skipped |
| `people.csv` | People | 3 imported |
| `songs.csv` | Songs | 1 imported; lyrics preserve the quoted line break and comma |

From **Movies**, open **People** for **Harbor Lights**. Select **Avery Stone** with role **Actor**, and **Morgan River** with role **Director**, then save. Add Avery as Actor to Northern Journey and Morgan as Director to O'Neil's Return if you want an example with one shared result and two individual-only results. Use **Songs** on Harbor Lights to attach Harbor Theme. From Songs, use **Credits** to credit Casey North as Singer.

Search for cast member `Avery Stone` and director `Morgan River`: only Harbor Lights should match. Searching for Avery alone should match Harbor Lights and Northern Journey. A name absent from this small catalog should return no matches.

Movie imports skip existing native-title/year pairs. People and song imports create new rows on every upload, so import those files once per fresh demonstration database. Associations are added through the admin forms and are not part of the three CSV formats. Generated IDs may differ after previous inserts or deletes; select records by their displayed names.
