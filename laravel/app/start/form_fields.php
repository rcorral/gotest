<?php

Form::macro('published', function( $name = 'published', $value = 'published' )
{
	echo Form::select($name, array('published' => 'Active', 'unpublished' => 'Inactive'), $value);
});