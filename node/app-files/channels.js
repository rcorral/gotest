Channels = (function() {
	function Channels() {}

	Channels.prototype.exists = function(socket, channel) {
		var nsp = socket.namespace.name
		, channel = (nsp + '/') + channel;

		return socket.manager.rooms[channel];
	};

	Channels.prototype.get_channel = function(socket, channel) {
		var nsp = socket.namespace.name
		, channel = (nsp + '/') + channel;

		if ( !socket.manager.rooms[channel] ) {
			return false;
		}

		return socket.manager.rooms[channel];
	};

	Channels.prototype.get_channels = function(socket) {
		return socket.manager.rooms;
	};

	Channels.prototype.join = function(socket, channel) {
		return socket.join(channel);
	};

	Channels.prototype.is_member = function(socket, channel) {
		var id = client.get_id(socket);

		if ( !this.exists(socket, channel) ) {
			return false;
		}

		var nsp = socket.namespace.name
		, channel = (nsp + '/') + channel;

		if ( typeof socket.manager.rooms[channel][id] != 'undefined' ) {
			return false;
		}

		return client.is_member( socket, channel );
	};

	Channels.prototype.leave = function(socket, channel) {
		return socket.leave(channel);
	};

	return Channels;
})();

exports.setup = function()
{
	return new Channels;
}