-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 29 dec 2019 om 23:54
-- Serverversie: 10.1.38-MariaDB
-- PHP-versie: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `galerij`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `address`
--

CREATE TABLE `address` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address_street` varchar(255) NOT NULL,
  `address_number` varchar(10) NOT NULL,
  `address_appartment` varchar(100) DEFAULT NULL,
  `address_postalcode` varchar(12) NOT NULL,
  `address_city` varchar(50) NOT NULL,
  `address_country` varchar(11) NOT NULL,
  `address_created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `address_updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_start` datetime NOT NULL,
  `event_end` datetime NOT NULL,
  `event_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gallerys`
--

CREATE TABLE `gallerys` (
  `gallery_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `gallery_name` varchar(255) NOT NULL,
  `gallery_created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `gallery_included` int(4) NOT NULL DEFAULT '0',
  `gallery_imageammount` int(4) NOT NULL DEFAULT '0',
  `download` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `images`
--

CREATE TABLE `images` (
  `image_id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `image_selected` tinyint(4) NOT NULL DEFAULT '0',
  `image_created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `image_locked_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `image_locked` tinyint(4) NOT NULL DEFAULT '0',
  `additional` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `links`
--

CREATE TABLE `links` (
  `link_id` int(11) NOT NULL,
  `gallery_id` int(11) NOT NULL,
  `link_unique` varchar(255) NOT NULL,
  `link_created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `logs`
--

CREATE TABLE `logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `log_code` int(11) NOT NULL,
  `log_message` varchar(255) NOT NULL,
  `log_created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `setting_name` varchar(255) NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `settings` (`setting_id`, `setting_name`) 
VALUES 	(1, 'download'),
				(2, 'select'),
				(3, 'store_full_size'),
				(4, 'resize'),
				(5, 'visitor'),
				(6, 'personal'),
				(7, 'site_name'),
				(8, 'template');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `setting_list`
--

CREATE TABLE `settings_list` (
  `setting_list_id` int(11) NOT NULL,
	`setting_id` int(11) NOT NULL,
  `user_id` int(11),
  `setting_list_site` tinyint(2),
	`setting_list_value` varchar(255) NOT NULL,
	`setting_list_created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
	`setting_list_updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `settings_list` (`setting_list_id`, `setting_id`, `user_id`, `setting_list_site`, `setting_list_value`, `setting_list_created_at`, `setting_list_updated_at`) 
VALUES 	(1, 1, NULL, 1, 1, '2020-01-01 01:00:00', '0000-00-00 00:00:00'),
				(2, 2, NULL, 1, 1, '2020-01-01 01:00:00', '0000-00-00 00:00:00'),
				(3, 3, NULL, 1, 1, '2020-01-01 01:00:00', '0000-00-00 00:00:00'),
				(4, 4, NULL, 1, 1, '2020-01-01 01:00:00', '0000-00-00 00:00:00'),
				(5, 5, NULL, 1, 1, '2020-01-01 01:00:00', '0000-00-00 00:00:00'),
				(6, 6, NULL, 1, 1, '2020-01-01 01:00:00', '0000-00-00 00:00:00'),
				(7, 7, NULL, 1, 'To be edited', '2020-01-01 01:00:00', '0000-00-00 00:00:00'),
				(8, 8, NULL, 1, 'sandy', '2020-01-01 01:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `resets`
--

CREATE TABLE `resets` (
  `reset_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `reset_created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_name` varchar(25) NOT NULL,
  `user_firstname` varchar(25) NOT NULL,
  `user_telephone` varchar(12) DEFAULT NULL,
  `user_created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `user_updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `user_auth` int(100) NOT NULL,
  `user_language` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`address_id`,`user_id`),
  ADD KEY `address_fk0` (`user_id`);

--
-- Indexen voor tabel `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexen voor tabel `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`);

--
-- Indexen voor tabel `settings_list`
--
ALTER TABLE `settings_list`
  ADD PRIMARY KEY (`setting_list_id`, `setting_id`),
	ADD KEY `settings_list_fk0` (`setting_id`);

--
-- Indexen voor tabel `gallerys`
--
ALTER TABLE `gallerys`
  ADD PRIMARY KEY (`gallery_id`,`user_id`),
  ADD KEY `gallerys_fk0` (`user_id`);

--
-- Indexen voor tabel `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`image_id`,`gallery_id`),
  ADD KEY `images_fk0` (`gallery_id`);

--
-- Indexen voor tabel `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`link_id`,`gallery_id`),
  ADD KEY `links_fk0` (`gallery_id`);

--
-- Indexen voor tabel `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`log_id`,`user_id`);

--
-- Indexen voor tabel `resets`
--
ALTER TABLE `resets`
  ADD PRIMARY KEY (`reset_id`,`user_email`),
  ADD UNIQUE KEY `users_email` (`user_email`),
  ADD UNIQUE KEY `token` (`reset_token`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`,`user_email`),
  ADD UNIQUE KEY `email` (`user_email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `address`
--
ALTER TABLE `address`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `settings_list`
--
ALTER TABLE `settings_list`
  MODIFY `setting_list_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `gallerys`
--
ALTER TABLE `gallerys`
  MODIFY `gallery_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `images`
--
ALTER TABLE `images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `links`
--
ALTER TABLE `links`
  MODIFY `link_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `logs`
--
ALTER TABLE `logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `resets`
--
ALTER TABLE `resets`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `address`
--
ALTER TABLE `address`
  ADD CONSTRAINT `address_fk0` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Beperkingen voor tabel `address`
--
ALTER TABLE `settings_list`
  ADD CONSTRAINT `settings_list_fk0` FOREIGN KEY (`setting_id`) REFERENCES `settings` (`setting_id`);

--
-- Beperkingen voor tabel `gallerys`
--
ALTER TABLE `gallerys`
  ADD CONSTRAINT `gallerys_fk0` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Beperkingen voor tabel `images`
--
ALTER TABLE `images`
  ADD CONSTRAINT `images_fk0` FOREIGN KEY (`gallery_id`) REFERENCES `gallerys` (`gallery_id`);

--
-- Beperkingen voor tabel `links`
--
ALTER TABLE `links`
  ADD CONSTRAINT `links_fk0` FOREIGN KEY (`gallery_id`) REFERENCES `gallerys` (`gallery_id`);

--
-- Beperkingen voor tabel `resets`
--
ALTER TABLE `resets`
  ADD CONSTRAINT `resets_fk0` FOREIGN KEY (`user_email`) REFERENCES `users` (`user_email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
