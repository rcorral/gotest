var socket, xclick;

XClick = (function() {
	function XClick() {
		this.debug = true;
		this.user = { channels: {} };
		setup();
	}

	function setup() {
		socket = io.connect('http://' + io_server + ':8000/');
		socket.on('next_question', function(data){
			if ( xclick.debug ) {
				console.log('Next question:', data);
			}

			if ( !data || !data.question ) {
				return;
			}

			question = eval( '(' + data.question + ')' );
			template = templates.parse( question.question_type, question );

			document.getElementById('next_question').value = Number( question.order ) + 1;
			document.getElementById('form-data').innerHTML = template;
			document.getElementById('form-static').style.display = '';
		});

		return socket;
	};

	XClick.prototype.submit = function(form) {
		test_id = document.getElementById( 'test_id' ).value;
		question_id = document.getElementById( 'next_question' ).value;
		xclick.next_question( test_id, question_id );

		return false;
	};

	XClick.prototype.next_question = function( test_id, question_id ) {
		msg = {};

		if ( test_id ) {
			msg.test_id = test_id;
		};

		if ( question_id ) {
			msg.question_id = question_id;
		};

		if ( !this._object_empty( msg ) ) {
			this.emit('next_question', msg);
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

	XClick.prototype._object_empty = function( ob ) {
		for ( var i in ob ) {
			return false;
		}

		return true;
	};

	return XClick;
})();
