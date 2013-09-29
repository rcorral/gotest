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
				'unique' => $this->_return_with_unique_id($test_id, $name, $unique_id))
			);

		$test = Test::load_populate($test_id);

		if ( !$test->id || !Helper::is_test_session_active($test->id, $unique_id) )
			$this->_buffer = View::make('presenter.test_noexists');
		else
		{
			$this->libs = array_merge($this->libs, array('deparam', 'timer', 'core', 'socket.io', 'presenter_click',
				'templates'));

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

	/**
	 * API call to complete test
	 */
	public function complete()
	{
		try
		{
			$user = Helper::get_api_user();

			if ( !$user->hasAccess('teacher') ) throw new JException('Not authorised.', 400);

			$test_id = Input::get('test_id');
			$unique_id = Input::get('unique_id');

			if ( !$test_id || !$unique_id ) throw new JException('Invalid request', 400);

			DB::table('test_sessions')
				->where('test_id', (int) $test_id)
				->where('unique_id', $unique_id)
				->where('user_id', (int) $user->id)
				->update(array('is_active' => '0'))
				;

			return Helper::json_success_response(array('message' => 'Deactivated'), 201);
		}
		catch ( Exception $e )
		{
			return Helper::json_success_response(array('message' => $e->getMessage()), $e->getCode());
		}
	}

	/**
	 * Function to display the test for students
	 */
	public function take( $test_id, $unique_short )
	{
		$user = Helper::get_current_user();
		$sessions_model = new Sessions;
		$session = $sessions_model->get_session_from_short_id($test_id, $unique_short);
		$test = Test::load_populate($session->test_id, null, false);
		$doc = Document::get_instance();

		// Lets see if we need to authenticate, check if test is not anonymous and user not logged
		// **If session id is invalid, it will get caught by the view and show an error page
		if ( $session->unique_id && $session->is_active && !$test->anon && !$user )
		{
			// Register events
			$subscriber = new AuthGoogle;
			Event::subscribe($subscriber);

			try
			{
				// Check to see if we should be triggering any authentication plugins
				if ( Input::get('auth') )
				{
					// User should be redirected to Google at this point
					Event::fire('auth.begin_authentication');
				}
				elseif ( Input::get('authenticate') )
				{
					// Authenticate
					Event::fire('auth.user_authenticate');
				}
				else
				{
					$this->libs = array_merge($this->libs, array('deparam', 'core'));
					$this->_buffer = View::make('student.login')->nest('login_form', 'login', array('student' => true));
				}
			}
			catch ( Exception $e )
			{
				$this->_buffer = View::make('student.login_failed', array('error' => $e->getMessage()));
			}
		}
		elseif ( $session->unique_id && $session->is_active && $test->anon )
		{// TODO: Allow anonymous tests
			// Let's check to see if URL has a unique_id if not create it
			$uri = JFactory::getURI();
			$unique = $uri->getVar( '_' );

			if ( !$unique || strlen( $unique ) != 8 ) {
				$unique = THelper::generate_unique_anon_id( $test->id, $session->unique_id );

				if ( !$unique ) {
					JError::raiseError( 500, 'Error generating unique id.' );
					return false;
				}

				$uri->setVar( '_', $unique );
				$app->redirect( $uri );
			}
		}

		// If none of the above views have been called, then we are ready to display the test
		if ( !$this->_buffer )
		{
			// Check to see that this is even a valid session and that it is active
			if ( !$session->test_id || !$session->unique_id || !$session->is_active )
			{
				$this->_buffer = View::make('student.test_noexists');
			}
			else
			{
				if ( empty($test) || !$test->id ) throw new Exception('Test does\'t exist.', 500);

				$this->libs = array_merge($this->libs, array('deparam', 'timer', 'core', 'socket.io', 'click',
					'templates'));

				// Set the anon_id
				if ( $test->anon ) $doc->add_script_declaration("var anon_id = '{$unique}';");

				$doc->add_inline_view_file('test.student.css');
				$doc->add_script_declaration("var test_uri = '" .Request::url(). "', _token = '" .csrf_token(). "';");

				$this->_buffer = View::make('student.test',
					array('test' => $test, 'session' => $session, 'user' => $user));
			}
		}

		return $this->exec(array('simple' => true));
	}

	/**
	 * Used when a student answers a question
	 */
	public function answer()
	{
		try
		{
			$user = Helper::get_api_user();
			$data = Input::all();

			// Let's make sure that the answer sent matches all the ids in our system
			$test_data = DB::table('test_tests')
				->select('test_tests.id AS test_id', 'test_tests.anon',
					'test_sessions.id AS session_id',
					'test_questions.id AS question_id',
					'test_question_types.type AS qtype')
				->join('test_sessions', 'test_sessions.test_id', '=', 'test_tests.id' )
				->join('test_questions', 'test_questions.test_id', '=', 'test_tests.id' )
				->join('test_question_types', 'test_question_types.id', '=', 'test_questions.question_type')
				->where('test_tests.id', (int) $data['test_id'])
				->where('test_sessions.unique_id', $data['unique_id'])
				->where('test_sessions.is_active', '1')
				->where('test_questions.id', (int) $data['question_id'])
				->groupBy('test_sessions.id')
				->first()
				;
			if ( empty($test_data) || ($test_data->anon && (!$data['anon_id'] && strlen($data['anon_id']) != 8)) )
			{
				throw new Exception('Invalid request.', 400);
			}

			// Delete all previous answers to this question for this test session
			$query = DB::table('test_answers')
				->where('session_id', (int) $test_data->session_id)
				->where('question_id', (int) $test_data->question_id)
				;
			if ( $test_data->anon ) $query->where('anon_user_id', $data['anon_id']);
			else                    $query->where('user_id', (int) $user->id);
			// Do delete
			$query->delete();

			// Add answer(s) to db
			if ( $test_data->anon ) $row_defaults = array('user_id' => 0, 'anon_user_id' => $data['anon_id']);
			else                    $row_defaults = array('user_id' => (int) $user->id, 'anon_user_id' => '');

			$row_defaults['session_id'] = (int) $test_data->session_id;
			$row_defaults['question_id'] = (int) $test_data->question_id;

			$tuples = array();

			// Special treatment for multiple answer questions
			if ( 'mcma' == $test_data->qtype )
			{
				foreach ( $data['answer'] as $value )
				{
					$tuples[] = array_merge($row_defaults, array('answer_id' => $value, 'answer_text' => ''));
				}
			}
			else
			{
				$answer_id = $answer_text = '';

				if ( 'mcsa' == $test_data->qtype ) $answer_id = (int) $data['answer'];
				else                               $answer_text = (string) $data['answer'];

				$tuples[] = array_merge($row_defaults, array('answer_id' => $answer_id, 'answer_text' => $answer_text));
			}

			// Insert data
			DB::table('test_answers')->insert($tuples);

			return Helper::json_success_response(array('message' => 'Answer submitted'), 201);
		}
		catch ( Exception $e )
		{
			return Helper::json_success_response(array('message' => $e->getMessage()), $e->getCode());
		}
	}

	private function _return_with_unique_id( $test_id )
	{
		// Lets generate id for this test
		$unique_id = Helper::generate_unique_test_id($test_id);

		if ( !$unique_id )
			throw new Exception('Error creating unique ID for test.', 500);

		return $unique_id;
	}
}