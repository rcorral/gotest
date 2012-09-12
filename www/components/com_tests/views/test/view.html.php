<?php
defined('JPATH_PLATFORM') or die;

class TestViewTest extends JViewDisplay
{
	function render()
	{
		$this->test = $this->model->get_test();

		$this->document = JFactory::getDocument();
		$this->document->addStyleSheet( 'assets/css/template.css' );
		$this->document->addScript( 'assets/js/my.conf.js' );
		$this->document->addScript( 'assets/js/click.js' );
		$this->document->addScript( 'assets/js/templates.js' );

		return parent::render();
	}
}