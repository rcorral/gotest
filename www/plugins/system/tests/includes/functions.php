<?php
/**
 * @version		$Id: ppwell.php 21766 2011-07-08 12:20:23Z eddieajau $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

function tests_route( $link )
{
	return JRoute::_( $link );
}

function tests_parse_args( $args, $defaults = '' )
{
	if ( is_object( $args ) ) {
		$r = get_object_vars( $args );
	} elseif ( is_array( $args ) ) {
		$r = $args;
	} else {
		ppw_parse_str( $args, $r );
	}
 
	if ( is_array( $defaults ) ) {
		return array_merge( $defaults, $r );
	}

	return $r;
 }

function tests_parse_str( $string, &$array )
{
	parse_str( $string, $array );
	if ( get_magic_quotes_gpc() ) {
		$array = TestsHelper::stripslashes_deep( $array );
	}
}

if ( !function_exists( 'myPrint' ) ) {
	/**
	 * Function for printing data
	 * @return
	 */
	function myPrint( $var, $pre = true )
	{
		if( $pre )
			echo '<pre>';
		print_r( $var );
		if( $pre )
			echo '</pre>';
	}
}

if ( !function_exists( 'timer_start' ) ) {
	/**
	 * PHP 4 standard microtime start capture.
	 *
	 * @access private
	 * @since 0.71
	 * @global int $timestart Seconds and Microseconds added together from when function is called.
	 * @return bool Always returns true.
	 */
	function timer_start() {
		global $jd_timestart;

		$mtime = explode( ' ', microtime() );
		$mtime = $mtime[1] + $mtime[0];
		$jd_timestart = $mtime;

		return true;
	}
}

if ( !function_exists( 'timer_stop' ) ) {
	/**
	 * Return and/or display the time from the page start to when function is called.
	 *
	 * You can get the results and print them by doing:
	 * <code>
	 * $nTimePageTookToExecute = timer_stop();
	 * echo $nTimePageTookToExecute;
	 * </code>
	 *
	 * Or instead, you can do:
	 * <code>
	 * timer_stop(1);
	 * </code>
	 * which will do what the above does. If you need the result, you can assign it to a variable, but
	 * most cases, you only need to echo it.
	 *
	 * @since 0.71
	 * @global int $timestart Seconds and Microseconds added together from when timer_start() is called
	 * @global int $timeend  Seconds and Microseconds added together from when function is called
	 *
	 * @param int $display Use '0' or null to not echo anything and 1 to echo the total time
	 * @param int $precision The amount of digits from the right of the decimal to display. Default is 3.
	 * @return float The "second.microsecond" finished time calculation
	 */
	function timer_stop( $display = 0, $precision = 3 ) {
		global $jd_timestart, $jd_timeend;

		$mtime = microtime();
		$mtime = explode( ' ', $mtime );
		$mtime = $mtime[1] + $mtime[0];
		$jd_timeend = $mtime;
		$timetotal = $jd_timeend - $jd_timestart;
		$r = number_format( $timetotal, $precision );

		if ( $display ) {
			echo $r;
		}

		return $r;
	}
}

if ( !function_exists( 'get_memory_usage' ) ) {
	/**
	 * Returns a readable memory usage number
	 */
	function get_memory_usage()
	{
		$units = array( 'B', 'KB', 'MB', 'GB', 'TB' );
		$bytes = max( memory_get_usage(), 0 );
		$pow = floor( ( $bytes ? log( $bytes ) : 0 ) / log( 1024 ) );
		$pow = min( $pow, count( $units ) - 1 );
		$bytes /= pow( 1024, $pow );

		return round( $bytes, 2 ) . ' ' . $units[$pow];
	}
}