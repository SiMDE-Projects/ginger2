-- Adminer 4.8.1 MySQL 5.5.5-10.6.4-MariaDB-1:10.6.4+maria~focal dump

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

-- 2021-10-05 11:23:52
