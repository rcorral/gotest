<div class="row">
	<div class="col-sm-12 col-sm-12 col-md-12 col-lg-12">
		<div id="home-block">
			<div id="home-centered">
				<div>
					<noscript>
						<div class="col-lg-offset-3 col-sm-12 col-sm-12 col-md-12 col-lg-6">
							You must <a href="http://www.tutorialspoint.com/javascript/javascript_enabling.htm" rel="nofollow">enable JavaScript</a> to use this site.
						</div>
					</noscript>

					<h1 class="home-title">Create Unlimited Tests. For Free.</h1>
					<div class="col-sm-offset-1 col-md-offset-2 col-lg-offset-2 col-xs-12 col-sm-10 col-md-8 col-lg-8">
						<?php echo Form::open(array('route' => 'create', 'method' => 'get', 'class' => 'intro-form', 'role' => 'form')); ?>
							<div class="input-group">
								<?php echo Form::text('title', '', array('placeholder' => 'Enter A Test Name', 'class' => 'form-control input-lg')); ?>
								<div class="input-group-btn">
									<?php echo Form::button('Get Started!', array('class' => 'btn btn-lg btn-primary started', 'type' => 'submit')); ?>
								</div>
							</div>
						<?php echo Form::close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>