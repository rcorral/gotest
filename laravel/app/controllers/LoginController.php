<?php

class LoginController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->_buffer = View::make('account.login');

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

	/**
	 * Display form to recover password
	 *
	 * @return Response
	 */
	public function recover()
	{
		$this->_buffer = View::make('account.recover.initiate');

		if ( Request::ajax() )
		{
			return Response::json(array('modal' => array(
				'header' => 'Recover',
				'body' => (string) $this->_buffer,
				'footer' => Form::submit('Submit', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'recover-form')),
				'options' => array('width' => '250px')
			)));
		}

		return $this->exec();
	}

	/**
	 * This method handles logging in
	 */
	public function recover_send()
	{
		Helper::csrf_check();

		try
		{
			$credentials = Input::only('email');

			$validator = Validator::make(
				$credentials,
				array(
					'email' => 'required|email'
				)
			);

			if ( $validator->fails() )
			{
				$messages = $validator->messages();
				throw new Exception($messages->first(), 400);
			}

			// Find the user using the user email address
			$user = Sentry::findUserByLogin($credentials['email']);

			// Get the password reset code
			$reset_code = $user->getResetPasswordCode();

			Mail::send(array('emails.recover', 'emails.recover_text'), array('reset_url' => URL::to('reset', array('reset_code' => $reset_code))),
				function($message) use ($user)
				{
					$message->from('notify@buildyourexam.com', 'Build Your Exam');

					$message->to($user->email)->subject('Reset your password');
				}
			);

		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			// We should act like we found the user
			Log::warning('Invalid user account: base64:' . base64_encode($credentials['email']));
			sleep(rand(3, 5));
			// throw new Exception('User was not found.', 400);
		}
		catch ( Exception $e )
		{
			throw new Exception($e->getMessage(), 400);
		}

		$this->_buffer = View::make('account.recover.sent');

		if ( Request::ajax() )
		{
			return Response::json(array('modal' => array(
				'header' => Lang::get('It\'s on its way!'),
				'body' => (string) $this->_buffer,
				'footer' => Form::submit('Got it!', array('class' => 'btn btn-primary', 'data-dismiss' => 'modal', 'aria-hidden' => 'true')),
				'options' => array('width' => '300px')
			)));
		}

		return $this->exec();
	}

	public function reset_show( $reset_code )
	{
		try
		{
			$user = Sentry::findUserByResetPasswordCode($reset_code);

			$this->_buffer = View::make('account.recover.reset', array('reset_code' => $reset_code, 'user' => $user));

			return $this->exec();
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			throw new Exception('Invalid or expired code.', 400);
		}
	}

	public function reset_password( $reset_code )
	{
		try
		{
			$password = Input::get('password');

			if ( $password != Input::get('password2') )
			{
				throw new Exception('Passwords do not match.', 400);
			}

			// Find the user using the user id
			$user = Sentry::findUserById((int) Input::get('id'));

			$validator = Validator::make(
				array('password' => $password),
				array('password' => 'required|min:' . ($user->hasAccess('teacher') ? 8 : 4))
			);

			if ( $validator->fails() )
			{
				$messages = $validator->messages();
				throw new Exception($messages->first(), 400);
			}

			// Attempt to reset the user password
			if ( !$user->attemptResetPassword($reset_code, $password) )
			{
				// Password reset failed
				throw new Exception('Password reset failed', 400);
			}
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			throw new Exception('Password reset failed', 400);
		}

		$this->_buffer = View::make('account.recover.done')->nest('login_form', 'account.login');;

		if ( Request::ajax() )
		{
			return Response::json(array('modal' => array(
				'header' => 'Log in',
				'body' => (string) $this->_buffer,
				'footer' => Form::submit('Log in', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'login-form')),
				'options' => array('width' => '250px')
			)));
		}

		return $this->exec();
	}
}