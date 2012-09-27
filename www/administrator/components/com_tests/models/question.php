<?php
defined('JPATH_PLATFORM') or die;

class TestModelQuestion extends JModelDatabase
{
	public function get_question( $question_id = null )
	{
		$app = JFactory::getApplication();

		if ( null == $question_id ) {
			$question_id = $app->input->get( 'question_id', 1 );
		}

		$query = $this->db->getQuery(true)
			->select( 'q.`id`, q.`title`, q.`seconds`, q.`media`, q.`order`, q.`test_id`,
				qt.`title` AS `question_type`' )
			->from( '`questions` AS q' )
			->leftjoin( '`question_types` AS qt ON qt.`id` = q.`question_type`' )
			->where( 'q.`test_id` = ' . (int) $app->input->get( 'test_id' ) )
			->where( 'q.`order` >= ' . (int) $question_id )
			->order( 'q.`order` ASC' )
			;
		return $this->db->setQuery( $query, 0, 1 )->loadObject();
	}

	public function get_answers( $question_id )
	{
		if ( !$question_id ) {
			return array();
		}

		$query = $this->db->getQuery(true)
			->select( 'qa.`id`, qa.`title`' )
			->from( '`question_options` AS qa' )
			->where( 'qa.`question_id` = ' . (int) $question_id )
			->order( 'qa.`id` ASC' )
			;
		return $this->db->setQuery( $query )->loadObjectList();
	}

	public function max_order( $test_id )
	{
		if ( !$test_id ) {
			return 0;
		}

		$query = $this->db->getQuery(true)
			->select( 'MAX( `order` ) AS `order`' )
			->from( '`questions` AS q' )
			->where( 'q.`test_id` = ' . (int) $test_id )
			;
		return $this->db->setQuery( $query )->loadResult();
	}
}