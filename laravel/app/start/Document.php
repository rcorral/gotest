<?php
class Document
{
	/**
	 * Tab
	 **/
	public $_tab = "\11";

	/**
	 * Tab
	 **/
	public $_line_end = "\12";

	/**
	 * The output buffer contents
	 */
	protected $_buffer;

	/**
	 * Default page title
	 */
	public $_title = 'Home';

	/**
	 * Array of meta tags
	 */
	protected $_meta = array();

	/**
	 * Array of style declarations
	 */
	protected $_style = array();

	/**
	 * Array of script declarations
	 */
	protected $_script = array();

	/**
	 * String containing all js code to be put on the onLoad() function for jquery
	 */
	protected $_jquery_js = '';

	/**
	 * Array of stylesheets
	 */
	protected $_styles = array();

	/**
	 * Array of scripts
	 */
	protected $_scripts = array();

	/**
	 * Front-end libraries
	 * 
	 * This array is used in the combiner.php file to generate a single css and js file.
	 * When adding new scripts, test the combiner.php file to ensure there will be no problems
	 */
	public $_libs = array(
		'jquery' => array(
			'js' => "SITEPATH/js/jquery-1.10.2.min.js"
			),
		'bootstrap' => array(
			'js' => "SITEPATH/packages/bootstrap/js/bootstrap.min.js",
			'css' => array(
				"SITEPATH/packages/bootstrap/css/bootstrap.min.css",
				"SITEPATH/packages/bootstrap/css/bootstrap-theme.min.css",
				)
			),
		'bootstrap-clean' => array(
			'js' => "SITEPATH/packages/bootstrap/js/bootstrap.min.js",
			'css' => array(
				"SITEPATH/packages/bootstrap/css-clean/bootstrap.min.css",
				"SITEPATH/packages/bootstrap/css-clean/bootstrap-theme.min.css",
				)
			),
		'colorbox' => array(
			'js' => "SITEPATH/js/jquery.colorbox.js",
			'css' => "SITEPATH/css/colorbox.css"
			),
		'autocomplete' => array(
			'js' => "SITEPATH/js/jquery.autocomplete.js",
			'css' => "SITEPATH/css/jquery.autocomplete.css"
			),
		'textboxlist' => array(
			'js' => array(
				"SITEPATH/js/GrowingInput.js",
				"SITEPATH/js/TextboxList.js",
				"SITEPATH/js/TextboxList.Autocomplete.js"
				)
			),
		'timer' => array(
			'js' => 'SITEPATH/js/jquery.timer.js'
			),
		'deparam' => array(
			'js' => "SITEPATH/js/jquery.ba-bbq.min.js"
			),
		'sortable' => array(
			'js' => "SITEPATH/js/jquery.sortable.js"
			),
		'core' => array(
			'js' => "SITEPATH/js/core.js"
			),
		'templates' => array(
			'js' => 'SITEPATH/js/templates.js'
			),
		'socket.io' => array(
			'js' => 'http://SITE_HOST:IO_PORT/socket.io/socket.io.js'
			),
		'click' => array(
			'js' => 'SITEPATH/js/click.js'
			),
		'presenter_click' => array(
			'js' => 'SITEPATH/js/presenter_click.js'
			),
		'validation' => array(
			'js' => array(
				"SITEPATH/js/jquery.validate.min.js",
				"SITEPATH/js/additional-methods.min.js"
				)
			),
		'qtip' => array(
			'js' => "SITEPATH/js/jquery.qtip.pack.js",
			'css' => "SITEPATH/css/jquery.qtip.min.css"
			),
		'uniform' => array(
			'js' => "SITEPATH/js/jquery.uniform.min.js"
			),
		'main' => array(
			'js' => "SITEPATH/js/main.js",
			'css' => "SITEPATH/css/main.css"
			)
		);

	public static $io_port = 8080;

	static function get_instance()
	{
		static $instance;

		if ( !$instance )
			$instance = new Document();

		return $instance;
	}

	/**
	 * Queue meta tags to be added to <head>
	 */
	public function add_meta( $attr, $priority = 10 )
	{
		if ( !isset( $this->_meta[$priority] ) )
			$this->_meta[$priority] = array();

		$this->_meta[$priority][] = $attr;

		return $this;
	}

	/**
	 * Queue a style declaration to be added to the output
	 * 
	 * @param  $content String containing the contents of the declaration
	 * @param  $priority The priority of the declaration
	 */
	public function add_style_declaration( $content, $priority = 10 )
	{
		if ( !isset( $this->_style[$priority] ) )
			$this->_style[$priority] = '';

		$this->_style[$priority] .= $content;
	}

