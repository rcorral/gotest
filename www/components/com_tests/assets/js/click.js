var socket, xclick;

XClick = (function() {
	// Constructor
	function XClick() {
		this.debug = in_development;
		this.user = { channels: {} };
		this.test_id = jQuery('#test-id').val();
		this.unique_id = jQuery('#unique-id').val();
		this.test_initialized = false;
		this.test_started = false;
		this.question = {};
		this.api_key = api_key;
		setup();
	}

	/**
	 * Setup socket.io and it's event listeners
	 **/
	function setup() {
		socket = io.connect( io_server );

		socket.on('test_status', function( data ) {
			xclick._debug('in:test_status', data);
			xclick.update_test_status( data );
		});

		// Get current question function and listeners
		update_question = function update_question( data, type ) {
			xclick._debug('in:' + type, data);

			if ( xclick.timer ) {
				xclick.timer.stop();
			};
			jQuery('#counter').removeClass('text-error').removeClass('text-success');

			if ( !data || !data.question ) {
				if ( 'test' == data.type ) {
					xclick.update_test_status( data );
				};
				return;
			}

			question = eval( '(' + data.question + ')' );

			if ( !question.id ) {
				return;
			};

			// Store current question
			xclick.question = question;

			// If this is the first time we are loading a test question
			// then lets clean up the pre test stuff
			if ( false == xclick.test_started ) {
				xclick.test_started = true;
				xclick.remove_start_timer();

				jQuery('#pre-test-info').slideUp('slow', function(){
					jQuery('.pre-test-hide').slideDown('slow').removeClass('pre-test-hide');
					jQuery('.post-test-hide').slideUp('slow');
				});
			};

			// Clean up anything left over from the previous question
			jQuery('#btn-submit').removeClass('btn-success')
				.removeClass('btn-info').html('Submit');

			template = templates.parse( question.question_type, question );
			xclick.set_timer( Number( question.seconds )
				? ( question.seconds - data.offset ) : question.seconds, data.timer_action  );

			document.getElementById('form-data').innerHTML = template;
			if ( 0 == question.seconds || ( question.seconds - data.offset ) > 0 ) {
				jQuery('#btn-submit').slideDown();
			};
		}
		socket.on('current_question', function(data){
			update_question( data, 'current_question' );
		});
		socket.on('next_question', function(data){
			update_question( data, 'next_question' );
		});

		// Toggle timer
		socket.on('timer_toggle', function( data ) {
			xclick._debug('in:timer_toggle', data);

			if ( !xclick.timer ) {
				return;
			}

			if ( 'pause' == data.action ) {
				xclick.timer.pause();
				if ( xclick.seconds_left ) {
					jQuery('#counter').addClass('text-error');
				};
			} else if ( 'play' == data.action ) {
				xclick.timer.play();
				if ( xclick.seconds_left ) {
					jQuery('#counter').removeClass('text-error');
					jQuery('#counter').addClass('text-success');
					setTimeout("jQuery('#counter').removeClass('text-success');", 2000);
					jQuery('#btn-submit').slideDown();
				};
			}

			// Display the time
			xclick.display_time( core.seconds_to_readable_time( data.seconds_left ) );
			xclick.seconds_left = data.seconds_left;
		});

		// Complete
		socket.on('complete', function( data ) {
			xclick._debug('in:complete', data);

			jQuery('#test-active').hide();
			jQuery('#test-completed').slideDown();
		});

		return socket;
	};

	/**
	 * Initializes xclick, displays status type message to user as to what stage the test is on
	 **/
	XClick.prototype.init = function() {
		// Get the timer going
		xclick.start_seconds_left = 7;
		xclick.start_timer = jQuery.timer(function(){
			xclick.start_seconds_left--;

			// Show a little more info when there is 4 seconds left
			if ( 4 == xclick.start_seconds_left ) {
				jQuery('#pre-test-info p.pre-test-hide').slideDown( 'slow' );
			};
			jQuery('#pre-test-info h1').html( jQuery('#pre-test-info h1').html() + '.' );

			// If we reach the end of our timer, display an I don't know what i'm doing message
			if ( 0 == xclick.start_seconds_left ) {
				xclick.remove_start_timer();
				jQuery('#pre-test-info h1').slideUp('fast', function(){
					jQuery('#pre-test-info p.pre-test-hide').slideUp();
					jQuery(this).html( 'Test not ready.' )
						.slideDown( 'slow', function(){
							jQuery('#pre-test-info p.pre-test-hide').html( '(puzzledlook)' )
								.slideDown();
						}
					);
				});
			};
		});
		xclick.start_timer.set({ time : 1000, autostart : true });

		this.emit('current_question');
	}

	XClick.prototype.update_test_status = function( data ) {
		if ( this.test_started || this.test_initialized ) {
			return;
		}

		if ( !data.initialized ) {
			// Who knows what to do here...moar than likely the test doesn't fucking exist
			return;
		}

		this.test_initialized = true;
		xclick.remove_start_timer();
		var h1 = 'Waiting on presenter';
		var p = 'They\'re only are humans, give them a chance.';

		jQuery('#pre-test-info h1').slideUp('fast', function(){
			jQuery('#pre-test-info p.pre-test-hide').slideUp();
			jQuery(this).html( h1 ).slideDown( 'slow' );
			setTimeout(function(){
				jQuery('#pre-test-info p.pre-test-hide').html( p ).slideDown('slow');
				}, 3000);
		});
	};

	XClick.prototype.submit = function() {
		_data = jQuery('#student-form').serialize();
		data = jQuery.deparam( _data );

		// This means that the question hasn't been answered,
		// because test_id and unique_id are the only two parameters
		if ( 2 == Object.size( data ) ) {
			return false;
		}

		data.question_id = this.question.id;

		data.test_id = this.test_id;
		data.unique_id = this.unique_id;

		data.option = 'com_api';
		data.app = 'tests';
		data.resource = 'answer';
		data.key = this.api_key;

		jQuery('#btn-submit').removeClass('btn-success')
			.addClass('btn-info').html('Submitting...');

		core._ajax(
			data,
			function( data ) {
				jQuery('#btn-submit').removeClass('btn-info')
					.addClass('btn-success').html('Submitted');
			}, { type: 'POST' });

		return false;
	};

	XClick.prototype.emit = function( event, data ) {
		if ( !event ) {
			this._debug( 'out:error:Trying to emit but no event.' );
			return '';
		}

		if ( 'undefined' == typeof data ) {
			data = {};
		}

		// Add test_id
		data.test_id = this.test_id;

		// Add unique_id
		data.uid = this.unique_id;

		// Add api key
		data.key = api_key;

		this._debug( 'out:emit ' + event, data );

		socket.emit(event, data, function( type, data ){
			// This callback is mostly for errors only
			xclick._debug('in:fun:callback:', type, data);
		});
	};

	/**
	 * Method to create timer object if there are enough seconds left
	 **/
	XClick.prototype.set_timer = function( seconds, action ) {
		this.question_seconds = Number( seconds );
		this.seconds_left = this.question_seconds;

		if ( !seconds || 0 == seconds ) {
			jQuery('#counter').slideUp();
			jQuery('#counter span').html('');
			delete this.timer;
			return;
		} else if ( seconds < 0 ) {
			jQuery('#counter span.digit').html('0');
			jQuery('#counter span.units').html('s');
			jQuery('#counter').slideDown();
			delete this.timer;
			return;
		} else {
			jQuery('#counter').slideDown();
		}

		if ( 'pause' == action ) {
			autostart = false;
			jQuery('#counter').addClass('text-error');
		} else {
			autostart = true;
		}

		// Display the very original time
		this.display_time( core.seconds_to_readable_time( this.question_seconds,
			this.question_seconds % 60 ) );

		// Initialize timer
		this.timer = jQuery.timer(function(){
			xclick.seconds_left--;

			xclick.display_time( core.seconds_to_readable_time( xclick.seconds_left ) );
		});

		// Fire the timer up
		this.timer.set({ time : 1000, autostart : autostart });
	};

	/**
	 * Method that show/hides timer depending on the time to be displayed
	 **/
	XClick.prototype.display_time = function( display_time ) {
		// Here is where we take care of stopping the timer if we are out of time
		// We also hide submit button here
		if ( display_time.time < 0 ) {
			jQuery('#btn-submit').slideUp();
			xclick.timer.stop();
		} else {
			jQuery('#counter span.digit').html( display_time.time );
			jQuery('#counter span.units').html( display_time.units );
		}
	};

	// Removes start timer
	XClick.prototype.remove_start_timer = function() {
		if ( this.start_timer ) {
			this.start_timer.stop();
		};
		delete this.start_timer;
		delete this.start_seconds_left;

	};

	XClick.prototype._debug = function() {
		if ( this.debug ) {
			console.log( Array.prototype.slice.call(arguments) );
		};
	};

	return XClick;
})();

jQuery(document).ready(function(){
	if ( 'undefined' != typeof io && io ) {
		xclick = new XClick;
		xclick.init();
	} else {
		// Display connection error problem
		jQuery('#pre-test-info h1').html( 'Connection error' );
		jQuery('#pre-test-info p.pre-test-hide')
			.html( 'This is the worst error. Something is very very wrong.' ).slideDown();
	}
});
