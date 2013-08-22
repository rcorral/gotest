<?php

class TestsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index( $id = 0 )
	{
		$doc = Document::get_instance();
		$doc->add_inline_view_file('tests.create.js', array('jquery' => true));

		$test = Test::load_populate($id);

		$this->_buffer = View::make('tests_create', array('test' => $test, 'templates' => $test->get_templates(), 'user' => Helper::get_current_user()));

		return $this->exec();
	}

	public function show( $id )
	{
		return self::index($id);
	}

	public function store()
	{
		Helper::csrf_check();

		$input = Input::except('_token');

		$test = Test::load_populate($input['id'], null, false);
		$test->fill($input);

		try
		{
			$test->save();
		}
		catch (Exception $e)
		{
			// DEBUG
			file_put_contents( '/var/log/rafa.log',
				'ERROR: - '
				. var_export($e->getMessage(), true)
				. "\n\n", FILE_APPEND);
			die('Handle that error!');
		}
		
		// DEBUG
		file_put_contents( '/var/log/rafa.log',
			'log output: - '
			. var_export($test, true)
			. "\n\n", FILE_APPEND);
		die();
	}

}