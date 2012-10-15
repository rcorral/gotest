<?php
/**
 * @package	API
 * @version 1.5
 * @author 	Rafael Corral
 * @link 	http://www.corephp.com
 * @copyright Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');
jimport('joomla.filter.filteroutput');

class TestsApiResourceAnswer extends ApiResource
{
	public function get()
	{
		$this->plugin->setResponse( 'here is a get request' );
	}

	public function post()
	{
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$data = JRequest::get('post');

		// Let's make sure that the answer sent matches all the ids in our system
		$query = $db->getQuery( true )
			->select( 't.`id` AS `test_id`, ts.`id` AS `session_id`, tq.`id` AS `question_id`,
				tqt.`type` AS `qtype`' )
			->from( '#__test_tests AS t' )
			->leftjoin( '#__test_sessions AS ts ON ts.`test_id` = t.`id`' )
			->leftjoin( '#__test_questions AS tq ON tq.`test_id` = t.`id`' )
			->leftjoin( '#__test_question_types AS tqt ON tqt.`id` = tq.`question_type`' )
			->where( 't.`id` = ' . (int) $data['test_id'] )
			->where( 'ts.`unique_id` = ' . $db->q( $data['unique_id'] ) )
			->where( 'ts.`is_active` = 1'  )
			->where( 'tq.`id` = ' . (int) $data['question_id'] )
			->group( 'ts.`id`' )
			;
		$test_data = $db->setQuery( $query )->loadObjectList();
		if ( empty( $test_data ) || count( $test_data ) > 1 ) {
			throw new JException( 'Invalid request.', 400 );
		}
		$test_data = $test_data[0];

		// Delete all previous answers to this question for this test session
		$query->clear()
			->delete( '#__test_answers' )
			->where( '`user_id` = ' . (int) $user->get('id') )
			->where( '`session_id` = ' . (int) $test_data->session_id )
			->where( '`question_id` = ' . (int) $test_data->question_id )
			;
		$db->setQuery( $query )->query();

		// Add answer(s) to db
		$row_defaults = (int) $user->get('id') . ', ' . (int) $test_data->session_id . ', '
					. (int) $test_data->question_id . ', %s, %s';
		$tuples = array();

		// Special treatment for multiple answer questions
		if ( 'mcma' == $test_data->qtype ) {
			foreach ( $data['answer'] as $value ) {
				$tuples[] = sprintf( $row_defaults, $db->q( $value ), "''" );
			}
		} else {
			$answer_id = $answer_text = "''";

			if ( 'mcsa' == $test_data->qtype ) {
				$answer_id = (int) $data['answer'];
			} else {
				$answer_text = $db->q( (string) $data['answer'] );
			}

			$tuples[] = sprintf( $row_defaults, $answer_id, $answer_text );
		}

		$query->clear()
			->insert( '#__test_answers' )
			->columns( '`user_id`, `session_id`, `question_id`, `answer_id`, `answer_text`' )
			->values( $tuples )
			;

		$db->setQuery( $query )->query();

		$response = $this->getSuccessResponse( 201, 'Answer submitted.' );

		$this->plugin->setResponse( $response );
	}
}