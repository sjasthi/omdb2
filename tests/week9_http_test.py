"""Week 9 HTTP matrix: observable responses plus database evidence, not click counts."""
import csv
import html
import http.cookiejar
import io
import json
import os
from pathlib import Path
import re
import secrets
import subprocess
import urllib.error
import urllib.parse
import urllib.request

BASE = os.environ['OMDB_TEST_URL']
DB = os.environ['OMDB_TEST_DB']
if not re.fullmatch(r'omdb_week9_[a-f0-9]{16}_test', DB):
    raise RuntimeError('Run with tests/run_week9.py and its exclusively created database.')
RESULTS = []
COUNTERS = {}


class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, *args, **kwargs): return None


class Client:
    def __init__(self):
        self.jar = http.cookiejar.CookieJar()
        self.opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(self.jar), NoRedirect())

    def request(self, path, data=None, raw=None, headers=None):
        payload = raw if raw is not None else (urllib.parse.urlencode(data, doseq=True).encode() if data is not None else None)
        req = urllib.request.Request(BASE + path, data=payload, headers=headers or {})
        try: response = self.opener.open(req, timeout=20)
        except urllib.error.HTTPError as error: response = error
        return response.code, response.read().decode('utf8', errors='replace'), response.headers

    def token(self, path='index.php'):
        status, body, _ = self.request(path)
        match = re.search(r'name="csrf_token"\s+value="([a-f0-9]{64})"', body)
        if status != 200 or not match: raise RuntimeError('Cannot load test form token')
        return match[1]

    def post(self, path, data):
        return self.request(path, dict(data, csrf_token=self.token()))

    def login(self, role):
        return self.request('login.php', {'csrf_token': self.token('login.php'),
            'email': os.environ['OMDB_TEST_' + role + '_EMAIL'], 'password': os.environ['OMDB_TEST_' + role + '_PASSWORD']})


def sql(query):
    env = os.environ.copy()
    env['MYSQL_PWD'] = env.get('OMDB_TEST_DB_PASSWORD', '')
    return subprocess.check_output([env['OMDB_TEST_MYSQL'], '--no-defaults', '--default-character-set=utf8mb4',
        '-h', env.get('OMDB_TEST_DB_SERVER', '127.0.0.1'), '-u', env.get('OMDB_TEST_DB_USER', 'root'),
        '--batch', '--raw', '--skip-column-names', DB, '-e', query], env=env).decode('utf8').strip()


def literal(text): return '0x' + text.encode('utf8').hex()
def count(table, where='1=1'): return int(sql('SELECT COUNT(*) FROM `' + table + '` WHERE ' + where))
def snapshot(): return sql('CHECKSUM TABLE ' + ','.join('`' + t + '`' for t in sql('SHOW TABLES').splitlines()))
def safe(body): return not re.search(r'(Fatal error|Stack trace|Warning:|SQLSTATE|mysqli_sql_exception|[A-Z]:\\|/omdb-week9-[^ <]+)', body)


def case(group, scenario, expected, operation):
    COUNTERS[group] = COUNTERS.get(group, 0) + 1
    item = {'id': f'W9-{group}-{COUNTERS[group]:03d}', 'category': group, 'scenario': scenario, 'expected': expected}
    try:
        passed, actual = operation()
        item.update(actual=actual, result='PASS' if passed else 'FAIL')
    except Exception as error:
        item.update(actual='Test could not complete: ' + type(error).__name__, result='FAIL')
    RESULTS.append(item)
    if item['result'] == 'FAIL': print(item['id'] + ': ' + scenario + ' — ' + item['actual'], flush=True)


def rejected(path, data, expected=400):
    before = snapshot()
    status, body, _ = admin.post(path, data)
    unchanged = snapshot() == before
    return status == expected and safe(body) and unchanged, f'HTTP {status}; tables unchanged={unchanged}; diagnostics hidden={safe(body)}'


