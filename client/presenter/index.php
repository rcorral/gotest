<?php

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

define( 'JPATH_SITE', realpath( __DIR__ . '/..' ) );
define( 'JPATH_BASE', __DIR__ );

require '../libraries/import.php';

jimport( 'joomla.database.database' );
jimport( 'legacy.request.request' );

$app = JApplicationWeb::getInstance();
$app->initialise();
JFactory::$application = $app;
// print_r($app->execute());die();

/**
 * This needs to be completely redone, but some of the MVC structure is there,
 * but themes and other things are needed
 */
$_controller = $app->input->get( 'controller', '' );
require 'components/test/' .($_controller ? 'controllers/' . $_controller : 'controller') . '.php';
$_controller = 'TestController' . ucfirst( $_controller );
$controller = new $_controller();
$body = $controller->execute();

if ( 'component' == $app->input->get( 'tmpl', false ) ) {
	echo $body;
} else {
	require 'themes/system/index.php';
}