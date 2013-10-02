<?php
echo Form::open(array('url' => 'signup', 'method' => 'post', 'class' => 'signup-form ajax-form'));
echo Form::label('email', 'E-Mail Address');
echo Form::text('email');
echo Form::label('password', 'Password');
echo Form::password('password');
if ( $student )
{
	echo Form::hidden('student', 'student');
}
if ( !Request::ajax() )
	echo Form::submit('Sign up', array('class' => 'form-ajax-submit', 'data-form-ajax-submit' => 'signup-frm'));
echo Form::close();