def multipart(fields, filename, content):
    boundary = '----OMDBWeek9' + secrets.token_hex(12)
    parts = []
    for key, value in fields.items():
        parts.append(f'--{boundary}\r\nContent-Disposition: form-data; name="{key}"\r\n\r\n{value}\r\n'.encode())
    if filename is not None:
        parts.append(f'--{boundary}\r\nContent-Disposition: form-data; name="filename"; filename="{filename}"\r\nContent-Type: text/csv\r\n\r\n'.encode() + content + b'\r\n')
    return b''.join(parts) + f'--{boundary}--\r\n'.encode(), {'Content-Type': 'multipart/form-data; boundary=' + boundary}


def upload(table, text, filename='fixture.csv', client=None):
    client = client or admin
    raw, headers = multipart({'csrf_token': client.token(), 'table_name': table}, filename,
                             text if isinstance(text, bytes) else text.encode('utf8'))
    return client.request('setup.php', raw=raw, headers=headers)


guest, user, admin = Client(), Client(), Client()
for role, client in [('USER', user), ('ADMIN', admin)]:
    case('AUTH', role + ' signs in', 'HTTP 302 to index.php', lambda c=client, r=role:
         ((response := c.login(r))[0] == 302 and response[2].get('Location') == 'index.php', f'HTTP {response[0]}; destination={response[2].get("Location")}'))

routes = json.loads((Path(__file__).parent / 'admin_routes.json').read_text())
before = snapshot()
for route in routes['admin']:
    for role, client, method, expected in [('Guest', guest, 'GET', 302), ('Guest', guest, 'POST', 302), ('USER', user, 'GET', 403), ('USER', user, 'POST', 403)]:
        case('AUTH', role + ' ' + method + ' ' + route, 'HTTP ' + str(expected) + '; no diagnostics',
             lambda c=client, p=route, m=method, e=expected:
             ((r := c.request(p, {'submit': '1', 'csrf_token': c.token('login.php' if c is guest else 'index.php')} if m == 'POST' else None))[0] == e and safe(r[1]),
              f'HTTP {r[0]}; diagnostics hidden={safe(r[1])}'))
case('AUTH', 'All blocked admin requests', 'Every database table unchanged', lambda: (snapshot() == before, 'Tables unchanged=' + str(snapshot() == before)))
for path in routes['post']:
    case('AUTH', 'ADMIN opens write-only URL ' + path, 'HTTP 405 with Allow: POST', lambda p=path:
         ((r := admin.request(p))[0] == 405 and r[2].get('Allow') == 'POST', f'HTTP {r[0]}; Allow={r[2].get("Allow")}'))
for token in [None, '0' * 64, ['malformed']]:
    data = {'native_name': 'CSRF denied', 'english_name': 'CSRF denied', 'year': '2020'}
    if token is not None: data['csrf_token[]' if isinstance(token, list) else 'csrf_token'] = token
    case('AUTH', 'Create movie with ' + ('missing' if token is None else 'invalid') + ' CSRF', 'HTTP 403; movie count unchanged',
         lambda d=data: ((n := count('movies')) >= 0 and (r := admin.request('createTheMovie.php', d))[0] == 403 and count('movies') == n, f'HTTP {r[0]}; movie delta={count("movies") - n}'))

movie = {'native_name': 'Valid title', 'english_name': 'Valid title', 'year': '2020'}
person = {'stage_name': 'Valid person', 'first_name': '', 'middle_name': '', 'last_name': '', 'gender': ''}
song = {'title': 'Valid song', 'lyrics': '', 'theme': ''}
for field in ['native_name', 'english_name', 'year']:
    for value, label in [('', 'empty'), ('   ', 'whitespace'), (['array'], 'array')]:
        data = dict(movie); data.pop(field); data[field + ('[]' if isinstance(value, list) else '')] = value
        case('INPUT', 'Movie ' + field + ' ' + label, 'HTTP 400; no database changes', lambda d=data: rejected('createTheMovie.php', d))
for field, limit in [('native_name', 180), ('english_name', 180)]:
    case('INPUT', f'Movie {field} exceeds {limit}', 'HTTP 400; no database changes', lambda f=field, n=limit: rejected('createTheMovie.php', dict(movie, **{f: 'x' * (n + 1)})))
