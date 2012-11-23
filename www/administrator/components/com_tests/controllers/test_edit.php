<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.controllerform' );

/**
 * The test_edit Type Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_tests
 * @since		1.6
 */
class TestsControllerTest_Edit extends JControllerForm
{
	protected $view_list = 'tests';
	protected $view_item = 'test_edit';

	public function __construct( $config = array() )
	{
		parent::__construct( $config );
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit( $data = array(), $key = 'id' )
	{
		// Initialise variables.
		$record_id = (int) isset( $data[$key] ) ? $data[$key] : 0;
		$category_id = 0;
		$task = JRequest::getVar( 'task' );
		$actions = TestsHelper::getActions();

		if ( 'save' == $task ) {
			$user = JFactory::getUser();
			$item = $this->getModel()->getItem( $record_id );
			if ( $user->get( 'id' ) != $item->created_by && !$actions->get( 'core.admin' ) ) {
				return false;
			}
		}

		if ( $record_id ) {
			$category_id = (int) $this->getModel()->getItem( $record_id )->catid;
		}

		if ( $category_id ) {
			// The category has been set. Check the category permissions.
			return JFactory::getUser()->authorise( 'core.edit', $this->option . '.category.'
				. $category_id );
		} else {
			// Since there is no asset tracking, revert to the component permissions.
			return parent::allowEdit( $data, $key );
		}
	}

	function postSaveHook( JModel &$model, $validData )
	{
		$app  = JFactory::getApplication();
		$questions = JRequest::getVar( 'questions', array(), 'post', 'array' );
		$id = $model->getState( $model->getName() . '.id' );

		$model->add_test_questions( $questions, $id );
	}
}
