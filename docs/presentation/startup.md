# Startup and rehearsal checklist

## Portable repository tools

From an installed XAMPP copy in a PowerShell terminal whose policy permits local scripts (the private shortcut uses the available PowerShell 7 runtime):

```powershell
& .\tools\start-demo.ps1 -RequireDemo -OpenBrowser
```

The script starts a local database/Apache only if their configured ports are not running, then checks schema/account presence, the prepared cast/director match, and the sign-in form. It does not import, delete, migrate, reset or create accounts/data. The database connection stays in the ignored local configuration. The health tool never prints passwords or account emails.

Started servers send their startup output to private files under `%LOCALAPPDATA%\OMDB2\presentation-logs`, rather than inheriting a temporary terminal's pipes. The launcher does not change Windows execution policy. The older Windows PowerShell host may reject scripts under its default Restricted policy; use the existing PowerShell 7 terminal or manually start the servers and run the PHP check below.

For a separate MariaDB instance:

```powershell
& .\tools\start-demo.ps1 -DatabaseConfig C:/omdb-data/my.ini -RequireDemo -OpenBrowser
```

Supply the real local configuration path. Zach's private one-click launcher already supplies the clean port-3307 instance. No machine-specific configuration or login password belongs in the repository. If PowerShell blocks a downloaded script under your organization's policy, run the documented checks manually or ask your administrator rather than changing that policy.

If a server is shutting down, let its process finish before launching again. A closed network port does not always mean its database files have been released.

Read-only checks without starting servers:

```powershell
& .\tools\start-demo.ps1 -RequireDemo -CheckOnly
& C:\xampp\php\php.exe .\tools\check-demo.php --require-demo
```

`--require-demo` expects the fictional example credits and one Harbor Lights match. Omit it for an ordinary catalog without those fictional examples. An ordinary catalog still needs an administrator/member if you want to demonstrate both roles.

## Before the audience arrives

- Connect the display, open the slides and check readable text at the back of the room.
- Run the preflight, then verify both logins privately. Sign out before presenting.
- Close terminals, private configuration, credential files and unrelated tabs before sharing the screen.
- Open Home, Movies, Search and the test report. Keep the backup PDF/video and ZIP on the local computer.
- Check that no previous rehearsal left a Presentation Check record.
- Rehearse the planned ten minutes. Leave a few seconds between steps for the audience to see the result.

## Ready means

Database and Apache respond; the schema has 17 cascading keys and a unique email index; both roles exist; the prepared search returns one movie; the login form includes its token. The full security/CRUD suite gives the deeper behavior evidence. A startup check does not replace a regression run.
