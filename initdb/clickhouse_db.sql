CREATE DATABASE IF NOT EXISTS `callmedia`;

USE `callmedia`;

CREATE TABLE IF NOT EXISTS `urls_stat`(
  `created_at` DateTime,
  `url` String,
  `length` Int32
)
ENGINE = MergeTree()
PRIMARY KEY (`created_at`);