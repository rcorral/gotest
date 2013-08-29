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
		$doc = Document::get_instance();
		$doc->add_inline_view_file('tests.index.js', array('jquery' => true));

		$this->_buffer = View::make('tests_index', array(
			'tests' => Tests::get_tests()
		));

		return $this->exec();
	}

	public function create()
	{
		return $this->edit();
	}

	public function show( $id )
	{
		return Redirect::route('tests.edit', $id);
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

		return Redirect::route('tests.edit', $test->id);
	}

	/**
	 * Method to delete a test.
	 *
	 * @param	int $id A test id
	 * @return	object Success/Fail ajax response
	 */
	public function destroy( $id )
	{
		// Sanitize the id
		$test = Test::load_populate((int) $id);

		if ( !$test->id )
			return Helper::json_error_response(array('message' => Lang::get('Error deleting.')), 400);
		elseif ( $test->created_by != Helper::get_current_user()->id )
			throw new Exception(Lang::get('all.invalid_request'));

		// USE THIS WHEN WE WANT TO COMPLETELY DELETE
		// Delete questions
		// $question_ids = DB::table('test_questions')
			// ->where('test_id', $test->id)
			// ->lists('id')
			// ;

		// DB::table('test_question_options')
			// ->whereIn('question_id', $question_ids)
			// ->delete()
			// ;
		// DB::table('test_questions')
			// ->whereIn('id', $question_ids)
			// ->delete()
			// ;

		// Init sessions model
		// TODO: FIX THIS SO THAT IT DELETES SESSIONS
		// $sessions_model = JModel::getInstance( 'Session', 'TestsModel' );

		// Iterate the items to delete each one.
		// foreach ( $item_ids as $item_id ) {
			// Delete test sessions and answers associated with them
			// $sessions_model->delete( $item_id );

			$test->delete();
		// }

		return Helper::json_success_response(array(), 200);
	}

}