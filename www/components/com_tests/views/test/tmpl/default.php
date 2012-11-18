<?php
defined('_JEXEC') or die;
?>
<style type="text/css">
#counter { margin: 0; font-size: 44px; }
.video-container { height: 0; overflow: hidden; padding-bottom: 56.25%; padding-top: 30px; position: relative; margin-bottom: 12px; }
.video-container iframe, .video-container object, .video-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }
.pre-test-hide { display: none; }
</style>
<div id="test-active" class="container-fluid">
	<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid">
		<div class="page-header span12">
			<div class="row-fluid pre-test-hide">
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
		<div class="row-fluid post-test-hide" id="pre-test-info">
			<div class="span12">
			<div class="container">
			<div class="hero-unit">
				<h1>Loading</h1>
				<p></p>
				<p class="pre-test-hide">Trying to make sense of why it's not loading.</p>
			</div>
			</div>
			</div>
		</div>
		<form id="student-form" onSubmit="return false;">
			<div class="row-fluid" id="form-data"></div>
			<div class="row-fluid">
			<!-- To get past the first child -->
			<div></div>
			<div class="span12">
				<div class="control-group">
				<input type="hidden" name="test_id" value="<?php echo $this->test->id; ?>" id="test-id" />
				<input type="hidden" name="unique_id" value="<?php echo $this->test_session->unique_id; ?>" id="unique-id" />
				<button type="button" name="submit" onclick="xclick.submit(this);" id="btn-submit" class="btn btn-primary" style="display:none;">Submit</button>
				</div>
			</div>
			</div>
		</form>
	</div>
	</div>
</div>
<div id="test-completed" class="container hide">
	<div class="hero-unit">
		<h2>Test is done!</h2>
		<?php if ( @$this->test->self_admined ) : ?>
		<p>
			<button type="button" onclick="window.location='<?php echo $this->uri; ?>'" class="btn btn-primary">Start over</button>
		</p>
		<?php endif; ?>
	</div>
</div>
