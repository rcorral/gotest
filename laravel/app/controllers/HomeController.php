<?php

class HomeController extends \BaseController
{
	public function index()
	{
		$doc = Document::get_instance();
		$doc->add_inline_view_file('home.js', array('jquery' => true));

		$this->_buffer = View::make('home');

		return $this->exec();
	}
}