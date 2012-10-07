var socket, xclick;

XClick = (function() {
	function XClick() {
		this.debug = in_development;
		this.user = { channels: {} };
		setup();
	}

	function setup() {
		socket = io.connect( io_server );

		// Get current question function and listeners
		update_question = function update_question( data ) {
			if ( xclick.debug ) {
				console.log('Current question:', data);
			}

			if ( xclick.timer ) {
				xclick.timer.stop();
			};
			jQuery('#counter').removeClass('text-error').removeClass('text-success');

			if ( !data || !data.question ) {
				return;
			}

			question = eval( '(' + data.question + ')' );

			if ( !question.id ) {
				return;
			};

			template = templates.parse( question.question_type, question );
			xclick.set_timer( question.seconds - data.offset, data.timer_action );

			document.getElementById('form-data').innerHTML = template;
			if ( question.seconds && ( question.seconds - data.offset ) > 0 ) {
				jQuery('#btn-submit').slideDown();
			};
		}
		socket.on('current_question', update_question);
		socket.on('next_question', update_question);

		// Toggle timer
		socket.on('timer_toggle', function( data ) {
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

		return socket;
	};

	XClick.prototype.submit = function() {
		// Store question for later submission

		return false;
	};

	XClick.prototype.current_question = function( test_id ) {
		msg = {};

		if ( test_id ) {
			msg.test_id = test_id;
		};

		if ( !core._object_empty( msg ) ) {
			this.emit('current_question', msg);
		}
	};

	XClick.prototype.emit = function(event, data) {
		if ( this.debug ) {
			console.log('Emit:', data);
		}

		socket.emit(event, data, function( type, data ){
			// This callback is mostly for errors only
			console.log('Callback:', type, data);
		});
	};

	XClick.prototype.set_timer = function( seconds, action ) {
		this.question_seconds = Number( seconds );
		this.seconds_left = this.question_seconds;

		if ( !seconds ) {
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

	return XClick;
})();


jQuery(document).ready(function(){
	if ( io ) {
		xclick = new XClick;
		xclick.current_question( jQuery('#test-id').val() );
	} else {
		// SOME_ERROR
	}
});
