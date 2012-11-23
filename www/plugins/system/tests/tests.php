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
		'bootstrap' => array(
			'js' => 'TEMPLATEPATH/bootstrap/js/bootstrap.min.js',
			'css' => 'TEMPLATEPATH/bootstrap/css/bootstrap.min.css'
			),
		'bootstrap-responsive' => array(
			'css' => 'TEMPLATEPATH/bootstrap/css/bootstrap-responsive.min.css'
			),
		'colorbox' => array(
			'js' => "TEMPLATEPATH/js/jquery.colorbox.js",
			'css' => "TEMPLATEPATH/css/colorbox.css"
			),
		'timer' => array(
			'js' => 'TEMPLATEPATH/js/jquery.timer.js'
			),
		'deparam' => array(
			'js' => 'TEMPLATEPATH/js/jquery.ba-bbq.min.js'
			),
		'core' => array(
			'js' => "TEMPLATEPATH/js/core.js"
			),
		'templates' => array(
			'js' => 'SITEcomponents/com_tests/assets/js/templates.js'
			),
		'socket.io' => array(
			'js' => 'http://SITE_HOST:IO_PORT/socket.io/socket.io.js'
			),
		'click' => array(
			'js' => 'BASEcomponents/com_tests/assets/js/click.js'
			)
		);

	public static $io_port = 8080;

	function add_script( $scripts = array() )
	{
		static $called = false;

		$doc = JFactory::getDocument();
		$site_path = JURI::root();
		$base_path = JURI::base();
		$site_host = JURI::getInstance()->toString( array( 'host' ) );
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
							$file = str_replace(
								array( 'TEMPLATEPATH', 'BASE', 'SITE_HOST', 'SITE', 'IO_PORT' ),
								array( $template_path, $base_path, $site_host, $site_path,
									self::$io_port ),
								$file );
							$doc->addScript( $file );
						}
					} elseif ( 'css' == $type ) {
						foreach ( (array) $files as $file ) {
							$file = str_replace(
								array( 'TEMPLATEPATH', 'BASE', 'SITE_HOST', 'SITE' ),
								array( $template_path, $base_path, $site_host, $site_path ),
								$file );
							$doc->addStyleSheet( $file );
						}
					}
				}

				if ( 'jquery' == $script ) {
					self::addScriptDeclaration( ';jQuery.noConflict();' );
				}

				if ( 'bootstrap-responsive' == $script ) {
					$doc->setMetaData( 'viewport', 'width=device-width, initial-scale=1.0' );
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

		$host = JURI::getInstance()->toString( array( 'host' ) );
		$js = 'var live_site = \'' .JURI::root(). '\';';
		$js .= 'var site_host = \'' .$host. '\';';
		$js .= 'var io_server = \'http://' .$host. ':' .self::$io_port. '/\';';
		$js .= 'var in_development = ' . ( TESTS_DEVELOPMENT ? 1 : 0 ) . ';';

		if ( JFactory::getApplication()->isAdmin() ) {
			$js .= "\nvar community_token = 'ae548d19b6a7af79812708a2496c6f72ae8e5cd8';";
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
JLoader::register( 'THelper', $includes_path . '/helper.php' );
JLoader::register( 'plgSystemTestsFormEvents', $includes_path . '/form-events.php' );
require $includes_path . '/functions.php';

class plgSystemTests extends plgSystemTestsFormEvents
{
	public function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );
		$this->loadLanguage();
	}

	public function onAfterInitialise()
	{
		$app = JFactory::getApplication();
		$option = JRequest::getVar( 'option' );

		if ( $app->isAdmin() ) {
			return;
		}

		$path = str_replace( JURI::root( true ), '', $_SERVER['REQUEST_URI'] );

		// Nothing is return by this, then that means we have a short url for a test
		// Will match for /[0-9]/[0-9a-zA-Z]{6} or /[0-9]/[0-9a-zA-Z]{6}?.*
		if ( '' == preg_replace( '/^\/[0-9]+\/[0-9a-zA-Z]{6}\/?\??.*/', '', $path ) ) {
			$_REQUEST['option'] = 'com_tests';
			$_REQUEST['view'] = 'test';
			$_REQUEST['tmpl'] = 'component';
			return;
		}
	}

	function onUserAfterDelete( $user, $success, $msg )
	{
		if ( !$success ) {
			return false;
		}

		$app = JFactory::getApplication();
		$user_id = JArrayHelper::getValue( $user, 'id', 0, 'int' );
		$db = JFactory::getDBO();

		if ( $user_id ) {
			try {
				// Have the authentication plugins clean up after themselves
				JPluginHelper::importPlugin( 'authentication' );
				$app->triggerEvent( 'clickerUserDelete', array( $user, $success, $msg ) );

				// Delete all user answers
				$query = $db->getQuery( true )
					->delete( '#__test_answers' )
					->where( '`user_id` = ' . $user_id )
					;
				$db->setQuery( $query )->query();
			} catch ( JException $e ) {
				$this->_subject->setError( $e->getMessage() );
				return false;
			}
		}

		return true;
	}
}
