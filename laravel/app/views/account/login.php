<?php
echo Form::open(array('url' => 'login', 'method' => 'post', 'class' => 'login-form', 'role' => 'form'));
echo '<div class="form-group">';
	echo Form::label('email', 'E-Mail Address');
	echo Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'Email'));
echo '</div>';
echo '<div class="form-group">';
	echo Form::label('password', 'Password');
	echo Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password'));
echo '</div>';
?>
<a href="<?php echo URL::to('recover'); ?>" class="js-ajax-link pull-right">Forgot your password?</a>
<?php
if ( !Request::ajax() )
{
	echo '<a href="' .Url::route('register.index', array('no_login' => '1', 'student' => (isset($student) && $student) ? 1 : 0)). '" class="btn btn-default register-action">Register</a> ';
	echo Form::button('Log in', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'login-form'));
}
echo Form::close();

if ( !Request::ajax() )
{
?>
<hr />
<a href="<?php echo Request::create(Request::url(), 'GET', array('auth' => 1))->fullUrl(); ?>" class="btn-login-google">Log in with Google</a>
<?php } ?>