jQuery('.home-title').animate({top: 0}, 500, 'linear');
jQuery('.intro-form').animate({left: 0}, 450, 'linear', function()
	{
		jQuery(this).find('input[name="title"]').focus();
	}
);