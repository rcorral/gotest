Client = (function() {
	function Client() {
	}

	Client.prototype.get_id = function(socket) {
		return '' + socket.id + '';
	};

	Client.prototype.get_nick = function(socket, fn) {
		if ( !fn ) {
			fn = function(err, nick){ return nick; }
		}

		return socket.get('nick', fn);
	};

	Client.prototype.set_nick = function(socket, nick, fn) {
		if ( socket.set('nick', nick) ) {
			if ( typeof fn == 'function' ) {
				fun();
			}

			return this;
		}
	};

	Client.prototype.get_channels = function(socket) {
		id = client.get_id(socket);

		if ( !socket.manager.roomClients[id] ) {
			socket.manager.roomClients[id] = {};
		}

		return socket.manager.roomClients[id];
	};

	Client.prototype.is_member = function(socket, channel) {
		id = client.get_id(socket);

		if ( !socket.manager.roomClients[id] ) {
			return false;
		}

		if ( !socket.manager.roomClients[id][channel]) {
			return false;
		}

		return true;
	};

	return Client;
})();

exports.setup = function()
{
	return new Client;
}