for year in ['1900', '2156', '2020 OR 1=1', '2020.5', '99999999999999999999999999']:
    case('INPUT', 'Invalid release year ' + year, 'HTTP 400; no database changes', lambda y=year: rejected('createTheMovie.php', dict(movie, year=y)))
for route, values, field, limit in [('create_people.php', person, 'stage_name', 30), ('create_people.php', person, 'first_name', 20),
                                    ('create_song.php', song, 'title', 20), ('create_song.php', song, 'lyrics', 500), ('create_song.php', song, 'theme', 20)]:
    case('INPUT', route + ' overlong ' + field, 'HTTP 400; no database changes', lambda p=route, d=values, f=field, n=limit: rejected(p, dict(d, **{f: 'x' * (n + 1)})))
for route, values, field in [('create_people.php', person, 'stage_name'), ('create_song.php', song, 'title')]:
    case('INPUT', route + ' empty required field', 'HTTP 400; no database changes', lambda p=route, d=values, f=field: rejected(p, dict(d, **{f: '  '})))
    case('INPUT', route + ' array text field', 'HTTP 400; no database changes', lambda p=route, d=values, f=field: rejected(p, dict(d, **{f + '[]': ['bad'], f: ''})))
for path, key in [('movie_info.php', 'movie_id'), ('people_info.php', 'people_id'), ('song_info.php', 'song_id')]:
    for value in ['', '0', '-1', 'abc', '1 OR 1=1', '999999999999999999999999', ['1']]:
        url = path + '?' + urllib.parse.urlencode({key + ('[]' if isinstance(value, list) else ''): value}, doseq=True)
        case('URL', path + ' invalid ID ' + str(value), 'HTTP 400; diagnostics hidden', lambda p=url:
             ((r := user.request(p))[0] == 400 and safe(r[1]), f'HTTP {r[0]}; diagnostics hidden={safe(r[1])}'))
    case('URL', path + ' nonexistent positive ID', 'HTTP 404', lambda p=path, k=key:
         ((r := user.request(p + '?' + k + '=2147483647'))[0] == 404 and safe(r[1]), f'HTTP {r[0]}'))
for path in ['no-such-page.php', 'removed-endpoint.php', 'tools/week9_fixture.php', 'tests/week9_fixture.php']:
    case('URL', 'Unknown/CLI-only URL ' + path, 'HTTP 404; no server path', lambda p=path:
         ((r := guest.request(p))[0] == 404 and safe(r[1]), f'HTTP {r[0]}; diagnostics hidden={safe(r[1])}'))
for route, data in [('modifyTheMovies.php', {'movie_id': 2147483647, 'english_name_update': 'Missing', 'year_update': '2020'}),
                    ('modifyThePeople.php', dict(person, people_id=2147483647)), ('modifyTheSongs.php', dict(song, song_id=2147483647))]:
    case('URL', 'Update a nonexistent record via ' + route, 'HTTP 400; no false success; no database changes', lambda p=route, d=data: rejected(p, d))
for path, key in [('modify.php', 'movie_id'), ('modify.php', 'people_id'), ('modify.php', 'song_id'),
                  ('create_Data.php', 'movie_id'), ('add_people.php', 'movie_id'), ('add_song.php', 'movie_id'),
                  ('songs_people_list.php', 'song_id'), ('modify_people.php', 'people_id'), ('modify_song.php', 'song_id')]:
    case('URL', path + ' valid ' + key, 'HTTP 200; existing admin form remains usable', lambda p=path, k=key:
         ((r := admin.request(p + '?' + k + '=1'))[0] == 200 and safe(r[1]) and '<form' in r[1], f'HTTP {r[0]}; form present={"<form" in r[1]}; diagnostics hidden={safe(r[1])}'))
    case('URL', path + ' form for deleted ' + key, 'HTTP 404; no empty or unrelated edit form', lambda p=path, k=key:
         ((r := admin.request(p + '?' + k + '=2147483647'))[0] == 404 and safe(r[1]), f'HTTP {r[0]}; diagnostics hidden={safe(r[1])}'))
    case('URL', path + ' form with SQL text in ' + key, 'HTTP 400 or redirect; never opens record 1', lambda p=path, k=key:
         ((r := admin.request(p + '?' + urllib.parse.urlencode({k: '1 OR 1=1'})))[0] in [400, 302] and safe(r[1]), f'HTTP {r[0]}; diagnostics hidden={safe(r[1])}'))

