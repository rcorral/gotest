<?php
defined('JPATH_PLATFORM') or die;

class TestViewTest extends JViewDisplay
{
	function render()
	{
		$this->test = $this->model->get_test();

		$document = JFactory::getDocument();
		$document->addStyleSheet( '../assets/css/template.css' );
		$document->addScript( '../assets/js/my.conf.js' );
		$document->addScript( 'assets/js/click.js' );
		$document->addScript( '../assets/js/templates.js' );

		return parent::render();
	}
}