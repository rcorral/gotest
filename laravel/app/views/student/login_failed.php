<div class="container">
	<div class="row">
	<div class="col-md-12">
		<p></p>
		<div class="jumbotron">
			<h1>Failed</h1>
			<p>Authenticaiton failed:</p>
			<p><?php echo $error; ?></p>
			<p>Did ya hit cancel on the authentication page?</p>
			<p>
				<img src="<?php echo Request::root(); ?>/images/rufkm.png" width="300" />
			</p>
			<p>
				<a href="<?php echo Request::url(); ?>" class="btn btn-primary btn-lg">Go back</a>
			</p>
		</div>
	</div>
	</div>
</div>
