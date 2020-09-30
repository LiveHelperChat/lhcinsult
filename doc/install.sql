CREATE TABLE `lhc_insult` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `msg_id` bigint(20) NOT NULL,
  `msg` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `chat_id` bigint(20) NOT NULL,
  `not_insult` tinyint(1) NOT NULL DEFAULT 0,
  `terminated` tinyint(1) NOT NULL DEFAULT 0,
  `ctime` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`),
  KEY `msg_id` (`msg_id`),
  KEY `terminated` (`terminated`),
  KEY `ctime` (`ctime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
