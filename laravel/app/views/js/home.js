jQuery('.intro-form').animate({left: 0, opacity: 1}, 400, 'linear', function()
	{
		jQuery(this).find('input[name="title"]').focus();
	}
);
jQuery('.home-title').animate({top: 0}, 400, 'linear');