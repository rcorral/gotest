<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * PPwell Controller
 */
class TestsControllerTests extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel( $name = 'Test_Edit', $prefix = 'TestsModel',
		$config = array( 'ignore_request' => true ) 
	) {
		$model = parent::getModel( $name, $prefix, $config );
		return $model;
	}
}
