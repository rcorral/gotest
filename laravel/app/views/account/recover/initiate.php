<?php
echo Form::open(array('url' => 'recover', 'method' => 'post', 'class' => 'recover-form ajax-frm', 'role' => 'form'));
echo '<div class="form-group">';
	echo Form::label('email', 'E-Mail Address');
	echo Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'Email'));
echo '</div>';
if ( !Request::ajax() )
{
	echo '<div class="pull-right">';
		echo Form::button('Recover', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'recover-form', 'type' => 'submit'));
	echo '</div>';
}
echo Form::close();