<?php

class BaseController extends Controller
{
	/**
	 * Default front-end libraries to be added to every page
	 */
	protected $libs = array('jquery', 'bootstrap', 'core', 'main');

	/**
	 * Gets the curret output buffer
	 */
	public function get_buffer()
	{
		return $this->_buffer;
	}

	public function exec()
	{
		$doc = Document::get_instance();
		$doc->add_lib($this->libs);

		// Get the template
		$this->_buffer = View::make('tmpl/index', array( 'contents' => (string) $this->_buffer, 'that' => $this ));

		return $this->_buffer;
	}

	public function missingMethod($parameters)
	{
		App::abort(404);
	}
}