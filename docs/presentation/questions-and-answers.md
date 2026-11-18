# Questions to practice

**What did you personally contribute?**

I improved the existing repository's authorization, backend/database reliability, shared UI, combined search, testing, documentation and installation package. I can point to the relevant changes and merged pull requests. The original application and contributors retain credit.

**Why are the admin buttons hidden and the endpoints protected?**

Hiding controls makes the member interface clearer. The server-side role check prevents writes when someone opens a saved URL or sends their own request. A hidden button alone would not enforce permission.

**What is CSRF protection?**

The server places an unpredictable token in a form and stores the expected token in the session. A write request must send the matching token. The request also needs the correct login, role and POST method.

**How do you prevent SQL injection and XSS?**

Queries bind submitted values through prepared statements. HTML output escapes database text with htmlspecialchars, and catalog tables use a text renderer. Tests submit apostrophes, markup and injection strings to check those boundaries.

**Why replace MAX(id) + 1?**

Two concurrent inserts could calculate the same next ID. AUTO_INCREMENT lets the database generate IDs, and the code reads the ID from its own connection.

**Why use transactions?**

A movie create or CSV import can affect several rows/tables. A transaction commits the complete operation or rolls it back when a later step fails. Tests confirm failed operations leave no partial parent/child data.

**What happens when you delete a movie?**

The 17 ownership/association foreign keys define cascading cleanup. Deleting a movie removes its details and connection rows while keeping people and songs. Poster filesystem cleanup happens after the database commit, which is a documented limitation.

**Did you write all 1,135 tests this week?**

No. That total combines the maintained regression suites and the 361-case edge-case matrix. I extended and ran them systematically. The final installation added 38 separate acceptance checks. I keep those counts distinct.

**Can another person install the application?**

Yes. A fresh clone and empty schema worked in a separate folder/database. The README documents requirements, local configuration, creating an administrator, optional fictional samples and running the tests. No existing developer password or production dump is required.

**Is it deployed publicly?**

The application is verified locally with XAMPP. GitHub contains the source and deployment package. Public production hosting and its HTTPS/operational setup remain separate work.

**What was a challenge?**

The old local MariaDB instance had a corrupt system permission table. I did not treat that as an application-schema defect. I initialized a separate clean instance on port 3307 and documented the successful independent installation.

**Why can a celebrity search return no results?**

This is a local catalog, not an automatic movie-data service. The people and credits must already exist. The fictional Avery Stone/Morgan River example has a prepared, known match.

**What would you improve next?**

Account recovery, modernization of the legacy UI libraries, and server-side pagination/performance testing for large catalogs. I would validate those changes against the current behavior and regression suite.
