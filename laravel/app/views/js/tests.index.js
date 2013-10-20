jQuery('table')
	.on('click', '.js-start-session', function()
	{
		core.modal({
			header: 'Start new session',
			body: '<form class="js-session-begin" action="' +jQuery(this).prop('href')+ '" method="get" target="_blank" role="form"><div class="form-group tooltips" data-title="Session title" data-content="An optional title for your session ie. &quot;10am Class&quot;."><label for="session-title">Title</label> <small>(optional)</small><input type="text" name="title" id="session-title" class="form-control" /></div></form>',
			footer: '<button data-dismiss="modal" aria-hidden="true" class="btn btn-default">Close</button> <button class="btn btn-primary form-submit" data-form-submit="js-session-begin">Start!</button>'
		});

		return false;
	});