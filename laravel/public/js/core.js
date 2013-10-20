var core_class = function() {
	this.site_url = live_site;
	this.modal_container = jQuery('#modal-container');
}

core_class.prototype.inline_popup = function( msg, auto_close, complete_callback ) {
	this.modal_container.on('shown', complete_callback);

	this.modal(msg);

	if ( Number( auto_close ) > 0 ) {
		setTimeout( function(){ this.modal_container.modal('hide'); }, auto_close );
	}
};

core_class.prototype.modal = function(data) {
	if ( typeof data === 'string' ) {
		data = {body: data};
	}

	if ( data.options ) {
		// if ( data.options.width ) this.modal_container.css({width: data.options.width});
		// else this.modal_container.css({width: 'auto'});
	}

	html = '';
	if ( data.header )
		html += '<div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 id="modal-label" class="modal-title">' + data.header + '</h4></div>';

	if ( data.body )
		html += '<div class="modal-body">' + data.body + '</div>';

	if ( data.footer )
		html += '<div class="modal-footer">' + data.footer + '</div>';

	this.modal_container.find('.modal-content:first').html(html);
	this.modal_container.modal()
		// .css((jQuery(document).width() <= 751 ? {margin:'30px auto'} : {'margin-left':function () { return -(jQuery(this).width() / 2);}}));
	setTimeout(function(){
		$el = core.modal_container.find('input[type="text"]:first');
		if ( $el[0] ) $el[0].focus();

		// Initialize tooltips
		core.modal_container.find('.tooltips').popover({placement: 'auto bottom', trigger: 'hover focus', container: 'body'});
	}, 500);
};

core_class.prototype.modal_close = function() {
	this.modal_container.modal('hide');
}

core_class.prototype.parse_request = function(req) {
	if ( typeof req.modal !== 'undefined' ) this.modal(req.modal);

	if ( typeof req.alert !== 'undefined' ) _alert(req.alert);

	if ( typeof req.redirect !== 'undefined' )
	{
		if ( 'current.location' == req.redirect ) window.location = window.location.href;
		else window.location.replace(req.redirect);
	}

	if ( typeof req.exec !== 'undefined' ) this.eval(req);
};

/**
 * Creates an iframe to log user out
 */
core_class.prototype.logout = function() {
	ifrm = document.createElement('IFRAME');
	ifrm.setAttribute('src', this.site_url + 'logout');
	ifrm.style.width = '1px';
	ifrm.style.height = '1px';
	ifrm.style.display = 'none';
	document.body.appendChild( ifrm );
};

core_class.prototype._ajax = function( data, success, opts ) {
	options = {
		url: this.site_url,
		async: true,
		type: 'GET',
		dataType: 'json',
		cache: false,
		error: function(jxhr){
			core._stop_loader();
			if ( typeof jxhr.responseText == 'undefined' || !jxhr.responseText ) {
				return;
			}

			try {
				t = jQuery.parseJSON( jxhr.responseText );
				if ( t.message ) {
					_alert( t.message );
				} else {
					_alert( 'An error ocurred: There was no response from the server.' );
				}
			} catch(e) {
				_alert( 'An error ocurred: There was no response from the server.' );
			}
		}
	};

	if ( typeof opts != 'undefined' ) {
		options = this._merge_objects( options, opts );
	}

	if ( is_loggedin && !data._token ) data._token = _token;

	return jQuery.ajax({
		url: options.url,
		dataType: options.dataType,
		cache: options.cache,
		async: options.async,
		type: options.type,
		data: data,
		success: success,
		error: options.error
	}).responseText;
};

core_class.prototype._object_empty = function( ob ) {
	for ( var i in ob ) {
		return false;
	}

	return true;
};

core_class.prototype._merge_objects = function( obj1, obj2 ) {
	var obj3 = {};

	for ( var attrname in obj1 ) { obj3[attrname] = obj1[attrname]; }
	for ( var attrname in obj2 ) { obj3[attrname] = obj2[attrname]; }

	return obj3;
};

core_class.prototype._load_asset = function( filename, filetype, async ) {
	if ( 'js' == filetype ) {
		var sc = document.createElement('script');
		sc.setAttribute( 'type', 'text/javascript' );
		sc.setAttribute( 'src', filename );
	} else if ( 'css' == filetype ) {
		var sc = document.createElement( 'link' );
		sc.setAttribute( 'rel', 'stylesheet' );
		sc.setAttribute( 'type', 'text/css' );
		sc.setAttribute( 'href', filename );
	}

	if ( async ) {
		sc.async = true;
	} else {
		sc.async = false;
	}

	if ( typeof sc != 'undefined' )
		document.getElementsByTagName('head')[0].appendChild( sc );
};

