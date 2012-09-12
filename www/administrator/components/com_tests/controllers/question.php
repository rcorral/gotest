<?php
defined('JPATH_PLATFORM') or die;

/**
 * This needs to be re-done to be more mvc, but this is a start
 */
jimport( 'joomla.controller.base' );

class TestControllerQuestion extends JControllerBase
{
	public function execute()
	{
		require JPATH_BASE . '/components/test/models/question.php';
		require JPATH_BASE . '/components/test/views/question/view.html.php';

		$paths = new SplPriorityQueue();
		$paths->insert( JPATH_BASE . '/components/test/views/question/tmpl', 1 );
		$model = new TestModelQuestion();
		$view = new TestViewQuestion( $model, $paths );
		$view->display();
	}
}
