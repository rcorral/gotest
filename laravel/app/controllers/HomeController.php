<?php

class HomeController extends \BaseController
{
	public function index()
	{
		$this->_buffer = View::make('home');

		return $this->exec();
	}
}