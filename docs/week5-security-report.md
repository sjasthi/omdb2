# Week 5 security hardening

Database-changing pages now require ADMIN or SUPER-ADMIN authorization, POST, and CSRF validation. Regular users retain browsing, search, reports, and user games. Remaining input-built queries use bound values, database output is escaped, AJAX columns render text, and technical errors return generic responses. Poster uploads validate image content and use server-generated names; deletion is scoped to stored poster records.

Validation: 295 security checks passed against an isolated MariaDB copy; 12 Apache deployment and package checks passed; all 117 deployed PHP files passed syntax checks. Tests covered anonymous and USER denials, valid and invalid tokens, active-session role changes, SUPER-ADMIN consistency, authorized movie/person/song CRUD, relationships, account creation, hostile search values, and error responses. Browser inspection confirmed stored script markup remained text and USER actions showed only View.

Private configuration, database dumps, and obsolete diagnostic pages are removed from the current Git tree. No test-account logins are included. SECURITY.md explains configuration, local hooks, CI, and historical credential cleanup. Historical commits remain intact; repository owners should review accounts whose details were included in older dumps.

The older FP3 Word report was also removed because it contained the supplied test-account logins. Change those accounts' passwords; deleting the current file does not remove the copies in Git history.

Manual ID allocation and the existing schema remain. Production hosting and external puzzle services were not assessed.
