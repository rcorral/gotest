var io_server = '10.0.1.20';

var ga = document.createElement('script');
ga.type = 'text/javascript';
ga.async = true;
ga.src = 'http://' + io_server + ':8000/socket.io/socket.io.js';
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(ga, s);

var io_interval = window.setInterval(function() {
	if (io) {
		window.clearInterval(io_interval);
		xclick = new XClick;
	}
}, 500);