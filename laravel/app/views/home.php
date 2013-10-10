<section class="home">
	<a id="home"></a>
	<h1 class="home-title museo">Create Unlimited Tests. For Free.</h1>
	<?php echo Form::open(array('route' => 'create', 'method' => 'get', 'class' => 'intro-form')); ?>
		<div class="button">
			<?php echo Form::text('title', '', array('placeholder' => 'Enter A Test Name')); ?>
		</div>
	<?php
	echo Form::button('Get Started!', array('class' => 'started'));
	echo Form::close();
	?>
</section>