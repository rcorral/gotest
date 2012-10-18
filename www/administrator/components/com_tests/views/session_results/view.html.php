<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class TestsViewSession_Results extends JView
{
	protected $item;

	/**
	 * Display the view
	 */
	public function display( $tpl = null )
	{
		$this->test = $this->get( 'Test' );

		// This more than likely means that user can't view these results
		if ( empty( $this->test ) ) {
			JError::raiseError( 500, 'Test not valid.' );
			return false;
		}

		$this->questions = $this->get( 'Questions' );
		$this->student_answers = $this->get( 'StudentAnswers' );

		parent::display( $tpl );
	}
}
