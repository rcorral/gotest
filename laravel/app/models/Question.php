<?php

class Question extends Eloquent
{
	public function get_question( $test_id, $question_id )
	{
		return DB::table('test_questions')
			->select('test_questions.id', 'test_questions.title', 'test_questions.seconds', 'test_questions.media',
				'test_questions.media_type', 'test_questions.order', 'test_questions.test_id',
				'test_question_types.type AS question_type')
			->join('test_question_types', 'test_question_types.id', '=', 'test_questions.question_type')
			->where('test_questions.test_id', (int) $test_id)
			->where('test_questions.order', '>=', (int) $question_id)
			->orderBy('test_questions.order', 'ASC')
			->first()
			;
	}

	public function get_options( $question_id )
	{
		if ( !$question_id ) return array();

		return DB::table('test_question_options' )
			->select('id', 'title')
			->where('question_id', (int) $question_id)
			->orderBy('id', 'ASC')
			->get()
			;
	}

	public function max_order( $test_id )
	{
		if ( !$test_id ) return 0;

		return DB::table('test_questions')
			->select(DB::raw('MAX(`order`) AS `order`'))
			->where('test_id', (int) $test_id)
			->pluck('order')
			;
	}
}