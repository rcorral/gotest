<div>
<?php
echo Form::model((array) $user, array('route' => array('account.store'), 'method' => 'post', 'class' => 'account-form ajax-frm')); ?>

	<?php echo Form::label('name', 'Name'); ?>
	<?php echo Form::text('name', $user->first_name . ' ' . $user->last_name); ?>

	<?php echo Form::label('email', 'Email'); ?>
	<?php echo Form::text('email', $user->email); ?>

	<?php echo Form::label('password', 'Password'); ?>
	<?php echo Form::password('password'); ?>

<?php
echo Form::submit('Save', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'account-form'));
echo '<a href="' . Url::route('tests.index') . '" class="btn js-dbl-chk">' . Lang::get('Cancel') . '</a>';
echo Form::close();
?>
</div>