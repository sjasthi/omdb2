"""Run against an isolated OMDB test database. Credentials come only from the environment."""
import os, json, re, secrets, subprocess, urllib.parse, urllib.request, urllib.error, http.cookiejar
from pathlib import Path

BASE = os.environ.get('OMDB_TEST_URL', 'http://127.0.0.1:8785/')
MYSQL = os.environ.get('OMDB_TEST_MYSQL', r'C:\xampp\mysql\bin\mysql.exe')
DB = os.environ.get('OMDB_TEST_DB', 'omdb_week5_test')
if not DB.endswith('_test'):
    raise RuntimeError('Use an isolated database whose name ends with _test.')
RESULTS = []
class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self, req, fp, code, msg, headers, newurl): return None
class Client:
    def __init__(self):
        self.jar = http.cookiejar.CookieJar()
        self.opener = urllib.request.build_opener(urllib.request.HTTPCookieProcessor(self.jar), NoRedirect())
    def request(self, path, data=None, raw=None, headers=None):
        payload = raw if raw is not None else (urllib.parse.urlencode(data, doseq=True).encode() if data is not None else None)
        req = urllib.request.Request(BASE + path, data=payload, headers=headers or {})
        try: response = self.opener.open(req, timeout=20)
        except urllib.error.HTTPError as error: response = error
        return response.code, response.read().decode('utf-8', errors='replace'), response.headers
    def token(self, path='login.php'):
        status, body, _ = self.request(path)
        if status != 200: raise AssertionError(f'{path} returned {status}')
        match = re.search(r'name="csrf_token"\s+value="([a-f0-9]{64})"', body)
        if not match: raise AssertionError(f'No CSRF field in {path}')
        return match[1]
    def login(self, kind):
        token = self.token()
        status, _, headers = self.request('login.php', {'csrf_token': token,
            'email': os.environ['OMDB_TEST_' + kind + '_EMAIL'], 'password': os.environ['OMDB_TEST_' + kind + '_PASSWORD']})
        if status != 302 or headers.get('Location') != 'index.php': raise AssertionError(f'{kind} login failed ({status})')
        return self.token('index.php')

def check(label, condition):
    RESULTS.append({'test': label, 'passed': bool(condition)})
    if not condition: print('FAIL:', label, flush=True)
def sql(query):
    env = os.environ.copy()
    if os.environ.get('OMDB_TEST_DB_PASSWORD') is not None: env['MYSQL_PWD'] = os.environ['OMDB_TEST_DB_PASSWORD']
    return subprocess.check_output([MYSQL, '--no-defaults', '-h', '127.0.0.1', '-u', os.environ.get('OMDB_TEST_DB_USER', 'root'),
        '--batch', '--raw', '--skip-column-names', DB, '-e', query], env=env).decode('utf-8').strip()
def hexstr(value): return '0x' + value.encode('utf-8').hex()
def snapshot():
    tables = sql('SHOW TABLES').splitlines()
    return {table: sql('CHECKSUM TABLE `' + table + '`') for table in tables}
def multipart(fields, files):
    boundary = '----OMDBSecurityTestBoundary'
    parts=[]
    for key, value in fields.items():
        parts.append(f'--{boundary}\r\nContent-Disposition: form-data; name="{key}"\r\n\r\n{value}\r\n'.encode())
    for key, filename, content, content_type in files:
        parts.append(f'--{boundary}\r\nContent-Disposition: form-data; name="{key}"; filename="{filename}"\r\nContent-Type: {content_type}\r\n\r\n'.encode()+content+b'\r\n')
    return b''.join(parts)+f'--{boundary}--\r\n'.encode(), {'Content-Type': 'multipart/form-data; boundary='+boundary}

routes=json.loads((Path(__file__).parent/'admin_routes.json').read_text())
if 'movies_anagram.php' not in routes['admin']: routes['admin'].append('movies_anagram.php')
anonymous, user, admin = Client(), Client(), Client()
user.login('USER'); admin.login('ADMIN')
original = snapshot()
for route in routes['admin']:
    status, _, _ = anonymous.request(route, {'csrf_token': '0'*64, 'submit':'1'})
    check(route + ': anonymous POST requires login', status == 302)
    for method in ['GET', 'POST']:
        status, _, _ = user.request(route, None if method=='GET' else {'csrf_token': user.token('index.php'), 'submit':'1'})
        check(route + ': regular user ' + method + ' forbidden', status==403)
    for bad in [None, '0'*64, ['array-token']]:
        data={'submit':'1'}
        if bad is not None: data['csrf_token' if not isinstance(bad,list) else 'csrf_token[]'] = bad
        status, _, _ = admin.request(route, data)
        check(route + ': invalid CSRF rejected ('+str(type(bad).__name__)+')', status == 403)