password = secrets.token_urlsafe(24)
account = {'first_name': 'Week9', 'last_name': "O'Neil", 'email': 'week9-new@example.invalid', 'password': password, 'repeat_password': password}
for field in ['first_name', 'last_name', 'email', 'password', 'repeat_password']:
    case('ACCOUNT', 'Empty ' + field, 'HTTP 400; no account created', lambda f=field: rejected('sign_up.php', dict(account, **{f: ''})))
for label, changes in [('invalid email', {'email': 'bad@@example'}), ('mismatched passwords', {'repeat_password': password + 'x'}),
                       ('short password', {'password': str('short'), 'repeat_password': str('short')}), ('overlong password', {'password': str('x') * 73, 'repeat_password': str('x') * 73}),
                       ('NUL in password', {'password': str('Valid\0password'), 'repeat_password': str('Valid\0password')})]:
    case('ACCOUNT', label, 'HTTP 400; no database changes', lambda d=changes: rejected('sign_up.php', dict(account, **d)))
def create_account():
    r = admin.post('sign_up.php', dict(account, role='ADMIN'))
    row = sql('SELECT role, password=' + literal(password) + ' FROM users WHERE email=' + literal(account['email'])).split('\t')
    return r[0] == 200 and row == ['USER', '0'] and password not in r[1], f'HTTP {r[0]}; role={row[0] if row else "missing"}; plaintext password stored/echoed=False' if row == ['USER', '0'] and password not in r[1] else 'Account role/hash/response check failed'
case('ACCOUNT', 'Create account with attempted ADMIN role override', 'USER account; hashed password; no password echoed', create_account)
for email in [account['email'], account['email'].upper()]:
    case('ACCOUNT', 'Duplicate account ' + ('case variant' if email.isupper() else 'same email'), 'HTTP 400; existing account unchanged', lambda e=email: rejected('sign_up.php', dict(account, email=e)))
for payload in ["' OR 1=1 -- ", "admin@example.invalid' #", '<script>alert(9)</script>']:
    case('ACCOUNT', 'Login attack string ' + payload, 'HTTP 200 invalid credentials; no authenticated session', lambda p=payload:
         ((r := guest.request('login.php', {'csrf_token': guest.token('login.php'), 'email': p, 'password': 'incorrect'}))[0] == 200 and 'Invalid email or password.' in r[1] and guest.request('create_movie.php')[0] == 302,
          f'HTTP {r[0]}; admin form remains inaccessible={guest.request("create_movie.php")[0] == 302}'))

hostile = "O'Neil <b>W9</b> & తెలుగు"
ids = {}
def create_entity(kind, title):
    table, pk, route, data = {
        'movie': ('movies', 'movie_id', 'createTheMovie.php', dict(movie, native_name=title, english_name=title)),
        'person': ('people', 'people_id', 'create_people.php', dict(person, stage_name=title)),
        'song': ('songs', 'song_id', 'create_song.php', dict(song, title=title, lyrics=hostile))}[kind]
    status, body, _ = admin.post(route, data)
    ids[kind] = sql('SELECT MAX(`' + pk + '`) FROM `' + table + '`')
    column = {'movie': 'native_name', 'person': 'stage_name', 'song': 'title'}[kind]
    intact = sql('SELECT `' + column + '` FROM `' + table + '` WHERE `' + pk + '`=' + ids[kind]) == title
    return status == 302 and intact and safe(body), f'HTTP {status}; exact text stored={intact}'
for kind, title in [('movie', hostile), ('person', "O'Neil <b>W9</b>"), ('song', "O'Neil <b>W9</b>")]:
    case('CRUD', 'Create ' + kind + ' with apostrophe/markup', 'HTTP 302; exact text stored', lambda k=kind, t=title: create_entity(k, t))
