"""Reproducible local/CI runner. Needs PHP, mysql client, and a running test DB server.

Uses random accounts, an exclusively created *_test database, and a temporary
copy of the source. Never reads/copies db_credentials.php or changes live data.
"""
import argparse
import hashlib
import json
import os
from pathlib import Path
import secrets
import shutil
import socket
import subprocess
import sys
import tempfile
import time
import urllib.request


def main():
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument('--output', required=True, type=Path, help='Private results directory outside the source/web root')
    parser.add_argument('--php', default=shutil.which('php') or r'C:\xampp\php\php.exe')
    parser.add_argument('--mysql', default=shutil.which('mysql') or r'C:\xampp\mysql\bin\mysql.exe')
    args = parser.parse_args()
    repo = Path(__file__).resolve().parents[1]
    output = args.output.resolve()
    if output == repo or repo in output.parents:
        parser.error('Keep runtime results outside the source/web root.')
    output.mkdir(parents=True, exist_ok=True)
    env = os.environ.copy()
    env.update(OMDB_TEST_DB='omdb_week9_' + secrets.token_hex(8) + '_test', OMDB_TEST_MYSQL=args.mysql)
    for role in ['ADMIN', 'USER']:
        env['OMDB_TEST_' + role + '_EMAIL'] = role.lower() + '@example.invalid'
        env['OMDB_TEST_' + role + '_PASSWORD'] = secrets.token_urlsafe(24)
    env.update(OMDB_DB_SERVER=env.get('OMDB_TEST_DB_SERVER', '127.0.0.1'),
               OMDB_DB_USER=env.get('OMDB_TEST_DB_USER', 'root'),
               OMDB_DB_PASS=env.get('OMDB_TEST_DB_PASSWORD', ''), OMDB_DB_NAME=env['OMDB_TEST_DB'])
    created = False
    suites = []
    source_files = {}
    # Includes uncommitted PHP fixes and new tests; excludes all private config.
    for file in sorted(repo.rglob('*')):
        if file.is_file() and '.git' not in file.relative_to(repo).parts and file.suffix in ['.php', '.js', '.css', '.sql', '.py'] and file.name != 'db_credentials.php' and not file.name.endswith('.local.php'):
            source_files[file.relative_to(repo).as_posix()] = hashlib.sha256(file.read_bytes()).hexdigest()
    try:
        revision = subprocess.run(['git', 'rev-parse', 'HEAD'], cwd=repo, capture_output=True, text=True)
        base_commit = revision.stdout.strip() if revision.returncode == 0 else None
    except FileNotFoundError:
        base_commit = None
    metadata = {'started_utc': time.strftime('%Y-%m-%dT%H:%M:%SZ', time.gmtime()),
                'base_commit': base_commit,
                'source_sha256': source_files, 'php': subprocess.check_output([args.php, '-v'], text=True).splitlines()[0],
                'mysql_client': subprocess.check_output([args.mysql, '--version'], text=True).strip()}
    def run(label, command):
        result = subprocess.run(command, cwd=source, env=env, capture_output=True, text=True, encoding='utf8', errors='replace')
        (output / (label + '.log')).write_text(result.stdout + result.stderr, encoding='utf8')
        suites.append({'suite': label, 'exit_code': result.returncode})
        print(label + ': ' + ('PASS' if result.returncode == 0 else 'FAIL'), flush=True)
        if result.returncode:
            diagnostic = result.stdout + result.stderr
            for role in ['ADMIN', 'USER']:
                diagnostic = diagnostic.replace(env['OMDB_TEST_' + role + '_PASSWORD'], '[redacted]')
            print(diagnostic, flush=True)
        return result
    try:
        with tempfile.TemporaryDirectory(prefix='omdb-week9-') as temp:
            source = Path(temp) / 'app'
            def ignore(directory, names):
                return [n for n in names if n in {'.git', 'db_credentials.php', 'outputs', 'work', '__pycache__'}
                        or n.startswith('.env') or n.endswith(('.local.php', '.log', '.bak', '.zip'))]
            shutil.copytree(repo, source, ignore=ignore)
            fixture = subprocess.run([args.php, str(source / 'tests/week9_fixture.php')], env=env, check=True, capture_output=True, text=True)
            created = True
            metadata.update(json.loads(fixture.stdout))
            print('Isolated Week 9 fixture created.', flush=True)
            run('backend', [args.php, 'tests/backend.php'])
            run('movie-search', [args.php, 'tests/movie-search.php'])
            with socket.socket() as sock:
                sock.bind(('127.0.0.1', 0)); port = sock.getsockname()[1]
            env.update(OMDB_TEST_URL=f'http://127.0.0.1:{port}/', OMDB_TEST_RESULTS=str(output / 'security.json'),
                       OMDB_UI_RESULTS=str(output / 'ui.json'), OMDB_WEEK9_RESULTS=str(output / 'matrix.json'))
            with (output / 'http-server.log').open('wb') as log:
                server = subprocess.Popen([args.php, '-S', f'127.0.0.1:{port}', '-t', str(source)], cwd=source,
                                          env=env, stdout=log, stderr=log)
                try:
                    for _ in range(100):
                        if server.poll() is not None: raise RuntimeError('PHP test server did not start.')
                        try:
                            urllib.request.urlopen(env['OMDB_TEST_URL'] + 'login.php', timeout=1).close(); break
                        except OSError: time.sleep(.1)
                    else: raise RuntimeError('PHP test server did not become ready.')
                    for suite, filename in [('security', 'security_http_test.py'), ('ui', 'ui_http_test.py'), ('matrix', 'week9_http_test.py')]:
                        run(suite, [sys.executable, '-X', 'utf8', 'tests/' + filename])
                finally:
                    server.terminate(); server.wait(timeout=15)
    finally:
        if created:
            subprocess.run([args.php, str(repo / 'tests/week9_fixture.php'), '--drop'], env=env, check=True)
        metadata.update(suites=suites, database_removed=created, finished_utc=time.strftime('%Y-%m-%dT%H:%M:%SZ', time.gmtime()))
        (output / 'run.json').write_text(json.dumps(metadata, indent=2), encoding='utf8')
    return int(any(s['exit_code'] for s in suites))


if __name__ == '__main__':
    raise SystemExit(main())
