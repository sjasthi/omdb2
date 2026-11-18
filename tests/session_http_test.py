"""Verify that two installations cannot share login cookies. Uses only the isolated test fixture."""
import http.cookiejar
import json
import os
from pathlib import Path
import re
import shutil
import tempfile
import urllib.error
import urllib.parse
import urllib.request

if not os.environ['OMDB_TEST_DB'].endswith('_test'):
    raise RuntimeError('Use an isolated test database.')
root = Path(__file__).resolve().parents[1]
base = os.environ['OMDB_TEST_URL']
jar = http.cookiejar.CookieJar()
client = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(jar))
checks = []

def get(path, data=None, headers=None):
    request = urllib.request.Request(base + path, data=data, headers=headers or {})
    try:
        response = client.open(request, timeout=10)
    except urllib.error.HTTPError as error:
        response = error
    return response.status, response.read().decode('utf8'), response.headers

def check(name, passed):
    checks.append({'test': name, 'passed': bool(passed)})

_, body, _ = get('login.php')
token = re.search(r'name="csrf_token"\s+value="([a-f0-9]{64})"', body)[1]
get('login.php', urllib.parse.urlencode({'csrf_token': token, 'email': os.environ['OMDB_TEST_ADMIN_EMAIL'],
    'password': os.environ['OMDB_TEST_ADMIN_PASSWORD']}).encode())
original_names = {cookie.name for cookie in jar}
original_cookie = '; '.join(cookie.name + '=' + cookie.value for cookie in jar)
with tempfile.TemporaryDirectory(prefix='session-peer-', dir=root) as temporary:
    peer = Path(temporary).resolve()
    if peer.parent != root:
        raise RuntimeError('Unexpected peer fixture path.')
    shutil.copy2(root / 'security.php', peer / 'security.php')
    (peer / 'index.php').write_text("<?php require __DIR__ . '/security.php'; header('Content-Type: application/json'); echo json_encode(['authenticated'=>is_logged_in()]);", encoding='utf8')
    path = peer.name + '/index.php'
    status, body, _ = get(path)
    check('Sibling installation starts signed out', status == 200 and json.loads(body)['authenticated'] is False)
    peer_cookies = [cookie for cookie in jar if cookie.name not in original_names]
    check('Sibling installation uses a distinct session name', len(peer_cookies) == 1)
    check('Sibling session cookie is scoped to its own URL path', bool(peer_cookies) and peer_cookies[0].path == '/' + peer.name + '/')
    _, body, _ = get(path, headers={'Cookie': original_cookie})
    check('Replaying another installation cookie cannot authenticate', json.loads(body)['authenticated'] is False)

destination = Path(os.environ['OMDB_SESSION_RESULTS'])
destination.write_text(json.dumps(checks, indent=2), encoding='utf8')
passed = sum(item['passed'] for item in checks)
print(f'{passed}/{len(checks)} installation-session checks passed.')
raise SystemExit(0 if passed == len(checks) else 1)
