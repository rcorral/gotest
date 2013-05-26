<?php

class BaseController extends Controller
{
	public function exec()
	{
		// Display the main view
		$this->_buffer = $this->display();

		// Get the template
		$this->_buffer = View::make('index', array( 'contents' => (string) $this->_buffer, 'that' => $this ));

		return $this->_buffer;
	}
}