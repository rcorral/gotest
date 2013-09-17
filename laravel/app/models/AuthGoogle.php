<?php
/**
 * Google Authentication
 */
class AuthGoogle
{
	/**
	 * OpenID Endpoint
	 */
	protected $_openid_endpoint = 'https://www.google.com/accounts/o8/id';

	protected function init()
	{
		$path_extra = app_path() . '/libs/php-openid/';
		$path = ini_get('include_path');
		ini_set('include_path', $path_extra . '/' . $path);

		/**
		 * Require the OpenID consumer code.
		 */
		require_once "Auth/OpenID/Consumer.php";

		/**
		 * Require the "file store" module, which we'll need to store
		 * OpenID information.
		 */
		require_once "Auth/OpenID/FileStore.php";

		/**
		 * Require the AX extension API.
		 */
		require_once "Auth/OpenID/AX.php";

		/**
		 * Require the PAPE extension module.
		 */
		require_once "Auth/OpenID/PAPE.php";

		// Generate trusted root url, which is just the root of our site
		$this->trusted_root = Request::root();

		// Get Return to URL
		$this->return_to = Request::create(Request::url(), 'GET', array('authenticate' => 1))->fullUrl();
	}

	/**
	 * Method starts the authentication with google
	 */
	function begin_authentication()
	{
		$this->init();

		$consumer = getConsumer();

		// Begin the OpenID authentication process.
		$auth_request = $consumer->begin($this->_openid_endpoint);

		// No auth request means we can't begin OpenID.
		if ( !$auth_request ) throw new Exception('PLG_AUTHENTICATION_CLICKER_GOOGLE_ERR_AUTH_SERVER');

		// Create attributes fetch request
		$ax = new Auth_OpenID_AX_FetchRequest;

		// Add attributes to fetch request
		// See https://developers.google.com/accounts/docs/OpenID#Parameters for parameters
		// Usage: make( $type_uri, $count = 1, $required = false, $alias = null )
		$ax->add( Auth_OpenID_AX_AttrInfo::make('http://axschema.org/contact/email', 1, 1, 'email'));
		$ax->add( Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/first', 1, 1, 'firstname'));
		$ax->add( Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/last', 1, 1, 'lastname'));

		// Add AX fetch request to authentication request
		$auth_request->addExtension($ax);

		// $policy_uris = null;
		// if ( isset( $_GET['policies'] ) ) {
			// $policy_uris = $_GET['policies'];
		// }

		// $pape_request = new Auth_OpenID_PAPE_Request( $policy_uris );
		// if ( $pape_request ) {
			// $auth_request->addExtension( $pape_request );
		// }

		// Redirect the user to the OpenID server for authentication.
		// Store the token for this authentication so we can verify the
		// response.

		// For OpenID 1, send a redirect.  For OpenID 2, use a Javascript
		// form to send a POST request to the server.
		if ( $auth_request->shouldSendRedirect() )
		{
			$redirect_url = $auth_request->redirectURL($this->trusted_root, $this->return_to);

			// If the redirect URL can't be built, display an error message.
			if ( Auth_OpenID::isFailure($redirect_url) )
				throw new Exception(sprintf('PLG_AUTHENTICATION_CLICKER_GOOGLE_ERR_REDIRECT_SERVER',
					$redirect_url->message));
			else
			{
				// Send redirect.
				Helper::immediate_redirect($redirect_url, 200);
			}
		}
		else
		{
			// Generate form markup and render it.
			$form_id = 'openid_message';
			$form_html = $auth_request->htmlMarkup($this->trusted_root, $this->return_to, false,
				array('id' => $form_id));

			// Display an error if the form markup couldn't be generated or render the HTML.
			if ( Auth_OpenID::isFailure( $form_html ) )
				throw new Exception(sprintf('PLG_AUTHENTICATION_CLICKER_GOOGLE_ERR_REDIRECT_SERVER',
					$redirect_url->message));
			else
			{
				echo $form_html;
				die();
			}
		}
	}

	/**
	 * Processed response from google OpenID authentication request
	 */
	function user_authenticate()
	{
		$this->init();

		$consumer = getConsumer();

		// Complete the authentication process using the server's response.
		$response = $consumer->complete($this->return_to);

		// Check the response status.
		if ( $response->status == Auth_OpenID_CANCEL )
		{
			// This means the authentication was cancelled.
			throw new Exception('PLG_AUTHENTICATION_CLICKER_GOOGLE_AUTH_ERR_CANCELLED');
		}
		else if ( $response->status == Auth_OpenID_FAILURE )
		{
			// Authentication failed; display the error message.
			// This means the authentication was cancelled.
			throw new Exception(sprintf('PLG_AUTHENTICATION_CLICKER_GOOGLE_AUTH_ERR_FAIL', $response->message));
		}
		else if ( $response->status == Auth_OpenID_SUCCESS )
		{
			// This means the authentication succeeded; extract the
			// identity URL and Simple Registration data (if it was returned).
			$openid = $response->getDisplayIdentifier();

			$ax = new Auth_OpenID_AX_FetchResponse();
			$obj = $ax->fromSuccessResponse($response);

			// $pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);

			// Get openid unique identifier
			$request = Request::create($openid);
			$openid_identifier = $request->input('id');

			// Log user into website
			// Save user if it doesn't already exist
			$this->save_or_update_user($obj, $openid_identifier);

			Helper::immediate_redirect(Request::url(), 200);
		}
	}

	/**
	 * Save user to DB
	 */
	protected function save_or_update_user( $user_data, $openid_identifier )
	{
		foreach ( $user_data->data as $key => $value )
		{
			switch ( $key )
			{
				case 'http://axschema.org/namePerson/first':
					$firstname = $value[0];
					break;

				case 'http://axschema.org/namePerson/last':
					$lastname = $value[0];
					break;

				case 'http://axschema.org/contact/email':
					$email = $value[0];
					break;

				default:
					break;
			}
		}

		$data = array(
			'first_name' => trim($firstname),
			'last_name' => trim($lastname),
			'email' => $email,
			'password' => Str::random(20),
			);

		// Let's see if user already exists
		$user_id = DB::table('user_profiles')
			->select('user_id')
			->where('profile_key', 'openid.id.google')
			->where('profile_value', $openid_identifier)
			->pluck('user_id')
			;

		// If user already exists, then lets get them logged in
		if ( $user_id )
		{
			$user = Helper::get_user_by_id($user_id);
			Sentry::loginAndRemember($user);
		}
		else
		{
			// Register user and authenticate them
			$user = SignupController::register($data, 'student', true);

			// Add openid_identifier if user is new
			DB::table('user_profiles')->insert(array(
				'user_id' => $user->id, 'profile_key' => 'openid.id.google', 'profile_value' => $openid_identifier
			));
		}

		return true;
	}

	// TODO: Make this work when user is deleted
	function clickerUserDelete( $user, $success, $msg )
	{
		$db = JFactory::getDBO();
		$user_id = JArrayHelper::getValue( $user, 'id', 0, 'int' );

		// Delete profile settings
		$db->setQuery( "DELETE FROM #__user_profiles
			WHERE user_id = {$user_id}
			AND profile_key = 'openid.id.google'" );
		if ( !$db->query() ) {
			throw new Exception( $db->getErrorMsg() );
		}
	}

	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param  Illuminate\Events\Dispatcher  $events
	 * @return array
	 */
	public function subscribe( $events )
	{
		$events->listen('auth.begin_authentication', 'AuthGoogle@begin_authentication');
		$events->listen('auth.user_authenticate', 'AuthGoogle@user_authenticate');
	}
}

function &getStore()
{
    /**
     * This is where the example will store its OpenID information.
     * You should change this path if you want the example store to be
     * created elsewhere.  After you're done playing with the example
     * script, you'll have to remove this directory manually.
     */
    $store_path = null;
    if (function_exists('sys_get_temp_dir')) {
        $store_path = sys_get_temp_dir();
    }
    else {
        if (strpos(PHP_OS, 'WIN') === 0) {
            $store_path = $_ENV['TMP'];
            if (!isset($store_path)) {
                $dir = 'C:\Windows\Temp';
            }
        }
        else {
            $store_path = @$_ENV['TMPDIR'];
            if (!isset($store_path)) {
                $store_path = '/tmp';
            }
        }
    }
    $store_path .= DIRECTORY_SEPARATOR . '_php_consumer_test';

    if (!file_exists($store_path) &&
        !mkdir($store_path)) {
        print "Could not create the FileStore directory '$store_path'. ".
            " Please check the effective permissions.";
        exit(0);
    }
	$r = new Auth_OpenID_FileStore($store_path);

    return $r;
}

function &getConsumer() {
    /**
     * Create a consumer object using the store object created
     * earlier.
     */
    $store = getStore();
	$r = new Auth_OpenID_Consumer($store);
    return $r;
}
