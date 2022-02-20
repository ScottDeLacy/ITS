-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2+deb7u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 11, 2016 at 01:05 AM
-- Server version: 5.5.43
-- PHP Version: 5.4.4-14+deb7u12

SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `testITS`
--
CREATE DATABASE `testITS` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE testITS;

-- --------------------------------------------------------

--
-- Table structure for table `assignments`
--

CREATE TABLE IF NOT EXISTS `assignments` (
  `assignments_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `jobnumber` varchar(25) NOT NULL,
  `resource` varchar(255) NOT NULL,
  `assigntime` datetime NOT NULL,
  `jobclear` tinyint(1) NOT NULL,
  PRIMARY KEY (`assignments_id`),
  UNIQUE KEY `jobnumber` (`jobnumber`,`resource`)
) TYPE=InnoDB  AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `PageID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `address` int(7) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `mode` text NOT NULL,
  `type` text NOT NULL,
  `bitrate` text NOT NULL,
  `alerttype` text NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`PageID`)
) TYPE=InnoDB  AUTO_INCREMENT=88 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages2`
--

CREATE TABLE IF NOT EXISTS `pages2` (
  `PageID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary Key',
  `address` int(7) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `mode` text NOT NULL,
  `type` text NOT NULL,
  `bitrate` text NOT NULL,
  `alerttype` text NOT NULL,
  `message` longtext NOT NULL,
  PRIMARY KEY (`PageID`)
) TYPE=InnoDB AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE IF NOT EXISTS `resources` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'resource ID primary key',
  `unit` varchar(100) NOT NULL COMMENT 'The Unit Name',
  `name` varchar(50) NOT NULL COMMENT 'The resource name',
  PRIMARY KEY (`resource_id`),
  UNIQUE KEY `resource_id` (`resource_id`,`name`)
) TYPE=InnoDB  COMMENT='Lists unit resources (vehicles)' AUTO_INCREMENT=13 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
