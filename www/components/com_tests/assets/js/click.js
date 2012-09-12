var socket, xclick;

XClick = (function() {
	function XClick() {
		this.debug = true;
		this.user = { channels: {} };
		setup();
	}

	function setup() {
		socket = io.connect('http://' + io_server + ':8000/');
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

		if ( !this._object_empty( msg ) ) {
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

	XClick.prototype._object_empty = function( ob ) {
		for ( var i in ob ) {
			return false;
		}

		return true;
	};

	return XClick;
})();

function update_question( data ) {
	if ( xclick.debug ) {
		console.log('Current question:', data);
	}

	if ( !data || !data.question ) {
		return;
	}

	question = eval( '(' + data.question + ')' );

	if ( !question.id ) {
		return;
	};

	template = templates.parse( question.question_type, question );

	document.getElementById('form-data').innerHTML = template;
	document.getElementById('form-static').style.display = '';
}