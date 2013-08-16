<?php

Form::macro('published', function( $name = 'published', $value = 'published' )
{
	echo Form::select($name, array('published' => 'Active', 'unpublished' => 'Inactive'), $value);
});

Form::macro('categories', function( $name = 'catid', $value = 0, $options = array(), $return_on_empty = false )
{
	static $root;
	static $children;

	if ( !$root )
	{
		$root = Categories::find(1);
		$children = $root->getChildren();
	}

	if ( $return_on_empty && empty($children) )
		return false;

	$options = array_merge(array('default' => array(0 => 'Choose subject...', -1 => 'New subject...')), $options);
// DEBUG
file_put_contents( '/var/log/rafa.log',
	'log output: - '
	. var_export($root->getChildren(), true)
	. "\n\n", FILE_APPEND);

	$select = $options['default'];

	return Form::select($name, $select, $value);

	foreach ( $children as $country )
	{
		echo "<h3>{$country->name}</h3>";

			if ( count($country->getChildren()) )
			{
				echo "<p>{$country->name} has the following states registered with our system:</p>";
				echo "<ul>";

			foreach ( $country->getChildren() as $state )
			{
				echo "<li>{$state->name}</li>";
			}

			echo "</ul>";
		}
	}
});