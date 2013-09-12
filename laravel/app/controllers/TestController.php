<?php

use Illuminate\Http\RedirectResponse;

/**
 * Handles everything related to giving an examination
 * If you're looking for code related to creating a test see TestsController
 */
class TestController extends \BaseController {

	protected $libs = array('jquery', 'bootstrap', 'main');

	/**
	 * Present a test
	 *
	 * @return Response
	 */
	public function present( $test_id, $name, $unique_id = '' )
	{
		// If there is no unique id then generate one and redirect
		if ( !$unique_id )
			return Redirect::route('test', array('id' => $test_id, 'name' => $name,
				'unique' => $this->return_with_unique_id($test_id, $name, $unique_id))
			);

		$test = Test::load_populate($test_id);

		if ( !$test->id || !Helper::is_test_session_active($test->id, $unique_id) )
			$this->_buffer = View::make('presenter.test_noexists');
		else
		{
			$this->libs = array_merge($this->libs, array('deparam', 'core', 'timer', 'core', 'socket.io', 'presenter_click', 'templates'));

			$doc = Document::get_instance();
			$doc->add_inline_view_file('test.present.css');
			$doc->add_script_declaration("var test_unique_id='{$unique_id}';");

			$this->_buffer = View::make('presenter.test_present', array(
				'test' => $test,
				'unique_id' => $unique_id
			));
		}

		return $this->exec(array('simple' => true));
	}

	private function return_with_unique_id( $test_id )
	{
		// Lets generate id for this test
		$unique_id = Helper::generate_unique_test_id($test_id);

		if ( !$unique_id )
			throw new Exception('Error creating unique ID for test.', 500);

		return $unique_id;
	}
}