for route in routes['post']:
    status, _, _ = admin.request(route)
    check(route + ': GET cannot write', status==405)
check('Rejected requests leave all tables unchanged', snapshot()==original)
for route in ['security.php','initialize.php','database.php','db_credentials.php','functions.php','user_functions.php','query_functions.php','validation_functions.php','api_methods.php','movie_search.php','nav.php','bin/functions.php']:
    status, body, _ = anonymous.request(route)
    check(route + ': internal path is inaccessible', status in [403,404] and 'DB_PASS' not in body)
for route in ['tester.php','m_in_m_tester.php','test_api_file.php','session_practice_file.php','delete_media.php?movie_id=1','delete_poster.php?file=database.php']:
    status, _, _ = admin.request(route)
    check(route + ': debug or GET action unavailable', status in [404,405])
status, body, _ = anonymous.request('login.php', {'email': "' OR 1=1 -- ", 'password':'incorrect', 'csrf_token':anonymous.token()})
check('Login injection does not authenticate', status==200 and 'Invalid email or password.' in body)
before_search = snapshot()
for client, label in [(anonymous, 'Anonymous'), (user, 'Regular user'), (admin, 'Admin')]:
    query = urllib.parse.urlencode({'cast_member': '"><script>alert(1)</script>', 'director': "O'Reilly"})
    status, body, _ = client.request('advance_movie_search.php?' + query)
    check(label + ': combined search is accessible and escapes filters', status == 200 and '<script>alert(1)</script>' not in body and '&lt;script&gt;' in body)
phrase = 'movies with Brad Pitt directed by Steven Spielberg'
for data in [None, {'text_search': phrase}]:
    status, _, headers = user.request('search.php' + ('?' + urllib.parse.urlencode({'text_search': phrase}) if data is None else ''), data)
    destination = headers.get('Location', '')
    params = urllib.parse.parse_qs(urllib.parse.urlparse(destination).query)
    check('GET and legacy POST phrases route to both filters', status == 303 and destination.startswith('advance_movie_search.php?') and params.get('cast_member') == ['Brad Pitt'] and params.get('director') == ['Steven Spielberg'])
for invalid in ['cast_member[]=bad', 'director=' + 'x' * 181, 'page[]=1', 'page=0', 'movie_search=%FF']:
    status, body, _ = user.request('advance_movie_search.php?' + invalid)
    check('Invalid movie search returns a safe validation response', status == 400 and 'Stack trace' not in body and 'C:\\' not in body)
for category, expected in [('songs', 'advance_song_search.php'), ('people', 'advance_people_search.php')]:
    status, body, _ = user.request('search_data.php?type=' + category)
    check(category + ': search category navigation still works', status == 200 and 'action="' + expected + '"' in body)
check('Movie search and validation leave database tables unchanged', snapshot() == before_search)
status, body, _ = admin.request('add_movie_posters.php?page=1%20UNION%20SELECT%201')
check('Pagination input cannot alter SQL', status==200 and 'SQL syntax' not in body)

# Authorized CRUD with hostile strings proves values are data and output is escaped.
payload = "Week5 O'Reilly <script>window.omdbXss=1</script>"
token=admin.token('create_movie.php')
status, _, _=admin.request('createTheMovie.php', {'csrf_token':token,'native_name':payload,'english_name':payload,'year':'2020'})
check('Admin creates a movie containing quotes and markup', status==302)
movie_id=sql('SELECT MAX(movie_id) FROM movies WHERE native_name='+hexstr(payload))
check('Hostile movie title stored intact', sql('SELECT native_name FROM movies WHERE movie_id='+movie_id)==payload)
for route in ['movie_info.php?movie_id='+movie_id, 'modify.php?movie_id='+movie_id, 'movies_data.php', 'advance_movie_search.php?' + urllib.parse.urlencode({'movie_search':payload})]:
    status, body, _=admin.request(route)
    check(route + ': database text is escaped', status==200 and '<script>window.omdbXss=1</script>' not in body)
