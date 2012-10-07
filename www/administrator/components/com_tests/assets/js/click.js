var socket, xclick;

XClick = (function() {
	function XClick() {
		this.debug = in_development;
		this.user = { channels: {} };
		setup();
	}

	function setup() {
		socket = io.connect( io_server );
		socket.on('next_question', function(data){
			if ( xclick.debug ) {
				console.log('Next question:', data);
			}

			if ( !data || !data.question ) {
				return;
			}

			question = eval( '(' + data.question + ')' );

			if ( !question.id ) {
				return;
			};

			xclick.current_question = question;
			template = templates.parse( question.question_type, question );
			xclick.set_timer( question.seconds );

			document.getElementById('question-order').value = Number( question.order );
			document.getElementById('form-data').innerHTML = template;

			if ( question.order > 1 ) {
				jQuery('#btn-previous').removeClass('disabled').removeAttr('disabled');
			} else {
				jQuery('#btn-previous').addClass('disabled').attr('disabled', 'disabled');
			}

			if ( question.order == question.max_order ) {
				jQuery('#btn-next').addClass('disabled').attr('disabled', 'disabled');
			} else {
				jQuery('#btn-next').removeClass('disabled').removeAttr('disabled');
			}
		});

		return socket;
	};

	XClick.prototype.submit = function( type ) {
		// Stop timer if it exists
		if ( this.timer ) {
			this.timer.stop();
		};

		_question_order = jQuery('#question-order').val();
		if ( 'next' == type && _question_order == xclick.current_question.max_order ) {
			xclick.complete();
			return;
		}

		if ( 'next' == type ) {
			question_order = Number( _question_order ) + 1
		} else {
			question_order = Number( _question_order ) - 1;
		}

		test_id = document.getElementById( 'test-id' ).value;
		xclick.next_question( test_id, question_order );

		return false;
	};

	XClick.prototype.next_question = function( test_id, question_order ) {
		msg = {};

		if ( test_id ) {
			msg.test_id = test_id;
		};

		if ( question_order ) {
			msg.question_id = question_order;
		};

		if ( !core._object_empty( msg ) ) {
			msg.key = api_key;
			this.emit('next_question', msg);
		}
	};

	XClick.prototype.complete = function() {
		jQuery('#finish_modal').modal('show');
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
		} else {
			jQuery('#counter').slideDown();
		}

		// Reset color on counter
		jQuery('#counter').removeClass('text-error');

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
				// Change question
				xclick.submit( 'next' );
			};
		});
		this.timer.set({ time : 1000, autostart : true });
	};

	return XClick;
})();

jQuery(document).ready(function(){
	if ( io ) {
		xclick = new XClick;
		xclick.next_question( jQuery('#test-id').val() );
	} else {
		// SOME_ERROR
	}

	jQuery('#counter').on('click', function(){
		xclick.timer.toggle();
	}).hover(
		function(){
			if ( xclick.timer.isActive ) {
				jQuery(this).addClass('text-warning');
				jQuery(this).removeClass('text-success');
			} else {
				jQuery(this).addClass('text-success');
				jQuery(this).removeClass('text-error');
			}
		}, function(){
			if ( xclick.timer.isActive ) {
				jQuery(this).removeClass('text-warning');
				jQuery(this).removeClass('text-success');
			} else {
				jQuery(this).addClass('text-error');
				jQuery(this).removeClass('text-warning');
				jQuery(this).removeClass('text-success');
			}
		}
	);
});
