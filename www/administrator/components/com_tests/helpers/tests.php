<?php
// No direct access to this file
defined('_JEXEC') or die;

class TestsHelper
{
	static $actions;

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
				$views = TestsHelper::get_views();

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
		if ( empty( self::$actions ) ) {
			$user = JFactory::getUser();
			self::$actions = new JObject;

			$actions = JAccess::getActions( 'com_tests', 'component' );

			foreach ( $actions as $action ) {
				self::$actions->set( $action->name,
					$user->authorise( $action->name, 'com_tests' ) );
			}
		}

		return self::$actions;
	}
}
