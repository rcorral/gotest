var http = require('http')
	, io = require('socket.io').listen(8080)
	, in_development = true
	;

io.configure(function(){
	io.enable('browser client etag');
	io.enable('browser client gzip');

	if ( in_development ) {
		io.set('log level', 3);
	} else {
		io.set('log level', 2);
	}

	io.set('transports', [
		'websocket'
		, 'flashsocket'
		, 'htmlfile'
		, 'xhr-polling'
		, 'jsonp-polling'
  	]);
});

function _debug() {
	if ( in_development ) {
		console.log.apply( undefined, arguments );
	}
}

// for ( _client in channels.get_channel(data.channel) ) {
// 	_debug(_client);
// }

click = require('./app-files/click');
var tests = click.tests.setup();
client = click.client.setup();
// channels = click.channels.setup();
var lang = click.language.setup();

io.sockets.on('connection', function( socket ) {
	socket.on('test_begin', function( data ) {
		if ( !tests.validate_request_data( data, true, true ) ) {
			_debug( 'test_begin:not_presenter' );
			socket.disconnect();
			return;
		}

		var room = data.test_id + '-' + data.uid;
		var psid = client.get_id( socket );

		// Get the socket_id of the presenter socket id
		var test = tests.initialize_test( data.test_id, data.uid, psid );

		_debug( 'test_begin:test_initialized', tests.at );
		socket.join(room);
		socket.broadcast.to(room)
			.emit('test_status', { type: 'test', initialized: test.initialized });
	});

	socket.on('next_question', function( data, fn ) {
		var _return_type = 'success', _return_msg = { msg: lang._( 'msg_success' ) };

		if ( !tests.validate_request_data( data, true, true ) ) {
			_debug( 'next_question:not_presenter' );
			socket.disconnect();
			return;
		}

		if ( !tests.test_exists( data.test_id, data.uid ) ) {
			fn( 'error', { msg: lang._( 'test_not_init' ) } );
			return;
		};

		// Generate room name from request
		var room = data.test_id + '-' + data.uid;

		if ( !fn ) {
			fn = function(){}
		}

		var site = http.createClient(80, 'localhost');
		var path = '/index.php?option=com_api&app=tests&resource=question&test_id='
			+ data.test_id + '&key=' + data.key;

		if ( data.question_id ) {
			path += '&question_id=' + data.question_id;
		}

		_debug( 'next_question:requesting', path );
		var request = site.request( 'GET', path, {'host' : 'localhost'} );
		request.end();
		request.on('response', function(response){
			response.setEncoding('utf8');

			if ( 200 != response.statusCode ) {
				fn( 'error', { msg: lang._( 'no_php_connection' ) } );
				return;
			};

			var body = '';
			response.on('data', function(chunk) {
				body += chunk;
			});

			response.on('end', function() {
				test = tests.set_question( data.test_id, data.uid, body );
				_debug( 'next_question:test', test );
				io.sockets.in(room)
					.emit('next_question', { type: 'question', question: test.cq, offset: 0 });
			});
		});
	});

	socket.on('current_question', function( data ) {
		var _return = {};

		if ( !tests.validate_request_data( data ) ) {
			_debug( 'current_question:invalid_request' );
			socket.disconnect();
			return;
		}

		var room = data.test_id + '-' + data.uid;
		var test = tests.get_test( data.test_id, data.uid );

		// Join room
		socket.join(room);

		_debug( 'current_question:test', test );

		// Check if test is even existent
		if ( !test ) {
			_return.type = 'test';
			_return.initialized = false;
			_debug( 'current_question:test_non_existent' );
		} else if ( test.initialized && !test.started ) {
			// Check for test initialized but not started
			_return.type = 'test';
			_return.initialized = true;
			_return.started = false;
			_debug( 'current_question:test_init_not_started' );
		} else {
			var body = test.cq;
			var timer_action = test.timer_action;

			// If we have toggled the time then calculate the offset from the seconds left
			if ( test.seconds_left ) {
				var question = JSON.parse( test.cq );
				var offset = Number( question.seconds ) - Number( test.seconds_left );
				_debug( 'current_question:offset_by: seconds_left' );
			} else {
				var offset = Math.floor( new Date() / 1000 - test.q_date / 1000 );
				_debug( 'current_question:offset_by: current_date' );
			}

			_return = {
				type: 'question',
				question: test.cq,
				timer_action: test.timer_action,
				offset: offset
				};
			_debug( 'current_question:return', _return );
		};

		socket.emit('current_question', _return);
	});

	socket.on('timer_toggle', function( data ) {
		if ( !tests.validate_request_data( data, true, true ) ) {
			_debug( 'timer_toggle:invalid_request' );
			socket.disconnect();
			return;
		}

		var room = data.test_id + '-' + data.uid;
		var test = tests.get_test( data.test_id, data.uid );

		if ( 'pause' == data.action ) {
			tests.set_test_var( data.test_id, data.uid, 'seconds_left', data.seconds_left );
		} else if ( 'play' == data.action ) {
			tests.remove_test_var( data.test_id, data.uid, 'seconds_left' );
			question = JSON.parse( test.cq );
			offset = Number( question.seconds ) - Number( data.seconds_left );
			date = new Date();
			date.setSeconds( date.getSeconds() - offset );
			// Reset the start date of the question
			// we are basically faking it for possible later use
			tests.set_test_var( data.test_id, data.uid, 'q_date', date );
		}

		tests.set_test_var( data.test_id, data.uid, 'timer_action', data.action );

		_debug( 'timer_toggle:response', data.action, data.seconds_left );

		socket.broadcast.to(room)
			.emit('timer_toggle', { action: data.action, seconds_left: data.seconds_left });
	});

	socket.on('complete', function( data ) {
		if ( !tests.validate_request_data( data, true, true ) ) {
			_debug( 'complete:invalid_request' );
			socket.disconnect();
			return;
		}

		var room = data.test_id + '-' + data.uid;

		tests.remove_test( data.test_id, data.uid );

		_debug( 'complete:completing', room );

		socket.broadcast.to(room).emit('complete', {});
	});

	socket.on('disconnect', function () {
		// io.sockets.emit('user disconnected');
	});
});