<div class="container-fluid">
	<div class="row-fluid">
	<div class="span12">
		<p></p>
		<div class="hero-unit">
			<h1>Failed</h1>
			<p>Authenticaiton failed:</p>
			<p><?php echo $error; ?></p>
			<p>Did ya hit cancel on the authentication page?</p>
			<p>
				<img src="<?php echo Request::root(); ?>/images/rufkm.png" width="300" />
			</p>
			<p>
				<a href="<?php echo Request::url(); ?>" class="btn btn-primary btn-large">Go back</a>
			</p>
		</div>
	</div>
	</div>
</div>