for kind, page, key in [('movie', 'movie_info.php', 'movie_id'), ('person', 'people_info.php', 'people_id'), ('song', 'song_info.php', 'song_id')]:
    case('XSS', 'View hostile ' + kind + ' text', 'HTTP 200; escaped markup; no executable HTML', lambda k=kind, p=page, q=key:
         ((r := user.request(p + '?' + q + '=' + ids[k]))[0] == 200 and '<b>W9</b>' not in r[1] and '&lt;b&gt;W9&lt;/b&gt;' in r[1],
          f'HTTP {r[0]}; raw markup present={"<b>W9</b>" in r[1]}; escaped markup present={"&lt;b&gt;W9&lt;/b&gt;" in r[1]}'))
for kind, limit in [('movie', 180), ('person', 30), ('song', 20)]:
    case('INPUT', f'{kind} title exactly {limit} Unicode characters', 'Create succeeds at documented limit', lambda k=kind, n=limit: create_entity(k, 'é' * n))

before = snapshot()
for label, data, expected in [('zero', {'movie_search': 'definitely-no-match-week9'}, 0), ('one', {'cast_member': 'Avery Stone', 'director': 'Morgan River'}, 1),
                               ('many', {'cast_member': 'Avery Stone'}, 2), ('apostrophe and Unicode', {'movie_search': "O'Neil <b>W9</b> & తెలుగు"}, 1),
                               ('SQL injection', {'movie_search': "' OR 1=1 -- "}, 0), ('SQL UNION', {'cast_member': "' UNION SELECT 1 -- "}, 0),
                               ('XSS filter', {'director': '<script>alert(9)</script>'}, 0), ('literal wildcard', {'movie_search': '%_!'}, 0)]:
    case('SEARCH', label + ' results', f'HTTP 200; exactly {expected} matches; escaped text', lambda d=data, n=expected:
         ((r := guest.request('advance_movie_search.php?' + urllib.parse.urlencode(d)))[0] == 200 and bool(re.search(r'>' + str(n) + r' matching movies?</h2>', r[1])) and '<script>alert(9)</script>' not in r[1] and safe(r[1]),
          f'HTTP {r[0]}; matching count=' + (re.search(r'>(\d+) matching movies?</h2>', r[1])[1] if re.search(r'>(\d+) matching movies?</h2>', r[1]) else 'missing')))
for query in ['cast_member[]=bad', 'director=' + 'x' * 181, 'page=0', 'page=-1', 'page[]=1', 'page=99999999999999999999999999', 'movie_search=%FF']:
    case('SEARCH', 'Invalid query ' + query[:75], 'HTTP 400; no diagnostics', lambda q=query:
         ((r := user.request('advance_movie_search.php?' + q))[0] == 400 and safe(r[1]), f'HTTP {r[0]}; diagnostics hidden={safe(r[1])}'))
case('SEARCH', 'No filters', 'HTTP 200 with enter-a-filter guidance', lambda:
     ((r := guest.request('advance_movie_search.php'))[0] == 200 and 'Enter at least one filter' in r[1], f'HTTP {r[0]}; guidance present={"Enter at least one filter" in r[1]}'))
for i in range(3):
    case('SEARCH', 'Repeat combined search ' + str(i + 1), 'Same single result; no database changes', lambda:
         ((r := guest.request('advance_movie_search.php?cast_member=Avery&director=Morgan'))[0] == 200 and '1 matching movie</h2>' in r[1] and before == snapshot(), f'HTTP {r[0]}; unchanged={before == snapshot()}'))

# Relationship changes are exercised through the public admin forms.
mid, pid, sid = ids['movie'], ids['person'], ids['song']
for i in range(2):
    case('REPEAT', 'Add same movie/person credit ' + str(i + 1), 'HTTP 200; exactly one credit', lambda:
         ((r := admin.post('add_people.php', {'movie_id': mid, 'add': '1', 'people_id1': pid, 'role1': 'Actor', 'screen_name1': "O'Neil"}))[0] == 200 and count('movie_people', 'movie_id=' + mid) == 1,
          f'HTTP {r[0]}; credits={count("movie_people", "movie_id=" + mid)}'))
