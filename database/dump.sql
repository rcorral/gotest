-- MySQL dump 10.13  Distrib 5.5.20, for osx10.6 (i386)
--
-- Host: localhost    Database: clicker
-- ------------------------------------------------------
-- Server version	5.5.20

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `eab78_test_answers`
--

DROP TABLE IF EXISTS `eab78_test_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eab78_test_answers` (
  `user_id` int(11) unsigned NOT NULL COMMENT 'Relation to _users',
  `anon_user_id` varchar(8) NOT NULL COMMENT 'Unique ids for anonymous tests.',
  `session_id` int(11) unsigned NOT NULL COMMENT 'Relation to _test_sessions',
  `question_id` int(11) unsigned NOT NULL COMMENT 'Relation to _test_questions',
  `answer_id` int(11) NOT NULL COMMENT 'Relation to _test_question_options',
  `answer_text` mediumtext NOT NULL COMMENT 'For text type answers (essay)',
  KEY `session_id` (`session_id`),
  KEY `question_id` (`question_id`),
  KEY `user_id` (`user_id`),
  KEY `anon_user_id` (`anon_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eab78_test_questions`
--

DROP TABLE IF EXISTS `eab78_test_questions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eab78_test_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `test_id` int(11) NOT NULL,
  `question_type` int(11) NOT NULL,
  `seconds` int(11) NOT NULL,
  `min_answers` tinyint(3) NOT NULL,
  `media` varchar(255) NOT NULL,
  `media_type` enum('','link','image','youtube') NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question_type` (`question_type`,`order`),
  KEY `test_id` (`test_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eab78_test_question_options`
--

DROP TABLE IF EXISTS `eab78_test_question_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eab78_test_question_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `valid` tinyint(3) NOT NULL COMMENT 'If 1 then it means the answer is a correct one.',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `valid` (`valid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eab78_test_question_types`
--

DROP TABLE IF EXISTS `eab78_test_question_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eab78_test_question_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  `title` varchar(255) NOT NULL,
  `html` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eab78_test_sessions`
--

DROP TABLE IF EXISTS `eab78_test_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eab78_test_sessions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `test_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `unique_id` varchar(32) NOT NULL,
  `is_active` tinyint(3) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `test_id` (`test_id`),
  KEY `unique_id` (`unique_id`),
  KEY `is_active` (`is_active`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `eab78_test_tests`
--

DROP TABLE IF EXISTS `eab78_test_tests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eab78_test_tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `sub_title` varchar(255) NOT NULL,
  `catid` int(11) unsigned NOT NULL,
  `anon` tinyint(3) NOT NULL DEFAULT '0' COMMENT 'Tells if test can be submitted anonymously',
  `published` tinyint(3) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `alias` (`alias`),
  KEY `created_by` (`created_by`),
  KEY `catid` (`catid`),
  KEY `anon` (`anon`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-11-17 21:31:19
-- Dumping rows for eab78_test_question_types table
INSERT INTO `eab78_test_question_types` VALUES (1,'mcsa','Multiple choice single answer','<div class=\"question-wrapper\">\r\n	<h3>QUESTION_TYPE<button class=\"remove-question\">Remove</button></h3>\r\n	<input type=\"hidden\" name=\"questions[QID][type_id]\" value=\"TYPE_ID\" />\r\n	<label for=\"question-QID\">Question: </label>\r\n	<input type=\"text\" name=\"questions[QID][question]\" value=\"\" id=\"question-QID\" />\r\n	<label for=\"seconds-QID\">Seconds: </label>\r\n	<input type=\"text\" name=\"questions[QID][seconds]\" value=\"\" id=\"seconds-QID\" />\r\n	<div clas=\"clr\"></div>\r\n	Answers:\r\n	<div clas=\"clr\"></div>\r\n	<div class=\"answers\">\r\n		<table a:count=\"COUNTER_START\">\r\n			<thead>\r\n				<tr>\r\n					<th>Answer</th>\r\n					<th>Correct Answer</th>\r\n					<th colspan=\"2\">&nbsp;</th>\r\n				</tr>\r\n			</thead>\r\n			<tbody>\r\n				<tr>\r\n					<td>\r\n						<input type=\"text\" name=\"questions[QID][options][COUNTER_START]\" value=\"\" class=\"input-increment clear-input\" />\r\n					</td>\r\n					<td>\r\n						<input type=\"radio\" name=\"questions[QID][answers][]\" value=\"1\" class=\"val-auto-increment\" />\r\n					</td>\r\n					<td>\r\n						<button class=\"add-new-answer\">Add</button>\r\n					</td>\r\n					<td>\r\n						<button class=\"remove-answer\">Remove</button>\r\n					</td>\r\n				</tr>\r\n			</tbody>\r\n		</table>\r\n	</div>\r\n</div>'),(2,'mcma','Multiple choice multiple answer','<div class=\"question-wrapper\">\r\n	<h3>QUESTION_TYPE<button class=\"remove-question\">Remove</button></h3>\r\n	<input type=\"hidden\" name=\"questions[QID][type_id]\" value=\"TYPE_ID\" />\r\n	<label for=\"question-QID\">Question: </label>\r\n	<input type=\"text\" name=\"questions[QID][question]\" value=\"\" id=\"question-QID\" />\r\n	<label for=\"seconds-QID\">Seconds: </label>\r\n	<input type=\"text\" name=\"questions[QID][seconds]\" value=\"\" id=\"seconds-QID\" />\r\n	<label for=\"min-answers-QID\">Minimum Answers: </label>\r\n	<input type=\"text\" name=\"questions[QID][min_answers]\" value=\"\" id=\"min-answers-QID\" />\r\n	<div clas=\"clr\"></div>\r\n	Answers:\r\n	<div clas=\"clr\"></div>\r\n	<div class=\"answers\">\r\n		<table a:count=\"COUNTER_START\">\r\n			<thead>\r\n				<tr>\r\n					<th>Answer</th>\r\n					<th>Correct Answer</th>\r\n					<th colspan=\"2\">&nbsp;</th>\r\n				</tr>\r\n			</thead>\r\n			<tbody>\r\n				<tr>\r\n					<td>\r\n						<input type=\"text\" name=\"questions[QID][options][COUNTER_START]\" value=\"\" class=\"input-increment clear-input\" />\r\n					</td>\r\n					<td>\r\n						<input type=\"checkbox\" name=\"questions[QID][answers][]\" value=\"1\" class=\"val-auto-increment\" />\r\n					</td>\r\n					<td>\r\n						<button class=\"add-new-answer\">Add</button>\r\n					</td>\r\n					<td>\r\n						<button class=\"remove-answer\">Remove</button>\r\n					</td>\r\n				</tr>\r\n			</tbody>\r\n		</table>\r\n	</div>\r\n</div>'),(3,'fitb','Fill in the blank','<div class=\"question-wrapper\">\r\n	<h3>QUESTION_TYPE<button class=\"remove-question\">Remove</button></h3>\r\n	<input type=\"hidden\" name=\"questions[QID][type_id]\" value=\"TYPE_ID\" />\r\n	<label for=\"question-QID\">Question: </label>\r\n	<input type=\"text\" name=\"questions[QID][question]\" value=\"\" id=\"question-QID\" />\r\n	<label for=\"seconds-QID\">Seconds: </label>\r\n	<input type=\"text\" name=\"questions[QID][seconds]\" value=\"\" id=\"seconds-QID\" />\r\n	<div clas=\"clr\"></div>\r\n	Answers:\r\n	<div clas=\"clr\"></div>\r\n	<div class=\"answers\">\r\n		<table a:count=\"COUNTER_START\">\r\n			<thead>\r\n				<tr>\r\n					<th>Answer</th>\r\n					<th>Correct Answer</th>\r\n					<th colspan=\"2\">&nbsp;</th>\r\n				</tr>\r\n			</thead>\r\n			<tbody>\r\n				<tr>\r\n					<td>\r\n						<input type=\"text\" name=\"questions[QID][options][COUNTER_START]\" value=\"\" class=\"input-increment clear-input\" />\r\n					</td>\r\n					<td>\r\n						<input type=\"radio\" name=\"questions[QID][answers][]\" value=\"1\" class=\"val-auto-increment\" />\r\n					</td>\r\n					<td>\r\n						<button class=\"add-new-answer\">Add</button>\r\n					</td>\r\n					<td>\r\n						<button class=\"remove-answer\">Remove</button>\r\n					</td>\r\n				</tr>\r\n			</tbody>\r\n		</table>\r\n	</div>\r\n</div>'),(4,'fitbma','Fill in the blank multiple answer','<div class=\"question-wrapper\">\r\n	<h3>QUESTION_TYPE<button class=\"remove-question\">Remove</button></h3>\r\n	<input type=\"hidden\" name=\"questions[QID][type_id]\" value=\"TYPE_ID\" />\r\n	<label for=\"question-QID\">Question: </label>\r\n	<input type=\"text\" name=\"questions[QID][question]\" value=\"\" id=\"question-QID\" />\r\n	<label for=\"seconds-QID\">Seconds: </label>\r\n	<input type=\"text\" name=\"questions[QID][seconds]\" value=\"\" id=\"seconds-QID\" />\r\n	<label for=\"min-answers-QID\">Minimum Answers: </label>\r\n	<input type=\"text\" name=\"questions[QID][min_answers]\" value=\"\" id=\"min-answers-QID\" />\r\n	<div clas=\"clr\"></div>\r\n	Answers:\r\n	<div clas=\"clr\"></div>\r\n	<div class=\"answers\">\r\n		<table a:count=\"COUNTER_START\">\r\n			<thead>\r\n				<tr>\r\n					<th>Answer</th>\r\n					<th>Correct Answer</th>\r\n					<th colspan=\"2\">&nbsp;</th>\r\n				</tr>\r\n			</thead>\r\n			<tbody>\r\n				<tr>\r\n					<td>\r\n						<input type=\"text\" name=\"questions[QID][options][COUNTER_START]\" value=\"\" class=\"input-increment clear-input\" />\r\n					</td>\r\n					<td>\r\n						<input type=\"checkbox\" name=\"questions[QID][answers][]\" value=\"1\" class=\"val-auto-increment\" />\r\n					</td>\r\n					<td>\r\n						<button class=\"add-new-answer\">Add</button>\r\n					</td>\r\n					<td>\r\n						<button class=\"remove-answer\">Remove</button>\r\n					</td>\r\n				</tr>\r\n			</tbody>\r\n		</table>\r\n	</div>\r\n</div>'),(5,'essay','Essay','<div class=\"question-wrapper\">\r\n	<h3>QUESTION_TYPE<button class=\"remove-question\">Remove</button></h3>\r\n	<input type=\"hidden\" name=\"questions[QID][type_id]\" value=\"TYPE_ID\" />\r\n	<label for=\"question-QID\">Question: </label>\r\n	<input type=\"text\" name=\"questions[QID][question]\" value=\"\" id=\"question-QID\" />\r\n	<label for=\"seconds-QID\">Seconds: </label>\r\n	<input type=\"text\" name=\"questions[QID][seconds]\" value=\"\" id=\"seconds-QID\" />\r\n	<div clas=\"clr\"></div>\r\n</div>');
