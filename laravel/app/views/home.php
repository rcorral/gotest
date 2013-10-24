<div class="row">
	<div class="col-sm-12 col-sm-12 col-md-12 col-lg-12">
		<h1 class="home-title">Create Unlimited Tests. For Free.</h1>
		<div class="col-lg-offset-3 col-sm-12 col-sm-12 col-md-12 col-lg-6">
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