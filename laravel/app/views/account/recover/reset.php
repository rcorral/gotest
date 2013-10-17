<?php
echo Form::open(array('url' => array('reset', $reset_code), 'method' => 'post', 'class' => 'recover-reset-form form-horizontal ajax-frm', 'autocomplete' => 'off', 'role' => 'form'));
echo '<div class="form-group">';
	echo Form::label('password', 'New Password', array('class' => 'col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label'));
	echo '<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">';
		echo Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter password'));
	echo '</div>';
echo '</div>';
echo '<div class="form-group">';
	echo Form::label('password2', 'Confirm new password', array('class' => 'col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label'));
	echo '<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">';
		echo Form::password('password2', array('class' => 'form-control', 'placeholder' => 'Confirm password'));
	echo '</div>';
echo '</div>';
echo Form::hidden('id', $user->id);
echo '<div class="pull-right">';
	echo Form::button('Reset', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'recover-reset-form'));
echo '</div>';
echo Form::close();