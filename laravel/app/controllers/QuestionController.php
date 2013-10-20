<?php

class QuestionController extends \BaseController {

	/**
	 * Get question
	 *
	 * @return Response
	 */
	public function get( $test_id, $isp, $session_id, $question_id = null )
	{
		// Helper::csrf_check();

		try
		{
			$user = Helper::get_api_user();
			$test = Test::find($test_id);
			$session = Sessions::get_session_from_long_id($session_id, $test_id);
			$model = new Question();

			// If we are a teacher let's make sure that everything matches
			if ( $isp && (!$user->hasAccess('teacher') || $user->id != $session->user_id) ) throw new Exception('Not authorised.');
			// If test is interactive, then students can't request questions on their own
			elseif ( !$isp && $test->interactive ) throw new Exception('Not authorised.');

			$question = $model->get_question($test_id, $question_id);

			if ( $question->id )
			{
				$question->options = $model->get_options($question->id);
				$question->max_order = $model->max_order($question->test_id);

				// Not sure what the heck this is for.. it's from the Joomla days
				// if ( 'youtube' == $question->media_type ) {
					// $url = JURI::getInstance( $question->media );
					// $question->media = $url->getVar( 'v', $question->media );
				// }

				// Update to the question_id that we are showing
				// FUTURE: This could go in a queue
				if ( $isp && $test->interactive )
				{
					DB::table('test_sessions')
						->where('id', $session->id)
						->update(array('last_question' => $question->id))
						;
				}
			}

			if ( !$test->interactive )
			{
				$question->test_seconds = $test->seconds;
			}

			return Response::json($question, 200);
		}
		catch ( Exception $e )
		{
			return Response::json(array('message' => $e->getMessage()), 400);
		}
	}

}