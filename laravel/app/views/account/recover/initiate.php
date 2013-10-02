<?php
echo Form::open(array('url' => 'recover', 'method' => 'post', 'class' => 'recover-form ajax-frm'));
echo Form::label('email', 'E-Mail Address');
echo Form::text('email');
echo Form::close();