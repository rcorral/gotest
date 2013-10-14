<div class="question-wrapper list-group-item">
	<h4>QUESTION_TYPE<button class="remove-question btn btn-danger btn-xs pull-right" type="button" title="Remove"><span class="glyphicon glyphicon-remove"></span></button></h4>
	<div>
		<input type="hidden" name="questions[QID][type_id]" value="TYPE_ID" />
		<div class="form-group">
			<?php echo Form::label('question-QID', 'Question'); ?>
			<?php echo Form::text('questions[QID][question]', 'QUESTION_TITLE', array('id' => 'question-QID', 'class' => 'form-control', 'placeholder' => 'Question title')); ?>
		</div>
		<?php if ( $seconds ): ?>
		<div class="form-group">
			<?php echo Form::label('seconds-QID', 'Seconds'); ?>
			<?php echo Form::text('questions[QID][seconds]', 'QUESTION_SECONDS', array('id' => 'seconds-QID', 'class' => 'form-control', 'placeholder' => 'Seconds')); ?>
		</div>
		<?php endif; ?>
		<?php if ( $minimum_answers ): ?>
		<div class="form-group">
			<?php echo Form::label('min-answers-QID', 'Minimum Answers'); ?>
			<?php echo Form::text('questions[QID][min_answers]', 'QUESTION_MIN_ANSWERS', array('id' => 'min-answers-QID', 'class' => 'form-control', 'placeholder' => 'Seconds')); ?>
		</div>
		<?php endif; ?>
		<?php if ( $media ): ?>
		<div class="form-group">
			<?php echo Form::label('media-QID', 'Media'); ?>
			<div class="input-group">
				<?php echo Form::text('questions[QID][media]', 'QUESTION_MEDIA', array('id' => 'media-QID', 'class' => 'form-control', 'placeholder' => 'Link, image or YouTube url')); ?>

				<div class="input-group-btn radio-buttons">
					<input type="radio" id="media-link-QID" name="questions[QID][media_type]" value="link" OPTION_VALID_LINK />
					<label class="btn btn-default" for="media-link-QID">Link</label>
					<input type="radio" id="media-image-QID" name="questions[QID][media_type]" value="image" OPTION_VALID_IMAGE />
					<label class="btn btn-default" for="media-image-QID">Image</label>
					<input type="radio" id="media-yt-QID" name="questions[QID][media_type]" value="youtube" OPTION_VALID_YOUTUBE />
					<label class="btn btn-default" for="media-yt-QID">YouTube</label>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if ( $answers ): ?>
		<div class="answers">
			<table a:count="COUNTER_START" class="table table-striped table-hover table-condensed">
				<thead>
					<tr>
						<th>Options</th>
						<th width="42px">Valid</th>
						<th colspan="2">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					{OPTION_START}
					<tr>
						<td>
							<?php echo Form::text('questions[QID][options][COUNTER]', 'OPTION_TITLE', array('class' => 'form-control input-increment clear-input', 'placeholder' => 'Answer')); ?>
						</td>
						<td align="center" class="middle">
							<input type="<?php echo $answer_type; ?>" name="questions[QID][answers][]" value="COUNTER" class="val-auto-increment" OPTION_VALID title="Select if this is a correct answer." />
						</td>
						<td class="middle">
							<span class="add-new-answer glyphicon glyphicon-plus green pointer" title="Add"><span>Add</span></span>
						</td>
						<td class="middle">
							<span class="remove-answer glyphicon glyphicon-minus red pointer" title="Remove"><span>Remove</span></span>
						</td>
					</tr>
					{OPTION_END}
				</tbody>
			</table>
		</div>
		<?php endif; ?>
	</div>
</div>