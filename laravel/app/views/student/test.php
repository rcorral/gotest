<div id="test-active" class="container student">
	<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="row">
		<div class="page-header col-xs-12 col-sm-12 col-md-12 col-lg-12 pre-test-hide">
			<div class="row">
			<div class="<?php echo $test->interactive ? 'col-xs-8 col-sm-8 col-md-8 col-lg-8' : 'col-xs-6 col-sm-6 col-md-6 col-lg-6'; ?> clearfix">
				<h1><?php echo $test->title; ?>
				<?php if ( $test->sub_title ) { ?>
				<small><?php echo $test->sub_title; ?></small>
				<?php } ?>
				</h1>
			</div>
			<?php if ( $test->interactive ) : // If test is managed then we don't want to show all the controls ?>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 clearfix">
				<?php if ( $user->id ) : ?>
				<div class="form-group well well-sm pull-left single-control">
					<button type="button" onclick="xclick.logout(this);" class="btn btn-info">Logout</button>
				</div>
				<?php endif; ?>
			</div>
			<?php else: // Show controls ?>
			<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 clearfix">
				<!-- Next/Prev buttons -->
				<div class="form-group well well-sm pull-left controls">
					<button type="button" name="previous" onclick="xclick.change_question(this.name);" id="btn-previous" class="btn btn-default disabled" disabled="disabled"><i class="glyphicon glyphicon-arrow-left"></i></button>
					<button type="button" name="next" onclick="xclick.change_question(this.name);" id="btn-next" class="btn btn-default disabled">Next <i class="glyphicon glyphicon-arrow-right"></i></button>
					<button type="button" id="complete-test-btn" onclick="xclick.complete_prompt();" class="btn btn-danger disabled"><i class="glyphicon glyphicon-remove"></i></button>
				</div>
			</div>
			<?php endif; ?>
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
				<input type="hidden" name="question_order" value="" id="question-order" />
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
	</div>
	</div>
</div>

<?php if ( !$test->interactive ) : ?>
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
<?php endif; ?>