core_class.prototype.eval = function( variables )
{
	var text = '';
	for ( i in variables )
		if ( i != 'exec' )
			text += 'var ' + i + ' = "' + variables[i].replace(/"/g, '\\"') + '";';

	var script = document.createElement('script');
	script.type = "text/javascript";
	document.getElementsByTagName('head')[0].appendChild(script);
	// Closure that shit - fuck the polution
	script.text = '(function(){' + text + variables.exec + '})();';
}

core_class.prototype._start_loader = function( msg, callback ) {
	return;
};

core_class.prototype._stop_loader = function() {
	return;
};

core_class.prototype._merge_objects = function( obj1, obj2 ) {
	var obj3 = {};

	for ( var attrname in obj1 ) { obj3[attrname] = obj1[attrname]; }
	for ( var attrname in obj2 ) { obj3[attrname] = obj2[attrname]; }

	return obj3;
};

/**
 * Expects date object as parameter
 */
core_class.prototype._format_date = function( date ) {
	return ( date.getMonth() + 1 ) + '/' + date.getDate() + '/' + date.getFullYear();
};

/**
 * Returns highest units of time that can be made out of the seconds provided
 * Returns the time and the units for the seconds
 **/
core_class.prototype.seconds_to_readable_time = function( seconds, add_to_counter ) {
	var _seconds = Number( seconds );

	if ( typeof add_to_counter == "undefined" || add_to_counter ) {
		add_to_counter = 1;
	} else {
		add_to_counter = 0;
	}

	// Check for minutes
	seconds /= 60;

	// We only have seconds
	if ( seconds < 1 ) {
		return { time: _seconds, units: 's' };
	}

	// Check for hours
	seconds /= 60;

	// We have minutes
	if ( seconds < 1 ) {
		return { time: Math.floor( _seconds / 60 ) + add_to_counter, units: 'm' };
	};

	// Check for days
	seconds /= 24;

	// We have hours
	if ( seconds < 1 ) {
		return { time: Math.floor( _seconds / 60 / 60 ) + add_to_counter, units: 'h' };
	};

	// We have days
	return { time: Math.floor( _seconds / 60 / 60 / 24 ) + add_to_counter, units: 'd' };
};

core_class.prototype.double_check = function()
{
	return confirm('Are you sure?');
};

var core;
jQuery(document).ready(function(){
	core = new core_class;
});

function _datetime_to_date( datetime ) {
	// Function parses mysql datetime string and returns javascript Date object
	// input has to be in this format: 2007-06-05 15:26:02
	var regex = /^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9])\s?(?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
	var parts = datetime.replace( regex, '$1 $2 $3 $4 $5 $6' ).split( ' ' );
	return new Date( parts[0], parts[1]-1, parts[2], parts[3], parts[4], parts[5] );
}

var date_times = {
	minute: 60,
	hour: 3600,
	day: 86400,
	week: 604800,

	seconds: function( seconds, future ) {
		if ( !future ) {
			future = new Date();
		};

		future.setSeconds( seconds );

		return future;
	}
};

function _populate_select( selector, obj, _key, _value, opts ) {
	options = {
		show_default: false,
		default_value: 0,
		default_text: '-- Select --',
		select_option: false,
		selected_value: 0,
		refresh: false,
		rebuild: false,
		callback: null
	};

	if ( typeof opts != 'undefined' ) {
		options = core._merge_objects( options, opts );
	}

	el = jQuery(selector);
	el.html('');
	html = '';

	if ( options.show_default ) {
		html += '<option value="' + options.default_value + '">'
		+ options.default_text + '</option>';
	}

	jQuery.each(obj, function( key, row ){
		html += '<option value="' + eval( _key ) + '">' + eval( _value ) + '</option>';
	});

	el.html(html);

	if ( options.select_option ) {
		el.val(options.selected_value);
		el[0].value = options.selected_value;
	}

	if ( typeof options.callback == 'function' ) {
		options.callback();
	}
}

function _alert( msg ) {
	alert( msg );
}

function _confirm( msg ) {
	return confirm( msg );
}

function _gup( name, loc ) {
	_name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]" + _name + "=([^&#]*)";
	var regex = new RegExp( regexS );

	var hash_test = false;
	if ( window.location.hash && !loc ) {
		loc = window.location.hash
		hash_test = true;
	}

	if ( !loc ) {
		loc = window.location.href;
	}

	var results = regex.exec( loc );

	if ( results == null ) {
		if ( hash_test ) {
			return _gup( name, window.location.href );
		}
		return '';
	} else {
		return results[1];
	}
}

function array_search( needle, haystack, argStrict ) {
	var key = '', strict = !! argStrict;

	if ( strict ) {
		for ( key in haystack ) {
			if ( haystack[key] === needle ) {
				return key;
			}
		}
	} else {
		for ( key in haystack ) {
			if ( haystack[key] == needle ) {
				return key;
			}
		}
	}

	return false;
}

Object.size = function(obj) {
	var size = 0, key;
	for ( key in obj ) {
		if ( obj.hasOwnProperty( key ) ) {
			size++;
		}
	}

	return size;
};

// Colorbox fix for scrollbars
if ( jQuery ) {
	jQuery(document).ready(function(){
		jQuery(document).on('cbox_open', function(){
			document.documentElement.style.overflow = 'hidden'; // All except IE
			document.body.scroll = 'no'; // ie only
		}).on('cbox_closed', function(){
			document.documentElement.style.overflow = ''; // All except IE
			document.body.scroll = ''; // ie only
		});
	});
};