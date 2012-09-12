<?php
defined('JPATH_PLATFORM') or die;

$app = JFactory::getApplication();

$_controller = $app->input->get( 'controller', '' );
require 'components/test/' .($_controller ? 'controllers/' . $_controller : 'controller') . '.php';

$_controller = 'TestController' . ucfirst( $_controller );
$controller = new $_controller();
$controller->execute();