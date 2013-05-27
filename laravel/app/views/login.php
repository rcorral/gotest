<?php
echo Form::open(array('url' => 'login', 'method' => 'post', 'class' => 'login-form'));
echo Form::label('email', 'E-Mail Address');
echo Form::text('email');
echo Form::label('password', 'Password');
echo Form::password('password');
if ( !Request::ajax() )
	echo Form::submit('Log up');
echo Form::close();