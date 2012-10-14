<?php
/**
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Clicker:Google Authentication plugin
 */
class plgAuthenticationClicker_Google extends JPlugin
{
	/**
	 * OpenID Endpoint
	 */
	protected $_openid_endpoint = 'https://www.google.com/accounts/o8/id';

	public function __construct($subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * This method should handle authentication requests created by this plugin
	 *
	 * @access	public
	 * @param	array	Array holding the user credentials
	 * @param	array	Array of extra options
	 * @param	object	Authentication response object
	 * @return	boolean
	 * @since 1.5
	 */
	function onUserAuthenticate( $credentials, $options, &$response )
	{
		$response->type = 'Clicker:Google';

		if ( JFactory::getApplication()->isAdmin() ) {
			return;
		}

		if ( 'com_users' == JRequest::getVar( 'option' ) ) {
			$response->status = JAuthentication::STATUS_FAILURE;
			$response->error_message =
				JText::_( 'PLG_AUTHENTICATION_CLICKER_GOOGLE_AUTH_ERR_BAD_ENTRY' );
			return false;
		}

		if ( empty( $credentials['password'] ) ) {
			$response->status = JAuthentication::STATUS_FAILURE;
			$response->error_message = JText::_( 'JGLOBAL_AUTH_EMPTY_PASS_NOT_ALLOWED' );
			return false;
		}

		$db = JFactory::getDBO();

		// Let's attempt to get the user
		$query = $db->getQuery( true )
			->select( 'u.`id`' )
			->from( '#__users AS u' )
			->leftjoin( '#__user_profiles AS up ON u.`id` = up.`user_id`' )
			->where( 'u.`email` = ' . $db->q( $credentials['username'] ) )
			->where( 'up.`profile_key` = \'openid.id\'' )
			->where( 'up.`profile_value` = ' . $db->q( $credentials['password'] ) )
			;
		$user_id = $db->setQuery( $query )->loadResult();

		if ( $user_id ) {
			$user = JUser::getInstance( $user_id );
			$response->fullname = $user->name;
			$response->email = $user->email;
			$response->language = $user->getParam('language');
			$response->status = JAuthentication::STATUS_SUCCESS;
			$response->error_message = '';
		} else {
			$response->status = JAuthentication::STATUS_FAILURE;
			$response->error_message = JText::_('JGLOBAL_AUTH_NO_USER');
		}
	}

	protected function init()
	{
		$path_extra = JPATH_ADMINISTRATOR . '/components/com_tests/libraries/php-openid/';
		$path = ini_get( 'include_path' );
		ini_set( 'include_path', $path_extra . PATH_SEPARATOR . $path );

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
		$this->trusted_root = JURI::root();

		// Get Return to URL
		$this->return_to = JURI::getInstance();
		$this->return_to->setQuery( array( 'authenticate' => 1 ) );
		$this->return_to = $this->return_to->toString();
	}

	/**
	 * Method starts the authentication with google
	 */
	function clickerBeginAuthentication()
	{
		$this->init();

		$app = JFactory::getApplication();
		$consumer = getConsumer();

		// Begin the OpenID authentication process.
		$auth_request = $consumer->begin( $this->_openid_endpoint );

		// No auth request means we can't begin OpenID.
		if ( !$auth_request ) {
			die( JText::_( 'PLG_AUTHENTICATION_CLICKER_GOOGLE_ERR_AUTH_SERVER' ) );
		}

		// Create attributes fetch request
		$ax = new Auth_OpenID_AX_FetchRequest;

		// Add attributes to fetch request
		// See https://developers.google.com/accounts/docs/OpenID#Parameters for parameters
		// Usage: make( $type_uri, $count = 1, $required = false, $alias = null )
		$ax->add( Auth_OpenID_AX_AttrInfo::make( 'http://axschema.org/contact/email', 1, 1,
				'email' ) );
		$ax->add( Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/first', 1, 1,
				'firstname' ) );
		$ax->add( Auth_OpenID_AX_AttrInfo::make('http://axschema.org/namePerson/last', 1, 1,
				'lastname' ) );

		// Add AX fetch request to authentication request
		$auth_request->addExtension( $ax );

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
		if ( $auth_request->shouldSendRedirect() ) {
			$redirect_url = $auth_request->redirectURL( $this->trusted_root, $this->return_to );

			// If the redirect URL can't be built, display an error message.
			if ( Auth_OpenID::isFailure( $redirect_url ) ) {
				die( JText::sprintf( 'PLG_AUTHENTICATION_CLICKER_GOOGLE_ERR_REDIRECT_SERVER',
					$redirect_url->message ) );
			} else {
				// Send redirect.
				$app->redirect( $redirect_url );
			}
		} else {
			// Generate form markup and render it.
			$form_id = 'openid_message';
			$form_html = $auth_request->htmlMarkup( $this->trusted_root, $this->return_to, false,
				array( 'id' => $form_id ) );

			// Display an error if the form markup couldn't be generated or render the HTML.
			if ( Auth_OpenID::isFailure( $form_html ) ) {
				die( JText::sprintf( 'PLG_AUTHENTICATION_CLICKER_GOOGLE_ERR_REDIRECT_SERVER',
					$redirect_url->message ) );
			} else {
				echo $form_html;
				$app->close();
			}
		}
	}

