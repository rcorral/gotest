<div class="row">
<div class="col-sm-offset-3 col-md-offset-3 col-lg-offset-4 col-xs-12 col-sm-6 col-md-6 col-lg-4">
<?php
echo Form::model((array) $user, array('route' => array('account.store'), 'method' => 'post', 'class' => 'account-form ajax-frm', 'role' => 'form'));
echo '<div class="form-group">';
	echo Form::label('name', 'Name');echo '<span class="required">*</span>';
	echo Form::text('name', $user->first_name . ' ' . $user->last_name, array('class' => 'form-control', 'placeholder' => 'Name'));
echo '</div>';
echo '<div class="form-group">';
	echo Form::label('email', 'Email');echo '<span class="required">*</span>';
	echo Form::text('email', $user->email, array('class' => 'form-control', 'placeholder' => 'Email'));
echo '</div>';
echo '<div class="form-group">';
	echo Form::label('password', 'Password');
	echo Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password'));
echo '</div>';

echo '<div class="form-group">';
	echo '<div class="pull-right">';
		echo '<a href="' . Url::route('tests.index') . '" class="btn btn-default js-dbl-chk">' . Lang::get('Cancel') . '</a> ';
		echo Form::button('Save', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'account-form', 'type' => 'submit'));
	echo '</div>';
echo '</div>';
echo Form::close();
?>
</div>
</div>