case('RELATION', 'Batch credit includes missing person', 'HTTP 400; complete batch rolled back', lambda:
     rejected('add_people.php', {'movie_id': mid, 'add': '1', 'people_id2': pid, 'role2': 'Director', 'people_id9': 2147483647, 'role9': 'Actor'}))
case('RELATION', 'Credit for nonexistent movie', 'HTTP 400; no orphan credit', lambda:
     rejected('add_people.php', {'movie_id': 2147483647, 'add': '1', 'people_id1': pid, 'role1': 'Actor'}))
case('RELATION', 'Credit with empty role', 'HTTP 400; database unchanged', lambda:
     rejected('add_people.php', {'movie_id': mid, 'add': '1', 'people_id1': pid, 'role1': ''}))

def seed_children():
    sql(f"INSERT INTO movie_data (movie_id,language,country,genre,plot,tag_line) VALUES ({mid},'English','USA','Drama','Plot','Tag');"
        f"INSERT INTO movie_media (movie_id,m_link,m_link_type) VALUES ({mid},'week9-no-file','jpg');"
        f"INSERT INTO movie_keywords (movie_id,keyword) VALUES ({mid},'week9');"
        f"INSERT INTO movie_quotes (movie_id,movie_quote_name) VALUES ({mid},'quote');"
        f"INSERT INTO movie_trivia (movie_id,movie_trivia_name) VALUES ({mid},'trivia');"
        f"INSERT INTO movie_anagrams (movie_id,anagram) VALUES ({mid},'anagram');"
        f"INSERT INTO movie_song (movie_id,song_id) VALUES ({mid},{sid});"
        f"INSERT INTO song_people (song_id,people_id,role) VALUES ({sid},{pid},'Singer');"
        f"INSERT INTO people_trivia (people_id,people_trivia_name) VALUES ({pid},'trivia');"
        f"INSERT INTO song_keywords (song_id,keyword) VALUES ({sid},'week9');"
        f"INSERT INTO song_media (song_id,s_link,s_link_type) VALUES ({sid},'week9-no-file','audio');"
        f"INSERT INTO song_trivia (song_id,song_trivia_name) VALUES ({sid},'trivia')")
    return True, 'Fixture created all movie/person/song child and association types'
case('DELETE', 'Prepare records with every owned relationship type', 'All children exist before deletion', seed_children)
for kind, route, key, table, children, survivors in [
    ('person', 'delete_people.php', 'people_id', 'people', ['movie_people', 'song_people', 'people_trivia'], [('movies', 'movie_id', mid), ('songs', 'song_id', sid)]),
    ('song', 'delete_song.php', 'song_id', 'songs', ['movie_song', 'song_people', 'song_media', 'song_trivia', 'song_keywords'], [('movies', 'movie_id', mid)]),
    ('movie', 'delete_movie.php', 'movie_id', 'movies', ['movie_anagrams', 'movie_data', 'movie_numbers', 'movie_trivia', 'movie_quotes', 'movie_media', 'movie_keywords', 'movie_people', 'movie_song'], [])]:
    identifier = {'person': pid, 'song': sid, 'movie': mid}[kind]
    for attempt in range(2):
        case('DELETE', f'Delete {kind}, attempt {attempt + 1}', 'HTTP 302; record absent; repeat is harmless', lambda p=route, k=key, v=identifier, t=table:
             ((r := admin.post(p, {k: v}))[0] == 302 and count(t, k + '=' + v) == 0, f'HTTP {r[0]}; remaining parents={count(t, k + "=" + v)}'))
    for child in children:
        case('DELETE', kind + ' cleanup in ' + child, 'Zero children for deleted record', lambda t=child, k=key, v=identifier:
             ((n := count(t, k + '=' + v)) == 0, f'Remaining rows={n}'))
    for parent, parent_key, parent_id in survivors:
        case('DELETE', kind + ' deletion preserves related ' + parent, 'Related entity still exists', lambda t=parent, k=parent_key, v=parent_id:
             ((n := count(t, k + '=' + v)) == 1, f'Remaining related entities={n}'))

