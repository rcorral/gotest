<?php
echo Form::open(array('url' => 'recover', 'method' => 'post', 'class' => 'recover-form ajax-frm form-horizontal', 'role' => 'form'));
echo '<div class="form-group">';
	echo Form::label('email', 'E-Mail Address', array('class' => 'col-xs-3 col-sm-3 col-md-3 col-lg-3 control-label'));
	echo '<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">';
		echo Form::text('email', '', array('class' => 'form-control', 'placeholder' => 'Email'));
	echo '</div>';
echo '</div>';
if ( !Request::ajax() )
{
	echo '<div class="pull-right">';
		echo Form::button('Recover', array('class' => 'btn btn-primary form-ajax-submit', 'data-form-ajax-submit' => 'recover-form', 'type' => 'submit'));
	echo '</div>';
}
echo Form::close();