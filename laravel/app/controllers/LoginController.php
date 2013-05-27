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
	public function store($group_name = 'Teacher')
	{
		Helper::csrf_check();
die();
		try {
			// Create the user
			$user = Sentry::getUserProvider()->create(Input::only('email', 'password'));

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