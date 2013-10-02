<?php

class SignupController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->_buffer = View::make('account.signup', array('student' => Input::get('student', false)));

		if ( Request::ajax() )
		{
			return Response::json(array('modal' => array(
				'header' => 'Sign up',
				'body' => (string) $this->_buffer,
				'footer' => (Input::get('no_login', 0) ? '' : '<a href="#" class="btn login-action">Log in</a>' . ' ')
					. Form::submit('Sign up', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'signup-form')),
				'options' => array('width' => '250px')
			)));
		}

		return $this->exec();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store( $group_name = 'teacher', $silent_login = true )
	{
		Helper::csrf_check();

		try
		{
			// Override group
			if ( ($student = Input::get('student')) && 'student' == $student )
			{
				$group_name = 'student';
			}

			static::register(array(), $group_name, $silent_login);

			if ( $silent_login )
			{
				return Response::json(array('redirect' => 'current.location'), 200);
			}

			return true;
		}
		catch ( Exception $e )
		{
			return Response::json(array('message' => $e->getMessage()), ($e->getCode() ? $e->getCode() : 400));
		}
	}

	static public function register( $credentials = array(), $group_name = 'teacher', $silent_login = true )
	{
		try
		{
			if ( empty($credentials) )
			{
				$credentials = Input::only('email', 'password');
			}

			$validator = Validator::make(
				$credentials,
				array(
					'email' => 'required|email|unique:users,email',
					'password' => 'required|min:' . ('teacher' == $group_name ? 8 : 4)
				)
			);

			if ( $validator->fails() )
			{
				$messages = $validator->messages();
				throw new Exception($messages->first(), 400);
			}

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

			if ( $silent_login )
			{
				$user = Helper::authenticate($credentials, true);
			}
		} catch (Cartalyst\Sentry\Users\LoginRequiredException $e) {
			throw new Exception('Login field is required.');
		} catch (Cartalyst\Sentry\Users\PasswordRequiredException $e) {
			throw new Exception('Password field is required.');
		} catch (Cartalyst\Sentry\Users\UserExistsException $e) {
			throw new Exception('User with this login already exists.');
		} catch (Cartalyst\Sentry\Groups\GroupNotFoundException $e) {
			throw new Exception('Group was not found.');
		}

		return $user;
	}
}