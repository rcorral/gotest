<?php

class BaseController extends Controller
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
	 * Array of stylesheets
	 */
	protected $_styles = array();

	/**
	 * Array of scripts
	 */
	protected $_scripts = array();

	/**
	 * Queue meta tags to be added to <head>
	 */
	public function add_meta( $attr, $priority = 10 )
	{
		if ( !isset( $this->_meta[$priority] ) ) {
			$this->_meta[$priority] = array();
		}

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
		if ( !isset( $this->_style[$priority] ) ) {
			$this->_style[$priority] = '';
		}

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
		if ( !isset( $this->_script[$priority] ) ) {
			$this->_script[$priority] = '';
		}

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
		if ( !isset( $this->_styles[$priority] ) ) {
			$this->_styles[$priority] = array();
		}

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
		if ( !isset( $this->_scripts[$priority] ) ) {
			$this->_scripts[$priority] = array();
		}

		$this->_scripts[$priority][$src] = array_merge(array('type' => 'text/javascript'), (array)$attr);

		return $this;
	}

	/**
	 * Gets the curret output buffer
	 */
	public function get_buffer()
	{
		return $this->_buffer;
	}

	/**
	 * Generates the html that goes in the <head>
	 *
	 * @return string Contents for the <head>
	 **/
	public function get_head()
	{
		$buffer = '';
		ksort($this->_meta);
		ksort($this->_styles);
		ksort($this->_scripts);
		ksort($this->_style);
		ksort($this->_script);

		// Meta tags
		$used_list = array();
		foreach ( $this->_meta as $metas ) {
			foreach ( $metas as $meta ) {
				if ( isset($meta['name']) ) {
					if ( isset($used_list[$meta['name']]) ) {
						continue;
					}
					$used_list[$meta['name']] = true;
				}

				$buffer .= $this->_tab . '<meta ';
				foreach ( $meta as $key => $value ) {
					$buffer .= $key . '="' . htmlspecialchars($value) . '"';
				}
				$buffer .= ' />' . $this->_line_end;
			}
		}

		// Title
		$buffer .= $this->_tab . '<title>' . htmlspecialchars($this->_title, ENT_COMPAT, 'UTF-8') . '</title>' . $this->_line_end;

		// Stylesheets
		$used_list = array();
		foreach ( $this->_styles as $styles ) {
			foreach ( $styles as $src => $attr ) {
				if ( isset($used_list[$src]) ) {
					continue;
				}
				$used_list[$src] = true;

				$buffer .= $this->_tab . '<link src="' . $src . '" ';
				foreach ( $attr as $key => $value ) {
					$buffer .= $key . '="' . $value . '"';
				}
				$buffer .= ' />' . $this->_line_end;
			}
		}

		// Scripts
		$used_list = array();
		foreach ( $this->_scripts as $prio => $scripts ) {
			if ( $prio > 5 ) {
				break;
			}
			foreach ( $scripts as $src => $attr ) {
				if ( isset($used_list[$src]) ) {
					continue;
				}
				$used_list[$src] = true;

				$buffer .= $this->_tab . '<script src="' . $src . '" ';
				foreach ( $attr as $key => $value ) {
					$buffer .= $key . '="' . $value . '"';
				}
				$buffer .= ' ></script>' . $this->_line_end;
			}
		}

		// Style declarations
		foreach ( $this->_style as $style ) {
			foreach ( $style as $content ) {
				$buffer .= $this->_tab . '<style type="text/css">' . $this->_line_end;
				$buffer .= $content . $this->_line_end;
				$buffer .= $this->_tab . '</style>' . $this->_line_end;
			}
		}

		// Script declarations
		foreach ( $this->_script as $prio => $script ) {
			if ( $prio > 5 ) {
				break;
			}
			foreach ( $script as $content ) {
				$buffer .= $this->_tab . '<script type="text/javascript">' . $this->_line_end;
				$buffer .= $content . $this->_line_end;
				$buffer .= $this->_tab . '</style>' . $this->_line_end;
			}
		}

		return $buffer;
	}

	public function exec()
	{
		// Display the main view
		$this->_buffer = $this->display();

		// Get the template
		$this->_buffer = View::make('index', array( 'contents' => (string) $this->_buffer, 'that' => $this ));

		return $this->_buffer;
	}
}