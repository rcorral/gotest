<?php
defined('JPATH_PLATFORM') or die;

class TestViewTest extends JViewHtml
{
	function render()
	{
		$this->test = $this->model->get_test();

		return parent::render();
	}
}