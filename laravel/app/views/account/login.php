<?php
echo Form::open(array('url' => 'login', 'method' => 'post', 'class' => 'login-form'));
echo Form::label('email', 'E-Mail Address');
echo Form::text('email');
echo Form::label('password', 'Password');
echo Form::password('password');
?>
<a href="<?php echo URL::to('recover'); ?>" class="js-ajax-link">Forgot your password?</a>
<?php
if ( !Request::ajax() )
{
	echo '<a href="' .Url::route('signup.index', array('no_login' => '1', 'student' => (isset($student) && $student) ? 1 : 0)). '" class="btn signup-action">Register</a>';
	echo Form::submit('Log in', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'login-form'));
}
echo Form::close();

if ( !Request::ajax() )
{
?>
<hr />
<a href="<?php echo Request::create(Request::url(), 'GET', array('auth' => 1))->fullUrl(); ?>" class="btn-login-google">Log in with Google</a>
<?php } ?>