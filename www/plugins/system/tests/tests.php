<?php
/**
 * @version		$Id: tests.php 21766 2011-07-08 12:20:23Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

include 'development.php';
if ( !defined( 'TESTS_DEVELOPMENT' ) ) {
	define( 'TESTS_DEVELOPMENT', false );
}

class Tests
{
	/**
	 * String containing all script declarations
	 */
	public static $_script_declaration = array();

	/**
	 * String containing all jquery code
	 */
	public static $_jquery_declaration = '';

	/**
	 * Script files
	 * 
	 * This array is used in the combiner.php file to generate a single css and js file.
	 * When adding new scripts, test the combiner.php file to ensure there will be no problems
	 */
	public static $_scripts = array(
		'jquery' => array(
			'js' => "TEMPLATEPATH/js/jquery.min.js"
			),
		'colorbox' => array(
			'js' => "TEMPLATEPATH/js/jquery.colorbox.js",
			'css' => "TEMPLATEPATH/css/colorbox.css"
			),
		'core' => array(
			'js' => "TEMPLATEPATH/js/core.js"
			)
		);

	function add_script( $scripts = array() )
	{
		static $called = false;

		$doc = JFactory::getDocument();
		$site_path = JURI::root();
		$app = JFactory::getApplication();
		$tmpl = isset( $_REQUEST['tmpl'] );
		$template_path = JURI::root() . 'templates/clicker';
		self::js_variables();
		$_scripts = self::$_scripts;

		if ( !TESTS_DEVELOPMENT && $app->isSite() && !$tmpl ) {
			if ( !$called ) {
				// Need to write some code here look at ppwell project
			}
		} else {
			foreach ( (array) $scripts as $script ) {
				if ( !isset( $_scripts[$script] ) || true === $_scripts[$script] ) {
					continue;
				}

				foreach ( $_scripts[$script] as $type => $files ) {
					if ( 'js' == $type ) {
						foreach ( (array) $files as $file ) {
							$file = str_replace( array( 'TEMPLATEPATH', 'SITE' ),
								array( $template_path, $site_path ), $file );
							$doc->addScript( $file );
						}
					} elseif ( 'css' == $type ) {
						foreach ( (array) $files as $file ) {
							$file = str_replace( array( 'TEMPLATEPATH', 'SITE' ),
								array( $template_path, $site_path ), $file );
							$doc->addStyleSheet( $file );
						}
					}
				}

				if ( 'jquery' == $script ) {
					self::addScriptDeclaration( ';jQuery.noConflict();' );
				}

				$_scripts[$script] = true;
			}
		}
	}

	function js_variables()
	{
		static $done;

		if ( true === $done ) {
			return;
		}

		$js = 'var live_site = \'' .JURI::root(). '\';';

		if ( JFactory::getApplication()->isAdmin() ) {
			$js .= "\nvar community_token = '14f3bb75e8b6bcdc84f341c8872f68fe57c4023e';";
		} else {
			$active = JFactory::getApplication()->getMenu()->getActive();
			$user = JFactory::getUser();
			$loggedin = ( $user->id ) ? 1 : 0;

			if ( $active ) {
				$home = $active->home;
			} else {
				$home = 0;
			}
			$js .= "\nvar is_home={$home};";
			$js .= "\nvar is_loggedin={$loggedin};";
		}

		self::addScriptDeclaration( $js );

		$done = true;
	}

	/**
	 * Proxy method for JDocument::addScriptDeclaration
	 */
	function addScriptDeclaration( $content, $type = 'text/javascript' )
	{
		$app = JFactory::getApplication();

		if ( TESTS_DEVELOPMENT || !$app->isSite() ) {
			$doc = JFactory::getDocument();
			$doc->addScriptDeclaration( $content, $type );
		} else {
			if ( !isset( self::$_script_declaration[$type] ) ) {
				self::$_script_declaration[$type] = '';
			}

			self::$_script_declaration[$type] .= $content . ';';
		}
	}

	/**
	 * Method to add jquery declaration that need to be wrapped in jQuery(document).ready
	 */
	function add_jquery( $content )
	{
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();

		if ( TESTS_DEVELOPMENT || !$app->isSite() ) {
			$pre = 'jQuery(document).ready(function(){';
			$post = '});';
			$doc->addScriptDeclaration( $pre . $content . $post );
		} else {
			self::$_jquery_declaration .= $content . ';';
		}
	}

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
