<?php
echo Form::open(array('url' => 'signup', 'method' => 'post'));
echo Form::label('email', 'E-Mail Address');
echo Form::text('email');
echo Form::label('password', 'Password');
echo Form::password('password');
if ( !Request::ajax() )
	echo Form::submit('Sign up');
echo Form::close();