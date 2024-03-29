<div class="row">
<div class="col-sm-offset-2 col-md-offset-3 col-lg-offset-3 col-xs-12 col-sm-8 col-md-6 col-lg-6">
<?php
echo Form::model((array) $test, array('route' => array('tests.store'), 'method' => 'post', 'class' => 'create-form', 'role' => 'form')); ?>
	<div class="form-group">
		<?php echo Form::label('title', 'Title'); ?><span class="required">*</span>
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
		<?php echo Form::label('catid', 'Subject'); ?><span class="required">*</span>
		<?php echo Form::categories('catid', $test->catid); ?>
	</div>

	<div class="form-group">
		<div class="clearfix">
			<?php echo Form::label('', 'Test type'); ?>
		</div>
		<div class="btn-group" data-toggle="buttons">
			<label class="tooltips btn btn-default<?php echo !$test->interactive ? ' active' : ''; ?>" data-title="Self Administered" data-content="This option allows students to take the test on their own. All you'll need to do is provide them with a link to the test.">
				<?php echo Form::radio('interactive', 0, !$test->interactive); ?> Self Administered
			</label>

			<label class="tooltips btn btn-default<?php echo $test->interactive ? ' active' : ''; ?>" data-title="Interactive" data-content="Use this option to test as you teach. Questions will be pushed out to student devices when you want, students will only be able to answer the current question.">
				<?php echo Form::radio('interactive', 1, $test->interactive); ?> Interactive
			</label>
		</div>
	</div>

	<div class="form-group js-not-interactive"<?php echo $test->interactive ? ' style="display:none;"' : ''; ?>>
		<?php echo Form::label('seconds', 'Test minutes'); ?>
		<?php echo Form::text('seconds', $test->seconds, array('class' => 'form-control', 'placeholder' => 'Minutes to complete test')); ?>
	</div>

		<?php /*
		<?php echo Form::label('anon', 'Anonymous submission'); ?>
		<?php echo Form::checkbox('anon', 1, ($test->anon == 1)); ?>*/ ?>

	<div class="panel panel-default">
		<div class="panel-heading"><h3>Questions<button class="add-question btn btn-info pull-right" type="button">Add new question</button></h3></div>

		<div id="questions-wrapper" class="form-group list-group js-questions-wrapper">
		<?php if ( !empty( $test->questions ) ): ?>
		<?php foreach ( $test->questions as $question ): ?>
			<?php
			$template = $templates->{$question->tqt_type};

			// Convert the seconds to minutes
			$question->seconds = str_replace('.0', '', number_format($question->seconds / 60, 1));

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
	echo Form::open(array('url' => '/subjects', 'method' => 'post', 'class' => 'create-subject-frm ajax-frm', 'role' => 'form'));
	echo '<div class="form-group">';
		echo Form::label('subject', 'Please enter a new subject name:');
		echo Form::text('subject', '', array('class' => 'form-control', 'placeholder' => 'New subject'));
	echo '</div>';
	if ( $cats = Form::categories('nested_catid', 0, array('default_opt' => array(0 => ''), 'return_on_empty' => true)) )
	{
		echo '<div class="form-group">';
			echo '<label>';
				echo Form::checkbox('nest', '1', false, array('class' => 'nest'));
				echo ' Nest label under:';
			echo '</label>';
			echo $cats;
		echo '</div>';
	}
	echo Form::close();
	?>
	</div>
</div>
</div>

<!-- Help Modal -->
<div class="modal fade" id="help_modal" tabindex="-1" role="dialog" aria-labelledby="help_label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 id="help_label" class="modal-title">Help</h4>
			</div>
			<div class="modal-body">
				<p class="lead">
					These are some question examples:
				</p>

				<h4>&dash; Multiple choice single answer</h4>
				<blockquote>

					<div class="form-group">
						<label>Question title</label>
						<div class="radio">
							<label>
								<input type="radio" name="radio" /> Option 1
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="radio" /> Option 2
							</label>
						</div>
						<div class="radio">
							<label>
								<input type="radio" name="radio" /> Option 3
							</label>
						</div>
						<span class="help-block">Students can only pick one of the options.</span>
					</div>
				</blockquote>

				<h4>&dash; Multiple choice multiple answer</h4>
				<blockquote>

					<div class="form-group">
						<label>Question title</label>
						<div class="checkbox">
							<label>
								<input type="checkbox" /> Option 1
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" /> Option 2
							</label>
						</div>
						<div class="checkbox">
							<label>
								<input type="checkbox" /> Option 3
							</label>
						</div>
						<span class="help-block">Students can pick multiple options.</span>
					</div>
				</blockquote>

				<h4>&dash; Fill in the blank</h4>
				<blockquote>

					<div class="form-group">
						<label>Question title</label>
						<input type="text" class="form-control" placeholder="Answer" />
						<span class="help-block">Students will be able to enter an answer in the text box.</span>
					</div>
				</blockquote>

				<h4>&dash; Essay</h4>
				<blockquote>

					<div class="form-group">
						<label>Question title</label>
						<textarea rows="4" placeholder="Answer...." class="form-control"></textarea>
						<span class="help-block">Students will be able to enter an essay into the text area.</span>
					</div>
				</blockquote>
			</div>
			<div class="modal-footer">
				<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