def invalid_csv(table, text, filename='fixture.csv'):
    before = snapshot(); r = upload(table, text, filename)
    return r[0] == 400 and snapshot() == before and safe(r[1]), f'HTTP {r[0]}; atomic rollback={snapshot() == before}; diagnostics hidden={safe(r[1])}'
for label, table, text, filename in [
    ('no file', 'movies', '', None), ('wrong extension', 'movies', 'Title,Title,2020\n', 'fixture.php'),
    ('empty CSV', 'movies', '', 'empty.csv'), ('blank-only CSV', 'movies', '\n\r\n', 'blank.csv'),
    ('wrong column count', 'people', 'Only,two\n', 'fixture.csv'),
    ('bad year after valid row', 'movies', 'Atomic first,Atomic first,2020\nAtomic second,Atomic second,bad-year\n', 'fixture.csv'),
    ('overlong song after valid row', 'songs', 'Atomic song,Lyrics,Theme\n' + 'x' * 21 + ',Lyrics,Theme\n', 'fixture.csv'),
    ('invalid UTF-8', 'songs', b'\xff,Lyrics,Theme\n', 'fixture.csv'),
    ('unexpected table', 'users', 'Title,Lyrics,Theme\n', 'fixture.csv'),
    ('more than 10000 records', 'songs', 'W9 batch,Lyrics,Theme\n' * 10001, 'fixture.csv'),
    ('over 5 MB', 'songs', b'x' * (5 * 1024 * 1024 + 1), 'fixture.csv'),
    ('unclosed quoted field', 'songs', 'Broken,"unfinished\n', 'fixture.csv')]:
    case('CSV', label, 'HTTP 400; entire database unchanged', lambda t=table, x=text, f=filename: invalid_csv(t, x, f))
def valid_csv(table, text, expected, marker):
    before = count(table); r = upload(table, text)
    delta = count(table) - before
    return r[0] == 200 and delta == expected and marker in r[1], f'HTTP {r[0]}; inserted={delta}; summary correct={marker in r[1]}'
case('CSV', 'UTF-8 BOM, apostrophe, quoted comma and CRLF', '2 movies and derived rows imported', lambda:
     valid_csv('movies', '\ufeffW9 CSV one,"O\'Neil, first",2020\r\nW9 CSV two,తెలుగు,2021\r\n', 2, 'Imported 2 records'))
case('CSV', 'Repeat existing movie CSV', '0 imported, 2 skipped', lambda:
     valid_csv('movies', 'W9 CSV one,Changed text,2020\nW9 CSV two,తెలుగు,2021\n', 0, 'Imported 0 records; skipped 2'))
case('CSV', 'Duplicate movie within same upload', 'One imported, one skipped', lambda:
     valid_csv('movies', 'W9 duplicate,W9 duplicate,2020\nW9 duplicate,W9 duplicate,2020\n', 1, 'Imported 1 records; skipped 1'))
case('CSV', 'Quoted multiline lyrics and escaped double quotes', 'One song with exact newline/quotes', lambda:
     ((r := upload('songs', 'W9 CSV song,"First line\nSecond ""quoted"" line",Theme\n'))[0] == 200 and sql('SELECT HEX(lyrics) FROM songs WHERE title=' + literal('W9 CSV song')).lower() == 'First line\nSecond "quoted" line'.encode().hex(), f'HTTP {r[0]}; exact multiline lyrics=' + str(sql('SELECT HEX(lyrics) FROM songs WHERE title=' + literal('W9 CSV song')).lower() == 'First line\nSecond "quoted" line'.encode().hex())))
case('CSV', 'People CSV with apostrophe and optional empty names', 'One person imported', lambda:
     valid_csv('people', 'W9 O\'Neil,Avery,,,male\n', 1, 'Imported 1 records'))
