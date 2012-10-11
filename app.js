var http = require('http')
	, app = http.createServer(handler)
	, io = require('socket.io').listen(app)
	, fs = require('fs')

app.listen(8000);

io.configure(function(){
	io.enable('browser client etag');
	io.enable('browser client gzip');
	io.set('log level', 2);

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

// for ( _client in channels.get_channel(data.channel) ) {
// 	console.log(_client);
// }

click = require('./app-files/click');
activetests = click.activetests.setup();
lang = click.language.setup();
client = click.client.setup();
var current_question = [];

io.sockets.on('connection', function( socket ) {
	socket.on('next_question', function( data, fn ) {
		var _return_type = 'success', _return_msg = { msg: lang._( 'msg_success' ) };

		if ( !( data instanceof Object ) || !data.key ) {
			socket.disconnect();
			return;
		}

		if ( !fn ) {
			fn = function(){}
		}

		if ( null != data.test_id ) {
			data.test_id = Number( data.test_id );

			if ( !data.test_id ) {
				fn('error', { msg: lang._( 'no_test_id' ) });
				return;
			}

			var site = http.createClient(80, 'localhost');
			var path = '/clicker/index.php?option=com_api&app=tests&resource=question&test_id='
				+ data.test_id;

			if ( data.question_id ) {
				path += '&question_id=' + data.question_id;
			}

			if ( data.key ) {
				path += '&key=' + data.key;
			}

			var request = site.request('GET',
				path,
				{'host' : 'localhost'});
			request.end();
			request.on('response', function(response){
				response.setEncoding('utf8');

				if ( 200 != response.statusCode ) {
					fn('error', { msg: lang._( 'no_php_connection' ) });
					return;
				};

				var body = '';
				response.on('data', function(chunk) {
					body += chunk;
				});

				response.on('end', function() {
					current_question[data.test_id] = { date: new Date(), question: body };
					io.sockets.emit('next_question',
						{ type: 'question', question: body, offset: 0 });
				});
			});
		}
	});

	socket.on('current_question', function( data, fn ) {
		var body = '',
			offset = 0,
			timer_action = '',
			by = '';

		if ( current_question[data.test_id] ) {
			body = current_question[data.test_id].question;
			timer_action = current_question[data.test_id].timer_action;

			// If we have toggled the time then calculate the offset from the seconds left
			if ( current_question[data.test_id].seconds_left ) {
				_question = JSON.parse( current_question[data.test_id].question );
				offset = Number( _question.seconds )
					- Number( current_question[data.test_id].seconds_left );

				// For debugging
				by = 'seconds_left';
			} else {
				offset = Math.floor( new Date() / 1000
					- current_question[data.test_id].date / 1000 );

				// For debugging
				by = 'current_date';
			}
		};

		socket.emit('current_question', { type: 'question', question: body,
			timer_action: timer_action, offset: offset, by: by });
	});

	socket.on('timer_toggle', function( data ) {
		if ( !( data instanceof Object ) || !data.key ) {
			socket.disconnect();
			return;
		}

		if ( 'pause' == data.action ) {
			current_question[data.test_id].seconds_left = data.seconds_left;
		} else if ( 'play' == data.action ) {
			delete current_question[data.test_id].seconds_left;
			_question = JSON.parse( current_question[data.test_id].question );
			offset = Number( _question.seconds ) - Number( data.seconds_left );
			date = new Date();
			date.setSeconds( date.getSeconds() - offset );
			current_question[data.test_id].date = date;
		}

		current_question[data.test_id].timer_action = data.action;

		socket.broadcast.emit('timer_toggle',
			{ action: data.action, seconds_left: data.seconds_left });
	});

	socket.on('disconnect', function () {
		io.sockets.emit('user disconnected');
	});
});