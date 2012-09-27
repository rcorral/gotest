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

	public function __construct($config = array())
	{
		parent::__construct($config);
	}

	function postSaveHook( JModel &$model, $validData )
	{
		$app  = JFactory::getApplication();
		$questions = JRequest::getVar( 'questions', array(), 'post', 'array' );
		$id = $model->getState( $model->getName() . '.id' );

		$model->add_test_questions( $questions, $id );
	}
}
