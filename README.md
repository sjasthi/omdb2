# OMDB2 — online movie directory

This version hardens the existing OMDB XAMPP application. Use the current omdb database, with the existing users schema containing id, email, password, and role. Database dumps and account credentials are intentionally excluded from the source package.

Place the application in C:\xampp\htdocs\omdb, keep your local ignored db_credentials.php, and visit http://localhost/omdb/. For a new local configuration, copy db_credentials.example.php to db_credentials.php and fill in your own database settings. PHP requires mysqli with mysqlnd, mbstring, and fileinfo. Apache must allow this application's .htaccess rules.

Sign in with your existing account. Normal users have read access; ADMIN and SUPER-ADMIN users manage data. Administrators create new accounts using sign_up.php. Existing legacy passwords are upgraded to secure hashes after successful authentication.

See [SECURITY.md](SECURITY.md) for the access policy, commit checks, deployment rules, and removal of previously tracked private files.
