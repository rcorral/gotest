jQuery(function(){
	jQuery(document)
		.on('click', '.login-action', function(){
			core._ajax({}, function(data){
				core.parse_request(data);
			}, {
				url: '/login',
				cache: true
			});
		})
		.on('click', '.signup-action', function(){
			core._ajax({}, function(data){
				core.parse_request(data);
			}, {
				url: '/signup',
				cache: true
			});
		})
		.on('click', '.form-ajax-submit', function(){
			var form = jQuery('form.' + jQuery(this).data('form-ajax-submit'))
				, data = jQuery.deparam(form.serialize())
				;

			core._ajax(data, function(data){
				core.parse_request(data);
			}, {
				url: form[0]['action'],
				type: 'POST'
			});
			return false;
		})
	;

	jQuery('#modal-container')
		.on('keydown', function(e){
			if ( 13 == e.keyCode ) {
				// Find the form
				jQuery(this).find('.btn-primary').click();
			}
		})
	;

	jQuery('.intro').animate({
		left: 0,
		opacity: 1
		}, 400, 'linear', function() {
	});
	jQuery('.home-title').animate({
	top: 0
		}, 400, 'linear', function() {
	});
	jQuery('#start').focus();
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