-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- 主機: db
-- 產生時間： 2017 年 02 月 25 日 01:24
-- 伺服器版本: 5.7.17
-- PHP 版本： 7.0.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 資料庫： `lays201703`
--

-- --------------------------------------------------------

--
-- 資料表結構 `award`
--

DROP TABLE IF EXISTS `award`;
CREATE TABLE `award` (
  `award_id` int(11) NOT NULL,
  `vote_log_id` int(11) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `keychain` varchar(50) DEFAULT NULL,
  `opentime` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `fb_auth_log`
--

DROP TABLE IF EXISTS `fb_auth_log`;
CREATE TABLE `fb_auth_log` (
  `id` int(11) NOT NULL,
  `fb_id` varchar(50) NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `invoice`
--

DROP TABLE IF EXISTS `invoice`;
CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice` varchar(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `vote`
--

DROP TABLE IF EXISTS `vote`;
CREATE TABLE `vote` (
  `id` int(11) NOT NULL,
  `prod_name` varchar(50) NOT NULL,
  `vote_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 資料表結構 `vote_log`
--

DROP TABLE IF EXISTS `vote_log`;
CREATE TABLE `vote_log` (
  `vote_log_id` int(11) NOT NULL,
  `keychain` varchar(50) DEFAULT NULL,
  `fb_id` varchar(50) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `vote_id` int(11) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- 已匯出資料表的索引
--

--
-- 資料表索引 `award`
--
ALTER TABLE `award`
  ADD PRIMARY KEY (`award_id`);

--
-- 資料表索引 `fb_auth_log`
--
ALTER TABLE `fb_auth_log`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `vote`
--
ALTER TABLE `vote`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `vote_log`
--
ALTER TABLE `vote_log`
  ADD PRIMARY KEY (`vote_log_id`);

--
-- 在匯出的資料表使用 AUTO_INCREMENT
--

--
-- 使用資料表 AUTO_INCREMENT `award`
--
ALTER TABLE `award`
  MODIFY `award_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `fb_auth_log`
--
ALTER TABLE `fb_auth_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `vote`
--
ALTER TABLE `vote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- 使用資料表 AUTO_INCREMENT `vote_log`
--
ALTER TABLE `vote_log`
  MODIFY `vote_log_id` int(11) NOT NULL AUTO_INCREMENT;
