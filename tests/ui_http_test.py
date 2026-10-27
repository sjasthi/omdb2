"""Week 8 UI contracts. Run only against an isolated *_test database."""
import os,json,re,urllib.request,urllib.error,urllib.parse,http.cookiejar,subprocess
from html.parser import HTMLParser
from pathlib import Path
BASE=os.environ['OMDB_TEST_URL']
DB=os.environ['OMDB_TEST_DB']
if not DB.endswith('_test'):raise RuntimeError('Use an isolated test database.')
results=[]
class NoRedirect(urllib.request.HTTPRedirectHandler):
    def redirect_request(self,*args,**kwargs):return None
class Client:
    def __init__(self):self.opener=urllib.request.build_opener(urllib.request.HTTPCookieProcessor(http.cookiejar.CookieJar()),NoRedirect())
    def get(self,path,data=None):
        request=urllib.request.Request(BASE+path,data=None if data is None else urllib.parse.urlencode(data).encode())
        try:r=self.opener.open(request,timeout=20)
        except urllib.error.HTTPError as error:r=error
        return r.code,r.read().decode('utf8'),r.headers
    def token(self,path):
        status,body,_=self.get(path)
        if status!=200:raise AssertionError((path,status))
        return re.search(r'name="csrf_token"\s+value="([a-f0-9]{64})"',body)[1]
    def login(self,role):
        status,_,_=self.get('login.php',{'csrf_token':self.token('login.php'),'email':os.environ['OMDB_TEST_'+role+'_EMAIL'],'password':os.environ['OMDB_TEST_'+role+'_PASSWORD']})
        if status!=302:raise AssertionError('Fixture login failed')
class Markup(HTMLParser):
    def __init__(self,text):
        super().__init__();self.links=[];self.forms=[];self.h1=0;self.feed(text)
    def handle_starttag(self,tag,attrs):
        attrs=dict(attrs)
        if tag=='a':self.links.append(attrs.get('href',''))
        if tag=='form':self.forms.append(attrs.get('action',''))
        if tag=='h1':self.h1+=1
def check(label,ok):
    results.append({'test':label,'passed':bool(ok)})
    if not ok:print('FAIL:',label,flush=True)
def sql(query):
    env=os.environ.copy()
    if 'OMDB_TEST_DB_PASSWORD' in env:env['MYSQL_PWD']=env['OMDB_TEST_DB_PASSWORD']
    return subprocess.check_output([os.environ.get('OMDB_TEST_MYSQL',r'C:\xampp\mysql\bin\mysql.exe'),'--no-defaults','-h','127.0.0.1','-u',env.get('OMDB_TEST_DB_USER','root'),'--batch','--skip-column-names',DB,'-e',query],env=env).decode().strip()
def snapshot():return {t:sql('CHECKSUM TABLE `'+t+'`') for t in sql('SHOW TABLES').splitlines()}
forbidden=set(json.loads((Path(__file__).parent/'admin_routes.json').read_text())['admin'])
user=Client();user.login('USER');admin=Client();admin.login('ADMIN');guest=Client()
before=snapshot()
pages=['index.php','movies.php','people.php','songs.php','help.php','about.php','advance_movie_search.php',
    'search_data.php?type=songs','search_data.php?type=people','reports.php','reports_actors.php','reports_actors_actresses.php','reports_lyricist.php',
    'movies_data.php','movies_keywords.php','movies_media.php','movies_numbers.php','movies_quotes.php','movies_trivia.php','people_trivia.php',
    'songs_keyword.php','songs_media.php','songs_trivia.php','query_movies.php','query_songs.php','games_base_chars_user.php','anagrammer.php','movie_in_movies.php',
    'movie_info.php?movie_id=1','people_info.php?people_id=1','song_info.php?song_id=1']
for page in pages:
    status,body,_=user.get(page);markup=Markup(body)
    bad=[link for link in markup.links+markup.forms if urllib.parse.urlsplit(link).path.split('/')[-1] in forbidden]
    check(page+': member view works without technical errors',status==200 and not any(x in body for x in ['Fatal error','Stack trace','SQL syntax','Warning:']))
    check(page+': admin controls absent from rendered links/forms',not bad)
    check(page+': one shared page heading',markup.h1==1)
