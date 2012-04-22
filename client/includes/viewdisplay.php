<?php
defined('JPATH_PLATFORM') or die;

jimport( 'joomla.view.html' );

class JViewDisplay extends JViewHtml
{
	public function display()
	{
		echo $this->render();
	}
}