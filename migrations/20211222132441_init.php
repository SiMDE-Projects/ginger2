<?php

use Phoenix\Migration\AbstractMigration;

class Init extends AbstractMigration
{
    protected function up(): void
    {
        $sqlStructure = /** @lang MariaDB */
            <<<EOF
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
SET NAMES utf8mb4;
CREATE TABLE `applications`
(
    `id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `key`         VARCHAR(50)  NOT NULL,
    `name`        VARCHAR(255) NOT NULL,
    `owner`       VARCHAR(255) NOT NULL,
    `created_at`  DATETIME     NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP(),
    `last_access` DATETIME     NOT NULL,
    `removed_at`  DATETIME              DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 42
  DEFAULT CHARSET = `utf8mb4`;


CREATE TABLE `cards`
(
    `id`         INT(11)                                  NOT NULL AUTO_INCREMENT,
    `user_id`    INT(11)                                  NOT NULL,
    `type`       TINYINT(5)                               NOT NULL,
    `uid`        VARCHAR(30) COLLATE `utf8mb4_unicode_ci` NOT NULL,
    `created_at` DATETIME                                 NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `removed_at` DATETIME                                          DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uid` (`uid`),
    KEY `users_cards` (`user_id`),
    CONSTRAINT `users_cards` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 5
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci`;


CREATE TABLE `memberships`
(
    `id`         INT(11)  NOT NULL AUTO_INCREMENT,
    `user_id`    INT(11)  NOT NULL,
    `debut`      DATE     NOT NULL,
    `fin`        DATE     NOT NULL,
    `montant`    INT(11)  NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `deleted_at` DATETIME          DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `users_memberships` (`user_id`),
    CONSTRAINT `users_memberships` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci`;


CREATE TABLE `permissions`
(
    `id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `created_at`  DATETIME     NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 14
  DEFAULT CHARSET = `utf8mb4`;

CREATE TABLE `roles`
(
    `id`          INT(11)      NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(100) NOT NULL,
    `description` VARCHAR(255) NOT NULL,
    `created_at`  DATETIME     NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP(),
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 5
  DEFAULT CHARSET = `utf8mb4`;

CREATE TABLE `role_permissions`
(
    `role`       INT(11) NOT NULL,
    `permission` INT(11) NOT NULL,
    KEY `role` (`role`),
    KEY `permission` (`permission`),
    CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role`) REFERENCES `roles` (`id`),
    CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`;

CREATE TABLE `application_permissions`
(
    `application` INT(11) NOT NULL,
    `permission`  INT(11) NOT NULL,
    KEY `application` (`application`),
    KEY `permission` (`permission`),
    CONSTRAINT `application_permissions_ibfk_1` FOREIGN KEY (`application`) REFERENCES `applications` (`id`),
    CONSTRAINT `application_permissions_ibfk_2` FOREIGN KEY (`permission`) REFERENCES `permissions` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`;


CREATE TABLE `application_roles`
(
    `application` INT(11) NOT NULL,
    `role`        INT(11) NOT NULL,
    KEY `application` (`application`),
    KEY `role` (`role`),
    CONSTRAINT `application_roles_ibfk_1` FOREIGN KEY (`application`) REFERENCES `applications` (`id`),
    CONSTRAINT `application_roles_ibfk_2` FOREIGN KEY (`role`) REFERENCES `roles` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`;

CREATE TABLE `users`
(
    `id`          INT(11)                                   NOT NULL AUTO_INCREMENT,
    `login`       VARCHAR(64) COLLATE `utf8mb4_unicode_ci`  NOT NULL,
    `prenom`      VARCHAR(128) COLLATE `utf8mb4_unicode_ci` NOT NULL,
    `nom`         VARCHAR(128) COLLATE `utf8mb4_unicode_ci` NOT NULL,
    `mail`        VARCHAR(200) COLLATE `utf8mb4_unicode_ci` NOT NULL,
    `type`        TINYINT(5)                                NOT NULL,
    `is_adulte`   TINYINT(1)                                NOT NULL,
    `created_at`  DATETIME                                  NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `last_access` DATETIME                                  NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `last_sync`   DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE KEY `login` (`login`)
) ENGINE = InnoDB
  AUTO_INCREMENT = 3
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci`;


CREATE TABLE `user_overrides`
(
    `user_id`    INT(11)  NOT NULL,
    `prenom`     VARCHAR(128) COLLATE `utf8mb4_unicode_ci` DEFAULT NULL,
    `nom`        VARCHAR(128) COLLATE `utf8mb4_unicode_ci` DEFAULT NULL,
    `mail`       VARCHAR(200) COLLATE `utf8mb4_unicode_ci` DEFAULT NULL,
    `card_uid`   VARCHAR(200) COLLATE `utf8mb4_unicode_ci` DEFAULT NULL,
    `type`       TINYINT(5)                                DEFAULT NULL,
    `is_adulte`  TINYINT(1)                                DEFAULT NULL,
    `created_at` DATETIME NOT NULL                         DEFAULT CURRENT_TIMESTAMP() ON UPDATE CURRENT_TIMESTAMP(),
    `ignored_at` DATETIME                                  DEFAULT NULL,
    PRIMARY KEY (`user_id`),
    CONSTRAINT `user_overrides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = `utf8mb4`
  COLLATE = `utf8mb4_unicode_ci`;
EOF;

        $sqlDatas = /** @lang MariaDB */
            <<<EOF
SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;


INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`)
VALUES (1, 'MEMBERSHIPS_CAN_READ', '', '2021-10-03 12:15:52'),
       (2, 'MEMBERSHIPS_CAN_CREATE', '', '2021-10-03 12:15:52'),
       (3, 'MEMBERSHIPS_CAN_UPDATE', '', '2021-10-03 12:15:52'),
       (4, 'MEMBERSHIPS_CAN_DELETE', '', '2021-10-03 12:15:52'),
       (5, 'CARDS_CAN_READ', '', '2021-10-03 12:15:52'),
       (6, 'CARDS_CAN_READ_LIST', '', '2021-10-03 12:15:52'),
       (7, 'CARDS_CAN_READ_REMOVED', '', '2021-10-03 12:15:52'),
       (8, 'CARDS_CAN_CREATE', '', '2021-10-03 12:15:52'),
       (9, 'CARDS_CAN_UDPATE', '', '2021-10-03 12:15:52'),
       (10, 'LOGIN_CAN_UDPATE', '', '2021-10-03 12:16:54'),
       (11, 'LOGIN_CAN_READ', '', '2021-10-03 12:17:01'),
       (12, 'MAIL_CAN_UDPATE', '', '2021-10-05 11:22:11'),
       (13, 'MAIL_CAN_READ', '', '2021-10-05 11:22:21');

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`)
VALUES (1, 'CARD_READER', '', '2021-10-03 12:17:39'),
       (2, 'LOGIN_READER', '', '2021-10-03 12:17:46'),
       (3, 'MEMBERSHIP_UPDATER', '', '2021-10-03 12:18:01'),
       (4, 'BASE_ROLE', '', '2021-10-05 11:16:17');

INSERT INTO `role_permissions` (`role`, `permission`)
VALUES (3, 1),
       (3, 2),
       (3, 3),
       (2, 1),
       (2, 11),
       (1, 1),
       (1, 5),
       (1, 11),
       (3, 10),
       (3, 11),
       (4, 1),
       (4, 2),
       (4, 3),
       (4, 4),
       (4, 5),
       (4, 8),
       (4, 9),
       (4, 10),
       (4, 11),
       (4, 13);

INSERT INTO `applications` (`id`, `key`, `name`, `owner`, `created_at`, `last_access`, `removed_at`)
VALUES (1, 'validAppKey', 'Active test application', 'SiMDE', '2021-10-06 18:07:12', '2021-10-06 18:07:12', NULL),
       (2, 'removedAppKey', 'Removed test application', 'SiMDE', '2021-10-06 12:08:40', '2021-10-06 12:07:48',
        '2021-10-06 12:07:48');
INSERT INTO `application_roles` (`application`, `role`)
VALUES (1, 4),
       (2, 4);
INSERT INTO `application_roles` (`application`, `role`)
SELECT `id`, 4
FROM `applications`;
EOF;
        $this->execute($sqlStructure);
        $this->execute($sqlDatas);
    }

    protected function down(): void
    {
        $sql = /** @lang MariaDB */
            "SET foreign_key_checks = 0;
            DROP TABLE `applications`, `application_permissions`, `application_roles`, `cards`, `memberships`, `permissions`, `phoenix_log`, `roles`, `role_permissions`, `users`, `user_overrides`;";
        $this->execute($sql);
    }
}
