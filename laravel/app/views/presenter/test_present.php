<?php
$unique_url = Url::route('take_test', array('id' => $test->id, 'unique' => substr($unique_id, 0, 6)));
?>
<div id="test-active" class="container">
	<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<form id="presenter-form" onSubmit="return false;">
			<div class="row">
			<div class="page-header col-xs-12 col-sm-12 col-md-12 col-lg-12 pre-test-hide">
				<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 clearfix">
					<h1><?php echo $test->title; ?>
					<?php if ( $test->sub_title ) { ?>
					<small><?php echo $test->sub_title; ?></small>
					<?php } ?>
					</h1>
				</div>
				<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 clearfix">
					<!-- Next/Prev buttons -->
					<div class="form-group well well-sm pull-left controls">
						<button type="button" name="previous" onclick="xclick.change_question(this.name);" id="btn-previous" class="btn btn-default disabled" disabled="disabled"><i class="glyphicon glyphicon-arrow-left"></i></button>
						<button type="button" name="next" onclick="xclick.change_question(this.name);" id="btn-next" class="btn btn-default disabled">Next <i class="glyphicon glyphicon-arrow-right"></i></button>
						<button type="button" id="complete-test-btn" onclick="xclick.complete_prompt();" class="btn btn-danger disabled"><i class="glyphicon glyphicon-remove"></i></button>
					</div>
				</div>
				<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
					<div class="well well-lg text-muted pull-left" id="counter" style="display:none;">
						<span class="digit"></span><span class="units"></span>
					</div>
				</div>
				</div>
			</div>
			</div>
			<div class="row pre-test-hide" id="start-timer">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="text-muted">
						<span class="digit">5</span>
					</div>
				</div>
			</div>
			<p class="post-test-hide"></p>
			<div class="row post-test-hide" id="pre-test-info">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="jumbotron">
				<div class="container">
					<h1><?php echo $test->interactive ? 'Let\'s get started!' : 'Start testing!'; ?></h1>
					<p></p>
					<p>Give this link to students:</p>
					<p><a href="<?php echo $unique_url; ?>" onclick="return false;"><?php echo $unique_url; ?></a></p>
					<?php if ( $test->interactive ): ?>
					<p>
						<button type="button" class="btn btn-primary btn-lg" onclick="xclick.start_test();">Start testing</button>
					</p>
					<?php else: ?>
					<p>
						When you're ready, you can deactivate the test from the <a href="<?php echo URL::route('sessions.index'); ?>" class="js-sessions-page">sessions</a> page.
					</p>
					<?php endif; ?>
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
	<p></p>
	<div class="jumbotron">
	<div class="container">
		<h1>Test completed</h1>
		<p></p>
		<p>All done!</p>
	</div>
	</div>
</div>

<!-- Finish Modal -->
<div class="modal fade" id="finish_modal" tabindex="-1" role="dialog" aria-labelledby="finish_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 id="finish_label" class="modal-title">Complete Test?</h4>
			</div>
			<div class="modal-body">
				<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">No yet</button>
				<button class="btn btn-primary">Submit</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Test non existent -->
<div class="modal fade" id="non_existent_modal" tabindex="-1" role="dialog" aria-labelledby="non_existent_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 id="non_existent_label" class="modal-title">Non existent test.</h4>
			</div>
			<div class="modal-body">
				<p>There is not a unique identifier for the session on this test.</p>
				<p>Please close this window and administer the test again.</p>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
