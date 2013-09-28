<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container-fluid">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?php echo URL::route('home'); ?>">My Exam</a>
			<div class="nav-collapse">
				<ul class="nav pull-right">
					<?php if ( !Helper::is_logged_in() ): ?>
					<li><a href="#login" class="login-action">Log in</a></li>
					<li><a href="#signup" class="signup-action">Sign up</a></li>
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
					<li><a href="#account" class="account-action"><?php echo ($user->first_name ? ucfirst(strtolower($user->first_name)) : $user->email); ?></a></li>
					<li><a href="<?php echo URL::route('logout'); ?>" class="logout-action">Log out</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>

