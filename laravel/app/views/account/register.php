<?php if ( $pre_action ): ?>
<p>Please register before continuing.</p>
<?php endif; ?>
<?php
echo Form::open(array('url' => 'register', 'method' => 'post', 'class' => 'register-form ajax-form'));
echo Form::label('email', 'E-Mail Address');
echo Form::text('email');
echo Form::label('password', 'Password');
echo Form::password('password');
if ( $student )
{
	echo Form::hidden('student', 'student');
}
if ( $pre_action )
{
	echo Form::hidden('preaction', 'preaction');
}
if ( !Request::ajax() )
	echo Form::submit('Register', array('class' => 'form-ajax-submit', 'data-form-ajax-submit' => 'register-frm'));
echo Form::close();