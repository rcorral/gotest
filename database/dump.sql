-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 17, 2012 at 09:57 AM
-- Server version: 5.5.20
-- PHP Version: 5.3.17

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `clicker`
--

-- --------------------------------------------------------

--
-- Table structure for table `vjaq6_test_answers`
--

DROP TABLE IF EXISTS `vjaq6_test_answers`;
CREATE TABLE IF NOT EXISTS `vjaq6_test_answers` (
  `user_id` int(11) unsigned NOT NULL COMMENT 'Relation to _users',
  `session_id` int(11) unsigned NOT NULL COMMENT 'Relation to _test_sessions',
  `question_id` int(11) unsigned NOT NULL COMMENT 'Relation to _test_questions',
  `answer_id` int(11) NOT NULL COMMENT 'Relation to _test_question_options',
  `answer_text` mediumtext NOT NULL COMMENT 'For text type answers (essay)',
  KEY `session_id` (`session_id`),
  KEY `question_id` (`question_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `vjaq6_test_questions`
--

DROP TABLE IF EXISTS `vjaq6_test_questions`;
CREATE TABLE IF NOT EXISTS `vjaq6_test_questions` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `vjaq6_test_question_options`
--

DROP TABLE IF EXISTS `vjaq6_test_question_options`;
CREATE TABLE IF NOT EXISTS `vjaq6_test_question_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `valid` tinyint(3) NOT NULL COMMENT 'If 1 then it means the answer is a correct one.',
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `valid` (`valid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=459 ;

-- --------------------------------------------------------

--
-- Table structure for table `vjaq6_test_question_types`
--

DROP TABLE IF EXISTS `vjaq6_test_question_types`;
CREATE TABLE IF NOT EXISTS `vjaq6_test_question_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  `title` varchar(255) NOT NULL,
  `html` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `vjaq6_test_question_types`
--

INSERT INTO `vjaq6_test_question_types` (`id`, `type`, `title`, `html`) VALUES
(1, 'mcsa', 'Multiple choice single answer', '<div class="question-wrapper">\r\n  <h3>QUESTION_TYPE<button class="remove-question">Remove</button></h3>\r\n <input type="hidden" name="questions[QID][type_id]" value="TYPE_ID" />\r\n  <label for="question-QID">Question: </label>\r\n  <input type="text" name="questions[QID][question]" value="" id="question-QID" />\r\n  <label for="seconds-QID">Seconds: </label>\r\n  <input type="text" name="questions[QID][seconds]" value="" id="seconds-QID" />\r\n  <div clas="clr"></div>\r\n  Answers:\r\n  <div clas="clr"></div>\r\n  <div class="answers">\r\n   <table a:count="COUNTER_START">\r\n     <thead>\r\n       <tr>\r\n          <th>Answer</th>\r\n         <th>Correct Answer</th>\r\n         <th colspan="2">&nbsp;</th>\r\n       </tr>\r\n     </thead>\r\n      <tbody>\r\n       <tr>\r\n          <td>\r\n            <input type="text" name="questions[QID][options][COUNTER_START]" value="" class="input-increment clear-input" />\r\n          </td>\r\n         <td>\r\n            <input type="radio" name="questions[QID][answers][]" value="1" class="val-auto-increment" />\r\n          </td>\r\n         <td>\r\n            <button class="add-new-answer">Add</button>\r\n         </td>\r\n         <td>\r\n            <button class="remove-answer">Remove</button>\r\n         </td>\r\n       </tr>\r\n     </tbody>\r\n    </table>\r\n  </div>\r\n</div>'),
(2, 'mcma', 'Multiple choice multiple answer', '<div class="question-wrapper">\r\n  <h3>QUESTION_TYPE<button class="remove-question">Remove</button></h3>\r\n <input type="hidden" name="questions[QID][type_id]" value="TYPE_ID" />\r\n  <label for="question-QID">Question: </label>\r\n  <input type="text" name="questions[QID][question]" value="" id="question-QID" />\r\n  <label for="seconds-QID">Seconds: </label>\r\n  <input type="text" name="questions[QID][seconds]" value="" id="seconds-QID" />\r\n  <label for="min-answers-QID">Minimum Answers: </label>\r\n  <input type="text" name="questions[QID][min_answers]" value="" id="min-answers-QID" />\r\n  <div clas="clr"></div>\r\n  Answers:\r\n  <div clas="clr"></div>\r\n  <div class="answers">\r\n   <table a:count="COUNTER_START">\r\n     <thead>\r\n       <tr>\r\n          <th>Answer</th>\r\n         <th>Correct Answer</th>\r\n         <th colspan="2">&nbsp;</th>\r\n       </tr>\r\n     </thead>\r\n      <tbody>\r\n       <tr>\r\n          <td>\r\n            <input type="text" name="questions[QID][options][COUNTER_START]" value="" class="input-increment clear-input" />\r\n          </td>\r\n         <td>\r\n            <input type="checkbox" name="questions[QID][answers][]" value="1" class="val-auto-increment" />\r\n         </td>\r\n         <td>\r\n            <button class="add-new-answer">Add</button>\r\n         </td>\r\n         <td>\r\n            <button class="remove-answer">Remove</button>\r\n         </td>\r\n       </tr>\r\n     </tbody>\r\n    </table>\r\n  </div>\r\n</div>'),
(3, 'fitb', 'Fill in the blank', '<div class="question-wrapper">\r\n  <h3>QUESTION_TYPE<button class="remove-question">Remove</button></h3>\r\n <input type="hidden" name="questions[QID][type_id]" value="TYPE_ID" />\r\n  <label for="question-QID">Question: </label>\r\n  <input type="text" name="questions[QID][question]" value="" id="question-QID" />\r\n  <label for="seconds-QID">Seconds: </label>\r\n  <input type="text" name="questions[QID][seconds]" value="" id="seconds-QID" />\r\n  <div clas="clr"></div>\r\n  Answers:\r\n  <div clas="clr"></div>\r\n  <div class="answers">\r\n   <table a:count="COUNTER_START">\r\n     <thead>\r\n       <tr>\r\n          <th>Answer</th>\r\n         <th>Correct Answer</th>\r\n         <th colspan="2">&nbsp;</th>\r\n       </tr>\r\n     </thead>\r\n      <tbody>\r\n       <tr>\r\n          <td>\r\n            <input type="text" name="questions[QID][options][COUNTER_START]" value="" class="input-increment clear-input" />\r\n          </td>\r\n         <td>\r\n            <input type="radio" name="questions[QID][answers][]" value="1" class="val-auto-increment" />\r\n          </td>\r\n         <td>\r\n            <button class="add-new-answer">Add</button>\r\n         </td>\r\n         <td>\r\n            <button class="remove-answer">Remove</button>\r\n         </td>\r\n       </tr>\r\n     </tbody>\r\n    </table>\r\n  </div>\r\n</div>'),
(4, 'fitbma', 'Fill in the blank multiple answer', '<div class="question-wrapper">\r\n  <h3>QUESTION_TYPE<button class="remove-question">Remove</button></h3>\r\n <input type="hidden" name="questions[QID][type_id]" value="TYPE_ID" />\r\n  <label for="question-QID">Question: </label>\r\n  <input type="text" name="questions[QID][question]" value="" id="question-QID" />\r\n  <label for="seconds-QID">Seconds: </label>\r\n  <input type="text" name="questions[QID][seconds]" value="" id="seconds-QID" />\r\n  <label for="min-answers-QID">Minimum Answers: </label>\r\n  <input type="text" name="questions[QID][min_answers]" value="" id="min-answers-QID" />\r\n  <div clas="clr"></div>\r\n  Answers:\r\n  <div clas="clr"></div>\r\n  <div class="answers">\r\n   <table a:count="COUNTER_START">\r\n     <thead>\r\n       <tr>\r\n          <th>Answer</th>\r\n         <th>Correct Answer</th>\r\n         <th colspan="2">&nbsp;</th>\r\n       </tr>\r\n     </thead>\r\n      <tbody>\r\n       <tr>\r\n          <td>\r\n            <input type="text" name="questions[QID][options][COUNTER_START]" value="" class="input-increment clear-input" />\r\n          </td>\r\n         <td>\r\n            <input type="checkbox" name="questions[QID][answers][]" value="1" class="val-auto-increment" />\r\n         </td>\r\n         <td>\r\n            <button class="add-new-answer">Add</button>\r\n         </td>\r\n         <td>\r\n            <button class="remove-answer">Remove</button>\r\n         </td>\r\n       </tr>\r\n     </tbody>\r\n    </table>\r\n  </div>\r\n</div>'),
(5, 'essay', 'Essay', '<div class="question-wrapper">\r\n <h3>QUESTION_TYPE<button class="remove-question">Remove</button></h3>\r\n <input type="hidden" name="questions[QID][type_id]" value="TYPE_ID" />\r\n  <label for="question-QID">Question: </label>\r\n  <input type="text" name="questions[QID][question]" value="" id="question-QID" />\r\n  <label for="seconds-QID">Seconds: </label>\r\n  <input type="text" name="questions[QID][seconds]" value="" id="seconds-QID" />\r\n  <div clas="clr"></div>\r\n</div>');

-- --------------------------------------------------------

--
-- Table structure for table `vjaq6_test_sessions`
--

DROP TABLE IF EXISTS `vjaq6_test_sessions`;
CREATE TABLE IF NOT EXISTS `vjaq6_test_sessions` (
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Table structure for table `vjaq6_test_tests`
--

DROP TABLE IF EXISTS `vjaq6_test_tests`;
CREATE TABLE IF NOT EXISTS `vjaq6_test_tests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `sub_title` varchar(255) NOT NULL,
  `catid` int(11) unsigned NOT NULL,
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
  KEY `catid` (`catid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
