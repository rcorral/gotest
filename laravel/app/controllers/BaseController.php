<?php

class BaseController extends Controller
{
	/**
	 * Default front-end libraries to be added to every page
	 */
	protected $libs = array('jquery', 'bootstrap', 'deparam', 'core', 'main');

	/**
	 * An error to be displayed
	 *
	 * @var string
	 **/
	private $error_msg;

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

		// return $this->_buffer;

		// Get the template
		$this->_buffer = View::make('tmpl/index', array(
			'error' => $this->get_error_msg(),
			'contents' => (string) $this->_buffer,
			'that' => $this
		));

		return $this->_buffer;
	}

	protected function set_error( $msg = '' )
	{
		$this->error_msg = (string) $msg;
	}

	protected function get_error_msg()
	{
		return $this->error_msg;
	}

	public function missingMethod($parameters)
	{
		App::abort(404);
	}
}