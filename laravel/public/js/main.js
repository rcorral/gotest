jQuery(function(){
	jQuery(document)
		.on('click', '.login-action', function()
		{
			var href = jQuery(this).attr('href');

			core._ajax({}, function(data){
				core.parse_request(data);
			}, {
				url: href.indexOf('http') >= 0 ? href : '/login',
				cache: true
			});

			return false;
		})
		.on('click', '.signup-action', function()
		{
			var href = jQuery(this).attr('href');

			core._ajax({}, function(data){
				core.parse_request(data);
			}, {
				url: href.indexOf('http') >= 0 ? href : '/signup',
				cache: true
			});

			return false;
		})
		.on('click', '.js-ajax-link', function()
		{
			var href = jQuery(this).attr('href');
			if ( href.indexOf('http') == -1 ) throw 'invalid.link';

			core._ajax({}, function(data){
				core.parse_request(data);
			}, {
				url: href,
				cache: false
			});

			return false;
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
				type: form.attr('method') || 'POST'
			});

			return false;
		})
		// This is just so that the form is not submited via the normal browser function
		.on('submit', '.ajax-frm', function(event)
		{
			return false;
		})
		.on('click', '.js-change-state', function()
		{
			var $this = jQuery(this);
			var data = {
				id: $this.data('id'),
				action: 'change_state',
				state: $this.data('action').split('.').slice(1).join('')
			};
			core._ajax(data, function(data)
			{
				$this.replaceWith(data.html);
			}, {
				url: $this.data('action').split('.').slice(0, 1),
				type: 'POST'
			});

			return false;
		})
		.on('click', '.js-delete', function(event)
		{
			if ( !core.double_check() ) return false;

			var $this = jQuery(this);

			core._ajax({}, function( data )
			{
				if ( data.success )
					$this.parent().parent().slideUp('slow', function(){
						$this.remove();
					});
			}, {
				url: $this.prop('href'),
				type: 'DELETE'
			});

			return false;
		});
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

	// TODO: This needs to go somewhere else
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