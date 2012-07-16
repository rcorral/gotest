<?php
defined('JPATH_PLATFORM') or die;

/**
 * This needs to be re-done to be more mvc, but this is a start
 */
jimport( 'joomla.controller.base' );

class TestController extends JControllerBase
{
	public function execute()
	{
		require JPATH_BASE . '/components/test/models/test.php';
		require JPATH_BASE . '/components/test/views/test/view.html.php';

		$paths = new SplPriorityQueue();
		$paths->insert( JPATH_BASE . '/components/test/views/test/tmpl', 1 );
		$model = new TestModelTest();
		$view = new TestViewTest( $model, $paths );
		$view->display();
	}
}
