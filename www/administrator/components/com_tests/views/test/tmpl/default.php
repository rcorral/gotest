<?php
defined('_JEXEC') or die;

$unique_url = JURI::root()
	. JRequest::getInt( 'test_id' ) . '/' . substr( JRequest::getVar( 'unique_id' ), 0, 6 );
?>
<style type="text/css">
#counter { margin: 0; font-size: 44px; cursor: pointer; }
.video-container { height: 0; overflow: hidden; padding-bottom: 56.25%; padding-top: 30px; position: relative; margin-bottom: 12px; }
.video-container iframe, .video-container object, .video-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
.pre-test-hide { display: none; }
#start-timer { font-size: 180px; text-align: center; }
</style>
<div id="test-active" class="container-fluid">
	<div class="row-fluid">
	<div class="span12">
		<form id="presenter-form" onSubmit="return false;">
			<div class="row-fluid">
			<div class="page-header span12">
				<div class="row-fluid pre-test-hide">
				<div class="span7 clearfix">
					<h1><?php echo $this->test->title; ?>
					<?php if ( $this->test->sub_title ) { ?>
					<small><?php echo $this->test->sub_title; ?></small>
					<?php } ?>
					</h1>
				</div>
				<div class="span3 clearfix">
					<!-- Next/Prev buttons -->
					<div class="control-group well pull-left" style="max-width: 126px; margin: 0 auto 10px;">
						<button type="button" name="previous" onclick="xclick.submit(this.name);" id="btn-previous" class="btn disabled" disabled="disabled"><i class="icon-arrow-left"></i></button>
						<button type="button" name="next" onclick="xclick.submit(this.name);" id="btn-next" class="btn disabled">Next <i class="icon-arrow-right"></i></button>
					</div>
				</div>
				<div class="span2">
					<div class="well well-large muted pull-left" id="counter" style="display:none;">
						<span class="digit"></span><span class="units"></span>
					</div>
				</div>
				</div>
			</div>
			</div>
			<div class="row-fluid pre-test-hide" id="start-timer">
				<div class="span12">
					<div class="muted">
						<br /><br /><br />
						<span class="digit">5</span>
					</div>
				</div>
			</div>
			<div class="row-fluid post-test-hide" id="pre-test-info">
				<div class="span12">
				<div class="container">
				<div class="hero-unit">
					<h1>Let's get started!</h1>
					<p></p>
					<p>Give this link to students:</p>
					<p><a href="<?php echo $unique_url; ?>" onclick="return false;"><?php echo $unique_url; ?></a></p>
					<p>
						<button type="button" class="btn btn-primary btn-large" onclick="xclick.start_test();">Start testing</button>
					</p>
				</div>
				</div>
				</div>
			</div>
			<div class="row-fluid" id="form-data"></div>
			<div style="display:none;">
				<input type="hidden" name="test_id" value="<?php echo $this->test->id; ?>" id="test-id" />
				<input type="hidden" name="question_order" value="" id="question-order" />
			</div>
		</form>
	</div>
	</div>
</div>
<div id="test-completed" class="container hide">
	<div class="hero-unit">
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
	<button class="btn" data-dismiss="modal" aria-hidden="true">No yet</button>
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
