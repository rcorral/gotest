var $wrap = jQuery('#wrap')
	, $home_block = jQuery('#home-block')
	, $form = jQuery('.intro-form')
	, $test_title = $form.find('input[name="title"]')
	, $btn_submit = $form.find('button[type="submit"]')
	;

function sizing()
{
console.log(($wrap.outerHeight(true) - 300));
	$home_block.css({height: 0}).css({height: ($wrap.outerHeight(true) - 250)});

	if ( window.innerWidth <= 767 )
	{
		$test_title.removeClass('input-lg');
		$btn_submit.removeClass('btn-lg');
	}
	else
	{
		$test_title.addClass('input-lg');
		$btn_submit.addClass('btn-lg');
	}
};

// Invoke at the begining
sizing();
jQuery(window).on('resize', sizing);

jQuery('.home-title').animate({top: 0}, 500, 'linear');
jQuery('.intro-form').animate({left: 0}, 450, 'linear', function()
	{
		jQuery(this).find('input[name="title"]').focus();
	}
);