for attempt in range(2):
    case('CSV', 'Repeat people import ' + str(attempt + 1), 'One new row per upload, as documented', lambda:
         valid_csv('people', 'W9 repeated,Avery,,,\n', 1, 'Imported 1 records'))
for attempt in range(2):
    case('CSV', 'Repeat song import ' + str(attempt + 1), 'One new row per upload, as documented', lambda:
         valid_csv('songs', 'W9 repeated song,Lyrics,Theme\n', 1, 'Imported 1 records'))
case('CSV', 'Duplicate movie cannot bypass field validation', 'HTTP 400 for blank English title; no database changes', lambda:
     invalid_csv('movies', 'W9 CSV one,,2020\n'))

# Exercise multi-page search after imports, keeping the fixture's original cases stable.
for i in range(51):
    sql('INSERT INTO movies (native_name,english_name,year_made) VALUES (' + literal('Week9 page ' + str(i)) + ',' + literal('Week9 pagination ' + str(i)) + ',2020)')
for page, rows in [(1, 50), (2, 1), (999, 1)]:
    case('SEARCH', 'Pagination requested page ' + str(page), f'51 matches; {rows} rows; out-of-range page clamped', lambda p=page, n=rows:
         ((r := guest.request('advance_movie_search.php?movie_search=Week9+pagination&page=' + str(p)))[0] == 200 and '51 matching movies' in r[1] and len(re.findall(r'<tr>\s*<td><a href="movie_info.php', r[1])) == n,
          f'HTTP {r[0]}; rendered rows={len(re.findall(r"<tr>\s*<td><a href=", r[1]))}; 51 matches={"51 matching movies" in r[1]}'))

for bad in ['0', '-1', 'abc', '1 OR 1=1', '2147483648']:
    case('DELETE', 'Delete invalid/nonexistent movie ID ' + bad, '400 for malformed IDs; harmless redirect for absent positive ID; no changes', lambda v=bad:
         ((b := snapshot()) is not None and (r := admin.post('delete_movie.php', {'movie_id': v}))[0] == (302 if v == '2147483648' else 400) and snapshot() == b,
          f'HTTP {r[0]}; unchanged={snapshot() == b}'))
old = admin.token()
case('AUTH', 'Logout', 'HTTP 302 and subsequent admin form requires login', lambda:
     ((r := admin.request('log_out.php', {'csrf_token': old}))[0] == 302 and admin.request('create_movie.php')[0] == 302, f'HTTP {r[0]}; admin access revoked={admin.request("create_movie.php")[0] == 302}'))
case('AUTH', 'Replay a write after logout', 'HTTP 302; database unchanged', lambda:
     ((b := snapshot()) is not None and (r := admin.request('createTheMovie.php', dict(movie, csrf_token=old)))[0] == 302 and snapshot() == b, f'HTTP {r[0]}; unchanged={snapshot() == b}'))

output = Path(os.environ['OMDB_WEEK9_RESULTS'])
output.write_text(json.dumps(RESULTS, indent=2, ensure_ascii=False), encoding='utf8')
with output.with_suffix('.csv').open('w', newline='', encoding='utf-8-sig') as file:
    writer = csv.DictWriter(file, fieldnames=['id', 'category', 'scenario', 'expected', 'actual', 'result'])
    writer.writeheader(); writer.writerows(RESULTS)
def cell(value): return str(value).replace('|', '\\|').replace('\n', '<br>')
lines = ['# Week 9 executed test matrix', '', '| ID | Scenario | Expected | Actual | Result |', '|---|---|---|---|---|']
lines += ['| ' + ' | '.join(cell(r[k]) for k in ['id', 'scenario', 'expected', 'actual', 'result']) + ' |' for r in RESULTS]
output.with_suffix('.md').write_text('\n'.join(lines) + '\n', encoding='utf8')
failures = sum(r['result'] == 'FAIL' for r in RESULTS)
print(f'{len(RESULTS) - failures}/{len(RESULTS)} Week 9 matrix cases passed.', flush=True)
raise SystemExit(bool(failures))
