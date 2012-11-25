<?php
/**
 * @package		Tests
 * @subpackage	com_tests
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class TestsUpgrader
{
	/**
	 * Create config database
	 *
	 * @since 1.0
	 **/
	static public function create_config()
	{
		$db = JFactory::getDBO();
		$query = "
		CREATE TABLE IF NOT EXISTS `#__test_config` (
			`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			`name` varchar(255) NOT NULL,
			`params` text NOT NULL,
			`modified` datetime NOT NULL,
			`modified_by` int(11) unsigned NOT NULL DEFAULT '0',
		PRIMARY KEY (`id`)
		) CHARSET=utf8;";
		$db->setQuery( $query )->query();

		$datenow = &JFactory::getDate();
		$db_time = $datenow->toFormat( "%Y-%m-%d-%H-%M-%S" );
		$query = "INSERT INTO #__test_config
			( `modified`, `name`, `params`)
			VALUES
			( '{$db_time}', 'dbversion', '1000');";
		$db->setQuery( $query );
		$db->query();
	}

	/**
	 * Hopefully this is only temporary
	 */
	static public function find_upgrades( $db_version )
	{
		jimport( 'joomla.filesystem.file' );
		$path = JPATH_ROOT . '/tmp/stuff/sql';

		$db = JFactory::getDBO();
		$no_more = false;
		$version_check = intval( $db_version ) + 1;

		while ( false == $no_more ) {
			$_file = $path . "/db_{$version_check}.sql";
			if ( file_exists( $_file ) ) {
				$contents = JFile::read( $_file );

				if ( method_exists( 'TestsUpgrader', 'upgrade_' . $version_check ) ) {
					$_method = 'upgrade_' . $version_check;
					TestsUpgrader::$_method();
				}

				$queries = $db->splitSql( $contents );
				foreach ( (array) $queries as $query ) {
					$db->setQuery( $query )->query();
				}

				$version_check++;
			} else {
				$no_more = true;
			}
		}

		return $version_check - 1;
	}
}
