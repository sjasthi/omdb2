# Week 9 executed test matrix

| ID | Scenario | Expected | Actual | Result |
|---|---|---|---|---|
| W9-AUTH-001 | USER signs in | HTTP 302 to index.php | HTTP 302; destination=index.php | PASS |
| W9-AUTH-002 | ADMIN signs in | HTTP 302 to index.php | HTTP 302; destination=index.php | PASS |
| W9-AUTH-003 | Guest GET add_movie_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-004 | Guest POST add_movie_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-005 | USER GET add_movie_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-006 | USER POST add_movie_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-007 | Guest GET add_movie_people_post.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-008 | Guest POST add_movie_people_post.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-009 | USER GET add_movie_people_post.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-010 | USER POST add_movie_people_post.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-011 | Guest GET add_movie_posters.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-012 | Guest POST add_movie_posters.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-013 | USER GET add_movie_posters.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-014 | USER POST add_movie_posters.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-015 | Guest GET add_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-016 | Guest POST add_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-017 | USER GET add_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-018 | USER POST add_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-019 | Guest GET add_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-020 | Guest POST add_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-021 | USER GET add_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-022 | USER POST add_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-023 | Guest GET add_the_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-024 | Guest POST add_the_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-025 | USER GET add_the_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-026 | USER POST add_the_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-027 | Guest GET attach_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-028 | Guest POST attach_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-029 | USER GET attach_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-030 | USER POST attach_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-031 | Guest GET createTheAnagram.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-032 | Guest POST createTheAnagram.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-033 | USER GET createTheAnagram.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-034 | USER POST createTheAnagram.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-035 | Guest GET createTheData.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-036 | Guest POST createTheData.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-037 | USER GET createTheData.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-038 | USER POST createTheData.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-039 | Guest GET createTheMovie.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-040 | Guest POST createTheMovie.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-041 | USER GET createTheMovie.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-042 | USER POST createTheMovie.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-043 | Guest GET create_Data.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-044 | Guest POST create_Data.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-045 | USER GET create_Data.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-046 | USER POST create_Data.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-047 | Guest GET create_movie.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-048 | Guest POST create_movie.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-049 | USER GET create_movie.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-050 | USER POST create_movie.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-051 | Guest GET create_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-052 | Guest POST create_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-053 | USER GET create_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-054 | USER POST create_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-055 | Guest GET create_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-056 | Guest POST create_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-057 | USER GET create_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-058 | USER POST create_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-059 | Guest GET delete_media.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-060 | Guest POST delete_media.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-061 | USER GET delete_media.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-062 | USER POST delete_media.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-063 | Guest GET delete_movie.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-064 | Guest POST delete_movie.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-065 | USER GET delete_movie.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-066 | USER POST delete_movie.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-067 | Guest GET delete_movie_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-068 | Guest POST delete_movie_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-069 | USER GET delete_movie_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-070 | USER POST delete_movie_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-071 | Guest GET delete_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-072 | Guest POST delete_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-073 | USER GET delete_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-074 | USER POST delete_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-075 | Guest GET delete_poster.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-076 | Guest POST delete_poster.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-077 | USER GET delete_poster.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-078 | USER POST delete_poster.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-079 | Guest GET delete_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-080 | Guest POST delete_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-081 | USER GET delete_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-082 | USER POST delete_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-083 | Guest GET delete_song1.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-084 | Guest POST delete_song1.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-085 | USER GET delete_song1.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-086 | USER POST delete_song1.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-087 | Guest GET delete_song_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-088 | Guest POST delete_song_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-089 | USER GET delete_song_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-090 | USER POST delete_song_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-091 | Guest GET games_base_chars_admin.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-092 | Guest POST games_base_chars_admin.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-093 | USER GET games_base_chars_admin.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-094 | USER POST games_base_chars_admin.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-095 | Guest GET modify.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-096 | Guest POST modify.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-097 | USER GET modify.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-098 | USER POST modify.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-099 | Guest GET modifyTheMovies.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-100 | Guest POST modifyTheMovies.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-101 | USER GET modifyTheMovies.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-102 | USER POST modifyTheMovies.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-103 | Guest GET modifyThePeople.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-104 | Guest POST modifyThePeople.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-105 | USER GET modifyThePeople.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-106 | USER POST modifyThePeople.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-107 | Guest GET modifyTheSongs.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-108 | Guest POST modifyTheSongs.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-109 | USER GET modifyTheSongs.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-110 | USER POST modifyTheSongs.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-111 | Guest GET modify_movie.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-112 | Guest POST modify_movie.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-113 | USER GET modify_movie.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-114 | USER POST modify_movie.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-115 | Guest GET modify_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-116 | Guest POST modify_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-117 | USER GET modify_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-118 | USER POST modify_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-119 | Guest GET modify_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-120 | Guest POST modify_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-121 | USER GET modify_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-122 | USER POST modify_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-123 | Guest GET post_create_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-124 | Guest POST post_create_song.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-125 | USER GET post_create_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-126 | USER POST post_create_song.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-127 | Guest GET post_modify_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-128 | Guest POST post_modify_people.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-129 | USER GET post_modify_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-130 | USER POST post_modify_people.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-131 | Guest GET setup.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-132 | Guest POST setup.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-133 | USER GET setup.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-134 | USER POST setup.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-135 | Guest GET sign_up.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-136 | Guest POST sign_up.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-137 | USER GET sign_up.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-138 | USER POST sign_up.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-139 | Guest GET song_people_list_post.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-140 | Guest POST song_people_list_post.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-141 | USER GET song_people_list_post.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-142 | USER POST song_people_list_post.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-143 | Guest GET songs_people_list.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-144 | Guest POST songs_people_list.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-145 | USER GET songs_people_list.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-146 | USER POST songs_people_list.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-147 | Guest GET movies_anagram.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-148 | Guest POST movies_anagram.php | HTTP 302; no diagnostics | HTTP 302; diagnostics hidden=True | PASS |
| W9-AUTH-149 | USER GET movies_anagram.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-150 | USER POST movies_anagram.php | HTTP 403; no diagnostics | HTTP 403; diagnostics hidden=True | PASS |
| W9-AUTH-151 | All blocked admin requests | Every database table unchanged | Tables unchanged=True | PASS |
| W9-AUTH-152 | ADMIN opens write-only URL add_movie_people_post.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-153 | ADMIN opens write-only URL add_the_song.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-154 | ADMIN opens write-only URL attach_song.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-155 | ADMIN opens write-only URL createTheData.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-156 | ADMIN opens write-only URL createTheMovie.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-157 | ADMIN opens write-only URL delete_media.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-158 | ADMIN opens write-only URL delete_movie_people.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-159 | ADMIN opens write-only URL delete_poster.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-160 | ADMIN opens write-only URL delete_song1.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-161 | ADMIN opens write-only URL delete_song_people.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-162 | ADMIN opens write-only URL modifyTheMovies.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-163 | ADMIN opens write-only URL modifyThePeople.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-164 | ADMIN opens write-only URL modifyTheSongs.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-165 | ADMIN opens write-only URL post_create_song.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-166 | ADMIN opens write-only URL post_modify_people.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-167 | ADMIN opens write-only URL song_people_list_post.php | HTTP 405 with Allow: POST | HTTP 405; Allow=POST | PASS |
| W9-AUTH-168 | Create movie with missing CSRF | HTTP 403; movie count unchanged | HTTP 403; movie delta=0 | PASS |
| W9-AUTH-169 | Create movie with invalid CSRF | HTTP 403; movie count unchanged | HTTP 403; movie delta=0 | PASS |
| W9-AUTH-170 | Create movie with invalid CSRF | HTTP 403; movie count unchanged | HTTP 403; movie delta=0 | PASS |
| W9-INPUT-001 | Movie native_name empty | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-002 | Movie native_name whitespace | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-003 | Movie native_name array | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-004 | Movie english_name empty | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-005 | Movie english_name whitespace | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-006 | Movie english_name array | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-007 | Movie year empty | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-008 | Movie year whitespace | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-009 | Movie year array | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-010 | Movie native_name exceeds 180 | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-011 | Movie english_name exceeds 180 | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-012 | Invalid release year 1900 | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-013 | Invalid release year 2156 | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-014 | Invalid release year 2020 OR 1=1 | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-015 | Invalid release year 2020.5 | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-016 | Invalid release year 99999999999999999999999999 | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-017 | create_people.php overlong stage_name | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-018 | create_people.php overlong first_name | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-019 | create_song.php overlong title | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-020 | create_song.php overlong lyrics | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-021 | create_song.php overlong theme | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-022 | create_people.php empty required field | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-023 | create_people.php array text field | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-024 | create_song.php empty required field | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-INPUT-025 | create_song.php array text field | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-URL-001 | movie_info.php invalid ID  | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-002 | movie_info.php invalid ID 0 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-003 | movie_info.php invalid ID -1 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-004 | movie_info.php invalid ID abc | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-005 | movie_info.php invalid ID 1 OR 1=1 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-006 | movie_info.php invalid ID 999999999999999999999999 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-007 | movie_info.php invalid ID ['1'] | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-008 | movie_info.php nonexistent positive ID | HTTP 404 | HTTP 404 | PASS |
| W9-URL-009 | people_info.php invalid ID  | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-010 | people_info.php invalid ID 0 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-011 | people_info.php invalid ID -1 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-012 | people_info.php invalid ID abc | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-013 | people_info.php invalid ID 1 OR 1=1 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-014 | people_info.php invalid ID 999999999999999999999999 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-015 | people_info.php invalid ID ['1'] | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-016 | people_info.php nonexistent positive ID | HTTP 404 | HTTP 404 | PASS |
| W9-URL-017 | song_info.php invalid ID  | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-018 | song_info.php invalid ID 0 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-019 | song_info.php invalid ID -1 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-020 | song_info.php invalid ID abc | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-021 | song_info.php invalid ID 1 OR 1=1 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-022 | song_info.php invalid ID 999999999999999999999999 | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-023 | song_info.php invalid ID ['1'] | HTTP 400; diagnostics hidden | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-024 | song_info.php nonexistent positive ID | HTTP 404 | HTTP 404 | PASS |
| W9-URL-025 | Unknown/CLI-only URL no-such-page.php | HTTP 404; no server path | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-026 | Unknown/CLI-only URL removed-endpoint.php | HTTP 404; no server path | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-027 | Unknown/CLI-only URL tools/week9_fixture.php | HTTP 404; no server path | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-028 | Unknown/CLI-only URL tests/week9_fixture.php | HTTP 404; no server path | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-029 | Update a nonexistent record via modifyTheMovies.php | HTTP 400; no false success; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-URL-030 | Update a nonexistent record via modifyThePeople.php | HTTP 400; no false success; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-URL-031 | Update a nonexistent record via modifyTheSongs.php | HTTP 400; no false success; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-URL-032 | modify.php valid movie_id | HTTP 200; existing admin form remains usable | HTTP 200; form present=True; diagnostics hidden=True | PASS |
| W9-URL-033 | modify.php form for deleted movie_id | HTTP 404; no empty or unrelated edit form | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-034 | modify.php form with SQL text in movie_id | HTTP 400 or redirect; never opens record 1 | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-035 | modify.php valid people_id | HTTP 200; existing admin form remains usable | HTTP 200; form present=True; diagnostics hidden=True | PASS |
| W9-URL-036 | modify.php form for deleted people_id | HTTP 404; no empty or unrelated edit form | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-037 | modify.php form with SQL text in people_id | HTTP 400 or redirect; never opens record 1 | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-038 | modify.php valid song_id | HTTP 200; existing admin form remains usable | HTTP 200; form present=True; diagnostics hidden=True | PASS |
| W9-URL-039 | modify.php form for deleted song_id | HTTP 404; no empty or unrelated edit form | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-040 | modify.php form with SQL text in song_id | HTTP 400 or redirect; never opens record 1 | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-041 | create_Data.php valid movie_id | HTTP 200; existing admin form remains usable | HTTP 200; form present=True; diagnostics hidden=True | PASS |
| W9-URL-042 | create_Data.php form for deleted movie_id | HTTP 404; no empty or unrelated edit form | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-043 | create_Data.php form with SQL text in movie_id | HTTP 400 or redirect; never opens record 1 | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-044 | add_people.php valid movie_id | HTTP 200; existing admin form remains usable | HTTP 200; form present=True; diagnostics hidden=True | PASS |
| W9-URL-045 | add_people.php form for deleted movie_id | HTTP 404; no empty or unrelated edit form | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-046 | add_people.php form with SQL text in movie_id | HTTP 400 or redirect; never opens record 1 | HTTP 302; diagnostics hidden=True | PASS |
| W9-URL-047 | add_song.php valid movie_id | HTTP 200; existing admin form remains usable | HTTP 200; form present=True; diagnostics hidden=True | PASS |
| W9-URL-048 | add_song.php form for deleted movie_id | HTTP 404; no empty or unrelated edit form | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-049 | add_song.php form with SQL text in movie_id | HTTP 400 or redirect; never opens record 1 | HTTP 302; diagnostics hidden=True | PASS |
| W9-URL-050 | songs_people_list.php valid song_id | HTTP 200; existing admin form remains usable | HTTP 200; form present=True; diagnostics hidden=True | PASS |
| W9-URL-051 | songs_people_list.php form for deleted song_id | HTTP 404; no empty or unrelated edit form | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-052 | songs_people_list.php form with SQL text in song_id | HTTP 400 or redirect; never opens record 1 | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-053 | modify_people.php valid people_id | HTTP 200; existing admin form remains usable | HTTP 200; form present=True; diagnostics hidden=True | PASS |
| W9-URL-054 | modify_people.php form for deleted people_id | HTTP 404; no empty or unrelated edit form | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-055 | modify_people.php form with SQL text in people_id | HTTP 400 or redirect; never opens record 1 | HTTP 400; diagnostics hidden=True | PASS |
| W9-URL-056 | modify_song.php valid song_id | HTTP 200; existing admin form remains usable | HTTP 200; form present=True; diagnostics hidden=True | PASS |
| W9-URL-057 | modify_song.php form for deleted song_id | HTTP 404; no empty or unrelated edit form | HTTP 404; diagnostics hidden=True | PASS |
| W9-URL-058 | modify_song.php form with SQL text in song_id | HTTP 400 or redirect; never opens record 1 | HTTP 400; diagnostics hidden=True | PASS |
| W9-ACCOUNT-001 | Empty first_name | HTTP 400; no account created | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-002 | Empty last_name | HTTP 400; no account created | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-003 | Empty email | HTTP 400; no account created | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-004 | Empty password | HTTP 400; no account created | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-005 | Empty repeat_password | HTTP 400; no account created | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-006 | invalid email | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-007 | mismatched passwords | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-008 | short password | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-009 | overlong password | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-010 | NUL in password | HTTP 400; no database changes | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-011 | Create account with attempted ADMIN role override | USER account; hashed password; no password echoed | HTTP 200; role=USER; plaintext password stored/echoed=False | PASS |
| W9-ACCOUNT-012 | Duplicate account same email | HTTP 400; existing account unchanged | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-013 | Duplicate account case variant | HTTP 400; existing account unchanged | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-ACCOUNT-014 | Login attack string ' OR 1=1 --  | HTTP 200 invalid credentials; no authenticated session | HTTP 200; admin form remains inaccessible=True | PASS |
| W9-ACCOUNT-015 | Login attack string admin@example.invalid' # | HTTP 200 invalid credentials; no authenticated session | HTTP 200; admin form remains inaccessible=True | PASS |
| W9-ACCOUNT-016 | Login attack string <script>alert(9)</script> | HTTP 200 invalid credentials; no authenticated session | HTTP 200; admin form remains inaccessible=True | PASS |
| W9-CRUD-001 | Create movie with apostrophe/markup | HTTP 302; exact text stored | HTTP 302; exact text stored=True | PASS |
| W9-CRUD-002 | Create person with apostrophe/markup | HTTP 302; exact text stored | HTTP 302; exact text stored=True | PASS |
| W9-CRUD-003 | Create song with apostrophe/markup | HTTP 302; exact text stored | HTTP 302; exact text stored=True | PASS |
| W9-XSS-001 | View hostile movie text | HTTP 200; escaped markup; no executable HTML | HTTP 200; raw markup present=False; escaped markup present=True | PASS |
| W9-XSS-002 | View hostile person text | HTTP 200; escaped markup; no executable HTML | HTTP 200; raw markup present=False; escaped markup present=True | PASS |
| W9-XSS-003 | View hostile song text | HTTP 200; escaped markup; no executable HTML | HTTP 200; raw markup present=False; escaped markup present=True | PASS |
| W9-INPUT-026 | movie title exactly 180 Unicode characters | Create succeeds at documented limit | HTTP 302; exact text stored=True | PASS |
| W9-INPUT-027 | person title exactly 30 Unicode characters | Create succeeds at documented limit | HTTP 302; exact text stored=True | PASS |
| W9-INPUT-028 | song title exactly 20 Unicode characters | Create succeeds at documented limit | HTTP 302; exact text stored=True | PASS |
| W9-SEARCH-001 | zero results | HTTP 200; exactly 0 matches; escaped text | HTTP 200; matching count=0 | PASS |
| W9-SEARCH-002 | one results | HTTP 200; exactly 1 matches; escaped text | HTTP 200; matching count=1 | PASS |
| W9-SEARCH-003 | many results | HTTP 200; exactly 2 matches; escaped text | HTTP 200; matching count=2 | PASS |
| W9-SEARCH-004 | apostrophe and Unicode results | HTTP 200; exactly 1 matches; escaped text | HTTP 200; matching count=1 | PASS |
| W9-SEARCH-005 | SQL injection results | HTTP 200; exactly 0 matches; escaped text | HTTP 200; matching count=0 | PASS |
| W9-SEARCH-006 | SQL UNION results | HTTP 200; exactly 0 matches; escaped text | HTTP 200; matching count=0 | PASS |
| W9-SEARCH-007 | XSS filter results | HTTP 200; exactly 0 matches; escaped text | HTTP 200; matching count=0 | PASS |
| W9-SEARCH-008 | literal wildcard results | HTTP 200; exactly 0 matches; escaped text | HTTP 200; matching count=0 | PASS |
| W9-SEARCH-009 | Invalid query cast_member[]=bad | HTTP 400; no diagnostics | HTTP 400; diagnostics hidden=True | PASS |
| W9-SEARCH-010 | Invalid query director=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx | HTTP 400; no diagnostics | HTTP 400; diagnostics hidden=True | PASS |
| W9-SEARCH-011 | Invalid query page=0 | HTTP 400; no diagnostics | HTTP 400; diagnostics hidden=True | PASS |
| W9-SEARCH-012 | Invalid query page=-1 | HTTP 400; no diagnostics | HTTP 400; diagnostics hidden=True | PASS |
| W9-SEARCH-013 | Invalid query page[]=1 | HTTP 400; no diagnostics | HTTP 400; diagnostics hidden=True | PASS |
| W9-SEARCH-014 | Invalid query page=99999999999999999999999999 | HTTP 400; no diagnostics | HTTP 400; diagnostics hidden=True | PASS |
| W9-SEARCH-015 | Invalid query movie_search=%FF | HTTP 400; no diagnostics | HTTP 400; diagnostics hidden=True | PASS |
| W9-SEARCH-016 | No filters | HTTP 200 with enter-a-filter guidance | HTTP 200; guidance present=True | PASS |
| W9-SEARCH-017 | Repeat combined search 1 | Same single result; no database changes | HTTP 200; unchanged=True | PASS |
| W9-SEARCH-018 | Repeat combined search 2 | Same single result; no database changes | HTTP 200; unchanged=True | PASS |
| W9-SEARCH-019 | Repeat combined search 3 | Same single result; no database changes | HTTP 200; unchanged=True | PASS |
| W9-REPEAT-001 | Add same movie/person credit 1 | HTTP 200; exactly one credit | HTTP 200; credits=1 | PASS |
| W9-REPEAT-002 | Add same movie/person credit 2 | HTTP 200; exactly one credit | HTTP 200; credits=1 | PASS |
| W9-RELATION-001 | Batch credit includes missing person | HTTP 400; complete batch rolled back | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-RELATION-002 | Credit for nonexistent movie | HTTP 400; no orphan credit | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-RELATION-003 | Credit with empty role | HTTP 400; database unchanged | HTTP 400; tables unchanged=True; diagnostics hidden=True | PASS |
| W9-DELETE-001 | Prepare records with every owned relationship type | All children exist before deletion | Fixture created all movie/person/song child and association types | PASS |
| W9-DELETE-002 | Delete person, attempt 1 | HTTP 302; record absent; repeat is harmless | HTTP 302; remaining parents=0 | PASS |
| W9-DELETE-003 | Delete person, attempt 2 | HTTP 302; record absent; repeat is harmless | HTTP 302; remaining parents=0 | PASS |
| W9-DELETE-004 | person cleanup in movie_people | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-005 | person cleanup in song_people | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-006 | person cleanup in people_trivia | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-007 | person deletion preserves related movies | Related entity still exists | Remaining related entities=1 | PASS |
| W9-DELETE-008 | person deletion preserves related songs | Related entity still exists | Remaining related entities=1 | PASS |
| W9-DELETE-009 | Delete song, attempt 1 | HTTP 302; record absent; repeat is harmless | HTTP 302; remaining parents=0 | PASS |
| W9-DELETE-010 | Delete song, attempt 2 | HTTP 302; record absent; repeat is harmless | HTTP 302; remaining parents=0 | PASS |
| W9-DELETE-011 | song cleanup in movie_song | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-012 | song cleanup in song_people | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-013 | song cleanup in song_media | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-014 | song cleanup in song_trivia | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-015 | song cleanup in song_keywords | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-016 | song deletion preserves related movies | Related entity still exists | Remaining related entities=1 | PASS |
| W9-DELETE-017 | Delete movie, attempt 1 | HTTP 302; record absent; repeat is harmless | HTTP 302; remaining parents=0 | PASS |
| W9-DELETE-018 | Delete movie, attempt 2 | HTTP 302; record absent; repeat is harmless | HTTP 302; remaining parents=0 | PASS |
| W9-DELETE-019 | movie cleanup in movie_anagrams | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-020 | movie cleanup in movie_data | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-021 | movie cleanup in movie_numbers | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-022 | movie cleanup in movie_trivia | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-023 | movie cleanup in movie_quotes | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-024 | movie cleanup in movie_media | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-025 | movie cleanup in movie_keywords | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-026 | movie cleanup in movie_people | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-DELETE-027 | movie cleanup in movie_song | Zero children for deleted record | Remaining rows=0 | PASS |
| W9-CSV-001 | no file | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-002 | wrong extension | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-003 | empty CSV | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-004 | blank-only CSV | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-005 | wrong column count | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-006 | bad year after valid row | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-007 | overlong song after valid row | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-008 | invalid UTF-8 | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-009 | unexpected table | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-010 | more than 10000 records | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-011 | over 5 MB | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-012 | unclosed quoted field | HTTP 400; entire database unchanged | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-CSV-013 | UTF-8 BOM, apostrophe, quoted comma and CRLF | 2 movies and derived rows imported | HTTP 200; inserted=2; summary correct=True | PASS |
| W9-CSV-014 | Repeat existing movie CSV | 0 imported, 2 skipped | HTTP 200; inserted=0; summary correct=True | PASS |
| W9-CSV-015 | Duplicate movie within same upload | One imported, one skipped | HTTP 200; inserted=1; summary correct=True | PASS |
| W9-CSV-016 | Quoted multiline lyrics and escaped double quotes | One song with exact newline/quotes | HTTP 200; exact multiline lyrics=True | PASS |
| W9-CSV-017 | People CSV with apostrophe and optional empty names | One person imported | HTTP 200; inserted=1; summary correct=True | PASS |
| W9-CSV-018 | Repeat people import 1 | One new row per upload, as documented | HTTP 200; inserted=1; summary correct=True | PASS |
| W9-CSV-019 | Repeat people import 2 | One new row per upload, as documented | HTTP 200; inserted=1; summary correct=True | PASS |
| W9-CSV-020 | Repeat song import 1 | One new row per upload, as documented | HTTP 200; inserted=1; summary correct=True | PASS |
| W9-CSV-021 | Repeat song import 2 | One new row per upload, as documented | HTTP 200; inserted=1; summary correct=True | PASS |
| W9-CSV-022 | Duplicate movie cannot bypass field validation | HTTP 400 for blank English title; no database changes | HTTP 400; atomic rollback=True; diagnostics hidden=True | PASS |
| W9-SEARCH-020 | Pagination requested page 1 | 51 matches; 50 rows; out-of-range page clamped | HTTP 200; rendered rows=50; 51 matches=True | PASS |
| W9-SEARCH-021 | Pagination requested page 2 | 51 matches; 1 rows; out-of-range page clamped | HTTP 200; rendered rows=1; 51 matches=True | PASS |
| W9-SEARCH-022 | Pagination requested page 999 | 51 matches; 1 rows; out-of-range page clamped | HTTP 200; rendered rows=1; 51 matches=True | PASS |
| W9-DELETE-028 | Delete invalid/nonexistent movie ID 0 | 400 for malformed IDs; harmless redirect for absent positive ID; no changes | HTTP 400; unchanged=True | PASS |
| W9-DELETE-029 | Delete invalid/nonexistent movie ID -1 | 400 for malformed IDs; harmless redirect for absent positive ID; no changes | HTTP 400; unchanged=True | PASS |
| W9-DELETE-030 | Delete invalid/nonexistent movie ID abc | 400 for malformed IDs; harmless redirect for absent positive ID; no changes | HTTP 400; unchanged=True | PASS |
| W9-DELETE-031 | Delete invalid/nonexistent movie ID 1 OR 1=1 | 400 for malformed IDs; harmless redirect for absent positive ID; no changes | HTTP 400; unchanged=True | PASS |
| W9-DELETE-032 | Delete invalid/nonexistent movie ID 2147483648 | 400 for malformed IDs; harmless redirect for absent positive ID; no changes | HTTP 302; unchanged=True | PASS |
| W9-AUTH-171 | Logout | HTTP 302 and subsequent admin form requires login | HTTP 302; admin access revoked=True | PASS |
| W9-AUTH-172 | Replay a write after logout | HTTP 302; database unchanged | HTTP 302; unchanged=True | PASS |
