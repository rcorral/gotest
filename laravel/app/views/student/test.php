<div id="test-active" class="container student">
	<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="row">
		<div class="page-header col-xs-12 col-sm-12 col-md-12 col-lg-12 pre-test-hide">
			<div class="row">
			<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 clearfix">
				<h1><?php echo $test->title; ?>
				<?php if ( $test->sub_title ) { ?>
				<small><?php echo $test->sub_title; ?></small>
				<?php } ?>
				</h1>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 clearfix">
				<?php if ( $user->id ) : ?>
				<div class="form-group well well-sm pull-left controlls">
					<button type="button" onclick="xclick.logout(this);" class="btn btn-info">Logout</button>
				</div>
				<?php endif; ?>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<div class="well well-large text-muted pull-left" id="counter" style="display:none;">
					<span class="digit"></span><span class="units"></span>
				</div>
			</div>
			</div>
		</div>
		</div>
		<p class="post-test-hide"></p>
		<div class="row post-test-hide" id="pre-test-info">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="jumbotron">
				<div class="container">
					<h1>Loading</h1>
					<p></p>
					<p class="pre-test-hide">Trying to make sense of why it's not loading.</p>
				</div>
				</div>
			</div>
		</div>
		<form id="student-form" onSubmit="return false;">
			<div class="row" id="form-data"></div>
			<div class="row">
			<!-- To get past the first child -->
			<div></div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="form-group">
				<input type="hidden" name="test_id" value="<?php echo $test->id; ?>" id="test-id" />
				<input type="hidden" name="unique_id" value="<?php echo $session->unique_id; ?>" id="unique-id" />
				<button type="button" name="submit" onclick="xclick.submit(this);" id="btn-submit" class="btn btn-primary" style="display:none;">Submit</button>
				</div>
			</div>
			</div>
		</form>
	</div>
	</div>
</div>
<div id="test-completed" class="container hide">
	<p></p>
	<div class="jumbotron">
	<div class="container">
		<h1>Test is done!</h1>
		<?php if ( @$test->self_admined ) : ?>
		<p>
			<button type="button" onclick="window.location='<?php echo $uri; ?>'" class="btn btn-primary">Start over</button>
		</p>
		<?php endif; ?>
	</div>
	</div>
</div>
