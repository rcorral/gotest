<?php
defined('JPATH_PLATFORM') or die;
?>
<h1><?php echo $this->test->title; ?></h1>
<h2><?php echo $this->test->sub_title; ?></h2>

<form id="presenter-form" onSubmit="return false;">
	<div id="form-data"></div>
	<div id="form-static" style="display:none;">
		<input type="hidden" name="test_id" value="<?php echo $this->test->id; ?>" id="test-id" />
		<input type="hidden" name="question_order" value="" id="question-order" />
		<input type="submit" name="previous" value="previous" onclick="xclick.submit(this);" style="display:none;" id="btn-previous" />
		<input type="submit" name="next" value="Next" onclick="xclick.submit(this);" id="btn-next" />
	</div>
</form>

<?php
$this->document->addScriptDeclaration("
var live_iste = '../';
var xclick_interval = window.setInterval(function() {
	if (xclick) {
		window.clearInterval(xclick_interval);
		test_id = document.getElementById( 'test-id' ).value;
		xclick.next_question( test_id );
	}
}, 500);
");