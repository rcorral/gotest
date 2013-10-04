<section class="error error-<?php echo $error_code; ?>">
	<h2>Ooops! There was an error!</h2>
	<h3>It's embarrassing, we know...</h3>
	<p><?php echo $error_message; ?></p>
	<p><a href="<?php echo URL::to('home'); ?>">Go home.</a></p>
</section>