for client,label in [(guest,'Guest'),(user,'Member'),(admin,'Admin')]:
    for page in ['movies.php','people.php','songs.php']:
        status,body,_=client.get(page)
        config=json.loads(re.search(r'<script type="application/json" id="catalog-config">(.*?)</script>',body,re.S)[1])
        actions=[a['label'] for a in config['actions']]
        check(label+' '+page+': role-specific catalog actions',('Edit' in actions and 'Delete' in actions) if label=='Admin' else actions==['View'])
    _,help_body,_=client.get('help.php')
    check(label+': Help documents the combined search','movies with Brad Pitt directed by Steven Spielberg' in help_body)
    check(label+': Help admin guide matches permissions',('id="admin-help"' in help_body)==(label=='Admin'))
for page,target in [('movies_movies.php','movies.php'),('people_people.php','people.php'),('songs_list.php','songs.php')]:
    status,_,headers=user.get(page)
    check(page+': duplicate URL uses canonical catalog',status==302 and headers.get('Location')==target)
for page in ['create_movie.php','create_people.php','create_song.php','setup.php','sign_up.php','modify_people.php?people_id=1','modify_song.php?song_id=1']:
    status,body,_=admin.get(page)
    check(page+': admin form uses shared heading and labels',status==200 and Markup(body).h1==1 and '<label' in body)
_,body,_=admin.get('modify_people.php?people_id=1')
check('Person form has no dead wizard code','currentTab' not in body and 'nextPrev' not in body)
status,body,_=user.get('reports_actors_actresses.php?name1=missing&name2=missing')
check('Empty shared-cast report has a usable no-match message',status==200 and 'No movies match both people' in body)
status,body,_=user.get('anagrammer.php',{'movie_id':'1','input':'Demo film 1'})
check('Member can solve a puzzle without receiving admin contribution controls',status==200 and 'createTheAnagram.php' not in body and 'Demo film 1' in body)
check('Read-only UI audit leaves database unchanged',before==snapshot())
token=admin.token('create_people.php')
status,body,_=admin.get('create_people.php',{'csrf_token':token,'stage_name':'','first_name':'Retained Name'})
check('Person validation is specific and retains safe input',status==400 and 'stage name' in body and 'value="Retained Name"' in body and 'role="alert"' in body)
token=admin.token('create_movie.php')
status,body,_=admin.get('createTheMovie.php',{'csrf_token':token,'native_name':'Retained title','english_name':'','year':'2020'})
check('Movie validation returns the usable form with entered title',status==400 and 'English title' in body and 'value="Retained title"' in body and 'Create movie' in body)
status,body,_=admin.get('create_song.php',{'csrf_token':admin.token('create_song.php'),'title':'x'*21,'lyrics':'Retained lyrics'})
check('Song length error states the limit and preserves lyrics',status==400 and '20 characters' in body and 'Retained lyrics' in body)
status,body,_=admin.get('createTheMovie.php',{'csrf_token':admin.token('create_movie.php'),'native_name':'Unsupported year','english_name':'Unsupported year','year':'1888'})
check('Unsupported release year is explained before a database write',status==400 and '1901' in body and 'SQL' not in body)
for page in ['movie_info.php?movie_id=2147483647','people_info.php?people_id=2147483647','song_info.php?song_id=2147483647']:
    status,body,_=user.get(page)
    check(page+': missing record has a helpful 404',status==404 and 'no longer in the catalog' in body)
status,body,_=user.get('setup.php')
check('Saved admin URL remains denied with styled safe response',status==403 and 'Administrator access is required.' in body and '<h1>' in body and 'styles/ui.css' in body)
status,body,_=user.get('ui.php')
check('Shared UI helper cannot be opened directly',status in [403,404])
check('Validation failures leave database unchanged',before==snapshot())
out=Path(os.environ.get('OMDB_UI_RESULTS','ui-http-results.json'));out.write_text(json.dumps(results,indent=2),encoding='utf8')
failed=sum(not r['passed'] for r in results)
print(f'{len(results)-failed}/{len(results)} UI checks passed.',flush=True)
raise SystemExit(bool(failed))
