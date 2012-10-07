<?php
defined('_JEXEC') or die;
?>
<style type="text/css">
#counter { margin: 0; font-size: 44px; }
</style>
<div class="container-fluid">
	<div class="row-fluid">
	<div class="span12">
		<form id="student-form" onSubmit="return false;">
			<div class="row-fluid">
			<div class="page-header span12">
				<div class="row-fluid">
				<div class="span10 clearfix">
					<h1><?php echo $this->test->title; ?>
					<?php if ( $this->test->sub_title ) { ?>
					<small><?php echo $this->test->sub_title; ?></small>
					<?php } ?>
					</h1>
				</div>
				<div class="span2">
					<div class="well well-large muted pull-left" id="counter" style="display:none;">
						<span class="digit"></span><span class="units"></span>
					</div>
				</div>
				</div>
			</div>
			</div>
			<div class="row-fluid">
				<div id="form-data" class="span12"></div>
			</div>
			<div class="row-fluid">
			<div class="span4">
				<input type="hidden" name="test_id" value="<?php echo $this->test->id; ?>" id="test-id" />
				<button type="button" name="submit" onclick="xclick.submit(this);" id="btn-submit" class="btn btn-primary" style="display:none;">Submit</button>
			</div>
			</div>
		</form>
	</div>
	</div>
</div>
