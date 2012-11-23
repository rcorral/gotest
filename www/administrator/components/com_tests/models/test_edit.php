<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

class TestsModelTest_Edit extends JModelAdmin
{
	/**
	 * Returns a Table object, always creating it
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 * @return	JTable	A database object
	*/
	public function getTable( $type = 'Test', $prefix = 'TestsTable', $config = array() )
	{
		return JTable::getInstance( $type, $prefix, $config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		// Load the User state.
		$id = (int) JRequest::getInt( 'id' );
		$this->setState( 'test.id', $id );
	}

	/**
	 * Method override to check-in a record or an array of record
	 *
	 * @param   mixed  $pks  The ID of the primary key or an array of IDs
	 * @return  mixed  Boolean false if there is an error, otherwise the count of records checked in.
	 */
	public function checkin( $pks = array() )
	{
		// Initialise variables.
		$pks = (array) $pks;
		$table = $this->getTable();
		$user = JFactory::getUser();
		$count = 0;

		if ( empty( $pks ) ) {
			$pks = array( (int) $this->getState( $this->getName() . '.id' ) );
		}

		// Check in all items.
		foreach ( $pks as $pk ) {
			if ( $table->load( $pk ) ) {
				if ( $table->checked_out > 0 ) {
					if ( $table->created_by != $user->get('id')
						&& $table->checked_out != $user->get('id')
						&& !$user->authorise( 'core.manage', 'com_checkin' )
					) {
						$this->setError( JText::_( 'COM_TESTS_CANT_CHECKIN' ) );
						return false;
					}

					// Attempt to check the row in.
					if ( !$table->checkin( $pk ) ) {
						$this->setError( $table->getError() );
						return false;
					}

					$count++;
				}
			} else {
				$this->setError( $table->getError() );

				return false;
			}
		}

		return $count;
	}

	/**
	 * Method override to check-out a record.
	 *
	 * @param   integer  $pk  The ID of the primary key.
	 * @return  boolean  True if successful, false if an error occurs.
	 */
	public function checkout( $pk = null )
	{
		// Initialise variables.
		$pk = ( !empty( $pk ) ) ? $pk : (int) $this->getState($this->getName() . '.id' );
		$table = $this->getTable();
		$user = JFactory::getUser();

		if ( $table->load( $pk ) ) {
			// Don't checkout if not owner or if not admin
			if ( $table->created_by != $user->get('id')
				&& $table->checked_out != $user->get('id')
				&& !$user->authorise( 'core.manage', 'com_checkin' )
			) {
				return true;
			}
		} else {
			$this->setError( $table->getError() );

			return false;
		}

		return parent::checkout( $pk );
	}

	/**
	 * Method to get a test item.
	 *
	 * @param	integer	The id of the test item to get.
	 *
	 * @return	mixed	Menu item data object on success, false on failure.
	 */
	public function &getItem( $item_id = null )
	{
		// Initialise variables.
		$item_id = ( !empty( $item_id ) ) ? $item_id : (int) $this->getState('test.id');
		$false	= false;

		// Get a test item row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		$return = $table->load( $item_id );

		// Check for a table object error.
		if ( $return === false && $table->getError() ) {
			$this->setError( $table->getError() );
			return $false;
		}

		$properties = $table->getProperties(1);
		$value = JArrayHelper::toObject( $properties, 'JObject' );

		return $value;
	}

	/**
	 * Method to get the test item form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm( $data = array(), $loadData = true )
	{
		// Get the form.
		$form = $this->loadForm( 'com_tests.test', 'test',
			array( 'control' => 'jform', 'load_data' => $loadData ) );

		if ( empty( $form ) ) {
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState( 'com_tests.edit.test_edit.data', array() );

		if ( empty( $data ) ) {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array	The form data.
	 * @return	boolean	True on success.
	 */
	public function save( $data )
	{
		$id	= ( !empty( $data['id'] ) ) ? $data['id'] : (int) $this->getState( 'test.id' );
		$isNew	= true;

		// Get a row instance.
		$table = $this->getTable();

		// Load the row if saving an existing item.
		if ( $id > 0 ) {
			$table->load( $id );
			$isNew = false;
		}

		// Bind the data.
		if ( !$table->bind( $data ) ) {
			$this->setError( $table->getError() );
			return false;
		}

		// Check the data.
		if ( !$table->check() ) {
			$this->setError( $table->getError() );
			return false;
		}

		// Store the data.
		if ( !$table->store() ) {
			$this->setError( $table->getError() );
			return false;
		}

		$this->setState( 'test_edit.id', $table->id );

		return true;
	}

	/**
	 * Saves questions that are submitted when editing a test
	 * It saves questions in order and updates or adds questions if new
	 */
	public function add_test_questions( $data, $test_id )
	{
		if ( empty( $data ) ) {
			return true;
		}

		$question_ids = array();
		foreach ( $data as $question_id => $question ) {
			$question['question'] = trim( $question['question'] );
			if ( empty( $question['question'] ) || !$test_id || !$question['type_id'] ) {
				continue;
			}

			$table = JTable::getInstance( 'Questions', 'TestsTable' );
			$_data = array(
				'title' => $question['question'],
				'test_id' => $test_id,
				'question_type' => $question['type_id'],
				'seconds' => $question['seconds'],
				'min_answers' => @$question['min_answers'],
				'media' => $this->clean_media_url( $question['media'], @$question['media_type'] ),
				'media_type' => @$question['media_type']
				);

			// This means that this question already exists so lets add the id to the array
			if ( substr( $question_id, 0, 1 ) != 'n' ) {
				$_data['id'] = (int) $question_id;
			} else {
				$query = $this->_db->getQuery( true )
					->select( 'MAX( tq.`order` ) AS `max_order`' )
					->from( '#__test_questions AS tq' )
					->where( 'tq.`test_id` = ' . (int) $test_id )
					;
				$_data['order'] = (int) $this->_db->setQuery( $query )->loadResult() + 1;
			}

			// Should we abort completely? or just continue?
			if ( !$table->save( $_data ) ) {
				$errors[] = "Question there was an error with question: {$question['question']}";
				continue;
			}

			$qid = $table->get('id');
			$question_ids[] = $qid;

			// Lets add all the options
			$tuples = array();

			if ( isset( $question['options'] ) && !empty( $question['options'] ) ) {

				// Delete all previous question options
				$query = $this->_db->getQuery( true )
					->delete( '#__test_question_options' )
					->where( '`question_id` = ' . (int) $qid )
					;
				$this->_db->setQuery( $query )->query();

				foreach ( $question['options'] as $option_id => $option ) {
					$option = trim( $option );
					if ( empty( $option ) ) {
						continue;
					}

					$opt_table = JTable::getInstance( 'QuestionOptions', 'TestsTable' );
					$_data = array(
						'question_id' => $qid,
						'title' => $option,
						'valid' => @in_array( $option_id, @$question['answers'] )
						);

					if ( !$opt_table->save( $_data ) ) {
						$errors[]
							= "Some answers weren't saved on question: {$question['question']}";
						continue;
					}
				}
			}
		}

		// Delete all questions that are not on request
		$query = $this->_db->getQuery( true )
			->delete( '#__test_questions' )
			->where( '`test_id` = ' . (int) $test_id )
			->where( '`id` NOT IN (' .implode( ',', $question_ids ). ')')
			;
		$this->_db->setQuery( $query )->query();

		if ( !empty( $errors ) ) {
			JError::raiseWarning( 400, implode( "\n", $errors ) );
		}

		return true;
	}

	/**
	 * Method to delete groups.
	 *
	 * @param	array	An array of item ids.
	 * @return	boolean	Returns true on success, false on failure.
	 */
	public function delete( $item_ids )
	{
		// Sanitize the ids.
		$item_ids = (array) $item_ids;
		JArrayHelper::toInteger( $item_ids );

		// Get a group row instance.
		$table = $this->getTable();

		// Iterate the items to delete each one.
		foreach ( $item_ids as $itemId ) {
			// TODO: Delete the menu associations - Menu items and Modules

			if ( !$table->delete( $itemId ) ) {
				$this->setError( $table->getError() );
				return false;
			}
		}

		return true;
	}

	public function getQuestions( $test_id = null )
	{
		if ( !$test_id ) {
			$test_id = $this->getState( 'test.id' );
		}

		$query = $this->_db->getQuery( true )
			->select( 'tq.*, tqt.`type` AS `tqt_type`' )
			->from( '#__test_questions AS tq' )
			->leftjoin( '#__test_question_types AS tqt ON tqt.`id` = tq.`question_type`')
			->where( 'tq.`test_id` = ' . (int) $test_id )
			->order( 'tq.`order` ASC' )
			;
		$questions = $this->_db->setQuery( $query )->loadObjectList();

		foreach ( $questions as &$question ) {
			$query = $this->_db->getQuery( true )
				->select( 'tqo.*' )
				->from( '#__test_question_options AS tqo' )
				->where( 'tqo.`question_id` = ' . $question->id )
				;
			$question->options = $this->_db->setQuery( $query )->loadObjectList();
		}

		return $questions;
	}

	public function getTemplates()
	{
		$query = $this->_db->getQuery( true )
			->select( 'tqt.`type`' )
			->from( '#__test_question_types AS tqt' )
			;
		$types = $this->_db->setQuery( $query )->loadColumn();

		$templates = array();
		foreach ( $types as $type ) {
			$templates[$type] = THelper::get_question_type( $type );
		}

		return (object) $templates;
	}

	public function clean_media_url( $url, $type )
	{
		if ( !$url ) {
			return '';
		}

		if ( !$type ) {
			$type = 'link';
		}

		$url_bak = $url;

		// Clean URL
		$_url = JURI::getInstance( $url );
		$url = $_url->toString();

		return $url;
	}
}
