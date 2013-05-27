jQuery(function(){
	jQuery(document)
		.on('click', '.login-action', function(){
			alert('login!');
		})
		.on('click', '.signup-action', function(){
			core._ajax({}, function(data){
				jQuery('#modal-container').css({width: '250px'});
				core.modal(data.modal);
			}, {
				url: '/signup',
				cache: true
			});
		})
	;
});