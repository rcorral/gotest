<?php
echo Form::open(array('url' => array('reset', $reset_code), 'method' => 'post', 'class' => 'recover-reset-form ajax-frm', 'autocomplete' => 'off'));
echo Form::label('password', 'New Password');
echo Form::password('password');
echo Form::label('password2', 'Confirm new password');
echo Form::password('password2');
echo Form::hidden('id', $user->id);
echo Form::submit('Reset', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'recover-reset-form'));
echo Form::close();