	/**
	 * Processed response from google OpenID authentication request
	 */
	function clickerAuthenticate()
	{
		$this->init();

		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$consumer = getConsumer();

		// Complete the authentication process using the server's response.
		$response = $consumer->complete( $this->return_to );

		// Assume it will fail? lulz, very negative.
		// It is easier in case we fail when creating the user
		$_REQUEST['option'] = 'com_tests';
		$_REQUEST['view'] = 'login';
		$_REQUEST['layout'] = 'default_failed';
		$_REQUEST['tmpl'] = 'component';

		// Check the response status.
		if ( $response->status == Auth_OpenID_CANCEL ) {
			// This means the authentication was cancelled.
			$user->set( 'auth_msg',
				JText::_( 'PLG_AUTHENTICATION_CLICKER_GOOGLE_AUTH_ERR_CANCELLED' ) );
		} else if ( $response->status == Auth_OpenID_FAILURE ) {
			// Authentication failed; display the error message.
			// This means the authentication was cancelled.
			$user->set( 'auth_msg', JText::_( 'PLG_AUTHENTICATION_CLICKER_GOOGLE_AUTH_ERR_FAIL',
				$response->message ) );
		} else if ( $response->status == Auth_OpenID_SUCCESS ) {
			// This means the authentication succeeded; extract the
			// identity URL and Simple Registration data (if it was returned).
			$openid = $response->getDisplayIdentifier();

			$ax = new Auth_OpenID_AX_FetchResponse();
			$obj = $ax->fromSuccessResponse( $response );

			// $pape_resp = Auth_OpenID_PAPE_Response::fromSuccessResponse($response);

			// Get openid unique identifier
			$openid_identifier_url = JURI::getInstance( $openid );
			$openid_identifier = $openid_identifier_url->getVar( 'id' );

			// Log user into website
			// Save user if it doesn't already exist
			$user_data = $this->save_or_update_user( $obj, $openid_identifier );

			if ( !$user_data ) {
				$user->set( 'auth_msg', $this->getError() );
				return;
			}

			$success = $app->login(
				array( 'username' => $user_data['email'], 'password' => $openid_identifier ),
				array( 'silent' => 1 ) );

			if ( $success ) {
				$redirect = JURI::getInstance();
				$redirect->setQuery( array() );
				$app->redirect( $redirect );
			} else {
				$user->set( 'auth_msg',
					JText::_( 'PLG_AUTHENTICATION_CLICKER_GOOGLE_AUTH_ERR_LOGGING_IN' ) );
			}
		}

		return;
	}

	/**
	 * Save user to DB
	 */
	protected function save_or_update_user( $user_data, $openid_identifier )
	{
		$db = JFactory::getDBO();

		foreach ( $user_data->data as $key => $value ) {
			switch ( $key ) {
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

		$user_params = JComponentHelper::getParams('com_users');

		$data = array(
			'name' => trim( $firstname ) . ' ' . trim( $lastname ),
			'username' => $email,
			'email' => $email,
			'password' => JUserHelper::genRandomPassword(20),
			'groups' => array( $user_params->get( 'new_usertype', 2 ) )
			);

		// Let's see if user already exists
		$query = $db->getQuery( true )
			->select( 'up.`user_id`' )
			->from( '#__user_profiles AS up' )
			->where( 'up.`profile_key` = \'openid.id\'' )
			->where( 'up.`profile_value` = ' . $db->q( $openid_identifier ) )
			;
		$user_id = $db->setQuery( $query )->loadResult();

		if ( $user_id ) {
			$data['id'] = $user_id;
		}

		// Get user object
		$user = new JUser;

		// Bind the data.
		if ( !$user->bind( $data ) ) {
			$this->setError( $user->getError() );
			return false;
		}

		// Store the data.
		if ( !$user->save() ) {
			$this->setError( $user->getError() );
			return false;
		}

		if ( !$user_id ) {
			// Add openid_identifier if user is new
			$query = $db->getQuery( true )
				->insert( '#__user_profiles' )
				->columns( '`user_id`, `profile_key`, `profile_value`' )
				->values( (int) $user->get('id') . ', \'openid.id\', ' . $db->q( $openid_identifier ) )
				;
			$db->setQuery( $query )->query();
		}

		return array( 'id' => $user->get('id'), 'email' => $email );
	}

	function clickerUserDelete( $user, $success, $msg )
	{
		$db = JFactory::getDBO();
		$user_id = JArrayHelper::getValue( $user, 'id', 0, 'int' );

		// Delete profile settings
		$db->setQuery( "DELETE FROM #__user_profiles
			WHERE user_id = {$user_id}
			AND profile_key = 'openid.id'" );
		if ( !$db->query() ) {
			throw new Exception( $db->getErrorMsg() );
		}
	}
}

function &getStore() {
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
