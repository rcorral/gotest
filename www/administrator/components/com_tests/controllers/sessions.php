<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 */
class TestsControllerSessions extends JControllerAdmin
{
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask( 'activate', 'activate' );
		$this->registerTask( 'deactivate', 'activate' );
	}

	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel( $name = 'Session', $prefix = 'TestsModel',
		$config = array( 'ignore_request' => true ) 
	) {
		$model = parent::getModel( $name, $prefix, $config );
		return $model;
	}

	/**
	 * Method to activate/deactivate sessions
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	public function activate()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));

		// Get items to publish from the request.
		$cid = JRequest::getVar( 'cid', array(), '', 'array' );
		$data = array( 'activate' => 1, 'deactivate' => 0 );
		$task = $this->getTask();
		$value = JArrayHelper::getValue( $data, $task, 0, 'int' );

		if ( empty( $cid ) ) {
			JError::raiseWarning( 500, JText::_( $this->text_prefix . '_NO_ITEM_SELECTED' ) );
		} else {
			// Get the model.
			$model = $this->getModel();

			// Make sure the item ids are integers
			JArrayHelper::toInteger( $cid );

			// Activate the items.
			if ( !$model->activate( $cid, $value ) ) {
				JError::raiseWarning( 500, $model->getError() );
			} else {
				if ( $value == 1 ) {
					$ntext = $this->text_prefix . '_N_ITEMS_ACTIVATED';
				} else {
					$ntext = $this->text_prefix . '_N_ITEMS_DEACTIVATED';
				}

				$this->setMessage( JText::plural( $ntext, count( $cid ) ) );
			}
		}

		$this->setRedirect( 'index.php?option=com_tests&view=sessions' );
	}
}
