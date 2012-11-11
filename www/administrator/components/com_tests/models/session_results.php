<?php
/**
 * @package     Tests
 * @copyright   Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class TestsModelSession_Results extends JModel
{
	/**
	 * Get test from request
	 */
	public function getTest()
	{
		$session_id = JRequest::getInt( 'id' );
		$user = JFactory::getUser();

		$query = $this->_db->getQuery(true)
			->select( 't.*, ts.`date` AS `administration_date`' )
			->from( '#__test_tests AS t' )
			->leftjoin( '#__test_sessions AS ts ON ts.`test_id` = t.`id`' )
			->where( 'ts.`id` = ' . (int) $session_id )
			;

		if ( !$user->authorise('core.admin') ) {
			$query->where( 'ts.`user_id` = ' . (int) $user->get('id') );
		}

		return $this->_db->setQuery( $query )->loadObject();
	}

	/**
	 * Get all questions for this test with their options
	 */
	public function getQuestions()
	{
		$session_id = JRequest::getInt( 'id' );
		$user = JFactory::getUser();

		$query = $this->_db->getQuery(true)
			->select( 'tq.`id`, tq.`title`, tqt.`type`' )
			->from( '#__test_tests AS t' )
			->leftjoin( '#__test_sessions AS ts ON ts.`test_id` = t.`id`' )
			->leftjoin( '#__test_questions AS tq ON tq.`test_id` = t.`id`' )
			->leftjoin( '#__test_question_types AS tqt ON tqt.`id` = tq.`question_type`' )
			->where( 'ts.`id` = ' . (int) $session_id )
			->order( 'tq.`order`' )
			;

		if ( !$user->authorise('core.admin') ) {
			$query->where( 'ts.`user_id` = ' . (int) $user->get('id') );
		}

		$questions = $this->_db->setQuery( $query )->loadObjectList();

		foreach ( $questions as &$question ) {
			$query->clear()
				->select( 'tqo.*' )
				->from( '#__test_question_options AS tqo' )
				->where( 'tqo.`question_id` = ' . (int) $question->id )
				;
			$question->options = $this->_db->setQuery( $query )->loadObjectList();
		}

		return $questions;
	}

	/**
	 * Gets all answers for test ordered by student
	 */
	public function getStudentAnswers()
	{
		$session_id = JRequest::getInt( 'id' );
		$user = JFactory::getUser();

		$query = $this->_db->getQuery(true)
			->select( 'u.`id` AS `user_id`, u.`name`, u.`email`, ta.`question_id`,
				IF( ta.`answer_id`, tqo.`title`, ta.`answer_text` ) as `answer`' )
			->from( '#__test_sessions AS ts' )
			->leftjoin( '#__test_tests AS t ON t.`id` = ts.`test_id`' )
			->leftjoin( '#__test_answers AS ta ON ta.`session_id` = ts.`id`' )
			->leftjoin( '#__test_questions AS tq ON tq.`id` = ta.`question_id`' )
			->leftjoin( '#__test_question_options AS tqo ON tqo.`id` = ta.`answer_id`' )
			->leftjoin( '#__users AS u ON u.`id` = ta.`user_id`' )
			->where( 'ts.`id` = ' . (int) $session_id )
			->order( 'u.`name` ASC, u.`id`, tq.`order` ASC' )
			;

		if ( !$user->authorise('core.admin') ) {
			$query->where( 'ts.`user_id` = ' . (int) $user->get('id') );
		}

		return $this->_db->setQuery( $query )->loadObjectList();
	}
}