	/**
	 * Queue a script to be added to the output
	 * 
	 * Anything with a priority of 5 or lower will be added to the <head> others
	 * will be added before the closing <body> tag.
	 * 
	 * @param  $content String containing the contents of the declaration
	 * @param  $priority The priority of the declaration
	 */
	public function add_script_declaration( $content, $priority = 10 )
	{
		if ( !isset( $this->_script[$priority] ) )
			$this->_script[$priority] = '';

		$this->_script[$priority] .= $content;

		return $this;
	}

	/**
	 * Queue a stylesheet to be added to the output
	 * 
	 * @param  $src String containing the path to teh script
	 * @param  $attr (Optional) Array with attributes to be added to script tag
	 * @param  $priority The priority of the script
	 */
	public function add_stylesheet( $src, $attr = array(), $priority = 10 )
	{
		if ( !isset( $this->_styles[$priority] ) )
			$this->_styles[$priority] = array();

		$this->_styles[$priority][$src] = array_merge(array('rel' => 'stylesheet'), (array)$attr);
	}

	/**
	 * Queue a script to be added to the output
	 * 
	 * Anything with a priority of 5 or lower will be added to the <head> others
	 * will be added before the closing <body> tag.
	 * 
	 * @param  $src String containing the path to teh script
	 * @param  $attr (Optional) Array with attributes to be added to script tag
	 * @param  $priority The priority of the script
	 */
	public function add_script( $src, $attr = array(), $priority = 10 )
	{
		if ( !isset( $this->_scripts[$priority] ) )
			$this->_scripts[$priority] = array();

		$this->_scripts[$priority][$src] = array_merge(array('type' => 'text/javascript'), (array)$attr);

		return $this;
	}

	/**
	 * Method to add jquery declaration that need to be wrapped in jQuery(document).ready
	 * This will be added to the <script> tag in the footer
	 */
	function add_jquery( $content )
	{
		$this->_jquery_js .= $content . (false === strpos(trim($content), ';') ? ';' : '');
	}

	/**
	 * Add a known library to the head
	 * If in production add the combined files.
	 */
	public function add_lib( $libs = array() )
	{
		static $called = false;

		$site_path = URL::to('/');
		$host = Request::getHttpHost();

		if ( 'local' != App::environment() )
		{
			if ( !$called )
			{
				require dirname(__FILE__) . '/combined-files.php';
				$this->add_script_declaration(';jQuery.noConflict();');
				$called = true;
			}
		}
		else
		{
			foreach ( (array) $libs as $lib )
			{
				// We only want to add this once
				if ( true === $this->_libs[$lib] )
					continue;

				// Throw error if lib doesn't exist
				if ( !isset($this->_libs[$lib]) )
					throw new Exception('Invalid library.', 500);
					

				foreach ( $this->_libs[$lib] as $type => $files )
					if ( 'js' == $type )
						foreach ( (array) $files as $file )
							$this->add_script(str_replace(array('SITEPATH', 'SITE_HOST', 'IO_PORT'),
								array($site_path, $host, static::$io_port),
								$file));
					elseif ( 'css' == $type )
						foreach ( (array) $files as $file )
							$this->add_stylesheet(str_replace('SITEPATH', $site_path, $file));

				switch ( $lib )
				{
					case 'jquery':
						$this->add_script_declaration(';jQuery.noConflict();');
						break;

					case 'bootstrap':
						$this->add_meta(array('http-equiv' => 'X-UA-Compatible', 'content' => 'IE=edge'), 1);
						$this->add_meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0'), 1);
						break;

					default:
						break;
				}

				$this->_libs[$lib] = true;
			}
		}

		$this->js_variables();

		// dat chain!
		return $this;
	}

	function add_inline_view_file($view, $options = array())
	{
		$type = substr($view, (strrpos($view, '.') + 1));

		if ( 'js' == $type )
		{
			ob_start();
			require app_path() . '/views/js/' . $view;
			$contents = ob_get_clean();

			if ( isset($options['jquery']) && $options['jquery'] )
				$this->add_jquery($contents);
			else
				$this->add_script_declaration($contents);
		}
		elseif ( 'css' == $type )
		{
			ob_start();
			require app_path() . '/views/css/' . $view;
			$contents = ob_get_clean();

			$this->add_style_declaration($contents);
		}
	}

	function js_variables()
	{
		static $done;

		if ( true === $done ) return;

		$host = Request::getHttpHost();

		$js = "\nvar live_site = '" . URL::to('/'). '/\'';
		$js .= "\n\t, in_dev=" . ('local' != App::environment() ? 'false' : 'true');
		$js .= "\n\t, io_server = 'http://{$host}:" .static::$io_port. '/\'';

		$is_logged_in = 0;
		if ( Helper::is_logged_in() )
		{
			$is_logged_in = true;
			$user = Helper::get_current_user();
			$js .= "\n\t, api_key = '{$user->api_token}'";
			$js .= "\n\t, _token = '" .csrf_token(). "'";
		}

		$home = (int) Helper::is_home();
		$js .= "\n\t, is_home={$home}";
		$js .= "\n\t, is_loggedin={$is_logged_in}";
		$js .= "\n\t;";

		$this->add_script_declaration($js);

		$done = true;
	}

