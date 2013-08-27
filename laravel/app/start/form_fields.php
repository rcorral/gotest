<?php

Form::macro('published', function( $name = 'published', $value = 'published' )
{
	echo Form::select($name, array(1 => 'Active', 0 => 'Inactive'), $value);
});

Form::macro('categories', function( $name = 'catid', $value = 0, $options = array() )
{
	static $root;
	static $children;

	$options = array_merge(array(
		'default_opt' => array(0 => 'Choose subject...', -1 => 'New subject...'),
		'return_on_empty' => true,
		'from_cache' => true)
	, $options);

	if ( !$root || !$options['from_cache'] )
	{
		$root = Categories::find(1);
		$children = $root->findChildren();
	}

	if ( $options['return_on_empty'] && empty($children) )
		return false;

	$select = $options['default_opt'];

	$loop_children = function( &$array, $children, $depth = 0 ) use ( &$loop_children )
	{
		foreach ( $children as $child )
		{
			$array[$child->id] = str_repeat( '-', $depth ) . ' ' . $child->name;

			$_children = $child->getChildren();
			if ( !empty($_children) )
				$loop_children($array, $_children, $depth + 1);
		}
	};

	$loop_children($select, $children);

	return Form::select($name, $select, $value);
});