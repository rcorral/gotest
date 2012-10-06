<?php
defined('_JEXEC') or die;
?>
<div class="container-fluid">
	<div class="row-fluid">
	<div class="span12">
		<form id="presenter-form" onSubmit="return false;">
			<div class="row-fluid">
			<div class="page-header span12">
				<div class="row-fluid">
				<div class="span8">
					<h1><?php echo $this->test->title; ?>
					<?php if ( $this->test->sub_title ) { ?>
					<small><?php echo $this->test->sub_title; ?></small>
					<?php } ?>
					</h1>
				</div>
				<div class="span4">
					<!-- Next/Prev buttons -->
					<div class="control-group well pull-left" style="max-width: 126px; margin: 0 auto 10px;">
						<button type="button" name="previous" onclick="xclick.submit(this);" id="btn-previous" class="btn disabled" disabled="disabled"><i class="icon-arrow-left"></i></button>
						<button type="button" name="next" onclick="xclick.submit(this);" id="btn-next" class="btn">Next <i class="icon-arrow-right"></i></button>
					</div>
				</div>
				</div>
			</div>
			</div>
			<div class="row-fluid">
				<div id="form-data" class="span12"></div>
			</div>
			<div style="display:none;">
				<input type="hidden" name="test_id" value="<?php echo $this->test->id; ?>" id="test-id" />
				<input type="hidden" name="question_order" value="" id="question-order" />
			</div>
		</form>
	</div>
	</div>
</div>