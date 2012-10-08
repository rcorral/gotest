<?php
defined('_JEXEC') or die;
?>
<style type="text/css">
#counter { margin: 0; font-size: 44px; cursor: pointer; }
.video-container { height: 0; overflow: hidden; padding-bottom: 56.25%; padding-top: 30px; position: relative; margin-bottom: 12px; }
.video-container iframe, .video-container object, .video-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
</style>
<div class="container-fluid">
	<div class="row-fluid">
	<div class="span12">
		<form id="presenter-form" onSubmit="return false;">
			<div class="row-fluid">
			<div class="page-header span12">
				<div class="row-fluid">
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
						<button type="button" name="next" onclick="xclick.submit(this.name);" id="btn-next" class="btn">Next <i class="icon-arrow-right"></i></button>
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
			<div class="row-fluid" id="form-data"></div>
			<div style="display:none;">
				<input type="hidden" name="test_id" value="<?php echo $this->test->id; ?>" id="test-id" />
				<input type="hidden" name="question_order" value="" id="question-order" />
			</div>
		</form>
	</div>
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