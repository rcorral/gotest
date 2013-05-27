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
			var data = jQuery.deparam(jQuery('form.' + jQuery(this).data('form-ajax-submit')).serialize());

			core._ajax(data, function(data){
				core.parse_request(data);
			}, {
				url: '/signup',
				type: 'POST'
			});
			return false;
		})
	;
});