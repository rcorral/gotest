<div class="row">
<div class="col-sm-offset-2 col-md-offset-3 col-lg-offset-4 col-xs-12 col-sm-8 col-md-6 col-lg-4">
<?php
echo Form::open(array('url' => array('reset', $reset_code), 'method' => 'post', 'class' => 'recover-reset-form ajax-frm', 'autocomplete' => 'off', 'role' => 'form'));
echo '<div class="form-group">';
	echo Form::label('password', 'New Password');
	echo Form::password('password', array('class' => 'form-control', 'placeholder' => 'Enter password'));
echo '</div>';
echo '<div class="form-group">';
	echo Form::label('password2', 'Confirm new password');
	echo Form::password('password2', array('class' => 'form-control', 'placeholder' => 'Confirm password'));
echo '</div>';
echo Form::hidden('id', $user->id);
echo '<div class="pull-right">';
	echo Form::button('Reset', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'recover-reset-form', 'type' => 'submit'));
echo '</div>';
echo Form::close();
?>
</div>
</div>