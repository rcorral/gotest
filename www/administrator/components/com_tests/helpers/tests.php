<?php
/**
 * @package		Tests
 * @subpackage	com_tests
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

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

	/**
	 * Checks db current database version
	 *
	 * This is a database check function
	 * it will check the current database version and run any updates
	 * Calles methods of class PagoUpgrader located: '/helpers/upgrade.php'
	 *
	 * @since 1.0
	 *
	 * @return void
	 **/
	static public function db_check()
	{
		// $current_version = TestsHelper::get_xml_tag_value( 'dbversion' );
		//
		// if ( !$current_version ) {
		// 	return;
		// }

		jimport( 'joomla.database.table' );

		// Supress all possible errors because there is a chance the db was never created
		$row = @JTable::getInstance( 'Config', 'TestsTable' );
		@$row->load( array( 'name' => 'dbversion' ) );
		$db_version = @$row->params;

		// If equal, nothing to do
		// if ( $db_version == $current_version ) {
		// 	return;
		// }

		if ( !$db_version ) {
			TestsUpgrader::create_config();
			$db_version = 1000;

			$row = JTable::getInstance( 'Config', 'TestsTable' );
			$row->load( array( 'name' => 'dbversion' ) );
			$db_version = $row->params;
		}

		// if ( $db_version < 1000 ) {
		// 	PagoUpgrader::upgrade_1000();
		// }

		$current_version = TestsUpgrader::find_upgrades( $db_version );

		// Finally store new version to DB
		// Only store if row already exists
		if ( $row->id ) {
			$row->params = $current_version;
			$row->check();
			$row->store();
		}
	}
}
