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
				'footer' => '<a href="#" class="login-action">(or log in)</a>' . ' | ' . Form::submit('Sign up', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'signup-form')),
				'options' => array('width' => '250px')
			)));

		return $this->exec();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store($group_name = 'Teacher')
	{
		Helper::csrf_check();

		try {
			// Register the user and activate them
			$user = Sentry::register(Input::only('email', 'password'), true);

			// Assign the group to the user
			$user->addGroup(Helper::get_group($group_name));

			return Response::json(array('message' => 'success!'), 201);
		} catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
			$error = 'Login field is required.';
		} catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
			$error = 'Password field is required.';
		} catch (Cartalyst\Sentry\Users\UserExistsException $e) {
			$error = 'User with this login already exists.';
		} catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
			$error = 'Group was not found.';
		}

		return Response::json(array('message' => $error), 400);
	}
}