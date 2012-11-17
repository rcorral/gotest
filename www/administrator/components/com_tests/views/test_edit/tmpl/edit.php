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
			core.inline_popup( '<p>Select type of question:</p><form class="question-selection"><p><ul><li><input type="radio" name="question_type" value="mcsa" id="type-mcsa" /> <label for="type-mcsa">Multiple choice single answer</label></li><li><input type="radio" name="question_type" value="mcma" id="type-mcma" /> <label for="type-mcma">Multiple choice multiple answer</label></li><li><input type="radio" name="question_type" value="fitb" id="type-fitb" /> <label for="type-fitb">Fill in the blank</label></li><li><input type="radio" name="question_type" value="fitbma" id="type-fitbma" /> <label for="type-fitbma">Fill in the blank multiple answer</label></li><li><input type="radio" name="question_type" value="essay" id="type-essay" /> <label for="type-essay">Essay</label></li></ul><input type="submit" name="select" value="Select" /></form></p>' );
			return false;
		});

		// Add new answer rows
		jQuery('#questions-wrapper').on('click', '.add-new-answer', function(){
			// Increase auto increment of answers
			var cel = jQuery(this).parent().parent().parent().parent();
			var val_counter = Number( cel.attr('a:count') ) + 1;
			cel.attr('a:count', val_counter);

			// Get old value for replacement later
			var val_old = jQuery(this).parent().parent().find('input.val-auto-increment').val();

			// Find it a different way
			if ( !val_old ) {
				val_old = jQuery(this).parent().parent().find('input.input-increment:first')
					.attr('name').match(/.*\[.*\]\[(\d)\]\[.*\]/)[1];
			};

			// Clone answers row
			var nel = jQuery(this).parent().parent().clone();
			nel.hide();
			nel.find('input.val-auto-increment').val( val_counter );
			nel.find('input.input-increment').each(function(){
				var re = new RegExp('\\[' +val_old+ '\\]');
				jQuery(this).attr('name',
					jQuery(this).attr('name').replace(re, '[' +val_counter+ ']')
				);
			});
			nel.find('input.clear-input').val('');
			nel.insertAfter(jQuery(this).parent().parent()).css('display', '');

			return false;
		});

		// Remove question
		jQuery('#questions-wrapper').on('click', '.remove-question', function(){
			jQuery(this).parent().parent().slideUp('slow', function(){ jQuery(this).remove(); });

			return false;
		});

		// Remove answer row
		jQuery('#questions-wrapper').on('click', '.remove-answer', function(){
			el = jQuery(this).parent().parent();

			// Check to see if it is the last answer on the questions
			if ( el.siblings()[0] ) {
				el.slideUp('slow', function(){ jQuery(this).remove(); });
			} else {
				el.parent().parent().parent().parent()
					.slideUp('slow', function(){ jQuery(this).remove(); });
			}

			return false;
		});

		jQuery(document).on('submit', '.question-selection', function(){
			type = jQuery('form.question-selection input[name="question_type"]:checked').val();

			if ( !type ) {
				_alert( 'Please make a selection.' );
				return false;
			};

			// Ajax call to com_api to get code to add question
			core._ajax({
				app: 'tests',
				resource: 'questiontemplate',
				type: type,
				key: community_token
			}, function( data ) {
				if ( data.success ) {
					jQuery('#questions-wrapper').append( data.html );
					jQuery.colorbox.close();
				};
			}, {type: 'POST'});

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

				<li><?php echo $this->form->getLabel('catid'); ?>
				<?php echo $this->form->getInput('catid'); ?></li>

				<li><?php echo $this->form->getLabel('anon'); ?>
				<?php echo $this->form->getInput('anon'); ?></li>
			</ul>
	</fieldset>
	</div>
	<div class="clr"></div>

	<h2>Questions</h2>

	<button class="add-question">Add new question</button>

	<div id="questions-wrapper">
	<?php if ( !empty( $this->questions ) ): ?>
	<?php foreach ( $this->questions as $question ): ?>
		<?php
		$template = $this->templates->{$question->tqt_type};

		// Do all replacements that only need to happen once
		$html = str_replace(
			array( 'TYPE_ID', 'QUESTION_TYPE', 'QID', 'QUESTION_TITLE',
				'QUESTION_SECONDS', 'QUESTION_MEDIA', 'QUESTION_MIN_ANSWERS',
				'COUNTER_START' ),
			array( $template->id, $template->title, $question->id, $question->title,
				$question->seconds, $question->media, $question->min_answers,
				count( $question->options ) ),
			$template->html );

		// See which media box is selected if any
		foreach ( array( 'link', 'image', 'youtube' ) as $value ) {
			$replace = '';
			if ( $value == $question->media_type ) {
				$replace = 'checked="checked"';
			}

			$html = str_replace( 'OPTION_VALID_' . strtoupper( $value ), $replace, $html );
		}

		preg_match( '/\{OPTION_START\}(.*)\{OPTION_END\}/sm', $html, $matches );

		if ( !isset( $matches[1] ) || empty( $matches[1] ) ) {
			echo $html;
			continue;
		}

		$html = preg_replace( '/\{OPTION_START\}(.*)\{OPTION_END\}/sm', 'OPTIONS_INSERT', $html );

		$option_template = $matches[1];
		$options_html = '';
		$counter = 1;
		foreach ( $question->options as $option ) {
			$options_html .= str_replace(
				array( 'COUNTER', 'OPTION_TITLE',
					'OPTION_VALID' ),
				array( $counter, $option->title,
					( $option->valid ? 'checked="checked"' : '' ) ),
				$option_template );
			$counter++;
		}
		$html = str_replace( 'OPTIONS_INSERT', $options_html, $html );

		echo $html;
		?>
	<?php endforeach; ?>
	<?php endif; ?>
	</div>

	<?php echo $this->form->getInput('id'); ?>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
