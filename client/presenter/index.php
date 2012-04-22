<?php
define('_JEXEC', 1);
 
// Fix magic quotes.
@ini_set('magic_quotes_runtime', 0);

@ini_set('zend.ze1_compatibility_mode', '0');
ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

define( 'JPATH_SITE', realpath( __DIR__ . '/..' ) );
define( 'JPATH_ADMINISTRATOR', realpath( __DIR__ . '/../administrator' ) );
define( 'JPATH_INSTALLATION', realpath( __DIR__ . '/../installation' ) );
define( 'JPATH_BASE', __DIR__ );
define( 'JPATH_LIBRARIES', JPATH_SITE . '/libraries' );

require JPATH_LIBRARIES . '/import.legacy.php';

jimport( 'joomla.controller.controller' );
jimport( 'joomla.model.model' );
jimport( 'joomla.view.view' );

require JPATH_SITE . '/includes/viewdisplay.php';

//It's an application, so let's get the application helper. 
jimport('joomla.application.helper'); 
$client = new stdClass;
$client->name = 'clickapplication';
$client->path = JPATH_BASE;

JApplicationHelper::addClientInfo($client);

// Instantiate the application.
$app = JFactory::getApplication('ClickApplication');

$app->initialise();
 
// Run the application
$app->execute();