<?php

use Illuminate\Http\RedirectResponse;

/**
 * Sessions Controller
 */
class SessionsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->_buffer = View::make('presenter.sessions', array(
			'user' => Helper::get_current_user(),
			'sessions' => Sessions::get_sessions()
		));

		return $this->exec();
	}

	/**
	 * Show the results of a test
	 */
	public function show( $id )
	{
		$user = Helper::get_current_user();
		$session = Sessions::get_session($id);
		$test = Test::load_populate($session->test_id, null, true);

		// This more than likely means that user can't view these results
		if ( !$test->id ) throw new Exception(Lang::get('Test not valid'), 400);
		if ( $test->created_by != $user->id ) throw new Exception(Lang::get('Test not valid'), 400);

		$this->_buffer = View::make('presenter.sessions_results', array(
			'test' => $test,
			'student_answers' => $session->get_student_answers($test->anon)
		));

		return $this->exec();
	}

	public function store()
	{
		Helper::csrf_check();

		$input = Input::except('_token');

		if ( isset($input['action']) )
		{
			switch ( $input['action'] )
			{
				case 'change_state':
					$session = Sessions::load_populate($input['id'], null);

					try
					{
						$session->is_active = ('active' == $input['state'] ? 1 : 0);
						$session->save();
						return Helper::json_success_response(array(
							'html' => Form::item_state($session->is_active, $session->id, 'sessions')
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
	}

	/**
	 * Method to delete a session.
	 *
	 * @param	int $id A session id
	 * @return	object Success/Fail ajax response
	 */
	public function destroy( $id )
	{
		// Sanitize the id
		$session = Sessions::load_populate((int) $id);

		if ( !$session->id )
			return Helper::json_error_response(array('message' => Lang::get('Error deleting.')), 400);
		elseif ( $session->user_id != Helper::get_current_user()->id )
			throw new Exception(Lang::get('all.invalid_request'));

		$session->delete();

		return Helper::json_success_response(array(), 200);
	}

}