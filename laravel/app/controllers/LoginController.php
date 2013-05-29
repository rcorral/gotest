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
			return Response::json(array('modal' => array(
				'header' => 'Log in',
				'body' => (string) $this->_buffer,
				'footer' => '<a href="#" class="signup-action">(or sign up)</a>' . ' | ' . Form::submit('Log in', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'login-form')),
				'options' => array('width' => '250px')
			)));

		return $this->exec();
	}

	/**
	 * This method handles logging in
	 */
	public function store()
	{
		Helper::csrf_check();

		try {
			$user = Helper::authenticate(Input::only('email', 'password'), true);

			return Response::json(array('redirect' => URL::route('home')), 200);
		} catch (Exception $e) {
			return Response::json(array('message' => $e->getMessage()), 400);
		}
	}
}