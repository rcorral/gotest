<?php if ( $pre_action ): ?>
<p>Please register before continuing.</p>
<?php endif; ?>
<?php
echo Form::open(array('url' => 'register', 'method' => 'post', 'class' => 'register-form ajax-form form-horizontal', 'role' => 'form'));
echo '<div class="form-group">';
echo Form::label('email', 'E-Mail Address', array('class' => 'col-lg-3 control-label'));
	echo '<div class="col-lg-9">';
		echo Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'Email'));
	echo '</div>';
echo '</div>';
echo '<div class="form-group">';
	echo Form::label('password', 'Password', array('class' => 'col-lg-3 control-label'));
	echo '<div class="col-lg-9">';
		echo Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password'));
	echo '</div>';
echo '</div>';
if ( $student )
{
	echo Form::hidden('student', 'student');
}
if ( $pre_action )
{
	echo Form::hidden('preaction', 'preaction');
}
if ( !Request::ajax() )
	echo Form::button('Register', array('class' => 'form-ajax-submit', 'data-form-ajax-submit' => 'register-frm'));
echo Form::close();