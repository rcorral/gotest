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

class TestsApiResourceQuestionTemplate extends ApiResource
{
	public function get()
	{
		$this->plugin->setResponse( 'here is a get request' );
	}

	public function post()
	{
		$type = JRequest::getWord( 'type' );
		$db = JFactory::getDBO();

		if ( !$type ) {
			throw new JException( JText::_('PLG_API_TESTS_NO_TYPE') );
		}

		// Lets check that this type actually exists
		$row = TestsHelper::get_question_type( $type );

		if ( !$row || empty( $row ) ) {
			throw new JException( JText::_('PLG_API_TESTS_QUESTION_UNAVAILABLE') );
		}

		// The 'n' determines that it is a new question
		$rand = 'n' . substr( md5( uniqid( rand(), true ) ), 0, 5 );
		$html = str_replace(
			array( 'TYPE_ID', 'QUESTION_TYPE', 'QID', 'QUESTION_TITLE',
				'QUESTION_SECONDS', 'QUESTION_MIN_ANSWERS', 'COUNTER_START', 'COUNTER',
				'OPTION_TITLE', 'OPTION_VALID', '{OPTION_START}', '{OPTION_END}' ),
			array( $row->id, $row->title, $rand, '',
				'', '', 1, 1,
				'', '', '', '' ),
			$row->html );

		$response = $this->getSuccessResponse( 200 );
		$response->html = $html;

		$this->plugin->setResponse( $response );
	}
}