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

			question = eval( '(' + data.question + ')' );

			if ( !data || !data.question || !question.id ) {
				return;
			}

			template = templates.parse( question.question_type, question );

			document.getElementById('question-order').value = Number( question.order );
			document.getElementById('form-data').innerHTML = template;
			document.getElementById('form-static').style.display = '';

			if ( question.order > 1 ) {
				document.getElementById('btn-previous').style.display = '';
			} else {
				document.getElementById('btn-previous').style.display = 'none';
			}

			if ( question.order == question.max_order ) {
				document.getElementById('btn-next').style.display = 'none';
			} else {
				document.getElementById('btn-next').style.display = '';
			}
		});

		return socket;
	};

	XClick.prototype.submit = function(btn) {
		if ( 'next' == btn.name ) {
			question_order = Number( document.getElementById( 'question-order' ).value ) + 1
		} else {
			question_order = Number( document.getElementById( 'question-order' ).value ) - 1;
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

		if ( !this._object_empty( msg ) ) {
			this.emit('next_question', msg);
		}
	};

	XClick.prototype.emit = function(event, data) {
		if ( this.debug ) {
			console.log('Emit:', data);
		}

		data.is_presenter = 1;

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
