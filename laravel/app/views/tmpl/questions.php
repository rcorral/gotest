<div class="question-wrapper list-group-item">
	<h4><span class="glyphicon glyphicon-play"></span>QUESTION_TYPE<button class="remove-question btn btn-danger btn-xs pull-right" type="button" title="Remove"><span class="glyphicon glyphicon-remove"></span></button></h4>
	<div>
		<input type="hidden" name="questions[QID][type_id]" value="TYPE_ID" />

		<div class="row js-qtitle-minutes">
			<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-<?php echo $is_interactive ? 10 : 12; ?>" data-classes="form-group col-xs-12 col-sm-12 col-md-12 col-lg-10">
				<?php echo Form::label('question-QID', 'Question'); ?><span class="required">*</span>
				<?php echo Form::text('questions[QID][question]', 'QUESTION_TITLE', array('id' => 'question-QID', 'class' => 'form-control', 'placeholder' => 'Question title')); ?>
			</div>
			<?php if ( $seconds ): ?>
			<div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-2<?php echo !$is_interactive ? ' hide' : ''; ?>">
				<?php echo Form::label('seconds-QID', 'Minutes'); ?>
				<?php echo Form::text('questions[QID][seconds]', 'QUESTION_SECONDS', array('id' => 'seconds-QID', 'class' => 'form-control', 'placeholder' => 'Minutes')); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php /* TODO: Make this work */ if ( false && $minimum_answers ): ?>
		<div class="form-group">
			<?php echo Form::label('min-answers-QID', 'Minimum Answers'); ?>
			<?php echo Form::text('questions[QID][min_answers]', 'QUESTION_MIN_ANSWERS', array('id' => 'min-answers-QID', 'class' => 'form-control', 'placeholder' => 'Min answers')); ?>
		</div>
		<?php endif; ?>
		<?php if ( $media ): ?>
		<div class="form-group media-group">
			<?php echo Form::label('media-QID', 'Media'); ?>
			<div class="input-group">
				<?php echo Form::text('questions[QID][media]', 'QUESTION_MEDIA', array('id' => 'media-QID', 'class' => 'form-control js-media-text', 'placeholder' => 'Link, image, YouTube')); ?>

				<div class="input-group-btn btn-group" data-toggle="buttons">
					<label class="btn btn-default" for="media-link-QID">
						<input type="radio" id="media-link-QID" name="questions[QID][media_type]" value="link" OPTION_VALID_LINK /><span title="Link" class="glyphicon glyphicon-link"></span>
					</label>
					<label class="btn btn-default" for="media-image-QID">
						<input type="radio" id="media-image-QID" name="questions[QID][media_type]" value="image" OPTION_VALID_IMAGE /><span title="Image" class="glyphicon glyphicon-picture"></span>
					</label>
					<label class="btn btn-default" for="media-yt-QID">
						<input type="radio" id="media-yt-QID" name="questions[QID][media_type]" value="youtube" OPTION_VALID_YOUTUBE /><span title="YouTube" class="glyphicon glyphicon-facetime-video"></span>
					</label>
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
						<th width="42px"<?php if ( !$valid ): ?>class="hidden"<?php endif; ?>>Valid</th>
						<th colspan="2">&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					{OPTION_START}
					<tr>
						<td>
							<?php echo Form::text('questions[QID][options][COUNTER]', 'OPTION_TITLE', array('class' => 'form-control input-increment clear-input', 'placeholder' => 'Answer')); ?>
						</td>
						<td align="center" class="middle<?php if ( !$valid ): ?> hidden<?php endif; ?>">
							<input type="<?php echo $answer_type; ?>" name="questions[QID][answers][]" value="COUNTER" class="val-auto-increment" OPTION_VALID title="Select if this is a correct answer." />
						</td>
						<td class="middle" align="center">
							<span class="add-new-answer glyphicon glyphicon-plus green pointer" title="Add"><span>Add</span></span>
						</td>
						<td class="middle" align="center">
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