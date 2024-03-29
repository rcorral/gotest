<?php

use Illuminate\Http\RedirectResponse;

/**
 * Handles everything related to creating tests, editing, deleting tests
 * This class does not handle starting an examination, see TestController
 */
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

		$this->_buffer = View::make('presenter.tests_index', array(
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
		array_push($this->libs, 'sortable');

		$test = Test::load_populate($id);

		View::share('is_interactive', $test->interactive);

		$templates = $test->get_templates();

		if ( $from_request )
		{
			$test->fill(Input::except('_token'));
			$test->questions = array();
			// This is commented out for now, the idea is to reload the questions
			// in the case that there was an error on submission.
			// $types = DB::table('test_question_types')->select('id')->addSelect('type')->remember(1440)
				// ->lists('type', 'id');
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

		// This is so that the request from the homepage works
		if ( !$test->id && !$test->title ) $test->title = Input::get('title', '');

		// Convert the seconds to minutes
		$test->seconds = str_replace('.0', '', number_format($test->seconds / 60, 1));

		$this->_buffer = View::make('presenter.tests_edit', array(
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

		// If this is just an ajax ction
		if ( isset($input['action']) )
		{
			switch ( $input['action'] )
			{
				case 'change_interactive':
					$test = Test::load_populate($input['id'], null, false);

					try
					{
						$test->interactive = ('interactive' == $input['state'] ? 1 : 0);
						$test->save();
						return Helper::json_success_response(array(
							'html' => Form::test_interactive($test->interactive, $test->id)
						));
					}
					catch (Exception $e)
					{
						return Helper::json_error_response(array('message' => Lang::get('Error Updating.')), 400);
					}

					break;
				default:
					throw new Exception('', 400);
			}
		}

		// Fix the seconds
		$seconds = 0;
		if ( $input['seconds'] )
		{
			if ( false !== ($pos = strpos($input['seconds'], '.')) )
			{
				$seconds = (substr($input['seconds'], 0, $pos) * 60) + ((float) substr($input['seconds'], $pos) * 60);
			}
			else
			{
				// Make seconds out of the minutes
				$seconds = $input['seconds'] * 60;
			}
		}
		$input['seconds'] = $seconds;

		$test = Test::load_populate($input['id'], null, false);
		$test->fill($input);

		if ( !isset($input['anon']) || $input['anon'] != 1 ) $test->anon = 0;

		// For now we must do this for all tests to show up
		$test->published = 1;

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