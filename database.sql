CREATE TABLE `tv_shows` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `imageUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `review` longtext COLLATE utf8_unicode_ci NOT NULL,
  `rating` smallint(6) NOT NULL,
  `reviewerName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tv_show_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6970EB0F5E3A35BB` (`tv_show_id`),
  CONSTRAINT `FK_6970EB0F5E3A35BB` FOREIGN KEY (`tv_show_id`) REFERENCES `tv_shows` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