check('Combined search displays the matching stored title as escaped text', '&lt;script&gt;window.omdbXss=1&lt;/script&gt;' in body and '1 matching movie' in body)
status, body, _=user.request('movie_info.php?movie_id='+movie_id)
check('Regular user can view movie safely', status==200 and '&lt;script&gt;window.omdbXss=1&lt;/script&gt;' in body)
new_title=payload+' updated'
status, _, _=admin.request('modifyTheMovies.php', {'csrf_token':admin.token('modify.php?movie_id='+movie_id),'movie_id':movie_id,'english_name_update':new_title,'year_update':'2020'})
check('Admin updates a movie safely', status==302 and sql('SELECT english_name FROM movies WHERE movie_id='+movie_id)==new_title)
# Relationship add and delete, including quotes in both role and screen name.
person_id=sql('SELECT MIN(people_id) FROM people')
screen_name = "O'Reilly <b>X</b>"
status, _, _=admin.request('add_people.php', {'csrf_token':admin.token('add_people.php?movie_id='+movie_id),'add':'1','movie_id':movie_id,'people_id1':person_id,'role1':"actor's role",'screen_name1':screen_name})
check('Admin adds movie/person relationship safely', status==200 and sql('SELECT COUNT(*) FROM movie_people WHERE movie_id='+movie_id)== '1')
status, _, _=admin.request('add_people.php', {'csrf_token':admin.token('add_people.php?movie_id='+movie_id),'delete':'1','movie_id':movie_id,'people_id1':person_id,'role1':"actor's role",'screen_name1':screen_name})
check('Admin deletes movie/person relationship safely', status==200 and sql('SELECT COUNT(*) FROM movie_people WHERE movie_id='+movie_id)=='0')
raw,headers=multipart({'csrf_token':admin.token('add_movie_posters.php'),'movie0':movie_id},[('0','payload.php',b'<?php echo "unsafe"; ?>','application/x-php')])
status,_,_=admin.request('add_movie_posters.php',raw=raw,headers=headers)
check('Executable poster upload rejected',status==400)
status,_,_=admin.request('delete_movie.php',{'csrf_token':admin.token('delete_movie.php?movie_id='+movie_id),'movie_id':movie_id})
check('Admin deletes movie and child rows',status==302 and sql('SELECT COUNT(*) FROM movies WHERE movie_id='+movie_id)=='0' and sql('SELECT COUNT(*) FROM movie_numbers WHERE movie_id='+movie_id)=='0')

# People and song forms, their alternate handlers, and user account creation.
short_title = "O'Reilly <b>X</b>"
person_data = {'csrf_token':admin.token('create_people.php'),'create_people':'1','stage_name':short_title,
    'first_name':"O'Reilly",'middle_name':'','last_name':'Week5','gender':'male'}
status,_,_=admin.request('create_people.php',person_data)
person_id=sql('SELECT MAX(people_id) FROM people WHERE stage_name='+hexstr(short_title))
check('Admin creates a person with quotes and markup',status==302 and person_id!='NULL')
status,body,_=admin.request('modify_people.php?people_id='+person_id)
check('Person edit fields escape database text',status==200 and '<b>X</b>' not in body and '&lt;b&gt;X&lt;/b&gt;' in body)
status,_,_=admin.request('post_modify_people.php',{'csrf_token':admin.token('modify_people.php?people_id='+person_id),
    'submit':'1','id':person_id,'stage_name':short_title+'2','first_name':"O'Reilly",'middle_name':'','last_name':'Week5','gender':'male'})
check('Admin modifies a person safely',status==302 and sql('SELECT stage_name FROM people WHERE people_id='+person_id)==short_title+'2')
status,_,_=admin.request('create_song.php',{'csrf_token':admin.token('create_song.php'),'create_song':'1','title':short_title,'lyrics':payload,'theme':'Week5'})
song_id=sql('SELECT MAX(song_id) FROM songs WHERE title='+hexstr(short_title))
check('Admin creates a song with quotes and markup',status==302 and song_id!='NULL')
status,_,_=admin.request('modify_song.php?song_id='+song_id,{'csrf_token':admin.token('modify_song.php?song_id='+song_id),
    'submit':'1','song_id':song_id,'title':short_title+'2','lyrics':payload,'theme':'Week5'})
check('Admin modifies a song safely',status==302 and sql('SELECT title FROM songs WHERE song_id='+song_id)==short_title+'2')
status,_,_=admin.request('songs_people_list.php',{'csrf_token':admin.token('songs_people_list.php?song_id='+song_id),
    'add':'1','song_id':song_id,'people_id1':person_id,'role1':'singer'})
