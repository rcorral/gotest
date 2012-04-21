<?php
defined('JPATH_PLATFORM') or die;

class TestViewQuestion extends JViewHtml
{
	function render()
	{
		$this->question = $this->model->get_question();
		$this->question->answers = $this->model->get_answers( $this->question->id );

		return parent::render();
	}
}