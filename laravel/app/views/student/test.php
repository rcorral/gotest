<div id="test-active" class="container">
	<div class="row">
	<div class="col-md-12">
		<div class="row">
		<div class="page-header col-md-12">
			<div class="row pre-test-hide">
			<div class="col-md-8 clearfix">
				<h1><?php echo $test->title; ?>
				<?php if ( $test->sub_title ) { ?>
				<small><?php echo $test->sub_title; ?></small>
				<?php } ?>
				</h1>
			</div>
			<div class="col-md-2 clearfix">
				<?php if ( $user->id ) : ?>
				<div class="control-group well pull-left" style="max-width: 74px; margin: 0 auto 10px;">
					<button type="button" onclick="xclick.logout( this );" class="btn btn-info">Logout</button>
				</div>
				<?php endif; ?>
			</div>
			<div class="col-md-2">
				<div class="well well-large muted pull-left" id="counter" style="display:none;">
					<span class="digit"></span><span class="units"></span>
				</div>
			</div>
			</div>
		</div>
		</div>
		<div class="row post-test-hide" id="pre-test-info">
			<div class="col-md-12">
			<div class="container">
			<div class="jumbotron">
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
			<div class="col-md-12">
				<div class="control-group">
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
	<div class="jumbotron">
		<h2>Test is done!</h2>
		<?php if ( @$test->self_admined ) : ?>
		<p>
			<button type="button" onclick="window.location='<?php echo $uri; ?>'" class="btn btn-primary">Start over</button>
		</p>
		<?php endif; ?>
	</div>
</div>
