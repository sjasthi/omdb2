-- OMDB2 schema only. Import into an EMPTY database; no accounts or passwords are seeded.
SET NAMES utf8mb4;

CREATE TABLE `movies` (
  `movie_id` int NOT NULL AUTO_INCREMENT,
  `native_name` varchar(180) NOT NULL,
  `english_name` varchar(180) NOT NULL,
  `year_made` year(4) NOT NULL,
  PRIMARY KEY (`movie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `people` (
  `people_id` int NOT NULL AUTO_INCREMENT,
  `stage_name` varchar(30) NOT NULL COMMENT 'Many people in the movie industry are known by short names',
  `first_name` varchar(20) NOT NULL,
  `middle_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `gender` varchar(6) NOT NULL,
  `image_name` varchar(30) NOT NULL COMMENT 'We will store the images locally on the server; This field refers to the image file name',
  PRIMARY KEY (`people_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `songs` (
  `song_id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  `lyrics` varchar(500) DEFAULT NULL,
  `theme` varchar(20) NOT NULL,
  PRIMARY KEY (`song_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(25) DEFAULT NULL,
  `last_name` varchar(25) DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'USER',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `movie_anagrams` (
  `movie_id` int(6) NOT NULL,
  `anagram_id` int NOT NULL AUTO_INCREMENT,
  `anagram` varchar(180) NOT NULL,
  PRIMARY KEY (`anagram_id`),
  CONSTRAINT `fk_movie_anagrams_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `movie_data` (
  `tag_line` varchar(100) NOT NULL,
  `movie_id` int(6) NOT NULL COMMENT 'This is both PK and FK; movie_data is a WEAK entity',
  `language` varchar(10) NOT NULL,
  `country` varchar(20) NOT NULL,
  `genre` varchar(15) NOT NULL,
  `plot` varchar(500) NOT NULL,
  PRIMARY KEY (`movie_id`),
  CONSTRAINT `fk_movie_data_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `movie_keywords` (
  `movie_id` int(6) NOT NULL,
  `keyword` varchar(15) NOT NULL,
  PRIMARY KEY (`movie_id`,`keyword`),
  CONSTRAINT `fk_movie_keywords_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `movie_media` (
  `movie_media_id` int NOT NULL AUTO_INCREMENT,
  `m_link` varchar(100) NOT NULL,
  `m_link_type` varchar(10) NOT NULL COMMENT 'video, poster, image are three possible values.',
  `movie_id` int(6) NOT NULL,
  PRIMARY KEY (`movie_media_id`),
  CONSTRAINT `fk_movie_media_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `movie_numbers` (
  `movie_id` int(6) NOT NULL COMMENT 'This is both PK and FK; movie_numbers is a WEAK entity',
  `running_time` int(3) DEFAULT NULL COMMENT 'Running Time in Minutes',
  `length` int(2) DEFAULT NULL COMMENT 'length (depends on the native_name)',
  `strength` int(2) DEFAULT NULL COMMENT 'strengh (depends on the native_name)',
  `weight` int(2) DEFAULT NULL COMMENT 'weight (depends on native name)',
  `budget` int(8) DEFAULT NULL COMMENT 'budget in local (native) currency',
  `box_office` int(8) DEFAULT NULL COMMENT 'box office numbers in local (native) currency',
  `base_chars` varchar(540) NOT NULL,
  PRIMARY KEY (`movie_id`),
  CONSTRAINT `fk_movie_numbers_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `movie_people` (
  `movie_id` int(6) NOT NULL,
  `people_id` int(6) NOT NULL,
  `role` varchar(20) NOT NULL COMMENT 'director, producer, music director, lead actor, lead actress, supporting actor, supporting actress are possible values',
  `screen_name` varchar(30) NOT NULL,
  PRIMARY KEY (`movie_id`,`people_id`,`role`,`screen_name`),
  CONSTRAINT `fk_movie_people_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_movie_people_people_id` FOREIGN KEY (`people_id`) REFERENCES `people` (`people_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `movie_quotes` (
  `movie_id` int(6) NOT NULL,
  `movie_quote_id` int NOT NULL AUTO_INCREMENT,
  `movie_quote_name` varchar(300) NOT NULL,
  PRIMARY KEY (`movie_quote_id`),
  CONSTRAINT `fk_movie_quotes_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `movie_song` (
  `movie_id` int(6) NOT NULL,
  `song_id` int(6) NOT NULL,
  PRIMARY KEY (`movie_id`,`song_id`),
  CONSTRAINT `fk_movie_song_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_movie_song_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='This tables reflects an associative entity (movie,song)';

CREATE TABLE `movie_trivia` (
  `movie_id` int(6) NOT NULL,
  `movie_trivia_id` int NOT NULL AUTO_INCREMENT,
  `movie_trivia_name` varchar(300) NOT NULL,
  PRIMARY KEY (`movie_trivia_id`),
  CONSTRAINT `fk_movie_trivia_movie_id` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `mpr_test_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `native_name` varchar(180) NOT NULL,
  `year_made` year(4) NOT NULL,
  `stage_name` varchar(30) NOT NULL,
  `role` varchar(20) NOT NULL,
  `screen_name` varchar(30) NOT NULL,
  `execution_status` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `people_trivia` (
  `people_id` int(6) NOT NULL,
  `people_trivia_id` int NOT NULL AUTO_INCREMENT,
  `people_trivia_name` varchar(300) NOT NULL,
  PRIMARY KEY (`people_trivia_id`),
  CONSTRAINT `fk_people_trivia_people_id` FOREIGN KEY (`people_id`) REFERENCES `people` (`people_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `person_matches` (
  `match_id` int NOT NULL AUTO_INCREMENT,
  `matches` varchar(30) DEFAULT NULL,
  `type` varchar(6) DEFAULT NULL,
  PRIMARY KEY (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `song_keywords` (
  `song_id` int(5) NOT NULL,
  `keyword` varchar(15) NOT NULL,
  PRIMARY KEY (`song_id`,`keyword`),
  CONSTRAINT `fk_song_keywords_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `song_matches` (
  `match_id` int NOT NULL AUTO_INCREMENT,
  `matches` varchar(20) DEFAULT NULL,
  `type` varchar(4) DEFAULT NULL,
  PRIMARY KEY (`match_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `song_media` (
  `song_media_id` int NOT NULL AUTO_INCREMENT,
  `s_link` varchar(100) NOT NULL,
  `s_link_type` varchar(10) NOT NULL COMMENT 'audio and video are two possible values',
  `song_id` int(6) NOT NULL COMMENT 'is the FK',
  PRIMARY KEY (`song_media_id`),
  CONSTRAINT `fk_song_media_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `song_people` (
  `song_id` int(6) NOT NULL,
  `people_id` int(6) NOT NULL,
  `role` varchar(15) NOT NULL,
  PRIMARY KEY (`song_id`,`people_id`,`role`),
  CONSTRAINT `fk_song_people_people_id` FOREIGN KEY (`people_id`) REFERENCES `people` (`people_id`) ON DELETE CASCADE,
  CONSTRAINT `fk_song_people_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `song_trivia` (
  `song_id` int(6) NOT NULL,
  `song_trivia_id` int NOT NULL AUTO_INCREMENT,
  `song_trivia_name` varchar(300) NOT NULL,
  PRIMARY KEY (`song_trivia_id`),
  CONSTRAINT `fk_song_trivia_song_id` FOREIGN KEY (`song_id`) REFERENCES `songs` (`song_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `omdb_migration_archive` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `source_table` varchar(64) NOT NULL,
  `reason` varchar(100) NOT NULL,
  `row_data` longtext NOT NULL,
  `archived_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `omdb_schema_migrations` (
  `version` varchar(64) NOT NULL PRIMARY KEY,
  `applied_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
INSERT INTO omdb_schema_migrations (version) VALUES ('007_database_cleanup');
