<?php
/**
 * @package	API
 * @version 1.5
 * @author 	Rafael Corral
 * @link 	http://www.corephp.com
 * @copyright Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
*/

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');
jimport('joomla.filter.filteroutput');

class TestsApiResourceQuestion extends ApiResource
{
	public function get()
	{
		JModel::addIncludePath( JPATH_ADMINISTRATOR . '/components/com_tests/models' );

		$model = JModel::getInstance( 'Question', 'TestsModel' );

		$question = $model->get_question();

		if ( $question->id ) {
			$question->options = $model->get_options( $question->id );
			$question->max_order = $model->max_order( $question->test_id );

			if ( 'youtube' == $question->media_type ) {
				$url = JURI::getInstance( $question->media );
				$question->media = $url->getVar( 'v', $question->media );
			}
		}

		$this->plugin->setResponse( $question );
	}

	public function post()
	{
		$this->plugin->setResponse( 'here is a post request' );
	}
}