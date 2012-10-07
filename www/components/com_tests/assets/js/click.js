var socket, xclick;

XClick = (function() {
	function XClick() {
		this.debug = in_development;
		this.user = { channels: {} };
		setup();
	}

	function setup() {
		socket = io.connect( io_server );
		socket.on('current_question', update_question);
		socket.on('next_question', update_question);

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

	XClick.prototype.set_timer = function( seconds ) {
		this.question_seconds = Number( seconds );

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

		// Display the very original time
		display_time = core.seconds_to_readable_time( this.question_seconds,
			this.question_seconds % 60 );
		jQuery('#counter span.digit').html( display_time.time );
		jQuery('#counter span.units').html( display_time.units );

		// Get the timer going
		this.timer = jQuery.timer(function(){
			xclick.seconds_left = --xclick.question_seconds;

			display_time = core.seconds_to_readable_time( xclick.seconds_left );
			jQuery('#counter span.digit').html( display_time.time );
			jQuery('#counter span.units').html( display_time.units );
			if ( 0 == xclick.seconds_left ) {
				// Hide question
				jQuery('#btn-submit').slideUp();
				xclick.timer.stop();
			};
		});
		this.timer.set({ time : 1000, autostart : true });
	};

	return XClick;
})();

function update_question( data ) {
	if ( xclick.debug ) {
		console.log('Current question:', data);
	}

	if ( xclick.timer ) {
		xclick.timer.stop();
	};

	if ( !data || !data.question ) {
		return;
	}

	question = eval( '(' + data.question + ')' );

	if ( !question.id ) {
		return;
	};

	template = templates.parse( question.question_type, question );
	xclick.set_timer( question.seconds - data.offset );

	document.getElementById('form-data').innerHTML = template;
	if ( question.seconds && ( question.seconds - data.offset ) > 0 ) {
		jQuery('#btn-submit').slideDown();
	};
}

jQuery(document).ready(function(){
	if ( io ) {
		xclick = new XClick;
		xclick.current_question( jQuery('#test-id').val() );
	} else {
		// SOME_ERROR
	}
});
