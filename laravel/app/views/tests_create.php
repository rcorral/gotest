<?php
// echo '<pre>'; print_r($test);die();
echo Form::model((array)$test,array('route' => array('tests.store'), 'method' => 'post', 'class' => 'create-form')); ?>
<div class="width-40">
	<fieldset class="adminform">
		<legend>Details</legend>
			<ul class="adminformlist">
				<li><?php echo Form::label('title', 'Title'); ?>
				<?php echo Form::text('title', $test->title); ?></li>

				<li><?php echo Form::label('sub_title', 'Sub title'); ?>
				<?php echo Form::text('sub_title', $test->sub_title); ?></li>

				<li><?php echo Form::label('published', 'Published'); ?>
				<?php echo Form::published('published', $test->published); ?></li>

				<li><?php echo Form::label('catid', 'Subject'); ?>
				<?php echo Form::categories('catid', $test->catid); ?></li>

				<li><?php echo Form::label('anon', 'Anonymous submission'); ?>
				<?php echo Form::checkbox('anon'); ?></li>
			</ul>
	</fieldset>
	</div>
	<div class="clr"></div>

	<h2>Questions</h2>

	<button class="add-question">Add new question</button>

	<div id="questions-wrapper">
	<?php if ( !empty( $test->questions ) ): ?>
	<?php foreach ( $test->questions as $question ): ?>
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
<?php
echo Form::hidden('id', $test->id);
echo Form::submit('Save');
echo Form::close();
?>
<div id="create-subject-frm-wrapper" style="display:none;">
<?php
echo Form::open(array('url' => '/subjects', 'method' => 'post', 'class' => 'create-subject-frm ajax-frm'));
echo Form::label('subject', 'Please enter a new subject name:');
echo Form::text('subject');
if ( $cats = Form::categories('nested_catid', 0, array('default_opt' => array(0 => ''), 'return_on_empty' => true)) )
{
	echo '<br />';
	echo Form::checkbox('nest', '1', false, array('class' => 'nest'));
	echo Form::label('nest', 'Nest label under:');
	echo $cats;
}
echo Form::close();
?>
</div>