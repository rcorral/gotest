<?php

class ModelBase extends Eloquent
{
	/**
	 * undocumented class variable
	 *
	 * @var string
	 **/
	protected $objects = array();

	/**
	 * Load row from database
	 **/
	public function load( $id, $key = null )
	{
		if ( isset( $this->objects[$id] ) )
			return $this->objects[$id];

		$this->objects[$id] = (object) array_merge($this->_populate(), $this->get_object_from_db($id, coalesce($key, $this->primary_key)));

		return $this->objects[$id];
	}

	protected function get_object_from_db( $id, $key )
	{
		$obj = DB::table($this->table)
			->where($key, $id)
			->get()
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
}