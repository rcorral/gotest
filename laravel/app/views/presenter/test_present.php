<?php
$unique_url = Url::route('take_test', array('id' => $test->id, 'unique' => substr($unique_id, 0, 6)));
?>
<div id="test-active" class="container">
	<div class="row">
	<div class="col-md-12">
		<form id="presenter-form" onSubmit="return false;">
			<div class="row">
			<div class="page-header col-md-12">
				<div class="row pre-test-hide">
				<div class="col-md-6 clearfix">
					<h1><?php echo $test->title; ?>
					<?php if ( $test->sub_title ) { ?>
					<small><?php echo $test->sub_title; ?></small>
					<?php } ?>
					</h1>
				</div>
				<div class="col-md-4 clearfix">
					<!-- Next/Prev buttons -->
					<div class="control-group well pull-left" style="max-width: 174px; margin: 0 auto 10px;">
						<button type="button" name="previous" onclick="xclick.submit(this.name);" id="btn-previous" class="btn btn-default disabled" disabled="disabled"><i class="glyphicon glyphicon-arrow-left"></i></button>
						<button type="button" name="next" onclick="xclick.submit(this.name);" id="btn-next" class="btn btn-default disabled">Next <i class="glyphicon glyphicon-arrow-right"></i></button>
						<button type="button" id="complete-test-btn" onclick="xclick.complete_prompt();" class="btn btn-danger disabled"><i class="glyphicon glyphicon-remove"></i></button>
					</div>
				</div>
				<div class="col-md-2">
					<div class="well well-large muted pull-left" id="counter" style="display:none;">
						<span class="digit"></span><span class="units"></span>
					</div>
				</div>
				</div>
			</div>
			</div>
			<div class="row pre-test-hide" id="start-timer">
				<div class="col-md-12">
					<div class="muted">
						<br /><br /><br />
						<span class="digit">5</span>
					</div>
				</div>
			</div>
			<div class="row post-test-hide" id="pre-test-info">
				<div class="col-md-12">
				<div class="container">
				<div class="jumbotron">
					<h1>Let's get started!</h1>
					<p></p>
					<p>Give this link to students:</p>
					<p><a href="<?php echo $unique_url; ?>" onclick="return false;"><?php echo $unique_url; ?></a></p>
					<p>
						<button type="button" class="btn btn-primary btn-lg" onclick="xclick.start_test();">Start testing</button>
					</p>
				</div>
				</div>
				</div>
			</div>
			<div class="row" id="form-data"></div>
			<div style="display:none;">
				<input type="hidden" name="test_id" value="<?php echo $test->id; ?>" id="test-id" />
				<input type="hidden" name="question_order" value="" id="question-order" />
			</div>
		</form>
	</div>
	</div>
</div>
<div id="test-completed" class="container hide">
	<div class="jumbotron">
		<h1>Test completed</h1>
		<p></p>
		<p>All done!</p>
	</div>
</div>

<!-- Finish Modal -->
<div class="modal hide fade" id="finish_modal" tabindex="-1" role="dialog" aria-labelledby="finish_label" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="finish_label">Complete Test?</h3>
	</div>
	<div class="modal-body">
	<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">No yet</button>
	<button class="btn btn-primary">Submit</button>
	</div>
</div>

<!-- Test non existent -->
<div class="modal hide fade" id="non_existent_modal" tabindex="-1" role="dialog" aria-labelledby="non_existent_label" aria-hidden="true">
	<div class="modal-header">
		<h3 id="non_existent_label">Non existent test.</h3>
	</div>
	<div class="modal-body">
		There is not a unique identifier for the session on this test. Please close this window and administer the test again.
	</div>
</div>
