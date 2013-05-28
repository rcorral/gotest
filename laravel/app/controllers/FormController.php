<?php

class FormController extends \BaseController
{
	public function index()
	{
		$this->_buffer = View::make('form');

		return $this->exec();
	}
}