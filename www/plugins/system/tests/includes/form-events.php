<?php
/**
 * @version		$Id: form-events.php 21766 2011-07-08 12:20:23Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

class plgSystemTestsFormEvents extends JPlugin
{
	/**
	 * @param	JForm	$form	The form to be altered.
	 * @param	array	$data	The associated data for the form.
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	function onContentPrepareForm( $form, $data )
	{
	}

	/**
	 * Hook for saving company assignments on module
	 */
	function onExtensionBeforeSave( $context, $table, $isNew )
	{
	}

	/**
	 * Check to see if content is changing state
	 */
	function onContentChangeState( $context, $pks, $value )
	{
	}
}