	/**
	 * Generates the html that goes in the <head>
	 *
	 * @return string Contents for the <head>
	 **/
	public function get_head()
	{
		// Fire off listener before anything else
		Event::fire('head.before_compile');

		$buffer = '';
		$is_dev = ('local' != App::environment());

		// Sort by priority
		ksort($this->_meta);
		ksort($this->_styles);
		ksort($this->_scripts);
		ksort($this->_style);
		ksort($this->_script);

		// Meta tags
		$used_list = array();
		foreach ( $this->_meta as $metas )
		{
			foreach ( $metas as $meta )
			{
				if ( isset($meta['name']) )
				{
					if ( isset($used_list[$meta['name']]) )
						continue;
					$used_list[$meta['name']] = true;
				}

				$buffer .= $this->_tab . '<meta';
				foreach ( $meta as $key => $value )
					$buffer .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
				$buffer .= '>' . $this->_line_end;
			}
		}

		// Title
		$buffer .= $this->_tab . '<title>' . htmlspecialchars($this->_title, ENT_COMPAT, 'UTF-8') . '</title>' . $this->_line_end;

		// Stylesheets
		$used_list = array();
		foreach ( $this->_styles as $styles )
		{
			foreach ( $styles as $src => $attr )
			{
				if ( isset($used_list[$src]) )
					continue;
				$used_list[$src] = true;

				$buffer .= $this->_tab . '<link href="' . $src . '" ';
				foreach ( $attr as $key => $value ) {
					$buffer .= $key . '="' . $value . '"';
				}
				$buffer .= ' />' . $this->_line_end;
			}
		}

		// Scripts
		$used_list = array();
		foreach ( $this->_scripts as $prio => $scripts )
		{
			if ( $prio > 5 )
				break;
			foreach ( $scripts as $src => $attr )
			{
				if ( isset($used_list[$src]) )
					continue;
				$used_list[$src] = true;

				$buffer .= $this->_tab . '<script src="' . $src . '" ';
				foreach ( $attr as $key => $value )
					$buffer .= $key . '="' . $value . '"';
				$buffer .= '></script>' . $this->_line_end;
			}
		}

		// Style declarations
		foreach ( $this->_style as $style )
		{
			$buffer .= $this->_tab . '<style type="text/css">' . $this->_line_end;
			// TODO: Add minifier
			$buffer .= $style . $this->_line_end;
			$buffer .= $this->_tab . '</style>' . $this->_line_end;
		}

		// Script declarations
		foreach ( $this->_script as $prio => $script )
		{
			if ( $prio > 5 )
				break;
			$buffer .= $this->_tab . '<script type="text/javascript">' . $this->_line_end;
			// TODO: Add minifier
			$buffer .= $content . $this->_line_end;
			$buffer .= $this->_tab . '</script>' . $this->_line_end;
		}

		return $buffer;
	}

	/**
	 * Generates the html that goes before the </body> tag
	 *
	 * @return string Contents to put before the </body> tag
	 **/
	public function get_footer()
	{
		// Fire off listener before anything else
		Event::fire('footer.before_compile');

		$buffer = '';
		$is_dev = ('local' != App::environment());

		// Sort by priority
		ksort($this->_scripts);
		ksort($this->_script);

		// Scripts
		$used_list = array();
		foreach ( $this->_scripts as $prio => $scripts )
		{
			if ( $prio < 6 )
				continue;
			foreach ( $scripts as $src => $attr )
			{
				if ( isset($used_list[$src]) )
					continue;
				$used_list[$src] = true;

				$buffer .= $this->_tab . '<script src="' . $src . '" ';
				foreach ( $attr as $key => $value )
					$buffer .= $key . '="' . $value . '"';
				$buffer .= '></script>' . $this->_line_end;
			}
		}

		// Script declarations
		foreach ( $this->_script as $prio => $content )
		{
			if ( $prio < 6 )
				continue;
			$buffer .= $this->_tab . '<script type="text/javascript">' . $this->_line_end;
			// TODO: Add minifier
			$buffer .= $content . $this->_line_end;
			$buffer .= $this->_tab . '</script>' . $this->_line_end;
		}

		return $buffer;
	}

	public function on_before_compile()
	{
		$doc = Document::get_instance();

		// Add jQuery
		if ( $doc->_jquery_js ) {
			$pre = 'jQuery(document).ready(function(){';
			$post = '});';
			$doc->add_script_declaration( $pre . $doc->_jquery_js . $post );
		}
	}
}
