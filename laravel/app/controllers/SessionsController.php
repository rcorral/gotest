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
		// $doc = Document::get_instance();
		// $doc->add_inline_view_file('tests.index.js', array('jquery' => true));

		$this->_buffer = View::make('presenter.sessions', array(
			'user' => Helper::get_current_user(),
			'sessions' => Sessions::get_sessions()
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
						$session->is_active = ('publish' == $input['state'] ? 1 : 0);
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