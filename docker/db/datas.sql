-- Adminer 4.8.1 MySQL 5.5.5-10.6.4-MariaDB-1:10.6.4+maria~focal dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;


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

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1,	'CARD_READER',	'',	'2021-10-03 12:17:39'),
(2,	'LOGIN_READER',	'',	'2021-10-03 12:17:46'),
(3,	'MEMBERSHIP_UPDATER',	'',	'2021-10-03 12:18:01'),
(4,	'BASE_ROLE',	'',	'2021-10-05 11:16:17');

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

INSERT INTO `applications` (`id`, `key`, `name`, `owner`, `created_at`, `last_access`, `removed_at`) VALUES
(1,	'validAppKey',	'Active test application',	'SiMDE',	'2021-10-06 18:07:12',	'2021-10-06 18:07:12',	NULL),
(2,	'removedAppKey',	'Removed test application',	'SiMDE',	'2021-10-06 12:08:40',	'2021-10-06 12:07:48',	'2021-10-06 12:07:48');
INSERT INTO `application_roles` (`application`, `role`) VALUES
(1,	4),
(2,	4);
INSERT INTO `application_roles` (`application`, `role`) SELECT `id`, 4 FROM `applications`;

-- 2021-10-05 11:23:52