-- phpMyAdmin SQL Dump
-- version 3.3.9.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 23, 2014 at 02:28 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `PhotoGallery`
--
CREATE DATABASE `PhotoGallery` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `PhotoGallery`;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `commentId` int(11) NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `author` varchar(45) NOT NULL,
  `text` varchar(1000) NOT NULL,
  `photoId` int(11) NOT NULL,
  PRIMARY KEY (`commentId`),
  KEY `fk_comment_photo1_idx` (`photoId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=127 ;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` VALUES(116, '2014-10-23 09:38:58', 'Sherief', 'test', 148);
INSERT INTO `comment` VALUES(117, '2014-10-23 09:39:34', 'Sherief', 'test2', 148);
INSERT INTO `comment` VALUES(118, '2014-10-23 09:39:50', 'Sherief', 'test2', 148);
INSERT INTO `comment` VALUES(119, '2014-10-23 09:47:34', 'Sherief', 'test1', 150);
INSERT INTO `comment` VALUES(120, '2014-10-23 09:47:45', 'Sherief', 'test2', 150);
INSERT INTO `comment` VALUES(121, '2014-10-23 09:48:06', 'Sherief', 'test2', 150);
INSERT INTO `comment` VALUES(122, '2014-10-23 09:48:41', 'Sherief', 'test3', 150);
INSERT INTO `comment` VALUES(123, '2014-10-23 09:51:18', 'Sherief', 'test4', 150);
INSERT INTO `comment` VALUES(124, '2014-10-23 09:53:44', 'Sherief', 'abc', 137);

-- --------------------------------------------------------

--
-- Table structure for table `photo`
--

CREATE TABLE `photo` (
  `photoId` int(11) NOT NULL AUTO_INCREMENT,
  `uniqueId` varchar(300) NOT NULL,
  `name` varchar(100) NOT NULL,
  `size` int(11) NOT NULL,
  `caption` varchar(500) NOT NULL,
  `typeId` int(11) NOT NULL,
  PRIMARY KEY (`photoId`),
  UNIQUE KEY `uniqueName` (`name`),
  KEY `fk_photo_photoType_idx` (`typeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=152 ;

--
-- Dumping data for table `photo`
--

INSERT INTO `photo` VALUES(136, 'dee01b789e017ad2663fb170ee9f102628ad4d78.jpg', 'ferrari-f12-berlinetta-1.jpg', 404878, '', 1);
INSERT INTO `photo` VALUES(137, '1ed47d4da27c71f7104ceba6344ab79f15656984.jpg', 'ferrari-f12-berlinetta-2.jpg', 427340, '', 1);
INSERT INTO `photo` VALUES(138, '85dc87949ec0ec84b5107a1fecd4c3d73d1a9ab0.jpg', 'ferrari-f12-berlinetta-3.jpg', 470074, '', 1);
INSERT INTO `photo` VALUES(147, 'ccd5d658cc3fe05213a9ea147855a5aaf09b296a.jpg', '2013-ferrari-f12-berlinetta-72.jpg', 461537, '', 1);
INSERT INTO `photo` VALUES(148, '7c23e01d10863c3df7db1c9c144beeccbbcb8508.jpg', 'ferrari-f12-berlinetta-9.jpg', 449085, 'Test Caption', 1);
INSERT INTO `photo` VALUES(150, '60873690b553e9e138092befac11170e81eae99d.jpg', 'ferrari-f12-berlinetta-4.jpg', 358751, 'Caption', 1);
INSERT INTO `photo` VALUES(151, 'f8342dd6c93fd69f78b79df98cf17ed40badeb2b.jpg', 'IMG_0466.JPG', 2192614, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `photoType`
--

CREATE TABLE `photoType` (
  `typeId` int(1) NOT NULL,
  `type` varchar(100) NOT NULL,
  PRIMARY KEY (`typeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `photoType`
--

INSERT INTO `photoType` VALUES(1, 'image/jpeg');
INSERT INTO `photoType` VALUES(2, 'image/png');
INSERT INTO `photoType` VALUES(3, 'image/gif');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `uniqueId` varchar(256) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `firstname` varchar(45) NOT NULL DEFAULT ' ',
  `surname` varchar(45) NOT NULL DEFAULT ' ',
  PRIMARY KEY (`userId`),
  UNIQUE KEY `uniqueUserName` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` VALUES(1, 'cTrxjRZa51NOXBn0JYju', 'Admin', 'e7cf3ef4f17c3999a94f2c6f612e8a888e5b1026878e4e19398b23bd38ec221a', 'Sherief', 'Badran');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_photo1` FOREIGN KEY (`photoId`) REFERENCES `photo` (`photoId`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `fk_photo_photoType` FOREIGN KEY (`typeId`) REFERENCES `photoType` (`typeId`) ON DELETE NO ACTION ON UPDATE NO ACTION;
