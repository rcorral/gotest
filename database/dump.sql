-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2012 at 08:12 PM
-- Server version: 5.6.3
-- PHP Version: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `clicker`
--

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `test_id` int(11) NOT NULL,
  `question_type` int(11) NOT NULL,
  `seconds` int(11) NOT NULL,
  `media` varchar(255) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_type` (`question_type`,`order`),
  KEY `test_id` (`test_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `title`, `test_id`, `question_type`, `seconds`, `media`, `order`) VALUES
(1, 'Do you like cats?', 1, 1, 30, 'BIGCAT.JPG', 1),
(2, 'Answers a and b are correct', 1, 2, 45, '', 2),
(3, 'What is this object?', 1, 3, 45, 'http://techgenie.com/wp-content/uploads/Motherboard1.jpg', 3),
(4, 'How do you spell my last name', 1, 3, 45, '', 4),
(5, 'Quoth the Raven _________', 1, 4, 45, '', 5),
(6, 'Name 2 food groups', 1, 4, 45, '', 6),
(7, 'Why was the 10 day war over so quickly?', 1, 5, 180, '', 7),
(8, 'Which political party do you prefer?', 1, 1, 60, '', 8),
(9, 'What is your favorite color?', 1, 3, 120, '', 9);

-- --------------------------------------------------------

--
-- Table structure for table `question_answers`
--

CREATE TABLE IF NOT EXISTS `question_answers` (
  `question_id` int(11) NOT NULL,
  `answer` varchar(255) NOT NULL,
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `question_answers`
--

INSERT INTO `question_answers` (`question_id`, `answer`) VALUES
(1, 'c'),
(2, 'a'),
(2, 'b'),
(3, 'Motherboard'),
(4, 'Fedele'),
(5, '1'),
(6, '2'),
(8, '0'),
(9, '0');

-- --------------------------------------------------------

--
-- Table structure for table `question_possible_answers`
--

CREATE TABLE IF NOT EXISTS `question_possible_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `question_possible_answers`
--

INSERT INTO `question_possible_answers` (`id`, `question_id`, `title`) VALUES
(1, 1, 'Yes'),
(2, 1, 'No'),
(3, 1, 'Yes, but I can''t eat a whole one by myself.'),
(4, 2, 'asdf'),
(5, 2, 'asdf'),
(6, 2, 'oiuy'),
(7, 2, 'kjhg'),
(8, 5, 'nevermore'),
(9, 5, 'wtf'),
(10, 6, 'meats'),
(11, 6, 'grains'),
(12, 6, 'fruits'),
(13, 6, 'vegetables'),
(14, 6, 'dairy'),
(15, 8, 'Democrat'),
(16, 8, 'Republican'),
(17, 8, 'Libritarian'),
(18, 8, 'Green'),
(19, 8, 'Pirate'),
(20, 8, 'Communist'),
(21, 8, 'Socialist'),
(22, 8, 'Independent'),
(23, 8, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `question_types`
--

CREATE TABLE IF NOT EXISTS `question_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `question_types`
--

INSERT INTO `question_types` (`id`, `title`) VALUES
(1, 'mcsa'),
(2, 'mcma'),
(3, 'fitb'),
(4, 'fitbma'),
(5, 'essay');

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE IF NOT EXISTS `tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `sub_title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tests`
--

INSERT INTO `tests` (`id`, `title`, `sub_title`) VALUES
(1, 'History', 'Sojourner Truth');
