<?php
defined('_JEXEC') or die;

$user = JFactory::getUser();
$uri = JURI::getInstance();
$uri->setQuery( array() );
?>
<div class="container-fluid">
	<div class="row-fluid">
	<div class="span12">
		<p></p>
		<div class="hero-unit">
			<h1>Failed</h1>
			<p>Authenticaiton failed:</p>
			<p><?php echo $user->get( 'auth_msg' ); ?></p>
			<p>Did ya hit cancel on the authentication page?</p>
			<p>
				<img src="<?php JURI::root(); ?>components/com_tests/assets/images/rufkm.png" width="300" />
			</p>
			<p>
				<a href="<?php echo $uri; ?>" class="btn btn-primary btn-large">Go back</a>
			</p>
		</div>
	</div>
	</div>
</div>
