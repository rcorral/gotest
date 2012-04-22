<?php
defined('JPATH_PLATFORM') or die;
?>
<h1><?php echo $this->test->title; ?></h1>
<h2><?php echo $this->test->sub_title; ?></h2>

<form id="presenter-form" onSubmit="return xclick.submit(this);">
	<div id="form-data"></div>
	<div id="form-static" style="display:none;">
		<input type="hidden" name="test_id" value="<?php echo $this->test->id; ?>" id="test_id" />
		<input type="hidden" name="next_question" value="" id="next_question" />
		<input type="submit" name="previous" value="previous" />
		<input type="submit" name="next" value="Next" />
	</div>
</form>

<script type="text/javascript">
var live_iste = '../';
var xclick_interval = window.setInterval(function() {
	if (xclick) {
		window.clearInterval(xclick_interval);
		test_id = document.getElementById( 'test_id' ).value;
		xclick.next_question( test_id );
	}
}, 500);
</script>