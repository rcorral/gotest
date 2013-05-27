<?php

class SignupController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->_buffer = View::make('signup');

		if ( Request::ajax() )
			return Response::json(array('modal' => array(
				'header' => 'Sign up',
				'body' => (string) $this->_buffer,
				'footer' => '<a href="#" class="login-action">(or log in)</a>' . ' | ' . Form::submit('Sign up', array('class' => 'btn btn-primary'))
			)));

		return $this->exec();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}
}