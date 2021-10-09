-- Adminer 4.8.1 MySQL 5.5.5-10.6.4-MariaDB-1:10.6.4+maria~focal dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

CREATE TABLE `applications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  `last_access` datetime NOT NULL,
  `removed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4;


CREATE TABLE `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` tinyint(5) NOT NULL,
  `uid` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `removed_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`),
  KEY `users_cards` (`user_id`),
  CONSTRAINT `users_cards` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `memberships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `debut` date NOT NULL,
  `fin` date NOT NULL,
  `montant` int(11) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `users_memberships` (`user_id`),
  CONSTRAINT `users_memberships` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4;

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`) VALUES
(1,	'MEMBERSHIPS_CAN_READ',	'',	'2021-10-03 12:15:52'),
(2,	'MEMBERSHIPS_CAN_CREATE',	'',	'2021-10-03 12:15:52'),
(3,	'MEMBERSHIPS_CAN_UPDATE',	'',	'2021-10-03 12:15:52'),
(4,	'MEMBERSHIPS_CAN_DELETE',	'',	'2021-10-03 12:15:52'),
(5,	'CARDS_CAN_READ',	'',	'2021-10-03 12:15:52'),
(6,	'CARDS_CAN_READ_LIST',	'',	'2021-10-03 12:15:52'),
(7,	'CARDS_CAN_READ_REMOVED',	'',	'2021-10-03 12:15:52'),
(8,	'CARDS_CAN_CREATE',	'',	'2021-10-03 12:15:52'),
(9,	'CARDS_CAN_UDPATE',	'',	'2021-10-03 12:15:52'),
(10,	'LOGIN_CAN_UDPATE',	'',	'2021-10-03 12:16:54'),
(11,	'LOGIN_CAN_READ',	'',	'2021-10-03 12:17:01'),
(12,	'MAIL_CAN_UDPATE',	'',	'2021-10-05 11:22:11'),
(13,	'MAIL_CAN_READ',	'',	'2021-10-05 11:22:21');

CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1,	'CARD_READER',	'',	'2021-10-03 12:17:39'),
(2,	'LOGIN_READER',	'',	'2021-10-03 12:17:46'),
(3,	'MEMBERSHIP_UPDATER',	'',	'2021-10-03 12:18:01'),
(4,	'BASE_ROLE',	'',	'2021-10-05 11:16:17');

CREATE TABLE `role_permissions` (
  `role` int(11) NOT NULL,
  `permission` int(11) NOT NULL,
  KEY `role` (`role`),
  KEY `permission` (`permission`),
  CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`),
  CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `role_permissions` (`role`, `permission`) VALUES
(3,	1),
(3,	2),
(3,	3),
(2,	1),
(2,	11),
(1,	1),
(1,	5),
(1,	11),
(3,	10),
(3,	11),
(4,	1),
(4,	2),
(4,	3),
(4,	4),
(4,	5),
(4,	8),
(4,	9),
(4,	10),
(4,	11),
(4,	13);

CREATE TABLE `application_permissions` (
  `application` int(11) NOT NULL,
  `permission` int(11) NOT NULL,
  KEY `application` (`application`),
  KEY `permission` (`permission`),
  CONSTRAINT `application_permissions_ibfk_1` FOREIGN KEY (`application`) REFERENCES `applications` (`id`),
  CONSTRAINT `application_permissions_ibfk_2` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `application_roles` (
  `application` int(11) NOT NULL,
  `role` int(11) NOT NULL,
  KEY `application` (`application`),
  KEY `role` (`role`),
  CONSTRAINT `application_roles_ibfk_1` FOREIGN KEY (`application`) REFERENCES `applications` (`id`),
  CONSTRAINT `application_roles_ibfk_2` FOREIGN KEY (`role`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` tinyint(5) NOT NULL,
  `is_adulte` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `last_access` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `user_overrides` (
  `user_id` int(11) NOT NULL,
  `prenom` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nom` varchar(128) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` tinyint(5) DEFAULT NULL,
  `is_adulte` tinyint(1) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`user_id`),
  CONSTRAINT `user_overrides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `applications` (`id`, `key`, `name`, `owner`, `created_at`, `last_access`, `removed_at`) VALUES
(1,	'validAppKey',	'Active test application',	'SiMDE',	'2021-10-06 18:07:12',	'2021-10-06 18:07:12',	NULL),
(2,	'removedAppKey',	'Removed test application',	'SiMDE',	'2021-10-06 12:08:40',	'2021-10-06 12:07:48',	'2021-10-06 12:07:48');
INSERT INTO `application_roles` (`application`, `role`) VALUES
(1,	4),
(2,	4);
INSERT INTO `application_roles` (`application`, `role`) SELECT `id`, 4 FROM `applications`;

-- 2021-10-05 11:23:52