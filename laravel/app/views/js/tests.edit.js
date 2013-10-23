jQuery('.js-questions-wrapper')
	.on('keyup', '.js-media-text', function()
	{
		var $this = jQuery(this),
			val = $this.val()
			qid = $this.prop('id').replace('media-', '');
			;

		if ( val.match(/^https?:\/\/(www\.)?youtube\.com/) || val.match(/^https?:\/\/youtu\.be/) )
		{
			jQuery('#media-yt-' + qid).prop({checked: 'checked'}).click();
		}
		else if ( val.match(/^https?:\/\/.*?\.(jpg|jpeg|png|gif)$/) )
		{
			jQuery('#media-image-' + qid).prop({checked: 'checked'}).click();
		}
		else if ( val.match(/^https?:\/\//) )
		{
			jQuery('#media-link-' + qid).prop({checked: 'checked'}).click();
		}
	})
	.on('click', '.question-wrapper h4', function()
	{
		var $this = jQuery(this);
		$this.find('span:first').toggleClass('collapsed');
		$this.next().slideToggle();
	})
	.sortable({handle: 'h4', forcePlaceholderSize: true})

	// Add new answer rows
	.on('click', '.add-new-answer', function()
	{
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
	})

	// Remove question
	.on('click', '.remove-question', function(){
		jQuery(this).parent().parent().slideUp('slow', function(){ jQuery(this).remove(); });

		return false;
	})

	// Remove answer row
	.on('click', '.remove-answer', function(){
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

jQuery('form.create-form')
	// Create a new subject listener on the dropdown
	.on('click', '#catid option[value="-1"]', function(e) {
		var $this = jQuery(this);
		$this.parent().val(0);

		if ( !is_loggedin ) return trigger_login_first();

		core.modal({
			header: 'New Subject',
			body: jQuery('#create-subject-frm-wrapper').html(),
			footer: '<button data-dismiss="modal" aria-hidden="true" class="btn btn-default">Close</button> <button class="btn btn-primary disabled form-ajax-submit" data-form-ajax-submit="create-subject-frm">Create</button>'
		});
	})

	// Prevent submission of the form if user is not logged in
	.on('submit', function()
	{
		if ( !is_loggedin ) return trigger_login_first();
	})


	.on('change', 'input[name="interactive"]', function()
	{
		// If it's interactive then show the minutes per question
		if ( 1 == jQuery(this).val() )
		{
			jQuery('.js-qtitle-minutes div:nth-child(1)').removeAttr('class').addClass(jQuery('.js-qtitle-minutes div:first').data('classes'));
			jQuery('.js-qtitle-minutes div:nth-child(2)').removeClass('hide');
			jQuery('.js-not-interactive').slideUp();
		}
		else
		{
			jQuery('.js-qtitle-minutes div:nth-child(1)').removeAttr('class').addClass('form-group col-xs-12 col-sm-12 col-md-12 col-lg-12');
			jQuery('.js-qtitle-minutes div:nth-child(2)').addClass('hide');
			jQuery('.js-not-interactive').slideDown();
		}
	})
	.find('.tooltips').popover({placement: 'auto bottom', trigger: 'hover focus', container: 'body'});

// Prompt the user to register or login before taking on this action
function trigger_login_first()
{
	jQuery('.register-action').prop({href: '/register?preaction=1'}).click();
	return false;
}
// Add new question popup form
jQuery('.add-question').on('click', function(){
	core.modal({
		header: 'Select type of question',
		body: '<form class="question-selection" role="form"><div class="checkbox"><label for="type-mcsa"><input type="radio" name="question_type" value="mcsa" id="type-mcsa" /> Multiple choice single answer</label></div><div class="checkbox"><label for="type-mcma"><input type="radio" name="question_type" value="mcma" id="type-mcma" /> Multiple choice multiple answer</label></div><div class="checkbox"><label for="type-fitb"><input type="radio" name="question_type" value="fitb" id="type-fitb" /> Fill in the blank</label></div><div class="checkbox"><label for="type-fitbma"><input type="radio" name="question_type" value="fitbma" id="type-fitbma" /> Fill in the blank multiple answer</label></div><div class="checkbox"><label for="type-essay"><input type="radio" name="question_type" value="essay" id="type-essay" /> Essay</label></div></form>',
		footer: '<button class="btn btn-primary form-submit" data-form-submit="question-selection">Select</button>'
	});
	return false;
});

jQuery(document)
	// Add a new question
	.on('submit', '.question-selection', function(){
		if ( !is_loggedin ) return trigger_login_first();

		var type = jQuery('form.question-selection input[name="question_type"]:checked').val();

		if ( !type )
		{
			_alert('Please make a selection.');
			return false;
		};

		// Ajax call to com_api to get code to add question
		core._ajax({interactive: jQuery('input[name="interactive"]:checked').val()}, function( data )
		{
			if ( data.success ) {
				jQuery('#questions-wrapper').append( data.html );
				core.modal_close();
				jQuery('.js-questions-wrapper').sortable('destroy').sortable({handle: 'h4', forcePlaceholderSize: true});
			};
		}, {url: core.site_url + 'questiontemplate/' + type});

		return false;
	})

	// Create new subject
	.on('keyup', '.create-subject-frm #subject', function(){
		var $btn = jQuery('#modal-container').find('button.btn-primary');

		if ( this.value && $btn.hasClass('disabled') ) $btn.removeClass('disabled');
		else if ( !this.value ) $btn.addClass('disabled');
	})
	// Create new subject
	.on('click', '.create-subject-frm input.nest', function(){
		var option = jQuery('form.create-subject-frm:visible select[name="nested_catid"] option:first');

		if ( jQuery(this).is(':checked') ) option.html('Please select a parent...');
		else option.html('');
	});

// Initialize media buttons
jQuery('.media-group input:checked').parent().addClass('active');