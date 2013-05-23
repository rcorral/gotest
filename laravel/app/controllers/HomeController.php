<?php

class HomeController extends BaseController
{
	public function display()
	{
		return View::make('home');
	}
}