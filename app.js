var http = require('http')
	, app = http.createServer(handler)
	, io = require('socket.io').listen(app)
	, fs = require('fs')
	, in_development = true
	;

app.listen(8000);

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

function handler ( req, res) {
	fs.readFile(__dirname + '/client/index.html',
	function (error, data) {
		if (error) {
			res.writeHead(500);
			return res.end('Error loading index.html');
		}

		res.writeHead(200);
		res.end(data);
	});
}

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
// client = click.client.setup();
// channels = click.channels.setup();
var lang = click.language.setup();

io.sockets.on('connection', function( socket ) {
	socket.on('test_begin', function( data ) {
		if ( !tests.validate_request_data( data, true ) ) {
			_debug( 'not_presenter' );
			socket.disconnect();
			return;
		}

		tests.initialize_test( data.test_id, data.uid );

		_debug( 'Test initialized', tests.at );
	});

	socket.on('next_question', function( data, fn ) {
		var _return_type = 'success', _return_msg = { msg: lang._( 'msg_success' ) };

		if ( !tests.validate_request_data( data, true ) ) {
			_debug( 'not_presenter' );
			socket.disconnect();
			return;
		}

		if ( !tests.test_exists( data.test_id, data.uid ) ) {
			fn( 'error', { msg: lang._( 'test_not_init' ) } );
			return;
		};

		if ( !fn ) {
			fn = function(){}
		}

		var site = http.createClient(80, 'localhost');
		var path = '/clicker/index.php?option=com_api&app=tests&resource=question&test_id='
			+ data.test_id + '&key=' + data.key;

		if ( data.question_id ) {
			path += '&question_id=' + data.question_id;
		}

		_debug( 'Requesting: ', path );
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
				io.sockets.emit('next_question',
					{ type: 'question', question: test.cq, offset: 0 });
			});
		});
	});

	socket.on('current_question', function( data ) {
		var _return = {};

		if ( !tests.validate_request_data( data ) ) {
			_debug( 'invalid_request' );
			socket.disconnect();
			return;
		}

		var test = tests.get_test( data.test_id, data.uid );

		// Check if test is even existent
		if ( !test ) {
			_return.type = 'test';
			_return.exists = false;
			_debug( 'current_question', 'test_non_existent' );
		};

		// Check to see if test has been initialized
		if ( !test.initialized ) {
			_return.type = 'test';
			_return.exists = true;
			_return.initialized = false;
			_debug( 'current_question', 'test_not_init' );
		} else {
			var body = test.cq;
			var timer_action = test.timer_action;

			// If we have toggled the time then calculate the offset from the seconds left
			if ( test.seconds_left ) {
				var question = JSON.parse( test.cq );
				var offset = Number( question.seconds ) - Number( test.seconds_left );
				_debug( 'Offset by: seconds_left' );
			} else {
				var offset = Math.floor( new Date() / 1000 - test.q_date / 1000 );
				_debug( 'Offset by: current_date' );
			}

			_return = {
				type: 'question',
				question: test.cq,
				timer_action: test.timer_action,
				offset: offset
				};
			_debug( 'current_question', _return );
		};

		socket.emit('current_question', _return);
	});

	socket.on('timer_toggle', function( data ) {
		if ( !tests.validate_request_data( data, true ) ) {
			_debug( 'invalid_request' );
			socket.disconnect();
			return;
		}

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

		_debug( 'timer_toggle', data.action, data.seconds_left );

		socket.broadcast.emit('timer_toggle',
			{ action: data.action, seconds_left: data.seconds_left });
	});

	socket.on('disconnect', function () {
		io.sockets.emit('user disconnected');
	});
});