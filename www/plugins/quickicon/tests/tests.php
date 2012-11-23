<?php
/**
 * @copyright	Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Tests icon plugin
 */
class plgQuickiconTests extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 *
	 * @since       2.5
	 */
	public function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );
		$this->loadLanguage();
	}

	/**
	 * Returns an icon definition for an icon which looks for extensions updates
	 * via AJAX and displays a notification when such updates are found.
	 *
	 * @param  $context  The calling context
	 *
	 * @return array A list of icon definition associative arrays, consisting of the
	 *				 keys link, image, text and access.
	 *
	 * @since       2.5
	 */
	public function onGetIcons( $context )
	{
		if ( $context != 'mod_quickicon'
			|| !JFactory::getUser()->authorise( 'core.manage', 'com_tests' )
		) {
			return;
		}

		JFactory::getLanguage()->load( 'com_tests' );

		return array(
			array(
				'link' => 'index.php?option=com_tests&view=tests',
				'image' => 'header/icon-48-article.png',
				'text' => JText::_( 'COM_TESTS_VIEW_TESTS' ),
				'id' => 'plg_quickicon_tests'
			),
			array(
				'link' => 'index.php?option=com_tests&view=sessions',
				'image' => 'header/icon-48-contacts.png',
				'text' => JText::_( 'COM_TESTS_VIEW_SESSIONS' ),
				'id' => 'plg_quickicon_tests_sessions'
			)
		);
	}
}
