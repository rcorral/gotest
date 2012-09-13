<?php
/**
 * @version		$Id: tests.php 21766 2011-07-08 12:20:23Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

class Tests
{
	function get_field( $type, $attributes = array(), $field_value = '' ) {
		static $types = null;

		$defaults = array( 'name' => '', 'id' => '' );

		if ( !$types ) {
			jimport('joomla.form.helper');
			$types = array();
		}

		if ( !in_array( $type, $types ) ) {
			JFormHelper::loadFieldClass( $type );
		}

		try {
			$attributes = array_merge( $defaults, $attributes );

			$xml = new JXMLElement( '<?xml version="1.0" encoding="utf-8"?><field />' );
			foreach ( $attributes as $key => $value ) {
				$xml->addAttribute( $key, $value );
			}

			$class = 'JFormField' . $type;
			$field = new $class();
			$field->setup( $xml, $field_value );

			return $field;
		} catch( Exception $e ) {
			self::exception( $e, "Error: Tests::get_field\n\tType: {$type}\n\tURI: " . JURI::getInstance()
				. "\n\tException: " . print_r( $e, true )
				. "\n\tBacktrace: " . print_r( debug_backtrace(), true ) );
		}
	}

	function exception( $exception, $message, $priority = JLog::CRITICAL,
		$category = 'tests_error'
	) {
		// If in development, output normall Exception
		if ( defined( 'TESTS_DEVELOPMENT' ) && TESTS_DEVELOPMENT ) {
			echo '<br /><strong>Caught exception</strong>: '
				. $exception->getMessage()
				. ' <strong>in</strong> ' . $exception->getFile() . ':' . $exception->getLine();
			return;
		}

		jimport('joomla.log.log');
		JLog::add( $message, $priority, $category );
	}
}

jimport('joomla.form.form');

JTable::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_tests/tables/' );
JForm::addFieldPath( JPATH_ADMINISTRATOR . '/components/com_tests/models/fields' );

$includes_path = JPATH_PLUGINS .DS. 'system' .DS. 'tests' .DS. 'includes';
JLoader::register( 'TestsHelper', $includes_path . '/helper.php' );
JLoader::register( 'plgSystemTestsFormEvents', $includes_path . '/form-events.php' );
require $includes_path . '/functions.php';

class plgSystemTests extends plgSystemTestsFormEvents
{
	public function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );
		$this->loadLanguage();
	}
}
