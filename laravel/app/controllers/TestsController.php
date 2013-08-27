<?php

use Illuminate\Http\RedirectResponse;

class TestsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->_buffer = View::make('tests', array(
			'test' => $test,
			'templates' => $templates,
			'user' => Helper::get_current_user()
		));

		return $this->exec();
	}

	public function show( $id )
	{
		return new RedirectResponse(Redirect::getUrlGenerator()->route('tests.edit', $id), 302, array());
	}

	public function edit( $id = 0, $from_request = false )
	{
		$doc = Document::get_instance();
		$doc->add_inline_view_file('tests.edit.js', array('jquery' => true));

		$test = Test::load_populate($id);
		$templates = $test->get_templates();

		if ( $from_request )
		{
			// $types = DB::table('test_question_types')->select('id')->addSelect('type')->remember(1440)
				// ->lists('type', 'id');
			$test->fill(Input::except('_token'));
			$test->questions = array();
			// $questions = (array) Input::get('questions');
			// array_walk($questions, function( &$question, $key ) use ( $types )
			// {
				// $question['id'] = $key;
				// $question['title'] = $question['question'];
				// $question['tqt_type'] = isset($types[$question['type_id']]) ? $types[$question['type_id']] : 1;
				// $question = (object) $question;
			// });
			// $test->questions = $questions;
			// unset($questions);
		}

		$this->_buffer = View::make('tests_edit', array(
			'test' => $test,
			'templates' => $templates,
			'user' => Helper::get_current_user()
		));

		return $this->exec();
	}

	public function store()
	{
		Helper::csrf_check();

		$input = Input::except('_token');

		$test = Test::load_populate($input['id'], null, false);
		$test->fill($input);

		if ( !isset($input['anon']) || $input['anon'] != 1 )
			$test->anon = 0;

		try
		{
			$test->save();
		}
		catch (Exception $e)
		{
			$this->set_error($e->getMessage());
			return $this->edit($input['id'], true);
		}

		return new RedirectResponse(Redirect::getUrlGenerator()->route('tests.edit', $test->id), 302, array());
	}

}