<?php

class BaseController extends Controller
{
	/**
	 * Default front-end libraries to be added to every page
	 */
	protected $libs = array('jquery', 'bootstrap', 'deparam', 'core', 'main');

	/**
	 * Meta tags to be added
	 */
	protected $meta_tags = array(
		array('name' => 'language', 'content' => 'en'),
		array('name' => 'description', 'content' => 'Create and administer tests online for free.'),
		array('name' => 'keywords', 'content' => 'Online testing, free online testing, web testing, online exams, teachers, students, fill in the blank, multiple choice, quizzes, administer exams')
		);

	/**
	 * Buffer  object of child controller
	 */
	protected $_buffer;

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

	public function exec( $options = array() )
	{
		$doc = Document::get_instance();
		$doc->add_lib($this->libs);

		foreach ( $this->meta_tags as $meta )
		{
			$doc->add_meta($meta, 2);
		}

		// return $this->_buffer;

		// Get the template
		if ( isset($options['simple']) )
			$this->_buffer = View::make('tmpl/simple', array(
				'error' => $this->get_error_msg(),
				'contents' => (string) $this->_buffer,
			));
		else
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