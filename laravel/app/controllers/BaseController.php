<?php

class BaseController extends Controller
{
	/**
	 * Gets the curret output buffer
	 */
	public function get_buffer()
	{
		return $this->_buffer;
	}

	public function exec()
	{
		// Display the main view
		$this->_buffer = $this->display();

		// Get the template
		$this->_buffer = View::make('tmpl/index', array( 'contents' => (string) $this->_buffer, 'that' => $this ));

		return $this->_buffer;
	}
}