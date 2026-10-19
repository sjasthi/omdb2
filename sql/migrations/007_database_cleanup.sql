-- Existing database upgrade. Back up first and stop application writes.
-- Run php tools/migrate.php --check, then --apply [--repair-legacy].
-- The CLI preflights data and resumes partially completed DDL; do not source this file directly.
-- DDL implicitly commits. Restore the backup if an unrecoverable error occurs.

ALTER TABLE `movies` ADD PRIMARY KEY (`movie_id`), MODIFY `movie_id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `people` ADD PRIMARY KEY (`people_id`), MODIFY `people_id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `songs` ADD PRIMARY KEY (`song_id`), MODIFY `song_id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `movie_anagrams` ADD PRIMARY KEY (`anagram_id`), MODIFY `anagram_id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `movie_data` ADD PRIMARY KEY (`movie_id`);

ALTER TABLE `movie_keywords` ADD PRIMARY KEY (`movie_id`,`keyword`);

ALTER TABLE `movie_media` ADD PRIMARY KEY (`movie_media_id`), MODIFY `movie_media_id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `movie_numbers` ADD PRIMARY KEY (`movie_id`);

ALTER TABLE `movie_people` ADD PRIMARY KEY (`movie_id`,`people_id`,`role`,`screen_name`);

ALTER TABLE `movie_quotes` ADD PRIMARY KEY (`movie_quote_id`), MODIFY `movie_quote_id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `movie_song` ADD PRIMARY KEY (`movie_id`,`song_id`);

ALTER TABLE `movie_trivia` ADD PRIMARY KEY (`movie_trivia_id`), MODIFY `movie_trivia_id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `mpr_test_data` ADD PRIMARY KEY (`id`), MODIFY `id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `people_trivia` ADD PRIMARY KEY (`people_trivia_id`), MODIFY `people_trivia_id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `person_matches` ADD COLUMN `match_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE `song_keywords` ADD PRIMARY KEY (`song_id`,`keyword`);

ALTER TABLE `song_matches` ADD COLUMN `match_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY;

ALTER TABLE `song_media` ADD PRIMARY KEY (`song_media_id`), MODIFY `song_media_id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `song_people` ADD PRIMARY KEY (`song_id`,`people_id`,`role`);

ALTER TABLE `song_trivia` ADD PRIMARY KEY (`song_trivia_id`), MODIFY `song_trivia_id` INT NOT NULL AUTO_INCREMENT;

ALTER TABLE `movie_anagrams` ADD CONSTRAINT `fk_movie_anagrams_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

ALTER TABLE `movie_data` ADD CONSTRAINT `fk_movie_data_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

ALTER TABLE `movie_keywords` ADD CONSTRAINT `fk_movie_keywords_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

ALTER TABLE `movie_media` ADD CONSTRAINT `fk_movie_media_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

ALTER TABLE `movie_numbers` ADD CONSTRAINT `fk_movie_numbers_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

ALTER TABLE `movie_people` ADD CONSTRAINT `fk_movie_people_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

ALTER TABLE `movie_people` ADD CONSTRAINT `fk_movie_people_people_id` FOREIGN KEY (`people_id`) REFERENCES `people` (`people_id`) ON DELETE CASCADE;

ALTER TABLE `movie_quotes` ADD CONSTRAINT `fk_movie_quotes_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

ALTER TABLE `movie_song` ADD CONSTRAINT `fk_movie_song_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

ALTER TABLE `movie_song` ADD CONSTRAINT `fk_movie_song_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE;

ALTER TABLE `movie_trivia` ADD CONSTRAINT `fk_movie_trivia_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE;

ALTER TABLE `people_trivia` ADD CONSTRAINT `fk_people_trivia_people_id` FOREIGN KEY (`people_id`) REFERENCES `people` (`people_id`) ON DELETE CASCADE;

ALTER TABLE `song_keywords` ADD CONSTRAINT `fk_song_keywords_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE;

ALTER TABLE `song_media` ADD CONSTRAINT `fk_song_media_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE;

ALTER TABLE `song_people` ADD CONSTRAINT `fk_song_people_people_id` FOREIGN KEY (`people_id`) REFERENCES `people` (`people_id`) ON DELETE CASCADE;

ALTER TABLE `song_people` ADD CONSTRAINT `fk_song_people_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE;

ALTER TABLE `song_trivia` ADD CONSTRAINT `fk_song_trivia_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE;