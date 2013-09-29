<?php

class LoginController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->_buffer = View::make('login');

		if ( Request::ajax() )
		{
			return Response::json(array('modal' => array(
				'header' => 'Log in',
				'body' => (string) $this->_buffer,
				'footer' => '<a href="#" class="btn signup-action">Register</a>' . ' ' . Form::submit('Log in', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'login-form')),
				'options' => array('width' => '250px')
			)));
		}

		return $this->exec();
	}

	/**
	 * This method handles logging in
	 */
	public function store()
	{
		Helper::csrf_check();

		try
		{
			$user = Helper::authenticate(Input::only('email', 'password'), true);

			if ( Request::ajax() )
			{
				return Response::json(array('redirect' => 'current.location'), 200);
			}

			return Redirect::route('home');
		}
		catch ( Exception $e )
		{
			throw new Exception($e->getMessage(), 400);
		}
	}
}