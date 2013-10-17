<?php

class QuestionController extends \BaseController {

	/**
	 * Get question
	 *
	 * @return Response
	 */
	public function get( $test_id, $question_id = null )
	{
		// Helper::csrf_check();

		try
		{
			$user = Helper::get_api_user();
			$model = new Question();

			if ( !$user->hasAccess('teacher') ) throw new Exception('Not authorised.');

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
			}

			return Response::json($question, 200);
		} catch (Exception $e) {
			return Response::json(array('message' => $e->getMessage()), 400);
		}
	}

}