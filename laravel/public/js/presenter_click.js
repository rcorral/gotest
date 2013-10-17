var socket, xclick;

XClick = (function()
{
	var has_json = JSON && JSON.parse;

	// Constructor
	function XClick()
	{
		this.debug = in_dev && typeof console != 'undefined';
		this.user = { channels: {} };
		this.test_id = jQuery('#test-id').val();
		this.unique_id = test_unique_id;
		this.test_started = false;
		this.api_key = api_key;
		setup();
	}

	/**
	 * Setup socket.io and it's event listeners
	 **/
	function setup()
	{
		socket = io.connect( io_server );
		socket.on('next_question', function( data )
		{
			xclick._debug('in:next_question', data);

			if ( !data || !data.question ) return;

			question = has_json ? JSON.parse(data.question) : eval('(' + data.question + ')');

			if ( !question.id ) return;

			xclick.current_question = question;
			template = templates.parse(question.question_type, question);
			xclick.set_timer(question.seconds);

			document.getElementById('question-order').value = Number( question.order );
			document.getElementById('form-data').innerHTML = template;

			if ( question.order > 0 )
			{
				jQuery('#btn-previous').removeClass('disabled').removeAttr('disabled');
			}
			else
			{
				jQuery('#btn-previous').addClass('disabled').attr('disabled', 'disabled');
			}

			if ( question.order == question.max_order )
			{
				jQuery('#btn-next').addClass('disabled').attr('disabled', 'disabled');
			}
			else
			{
				jQuery('#btn-next').removeClass('disabled').removeAttr('disabled');
			}
		});

		return socket;
	};

	XClick.prototype.init = function()
	{
		if ( !this.unique_id || !this.test_id )
		{
			jQuery('#non_existent_modal').modal('show');
			return;
		}

		this.emit('test_begin');
	}

	XClick.prototype.start_test = function()
	{
		jQuery('#pre-test-info').slideUp('slow', function()
		{
			// Display a 5 second countdown before starting test
			xclick.start_seconds_left = 6;
			var timer = jQuery.timer(function()
			{
				xclick.start_seconds_left--;

				jQuery('#start-timer span.digit').html(xclick.start_seconds_left);

				if ( 0 == xclick.start_seconds_left )
				{
					timer.stop();
					delete timer;
					delete xclick.start_seconds_left;
					jQuery('#start-timer').slideUp();

					// Get first question
					xclick.test_started = true;
					xclick.next_question();
					jQuery('#complete-test-btn').removeClass('disabled');
				};
			});
			timer.set({time: 1000, autostart: true});

			jQuery('.pre-test-hide').slideDown('slow').removeClass('pre-test-hide');
			jQuery('.post-test-hide').slideUp('slow');
		});

		return false;
	};

	XClick.prototype.submit = function( type )
	{
		if ( !this.unique_id || !this.test_started ) return;

		// Stop timer if it exists
		if ( this.timer ) this.timer.stop();

		_question_order = jQuery('#question-order').val();
		if ( 'next' == type && _question_order == xclick.current_question.max_order )
		{
			xclick.complete_prompt();
			return;
		}

		if ( 'next' == type )
		{
			question_order = Number( _question_order ) + 1
		}
		else
		{
			question_order = Number( _question_order ) - 1;
		}

		xclick.next_question( question_order );

		return false;
	};

	XClick.prototype.next_question = function( question_order )
	{
		msg = {};

		if ( question_order ) msg.question_id = question_order;

		this.emit('next_question', msg);
	};

	XClick.prototype.complete_prompt = function()
	{
		jQuery('#finish_modal').modal('show');
	};

	XClick.prototype.complete = function()
	{
		// Just in case
		delete this.timer;

		data = {
			test_id: this.test_id,
			unique_id: this.unique_id,
			key: this.api_key
		};

		core._ajax(
			data,
			function( data )
			{
				xclick.emit('complete');

				jQuery('#test-active').hide();
				jQuery('#finish_modal').modal('hide');
				jQuery('#test-completed').removeClass('hide').slideDown();
			}, {url: live_site + 'test/complete', type: 'POST'});
	};

	XClick.prototype.emit = function( event, data )
	{
		if ( !event )
		{
			this._debug( 'out:error Trying to emit but no event.' );
			return '';
		}

		if ( 'undefined' == typeof data ) data = {};

		// Add test_id
		data.test_id = this.test_id;

		// Add unique_id
		data.uid = this.unique_id;

		// Add api key
		data.key = this.api_key;

		// For now this is how we tell if you are a presenter
		data.isp = true;

		this._debug( 'out:emit ' + event, data );

		socket.emit( event, data, function( type, data )
		{
			// This callback is mostly for errors only
			xclick._debug('in:fun:callback:', type, data);
		});
	};

	XClick.prototype.set_timer = function( seconds )
	{
		this.question_seconds = Number( seconds );
		this.seconds_left = this.question_seconds;

		if ( !seconds || 0 == seconds )
		{
			jQuery('#counter').slideUp();
			jQuery('#counter span').html('');
			delete this.timer;
			return;
		}
		else
		{
			jQuery('#counter').slideDown();
		}

		// Reset color on counter
		jQuery('#counter').removeClass('text-error');

		// Display the very original time
		xclick.display_time( core.seconds_to_readable_time( this.seconds_left, this.seconds_left % 60 ) );

		// Get the timer going
		this.timer = jQuery.timer(function()
		{
			xclick.seconds_left--;

			xclick.display_time(core.seconds_to_readable_time(xclick.seconds_left));
			// Change question
			if ( 0 == xclick.seconds_left ) xclick.submit('next');
		});

		this.timer.set({time : 1000, autostart : true});
	};

	XClick.prototype.display_time = function( display_time )
	{
		jQuery('#counter span.digit').html( display_time.time );
		jQuery('#counter span.units').html( display_time.units );
	};

	XClick.prototype._debug = function()
	{
		if ( this.debug ) console.log(Array.prototype.slice.call(arguments));
	};

	return XClick;
})();

