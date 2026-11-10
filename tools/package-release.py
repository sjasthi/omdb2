"""Build a source-only ZIP from a clean Git commit; never include local configuration."""
import argparse
import hashlib
import json
from pathlib import Path
import subprocess
import zipfile


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--output', required=True, type=Path)
    args = parser.parse_args()
    repo = Path(__file__).resolve().parents[1]
    output = args.output.resolve()
    if output.suffix.lower() != '.zip' or output == repo or repo in output.parents:
        parser.error('Choose a .zip path outside the repository/web folder.')
    def git(*arguments):
        return subprocess.check_output(['git', *arguments], cwd=repo)
    if git('status', '--porcelain').strip():
        parser.error('Commit or set aside source edits first; the package must match a clean commit.')
    commit = git('rev-parse', 'HEAD').decode().strip()
    tree = git('rev-parse', 'HEAD^{tree}').decode().strip()
    paths = git('ls-tree', '-r', '--name-only', '-z', commit).decode().strip('\0').split('\0')
    allowed_sql = {'sql/schema.sql', 'sql/migrations/007_database_cleanup.sql', 'tests/fixtures/week6-schema.sql'}
    for path in paths:
        name = Path(path).name.lower()
        if (name == 'db_credentials.php' or name.startswith('.env') or name.endswith(('.local.php', '.log', '.bak', '.zip'))
                or ('credentials' in name and name.endswith('.json')) or ('secrets' in name and name.endswith('.json'))
                or (name.endswith('.sql') and path not in allowed_sql)):
            parser.error('Private or unreviewed artifact is tracked: ' + path)
    output.parent.mkdir(parents=True, exist_ok=True)
    subprocess.run(['git', 'archive', '--format=zip', '--prefix=omdb-final/', '--output=' + str(output), commit], cwd=repo, check=True)
    with zipfile.ZipFile(output) as archive:
        assert archive.testzip() is None
        packaged = {name.removeprefix('omdb-final/') for name in archive.namelist() if not name.endswith('/')}
        assert packaged == set(paths), 'Archive differs from the reviewed commit.'
    manifest = {'commit': commit, 'tree': tree, 'source_files': len(paths), 'zip_sha256': hashlib.sha256(output.read_bytes()).hexdigest(),
                'configuration_included': False, 'database_data_included': False,
                'installation_guide': 'omdb-final/README.md'}
    output.with_suffix('.manifest.json').write_text(json.dumps(manifest, indent=2), encoding='utf8')
    print(json.dumps(manifest, indent=2))


if __name__ == '__main__':
    main()
