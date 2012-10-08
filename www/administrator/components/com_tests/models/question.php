<?php
/**
 * @package     Tests
 * @copyright   Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class TestsModelQuestion extends JModel
{
	public function get_question( $question_id = null )
	{
		$app = JFactory::getApplication();

		if ( null == $question_id ) {
			$question_id = JRequest::getInt( 'question_id', 1 );
		}
		$test_id = JRequest::getInt( 'test_id' );

		$query = $this->_db->getQuery(true)
			->select( 'q.`id`, q.`title`, q.`seconds`, q.`media`, q.`media_type`, q.`order`,
				q.`test_id`,
				qt.`type` AS `question_type`' )
			->from( '#__test_questions AS q' )
			->leftjoin( '#__test_question_types AS qt ON qt.`id` = q.`question_type`' )
			->where( 'q.`test_id` = ' . (int) $test_id )
			->where( 'q.`order` >= ' . (int) $question_id )
			->order( 'q.`order` ASC' )
			;
		return $this->_db->setQuery( $query, 0, 1 )->loadObject();
	}

	public function get_options( $question_id )
	{
		if ( !$question_id ) {
			return array();
		}

		$query = $this->_db->getQuery(true)
			->select( 'qa.`id`, qa.`title`' )
			->from( '#__test_question_options AS qa' )
			->where( 'qa.`question_id` = ' . (int) $question_id )
			->order( 'qa.`id` ASC' )
			;
		return $this->_db->setQuery( $query )->loadObjectList();
	}

	public function max_order( $test_id )
	{
		if ( !$test_id ) {
			return 0;
		}

		$query = $this->_db->getQuery(true)
			->select( 'MAX( `order` ) AS `order`' )
			->from( '#__test_questions AS q' )
			->where( 'q.`test_id` = ' . (int) $test_id )
			;
		return $this->_db->setQuery( $query )->loadResult();
	}
}