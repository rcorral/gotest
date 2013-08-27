<?php

class ModelBase extends Eloquent
{
	/**
	 * Load row from database
	 **/
	public static function load_populate( $id, $key = null )
	{
		$instance = new static;

		if ( ($object = $instance->get_object_from_db($id, coalesce($key, $instance->primary_key))) === null )
			$object = array();
		elseif ( isset($object->{$instance->primary_key}) && $object->{$instance->primary_key} )
		{
			$instance->setAttribute($instance->primary_key, $object->{$instance->primary_key});
			$instance->exists = true;
		}

		$instance->unguard();
		$instance->fill(array_merge($instance->_populate(), (array) $object));
		$instance->reguard();

		return $instance;
	}

	protected function get_object_from_db( $id, $key )
	{
		$obj = DB::table($this->table)
			->where($key, $id)
			->first()
			;
		return $obj;
	}

	protected function _populate()
	{
		static $cache = null;

		if ( $cache )
			return $cache;

		$cols = $this->_get_table_columns( $this->table );
		$cache = array();

		foreach ( $cols as $name => $col )
			$cache[$name] = $col->Default ? $col->Default : ( false !== strpos($col->Type, 'int') ? 0 : $col->Default );

		if ( empty($this->fillable) )
		{
			$fillable = $cache;
			// No metadata
			unset($fillable[$this->primary_key]);
			if ( @is_null($fillable['created_at']) ) unset($fillable['created_at']);
			if ( isset($fillable['created_by']) ) unset($fillable['created_by']);
			if ( @is_null($fillable['updated_at']) ) unset($fillable['updated_at']);

			$fillable = array_keys($fillable);
			$this->fillable($fillable);
		}

		return $cache;
	}

	protected function _get_table_columns( $table )
	{
		$result = array();

		$table = Helper::prefix_table($table);

		// Set the query to get the table fields statement.
		$fields = DB::select('SHOW FULL COLUMNS FROM ' . $table);

		foreach ($fields as $field)
			$result[$field->Field] = $field;

		return $result;
	}

	/**
	 * Proxy to call pre-save checks
	 **/
	public function save( array $options = array() )
	{
		if ( !$this->check() )
			return false;

		return parent::save($options);
	}

	public function check()
	{
		return true;
	}
}