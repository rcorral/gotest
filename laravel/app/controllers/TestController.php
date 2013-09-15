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

	/**
	 * API call to complete test
	 */
	public function complete()
	{
		$user = Helper::get_api_user();

		try
		{
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
		} catch (Exception $e) {
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
					$this->_buffer = View::make('student.login');
				}
			}
			catch ( Exception $e )
			{
				$this->_buffer = View::make('student.login_failed', array('error' => $e->getMessage()));
			}
		}
		elseif ( $session->unique_id && $session->is_active && $test->anon )
		{
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
			print_r('wooop');
			print_r($user);die();
			$this->_buffer = View::make('student.login_failed');
		}

		return $this->exec(array('simple' => true));
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