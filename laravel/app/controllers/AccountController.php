<?php

class AccountController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->_buffer = View::make('account.edit', array('user' => Helper::get_current_user()));

		return $this->exec();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		Helper::csrf_check();

		try
		{
			$user = Helper::get_current_user();
			$data = Input::except('_token');

			$rules = array(
				'name' => 'required|min:2',
				'email' => 'required|email|unique:users,email,' . $user->id,
				'password' => 'required|min:' . ($user->hasAccess('teacher') ? 8 : 4)
			);

			// Update the user details
			$name = explode(' ', trim($data['name']));

			$user->last_name = count($name) > 1 ? array_pop($name) : '';
			$user->first_name = implode(' ', $name);
			$user->email = $data['email'];

			if ( $data['password'] ) $user->password = $data['password'];
			else unset($rules['password']);

			$validator = Validator::make($data, $rules);

			if ( $validator->fails() )
			{
				$messages = $validator->messages();
				throw new Exception($messages->first(), 400);
			}

			// Update the user
			if ( !$user->save() )
			{
			    throw new Exception('User was not found.', 400);
			}			

			if ( Request::ajax() )
			{
				return Helper::json_success_response(array('alert' => 'User updated.'));
			}

			return Redirect::route('account.index');
		}
		catch (Cartalyst\Sentry\Users\UserExistsException $e)
		{
		    throw new Exception('User with this login already exists.', 400);
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			throw new Exception('User was not found.', 400);
		}
		catch ( Exception $e )
		{
			if ( Request::ajax() )
			{
				return Helper::json_error_response(array('message' => $e->getMessage()), $e->getCode());
			}

			return Redirect::route('account.index')->withErrors(array($e->getMessage()));
		}
	}
}