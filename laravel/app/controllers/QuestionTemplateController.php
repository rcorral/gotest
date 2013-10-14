<?php

class QuestionTemplateController extends \BaseController
{
	/**
	 * Display the specified resource.
	 *
	 * @param  string  $type
	 * @return Response
	 */
	public function show( $type )
	{
		if ( !$type )
			throw new JException( Lang::get('api_tests.no_type') );

		// Lets check that this type actually exists
		$question = Helper::get_question_type( $type );

		if ( !$question || empty($question) ) throw new JException(JText::_('PLG_API_TESTS_QUESTION_UNAVAILABLE'));

		// The 'n' determines that it is a new question
		$rand = 'n' . substr(md5(uniqid(rand(), true)), 0, 5);
		$html = str_replace(
			array('TYPE_ID', 'QUESTION_TYPE', 'QID', 'QUESTION_TITLE',
				'QUESTION_SECONDS', 'QUESTION_MEDIA', 'OPTION_VALID_LINK', 'OPTION_VALID_IMAGE',
				'OPTION_VALID_YOUTUBE', 'QUESTION_MIN_ANSWERS', 'COUNTER_START', 'COUNTER',
				'OPTION_TITLE', 'OPTION_VALID', '{OPTION_START}', '{OPTION_END}'),
			array($question->id, $question->title, $rand, '',
				'', '', '', '',
				'', '', 1, 1,
				'', '', '', ''),
			$question->html
		);

		return Helper::json_success_response(array('html' => $html));
	}
}