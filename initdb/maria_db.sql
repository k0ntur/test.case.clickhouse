CREATE DATABASE IF NOT EXISTS `callmedia`;

USE `callmedia`;

CREATE TABLE IF NOT EXISTS `urls_stat`(
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `created_at` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
    `url` varchar(255) NOT NULL DEFAULT '',
    `length` int(11) UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
)ENGINE=InnoDB CHARSET=utf8;

CREATE USER 'user'@'%' IDENTIFIED BY 'pass';
GRANT ALL PRIVILEGES ON callmedia.* TO 'user'@'%';