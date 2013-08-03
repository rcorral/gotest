<?php

/**
 * Return the first argument that returns true
 */
function coalesce()
{
	$args = func_get_args();

	foreach ( $args as $arg )
		if ( $arg )
			return $arg;

	return null;
}