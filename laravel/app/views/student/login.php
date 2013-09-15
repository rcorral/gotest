<div class="container-fluid">
	<div class="row-fluid">
	<div class="span12">
		<p></p>
		<div class="hero-unit">
			<h1>Welcome.</h1>
			<p></p>
			<p>
				<a href="<?php echo Request::create(Request::url(), 'GET', array('auth' => 1))->fullUrl(); ?>" class="btn btn-primary btn-large">Please login to continue</a>
			</p>
		</div>
	</div>
	</div>
</div>