check('Admin adds song/person relationship',status==200 and sql('SELECT COUNT(*) FROM song_people WHERE song_id='+song_id)=='1')
status,_,_=admin.request('delete_song.php',{'csrf_token':admin.token('delete_song.php?song_id='+song_id),'song_id':song_id})
check('Admin deletes song and relationships',status==302 and sql('SELECT COUNT(*) FROM songs WHERE song_id='+song_id)=='0'
    and sql('SELECT COUNT(*) FROM song_people WHERE song_id='+song_id)=='0')
status,_,_=admin.request('delete_people.php',{'csrf_token':admin.token('delete_people.php?people_id='+person_id),'people_id':person_id})
check('Admin deletes person',status==302 and sql('SELECT COUNT(*) FROM people WHERE people_id='+person_id)=='0')
account='week5-'+secrets.token_hex(6)+'@example.invalid'
random_password=secrets.token_urlsafe(24)
status,_,_=admin.request('sign_up.php',{'csrf_token':admin.token('sign_up.php'),'first_name':'Week5','last_name':'Test','email':account,
    'password':random_password,'repeat_password':random_password,'role':'ADMIN'})
check('Admin creates a USER account with a hashed password',status==200 and sql('SELECT role FROM users WHERE email='+hexstr(account))=='USER'
    and sql('SELECT password='+hexstr(random_password)+' FROM users WHERE email='+hexstr(account))=='0')
sql('DELETE FROM users WHERE email='+hexstr(account))
for endpoint,data in [('search.php',{'search':'1','text_search':"' UNION SELECT 1 -- "}),
    ('advance_movie_search.php',{'movie_search_button':'1','movie_search':"' OR 1=1 -- "}),
    ('advance_people_search.php',{'people_search_button':'1','people_search':"' OR 1=1 -- "}),
    ('advance_song_search.php',{'song_search_button':'1','song_search':"' OR 1=1 -- "})]:
    status,body,_=user.request(endpoint,data)
    check(endpoint+': search injection remains data',status==200 and 'SQL syntax' not in body and 'Fatal error' not in body)
status,body,_=admin.request('create_song.php',{'csrf_token':admin.token('create_song.php'),'create_song':'1','title[]':'invalid','lyrics':'','theme':''})
check('Malformed input produces a generic response without server paths',status in [400,500] and 'C:\\' not in body and 'work/omdb' not in body and 'Stack trace' not in body)


# A role change during an active session takes effect before the next write.
email=os.environ['OMDB_TEST_ADMIN_EMAIL']
role=sql('SELECT role FROM users WHERE email='+hexstr(email))
sql("UPDATE users SET role='USER' WHERE email="+hexstr(email))
try:
    status,_,_=admin.request('create_people.php',{'csrf_token':admin.token('index.php'),'create_people':'1','stage_name':'must not be created'})
    check('Demoted active session cannot write',status==403)
finally: sql('UPDATE users SET role='+hexstr(role)+' WHERE email='+hexstr(email))
sql("UPDATE users SET role='SUPER-ADMIN' WHERE email="+hexstr(email))
try:
    super_admin=Client();super_admin.login('ADMIN')
    for endpoint in ['create_movie.php','setup.php','movies_anagram.php','games_base_chars_admin.php']:
        check(endpoint+': SUPER-ADMIN uses the same admin policy',super_admin.request(endpoint)[0]==200)
finally: sql('UPDATE users SET role='+hexstr(role)+' WHERE email='+hexstr(email))
for endpoint in ['index.php','games_base_chars_user.php']:
    body=user.request(endpoint)[1]
    check(endpoint+': admin navigation hidden from regular users', 'href="setup.php"' not in body and 'href="games_base_chars_admin.php"' not in body)
# Fresh sessions avoid a stale role after restoring the fixture account.
admin=Client();admin.login('ADMIN')
old_token=admin.token('index.php')
status,_,_=admin.request('log_out.php',{'csrf_token':old_token})
check('Logout requires token and ends the session',status==302 and admin.request('create_people.php')[0]==302)

path=Path(os.environ.get('OMDB_TEST_RESULTS', str(Path(__file__).parent/'security-test-results.json')))
path.write_text(json.dumps(RESULTS,indent=2),encoding='utf-8')
failures=sum(not result['passed'] for result in RESULTS)
print(f'{len(RESULTS)-failures}/{len(RESULTS)} security checks passed. Results: {path.name}')
raise SystemExit(1 if failures else 0)
