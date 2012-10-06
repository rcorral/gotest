var http = require('http')
	, app = http.createServer(handler)
	, io = require('socket.io').listen(app)
	, fs = require('fs')

app.listen(8000);

function handler (req, res) {
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

io.sockets.on('connection', function (socket) {
	socket.on('next_question', function (data, fn) {
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
					current_question[data.test_id] = body;
					io.sockets.emit('next_question', { type: 'question', question: body });
				});
			});
		}
	});

	socket.on('current_question', function (data, fn) {
		body = '';
		if ( current_question[data.test_id] ) {
			body = current_question[data.test_id];
		};
		socket.emit('current_question', { type: 'question', question: body });
	});

	socket.on('disconnect', function () {
		io.sockets.emit('user disconnected');
	});
});