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
			$array[$child->id] = str_repeat('&ndash;', $depth) . ' ' . $child->name;

			$_children = $child->getChildren();
			if ( !empty($_children) )
				$loop_children($array, $_children, $depth + 1);
		}
	};

	$loop_children($select, $children);

	return Form::select($name, $select, $value, array('class' => 'form-control'));
});

Form::macro('cb_id', function( $row_num, $value, $name = 'cid' )
{
	return '<input type="checkbox" id="cb' . $row_num . '" name="' . $name . '[]" value="' . $value
		. '" onclick="core.is_checked(this.checked);" title="' . Lang::get('JGRID_CHECKBOX_ROW_N', array($row_num + 1))
		. '" />';
});

/**
 * Displays active/deactivate image
 *
 * @param  $state bool Item is active or inactive
 */
Form::macro('item_state', function( $state, $id, $item_type )
{
	$current_state = $state ? (Lang::get('Active') . '&nbsp;&nbsp;') : Lang::get('Inactive');
	$state_to_be = $state ? Lang::get('Inactivate') : Lang::get('Active');
	$btn_class = $state ? 'success' : 'warning';
	$glyph = $state ? 'glyphicon-ok' : 'glyphicon-remove';
	return '<button title="' .$state_to_be. ' ' .Lang::get('Session'). '" class="js-change-state btn btn-' .$btn_class. '" data-id="'
		. $id. '" data-action="change_state" data-call="' .$item_type. '.' .strtolower($state_to_be)
		. '" href="javascript:void(0);"><span class="glyphicon ' .$glyph. '"></span> <span>' .$current_state. '</span></button>';
});

/**
 * Displays test interactive image
 *
 * @param  $state bool Item is interactive
 */
Form::macro('test_interactive', function( $state, $id )
{
	$state_to_be = $state ? Lang::get('Self-Administered') : Lang::get('Interactive');
	$btn_class = $state ? 'success' : 'default';
	$is = $state ? Lang::get('Yes') : Lang::get('No');
	return '<button title="' .$state_to_be. ' ' .Lang::get('Test'). '" class="js-change-state btn btn-' .$btn_class. '" data-id="'
		. $id. '" data-action="change_interactive" data-call="tests.' .strtolower($state_to_be)
		. '" href="javascript:void(0);">' .$is. '</button>';
});

Form::macro('csrf', function()
{
	return '<input type="hidden" name="_token" value="' .csrf_token(). '">';
});
