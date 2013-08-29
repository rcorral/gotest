jQuery(function(){
	jQuery(document)
		.on('click', '.login-action', function()
		{
			core._ajax({}, function(data){
				core.parse_request(data);
			}, {
				url: '/login',
				cache: true
			});
		})
		.on('click', '.signup-action', function()
		{
			core._ajax({}, function(data){
				core.parse_request(data);
			}, {
				url: '/signup',
				cache: true
			});
		})
		.on('click', '.js-dbl-chk', function(event, asked)
		{
			return core.double_check();
		})
		.on('click', '.form-ajax-submit', function(event)
		{
			var form = jQuery('form.' + jQuery(this).data('form-ajax-submit') + ':visible')
				, data = jQuery.deparam(form.serialize())
				;

			core._ajax(data, function( data )
			{
				core.parse_request(data);
			}, {
				url: form[0]['action'],
				type: 'POST'
			});

			return false;
		})
		.on('submit', '.ajax-frm', function(event)
		{
			return false;
		})
	;

	jQuery('#modal-container')
		.on('keydown', function(e){
			if ( 13 == e.keyCode )
				// Find the form
				jQuery(this).find('.btn-primary').click();
		})
	;

	// Set the right height of the main container
	jQuery('div.wrapper').css({top: jQuery('div.navbar').outerHeight(true)});

	// This needs to go somewhere else
	jQuery('.intro').animate({
		left: 0,
		opacity: 1
		}, 400, 'linear', function()
		{
	});
	jQuery('.home-title').animate({
	top: 0
		}, 400, 'linear', function()
		{
	});
	jQuery('#start').focus();
});