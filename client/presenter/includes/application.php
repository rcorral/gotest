<?php

class JClickApplication extends JApplicationWeb
{
	public function __construct( $config = null )
	{
		if ( array() == $config ) {
			$config = null;
		}

		return parent::__construct( null, $config );
	}
	/**
	 * Display the application.
	 */
	protected function doExecute()
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		$option = $app->input->get( 'option', 'test' );

		// Build the component path.
		$option = preg_replace( '/[^A-Z0-9_\.-]/i', '', $option );
		$file = $option;

		// Define component path.
		define('JPATH_COMPONENT', JPATH_BASE . '/components/' . $option);
		define('JPATH_COMPONENT_SITE', JPATH_SITE . '/components/' . $option);
		define('JPATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/' . $option);

		$path = JPATH_COMPONENT . '/' . $file . '.php';

		// If component is disabled throw error
		if ( !file_exists( $path ) ) {
			JError::raiseError( 404, JText::_('JLIB_APPLICATION_ERROR_COMPONENT_NOT_FOUND'));
		}

		$task = JRequest::getString('task');

		// Handle template preview outlining.
		$contents = null;

		// Execute the component.
		$contents = self::executeComponent( $path );

		$this->document->setTitle( $this->document->get( 'title' ) );
		$this->document->setDescription( $this->document->get( 'description' ) );
		$this->document->setBuffer( $contents, 'component' );

		return $contents;
	}

	/**
	 * Execute the component.
	 *
	 * @param   string  $path  The component path.
	 *
	 * @return  string  The component output
	 *
	 * @since   11.3
	 */
	protected static function executeComponent( $path )
	{
		ob_start();
		require_once $path;
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}
}