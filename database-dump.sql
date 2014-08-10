-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: 2014-08-10 17:49:12
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
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- 表的结构 `dimensions_favs`
--

DROP TABLE IF EXISTS `dimensions_favs`;
CREATE TABLE `dimensions_favs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `fav_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dimensions_likes`
--

DROP TABLE IF EXISTS `dimensions_likes`;
CREATE TABLE `dimensions_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `model_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `like_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `dimensions_models`
--

DROP TABLE IF EXISTS `dimensions_models`;
CREATE TABLE `dimensions_models` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'model id',
  `views` int(11) NOT NULL DEFAULT '0',
  `downloads` int(11) NOT NULL DEFAULT '0',
  `title` varchar(1000) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uploader` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `modelfile` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stamp` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `scale` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `rotation` varchar(500) COLLATE utf8_unicode_ci DEFAULT NULL,
  `isprivate` smallint(6) NOT NULL DEFAULT '0',
  `description` longtext COLLATE utf8_unicode_ci,
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=442 ;

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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=32 ;
