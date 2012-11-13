<?php
// No direct access to this file
defined('_JEXEC') or die;

abstract class TestsCHelper
{
	public static function get_views()
	{
		return array(
			array( 'view' => 'tests', 'name' => 'Tests' ),
			array( 'view' => 'sessions', 'name' => 'Sessions' )
			);
	}

	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu( $view ) 
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_TESTS_SUBMENU_VIEW_CATEGORIES' ),
			'index.php?option=com_categories&view=categories&extension=com_tests',
			$view == 'categories' );

		switch ( $view ) {
			case 'example':
				break;

			default:
				$views = TestsCHelper::get_views();

				foreach ( $views as $_view ) {
					JSubMenuHelper::addEntry(
						$_view['name'],
						'index.php?option=com_tests&view=' . $_view['view'],
						$view == $_view['view'] );
				}
				break;
		}
	}

	/**
	 * Get the actions
	 */
	public static function getActions()
	{
		$user   = JFactory::getUser();
		$result = new JObject;
  		$assetName = 'com_tests';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.delete'
		);
 
		foreach ( $actions as $action ) {
			$result->set( $action, $user->authorise( $action, $assetName ) );
		}
 
		return $result;
	}
}
