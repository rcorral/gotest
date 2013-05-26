<?php

class HomeController extends BaseController
{
	public function display()
	{
		$doc = Document::get_instance();

		$doc->add_lib(array('jquery', 'bootstrap', 'core', 'main'));

		return View::make('home');
	}
}