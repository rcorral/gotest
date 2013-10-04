<?php

class ErrorController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function show( $message, $code = 404 )
	{
		$this->_buffer = View::make('errors.404', array('error_message' => $message, 'error_code' => $code));

		return $this->exec(array('simple' => true));
	}
}