<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if ( task == 'test_edit.cancel' || document.formvalidator.isValid(document.id('item-form')) ) {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}

	jQuery(document).ready(function(){
		jQuery('.add-question').on('click', function(){
			tests.inline_popup( '<p>Select type of question:</p><form class="question-selection"><p><ul><li><input type="radio" name="question_type" value="mcsa" /> <label>Multiple choice single answer</label></li><li><input type="radio" name="question_type" value="mcma" /> <label>Multiple choice multiple answer</label></li><li><input type="radio" name="question_type" value="fitb" /> <label>Fill in the blank</label></li><li><input type="radio" name="question_type" value="fitbma" /> <label>Fill in the blank multiple answer</label></li><li><input type="radio" name="question_type" value="essay" /> <label>Essay</label></li></ul><input type="submit" name="select" value="Select" /></form></p>' );
		});

		jQuery(document).on('submit', '.question-selection', function(){
			value = jQuery('form.question-selection input[name="question_type"]:checked').val();

			if ( !value ) {
				_alert( 'Please make a selection.' );
				return false;
			};

			return false;
		});
	});
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tests&view=test_edit&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form">
<div class="width-40">
	<fieldset class="adminform">
		<legend>Details</legend>
			<ul class="adminformlist">
				<li><?php echo $this->form->getLabel('title'); ?>
				<?php echo $this->form->getInput('title'); ?></li>

				<li><?php echo $this->form->getLabel('sub_title'); ?>
				<?php echo $this->form->getInput('sub_title'); ?></li>

				<li><?php echo $this->form->getLabel('published'); ?>
				<?php echo $this->form->getInput('published'); ?></li>
			</ul>
	</fieldset>

	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
<div class="clr"></div>

<h2>Questions</h2>
<?php if ( empty( $this->questions ) ): ?>
	<button class="add-question">Add new question</button>
<?php endif; ?>
<div id="questions-wrapper">
<?php if ( empty( $this->questions ) ): ?>
<?php foreach ( $this->questions as $key => $value ): ?>
	
<?php endforeach; ?>
<?php endif; ?>
</div>