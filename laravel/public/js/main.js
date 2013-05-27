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
	jQuery('.intro').animate({
		left: 0,
		opacity: 1
		}, 400, 'linear', function() {
	});
	jQuery('.home-title').animate({
	top: 0
		}, 400, 'linear', function() {
	});
});