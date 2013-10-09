<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo URL::route('home'); ?>"><?php echo Config::get('app.site_name'); ?></a>
	</div>

	<div class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav navbar-right">
			<?php if ( !Helper::is_logged_in() ): ?>
			<li class="active"><a href="#login" class="login-action">Log in</a></li>
			<li><a href="#register" class="register-action">Register</a></li>
			<?php else:
			$user = Helper::get_current_user();
			$has_sessions = Helper::has_sessions();
			$has_tests = $has_sessions || Helper::has_tests();
			?>
			<li><a href="<?php echo URL::route('create'); ?>" class="create-action">Create a Test</a></li>
			<?php if ( $has_tests ) : ?>
			<li><a href="<?php echo URL::route('tests.index'); ?>" class="tests-action">Tests</a></li>
			<?php endif; ?>
			<?php if ( $has_sessions ) : ?>
			<li><a href="<?php echo URL::route('sessions.index'); ?>" class="sessions-action">Sessions</a></li>
			<?php endif; ?>
			<li><a href="<?php echo URL::route('account.index'); ?>" class="account-action">Account</a></li>
			<li><a href="<?php echo URL::route('logout'); ?>" class="logout-action">Log out</a></li>
			<?php endif; ?>
		</ul>
	</div>
</div>