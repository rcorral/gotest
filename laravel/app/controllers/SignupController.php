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
	public function store($group_name = 'Teacher', $silent_login = true)
	{
		Helper::csrf_check();

		try {
			$credentials = Input::only('email', 'password');
			$credentials['api_token'] = md5(uniqid(rand(), true));

			// Create a unique api_token
			while (
				$_r = DB::table('users')->where('api_token', $credentials['api_token'])->first()
				&& !empty($_r)
			) {
				$credentials['api_token'] = md5(uniqid(rand(), true));
			}

			// Register the user and activate them
			$user = Sentry::register($credentials, true);

			// Assign the group to the user
			$user->addGroup(Helper::get_group($group_name));

			if ( $silent_login ) {
				try {
					$user = Helper::authenticate($credentials, true);

					return Response::json(array('redirect' => URL::route('home')), 200);
				} catch (Exception $e) {
					return Response::json(array('message' => $e->getMessage()), 400);
				}
			} else {
				return true;
			}
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