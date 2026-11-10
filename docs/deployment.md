# Deployment package, upgrades and recovery

Use [README.md](../README.md) for a new XAMPP installation. The complete deployment unit is the reviewed Git commit, not an individual developer's web folder or database export.

## Source package

From a clean Git checkout, run:

```powershell
python tools/package-release.py --output C:/omdb-delivery/omdb2-final.zip
```

The builder requires a clean tracked source state, reads files from the committed tree with `git archive`, and verifies the ZIP entries match that tree. The archive has one top-level `omdb-final/` folder. An adjacent `.manifest.json` records commit/tree IDs, file count and SHA-256. It rejects tracked local credentials, environment files, database dumps, logs and nested ZIP artifacts. It includes only the three reviewed schema/migration/test-fixture SQL files. Run the secret scanner before committing; the package builder's path checks are not a content secret scanner.

The ZIP supplies PHP, local UI libraries/assets, schema/migration, examples, screenshots, documentation and test tooling. Install XAMPP/PHP/database/Python separately as appropriate. No database server, database rows, first-admin password, local configuration or Git history is packaged.

GitHub Actions also builds and verifies the ZIP after syntax/secret checks, then publishes it with its manifest as an **omdb2-final-<commit>** workflow artifact. Open a successful run under the repository's **Actions** tab to download it; artifacts are retained for 14 days. The clean-commit builder reproduces the package later from the source commit even after an artifact expires.

Verify an archive in PowerShell:

```powershell
Get-FileHash C:/omdb-delivery/omdb2-final.zip -Algorithm SHA256
```

Compare with the manifest. Extract into a new folder, configure your database, import the empty schema, create your own admin, then open the app. Keep ZIPs, private logs and backup files outside the web root. A GitHub source ZIP also works for installation; it lacks the builder's adjacent manifest and Git history.

## Updating an existing installation

1. Back up the full database and `posters/` outside the repository and Apache web root. Record the current code commit and local configuration. Verify the backup can be read before making changes.
2. Stop ordinary application requests and database writers. Never import `sql/schema.sql` over an existing catalog.
3. Use the database migration account, which needs DDL privileges, with the new code's CLI tools:

```sh
php tools/migrate.php --check
php tools/migrate.php --apply
php tools/migrate.php --check
```

Resolve reported duplicate entity IDs, duplicate emails or incompatible keys explicitly. Only the known legacy orphan/placeholder defects have an archival repair option:

```sh
php tools/migrate.php --apply --repair-legacy
```

Original repaired rows remain in the private `omdb_migration_archive` table. The migration does not reassign movie/person/song/account IDs. DDL can commit implicitly, so a failed migration may require restoring the backup; a transaction is not a substitute for it. The migration lock prevents two migration runners, not normal app writers. See [backend details](database.md).

4. Deploy the matching complete source, preserving private `db_credentials.php` and any separately stored poster files. Reapply equivalent access rules if using another server. Switch the application back to its restricted database account.
5. Check login, member/admin navigation, catalog reads, one authorized disposable CRUD/relationship operation, and protected URLs. Reopen the app after the checks pass.

## Restore

Keep the site offline. Restore the matching code commit, database backup and poster backup together into the intended target, then restore local configuration and rerun the smoke checks. Confirm account/role preservation before reopening. Do not put a backup dump into `htdocs` or commit it to Git.

## Public hosting scope

This delivery demonstrates a local classroom installation. Public hosting needs its own supported runtime, HTTPS/domain setup, private logging, restricted database account, permissions for poster storage, scheduled backups and restore checks. Apache's `.htaccess` is part of the application deployment. Documentation, SQL, tools, tests, hidden Git files and PHP includes should remain inaccessible by HTTP.

## Independent verification

[Fresh-installation record](fresh-installation.md) identifies the GitHub commit, new app folder, new database instance, bootstrap/import/login checks and observed setup issue. [Final report](final-test-report.md) records static checks and functional results. [Screenshots](screenshots.md) use fictional examples, not live user records.
