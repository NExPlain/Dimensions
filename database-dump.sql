-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: 2014-08-12 18:41:57
-- 服务器版本： 5.5.34
-- PHP Version: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `dimensions`
--
CREATE DATABASE IF NOT EXISTS `dimensions` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `dimensions`;

-- --------------------------------------------------------

--
-- 表的结构 `dimensions_comments`
--

DROP TABLE IF EXISTS `dimensions_comments`;
CREATE TABLE `dimensions_comments` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `model_id` bigint(20) NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- 表的结构 `dimensions_favs`
--

DROP TABLE IF EXISTS `dimensions_favs`;
CREATE TABLE `dimensions_favs` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `model_id` bigint(20) NOT NULL,
  `fav_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `dimensions_likes`
--

DROP TABLE IF EXISTS `dimensions_likes`;
CREATE TABLE `dimensions_likes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `model_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `like_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- 表的结构 `dimensions_models`
--

DROP TABLE IF EXISTS `dimensions_models`;
CREATE TABLE `dimensions_models` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'model id',
  `title` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `uploader_id` int(11) NOT NULL,
  `format` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `model_name` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `file_stamp` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `scale` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `is_private` smallint(6) NOT NULL DEFAULT '0',
  `price` varchar(500) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `description` longtext COLLATE utf8_unicode_ci,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `views` int(11) NOT NULL DEFAULT '0',
  `downloads` int(11) NOT NULL DEFAULT '0',
  `image_0` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `image_1` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_2` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_3` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_4` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image_5` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=447 ;

-- --------------------------------------------------------

--
-- 表的结构 `dimensions_purchases`
--

DROP TABLE IF EXISTS `dimensions_purchases`;
CREATE TABLE `dimensions_purchases` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL,
  `model_id` bigint(20) NOT NULL,
  `purchase_price` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `purchase_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `dimensions_users`
--

DROP TABLE IF EXISTS `dimensions_users`;
CREATE TABLE `dimensions_users` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `userpswd` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `joindate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `balance` int(11) NOT NULL DEFAULT '0',
  `avatar` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;
