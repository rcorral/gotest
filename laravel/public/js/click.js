var socket, xclick;

XClick = (function()
{
	var has_json = JSON && JSON.parse;

	// Constructor
	function XClick()
	{
		this.debug = in_dev && typeof console != 'undefined';
		this.user = {channels: {}};
		this.test_id = jQuery('#test-id').val();
		this.unique_id = jQuery('#unique-id').val();
		this.test_initialized = false;
		this.test_started = false;
		this.is_interactive = is_interactive;
		this.question = {};
		this.api_key = api_key;
		this.anon_id = typeof anon_id == 'undefined' ? '' : anon_id;
		setup();
	}

	/**
	 * Setup socket.io and it's event listeners
	 **/
	function setup()
	{
		socket = io.connect(io_server);

		socket.on('test_status', function( data )
		{
			xclick._debug('in:test_status', data);
			xclick.update_test_status( data );
		});

		// Get current question function and listeners
		update_question = function update_question( data, type )
		{
			xclick._debug('in:' + type, data);

			if ( xclick.timer )
			{
				// Stop if test is interactive as the next question may have a timer
				if ( xclick.is_interactive ) xclick.timer.stop();
				// Continue the global timer
				else xclick.timer.play();
			}

			jQuery('#counter').removeClass('text-danger').removeClass('text-success');

			if ( !data || !data.question )
			{
				if ( 'test' == data.type )
				{
					xclick.update_test_status(data);
				}

				return;
			}

			question = has_json ? JSON.parse(data.question) : eval('(' + data.question + ')');

			if ( !question.id ) return;

			// Store current question
			xclick.question = question;

			// If this is the first time we are loading a test question
			// then lets clean up the pre test stuff
			if ( false == xclick.test_started )
			{
				xclick.test_started = true;
				xclick.remove_start_timer();

				jQuery('#pre-test-info').slideUp('slow', function()
				{
					jQuery('.pre-test-hide').slideDown('slow').removeClass('pre-test-hide');
					jQuery('.post-test-hide').slideUp('slow');

					if ( !xclick.is_interactive )
					{
						jQuery('#complete-test-btn').removeClass('disabled');

						xclick.set_timer(Number(question.test_seconds) ? (question.test_seconds - data.offset) : question.test_seconds, data.timer_action);
					}
				});
			};

			// Clean up anything left over from the previous question
			jQuery('#btn-submit').removeClass('btn-success').removeClass('btn-info').html('Submit');

			template = templates.parse( question.question_type, question );

			document.getElementById('form-data').innerHTML = template;
			document.getElementById('question-order').value = Number(question.order);

			if ( xclick.is_interactive )
			{
				xclick.set_timer(Number(question.seconds) ? (question.seconds - data.offset) : question.seconds, data.timer_action);
			}
			else
			{
				xclick.current_question = question;

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
			}

			if ( 0 == question.seconds || (question.seconds - data.offset) > 0 )
			{
				jQuery('#btn-submit').slideDown();
			}
		};
		socket.on('current_question', function(data)
		{
			update_question( data, 'current_question' );
		});
		socket.on('next_question', function(data)
		{
			update_question( data, 'next_question' );
		});

		// Toggle timer
		socket.on('timer_toggle', function( data )
		{
			xclick._debug('in:timer_toggle', data);

			if ( !xclick.timer ) return;

			if ( 'pause' == data.action )
			{
				xclick.timer.pause();
				if ( xclick.seconds_left )
				{
					jQuery('#counter').addClass('text-danger');
				}
			} else if ( 'play' == data.action )
			{
				xclick.timer.play();
				if ( xclick.seconds_left )
				{
					jQuery('#counter').removeClass('text-danger');
					jQuery('#counter').addClass('text-success');
					setTimeout("jQuery('#counter').removeClass('text-success');", 2000);
					jQuery('#btn-submit').slideDown();
				}
			}

			// Display the time
			xclick.display_time( core.seconds_to_readable_time( data.seconds_left ) );
			xclick.seconds_left = data.seconds_left;
		});

		// Complete
		socket.on('complete', function( data )
		{
			xclick._debug('in:complete', data);

			xclick.complete();
		});

		return socket;
	};

	/**
	 * Initializes xclick, displays status type message to user as to what stage the test is on
	 **/
	XClick.prototype.init = function()
	{
		// Get the timer going
		xclick.start_seconds_left = 7;
		xclick.start_timer = jQuery.timer(function()
		{
			xclick.start_seconds_left--;

			// Show a little more info when there is 4 seconds left
			if ( 4 == xclick.start_seconds_left )
			{
				jQuery('#pre-test-info p.pre-test-hide').slideDown('slow');
			}

			jQuery('#pre-test-info h1').html(jQuery('#pre-test-info h1').html() + '.');

			// If we reach the end of our timer, display an I don't know what i'm doing message
			if ( 0 == xclick.start_seconds_left )
			{
				xclick.remove_start_timer();
				jQuery('#pre-test-info h1').slideUp('fast', function()
				{
					jQuery('#pre-test-info p.pre-test-hide').slideUp();
					jQuery(this).html('Test not ready.').slideDown('slow', function()
						{
							jQuery('#pre-test-info p.pre-test-hide').html('(puzzledlook)' ).slideDown();
						}
					);
				});
			};
		});

		xclick.start_timer.set({time: 1000, autostart: true});

		if ( this.is_interactive )
		{
			this.emit('current_question');
		}
		else
		{
			xclick.next_question();
		}
	}

	/**
	 * Goes to the next/prev question
	 */
	XClick.prototype.change_question = function( type )
	{
		if ( !this.unique_id || !this.test_started ) return;

		// Stop timer if it exists to compensate for latency
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

		msg.session = this.unique_id;

		this.emit('next_question', msg);
	};

	XClick.prototype.update_test_status = function( data )
	{
		if ( this.test_started || this.test_initialized || !this.is_interactive ) return;

		if ( !data.initialized )
		{
			// Who knows what to do here...moar than likely the test doesn't fucking exist
			return;
		}

		this.test_initialized = true;
		xclick.remove_start_timer();
		var h1 = 'Waiting on presenter';
		var p = 'They\'re only are humans, give them a chance.';

		jQuery('#pre-test-info h1').slideUp('fast', function()
		{
			jQuery('#pre-test-info p.pre-test-hide').slideUp();
			jQuery(this).html(h1).slideDown('slow');
			setTimeout(function()
			{
				jQuery('#pre-test-info p.pre-test-hide').html(p).slideDown('slow');
			}, 3000);
		});
	};

	XClick.prototype.complete_prompt = function()
	{
		jQuery('#finish_modal').modal('show');
	};

	// Complte for self admined tests
	XClick.prototype.complete = function()
	{
		// Just in case
		delete this.timer;

		jQuery('#test-active').hide();
		jQuery('#finish_modal').modal('hide');
		jQuery('#test-completed').removeClass('hide').slideDown();
	};

	XClick.prototype.submit = function()
	{
		_data = jQuery('#student-form').serialize();
		data = jQuery.deparam(_data);

		// This means that the question hasn't been answered, because test_id and unique_id are the only two parameters
		if ( 2 == Object.size(data) ) return false;

		data.question_id = this.question.id;

		data.test_id = this.test_id;
		data.unique_id = this.unique_id;

		// If test is anonymous, let's keep it that way
		if ( this.anon_id ) data.anon_id = this.anon_id;
		else data.key = this.api_key;

		jQuery('#btn-submit').removeClass('btn-success').addClass('btn-info').html('Submitting...');

		core._ajax(
			data,
			function( data )
			{
				jQuery('#btn-submit').removeClass('btn-info')
					.addClass('btn-success').html('Submitted');
			}, {url: core.site_url + 'test/answer', type: 'POST'});

		return false;
	};

	XClick.prototype.emit = function( event, data )
	{
		if ( !event )
		{
			this._debug('out:error:Trying to emit but no event.');
			return '';
		}

		if ( 'undefined' == typeof data )
		{
			data = {};
		}

		// Add test_id
		data.test_id = this.test_id;

		// Add unique_id
		data.uid = this.unique_id;

		// Add api key
		data.key = api_key;

		this._debug('out:emit ' + event, data);

		socket.emit(event, data, function( type, data )
		{
			// This callback is mostly for errors only
			xclick._debug('in:fun:callback:', type, data);
		});
	};

	/**
	 * Method to create timer object if there are enough seconds left
	 **/
	XClick.prototype.set_timer = function( seconds, action )
	{
		this.question_seconds = Number(seconds);
		this.seconds_left = this.question_seconds;

		if ( !seconds || 0 == seconds )
		{
			jQuery('#counter').slideUp();
			jQuery('#counter span').html('');
			delete this.timer;
			return;
		}
		else if ( seconds < 0 )
		{
			jQuery('#counter span.digit').html('0');
			jQuery('#counter span.units').html('s');
			jQuery('#counter').slideDown();
			delete this.timer;
			return;
		}
		else
		{
			jQuery('#counter').slideDown();
		}

		if ( 'pause' == action )
		{
			autostart = false;
			jQuery('#counter').addClass('text-danger');
		}
		else
		{
			autostart = true;
		}

		// Display the very original time
		this.display_time(core.seconds_to_readable_time(this.question_seconds, this.question_seconds % 60));

		// Initialize timer
		this.timer = jQuery.timer(function()
		{
			xclick.seconds_left--;

			xclick.display_time(core.seconds_to_readable_time(xclick.seconds_left));
		});

		// Fire the timer up
		this.timer.set({time: 1000, autostart: autostart});
	};

	/**
	 * Method that show/hides timer depending on the time to be displayed
	 **/
	XClick.prototype.display_time = function( display_time )
	{
		// Here is where we take care of stopping the timer if we are out of time
		// We also hide submit button here
		if ( display_time.time < 0 )
		{
			jQuery('#btn-submit').slideUp();
			xclick.timer.stop();

			// Finish the test for the user
			if ( !this.is_interactive ) xclick.complete();
		}
		else
		{
			jQuery('#counter span.digit').html(display_time.time);
			jQuery('#counter span.units').html(display_time.units);
		}
	};

	// Removes start timer
	XClick.prototype.remove_start_timer = function()
	{
		if ( this.start_timer )
		{
			this.start_timer.stop();
		}

		delete this.start_timer;
		delete this.start_seconds_left;

	};

	XClick.prototype.logout = function( el )
	{
		core.logout();

		// Redirect if test is not anon
		if ( !this.anon_id )
		{
			setTimeout(function(){window.location.href = test_uri;}, 1000);
		}
		else
		{
			jQuery(el).parent().slideUp();
		}
	};

	XClick.prototype._debug = function()
	{
		if ( this.debug )
		{
			console.log( Array.prototype.slice.call(arguments) );
		}
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
		jQuery('#pre-test-info h1').html('Connection error');
		jQuery('#pre-test-info p.pre-test-hide').html('This is the worst error. Something is very very wrong.').slideDown();
	}

	jQuery('#finish_modal button.btn-primary').on('click', function()
	{
		xclick.complete();
	});
});
