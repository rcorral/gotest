<?php
/**
 * @package	API
 * @version 1.5
 * @author 	Rafael Corral
 * @link 	http://www.rafaelcorral.com
 * @copyright Copyright (C) 2012 Rafael Corral. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.plugin.plugin');

class plgAPITests extends ApiPlugin
{
	public function __construct()
	{
		parent::__construct();
		$this->loadLanguage();

		$this->setResourceAccess( array( 'answer' ), 'public', JRequest::getMethod() );

		ApiResource::addIncludePath( JPATH_PLUGINS .DS. 'api' .DS. 'tests' .DS. 'resources' );
	}
}