jQuery(document).ready(function()
{
	if ( 'undefined' != typeof io && io )
	{
		xclick = new XClick;
		xclick.init();
	}
	else
	{
		// Display connection error problem
		jQuery('#pre-test-info h1').html( 'Connection error' );
		jQuery('#pre-test-info p').html( 'This is the worst error. Something is very very wrong.' ).slideDown();
	}

	jQuery('#counter').on('click', function()
	{
		if ( !xclick.timer ) return;

		if ( xclick.timer.isActive )
		{
			data = {action: 'pause'};
		}
		else
		{
			data = {action: 'play'};
		}

		data.seconds_left = xclick.seconds_left;

		// Emit before the toggle in hopes that there is less of a time difference
		// between client and presenter
		xclick.emit('timer_toggle', data);

		xclick.timer.toggle();
	}).hover(
		function()
		{
			if ( xclick.timer && xclick.timer.isActive )
			{
				jQuery(this).addClass('text-warning').removeClass('text-success');
			}
			else if ( xclick.timer )
			{
				jQuery(this).addClass('text-success').removeClass('text-error');
			}
		}, function()
		{
			if ( xclick.timer && xclick.timer.isActive )
			{
				jQuery(this).removeClass('text-warning').removeClass('text-success');
			}
			else if ( xclick.timer )
			{
				jQuery(this).addClass('text-error')
					.removeClass('text-warning')
					.removeClass('text-success');
			}
		}
	);

	jQuery('#finish_modal')
		.on('show', function()
		{
			if ( xclick.timer && xclick.timer.isActive )
			{
				jQuery('#counter').trigger('click');
			}
		})
		.on('hide', function()
		{
			if ( xclick.timer && !xclick.timer.isActive )
			{
				jQuery('#counter').trigger('click');
			}
		})
		;

	jQuery('#finish_modal button.btn-primary').on( 'click', function()
	{
		xclick.complete();
	});
});
