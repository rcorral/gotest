<div class="row">
<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-lg-offset-3">
<?php
echo Form::model((array) $test, array('route' => array('tests.store'), 'method' => 'post', 'class' => 'create-form', 'role' => 'form')); ?>
	<div class="form-group">
		<?php echo Form::label('title', 'Title'); ?>
		<?php echo Form::text('title', $test->title, array('class' => 'form-control', 'placeholder' => 'Title')); ?>
	</div>

	<div class="form-group">
		<?php echo Form::label('sub_title', 'Sub title'); ?>
		<?php echo Form::text('sub_title', $test->sub_title, array('class' => 'form-control', 'placeholder' => 'Sub title')); ?>
	</div>

	<div class="form-group hidden">
		<!-- Future feature //-->
		<?php echo Form::label('published', 'Published'); ?>
		<?php echo Form::published('published', $test->published); ?>
	</div>

	<div class="form-group">
		<?php echo Form::label('catid', 'Subject'); ?>
		<?php echo Form::categories('catid', $test->catid); ?>
	</div>

		<?php /*
		<?php echo Form::label('anon', 'Anonymous submission'); ?>
		<?php echo Form::checkbox('anon', 1, ($test->anon == 1)); ?>*/ ?>

	<div class="panel panel-default">
		<div class="panel-heading"><h3>Questions<button class="add-question btn btn-info pull-right">Add new question</button></h3></div>

		<div id="questions-wrapper" class="form-group list-group js-questions-wrapper">
		<?php if ( !empty( $test->questions ) ): ?>
		<?php foreach ( $test->questions as $question ): ?>
			<?php
			$template = $templates->{$question->tqt_type};

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
			foreach ( $question->options as $option )
			{
				$options_html .= str_replace(
					array('COUNTER', 'OPTION_TITLE', 'OPTION_VALID'),
					array($counter, $option->title, ($option->valid ? 'checked="checked"' : '')),
					$option_template
				);
				$counter++;
			}
			$html = str_replace('OPTIONS_INSERT', $options_html, $html);

			echo $html;
			?>
		<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</div>
<?php
echo Form::hidden('id', $test->id);
echo '<div class="form-group pull-right">';
	echo '<a href="' . Url::route('tests.index') . '" class="btn btn-default js-dbl-chk">' . Lang::get('Cancel') . '</a> ';
	echo Form::button('Save', array('type' => 'submit', 'class' => 'btn btn-primary'));
echo '</div>';
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
</div>
</div>