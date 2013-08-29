jQuery(document).on('click', 'a.js-delete', function(event)
{
	if ( !core.double_check() )
		return false;

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