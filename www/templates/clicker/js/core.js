var tests_class = function() {
	this.site_url = live_site;
}

tests_class.prototype.inline_popup = function( msg, auto_close, complete_callback ) {
	jQuery.colorbox({
		innerWidth: '500px',
		html: '<div id="inline_content">' +msg+ '</div>',
		onComplete: complete_callback
	});

	if ( Number( auto_close ) > 0 ) {
		setTimeout( function(){ jQuery.colorbox.close(); }, auto_close );
	}
};

tests_class.prototype._ajax = function( data, success, opts ) {
	options = {
		url: this.site_url + '?option=com_api',
		async: true,
		type: 'GET',
		dataType: 'json',
		error: function(jxhr){
			tests._stop_loader();
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
				_alert( 'An error ocurred: There was no response from the server. 409.' );
			}
		}
	};

	if ( typeof opts != 'undefined' ) {
		options = this._merge_objects( options, opts );
	}

	// Add default data parameters
	if ( typeof data.option == 'undefined' ) {
		data.option = 'com_api';
	}

	return jQuery.ajax({
		url: options.url,
		dataType: options.dataType,
		cache: false,
		async: options.async,
		type: options.type,
		data: data,
		success: success,
		error: options.error
	}).responseText;
};

tests_class.prototype._object_empty = function( ob ) {
	for ( var i in ob ) {
		return false;
	}

	return true;
};

tests_class.prototype._merge_objects = function( obj1, obj2 ) {
	var obj3 = {};

	for ( var attrname in obj1 ) { obj3[attrname] = obj1[attrname]; }
	for ( var attrname in obj2 ) { obj3[attrname] = obj2[attrname]; }

	return obj3;
};

tests_class.prototype._load_asset = function( filename, filetype, async ) {
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

tests_class.prototype._start_loader = function( msg, callback ) {
	return;
};

tests_class.prototype._stop_loader = function() {
	return;
};

tests_class.prototype._merge_objects = function( obj1, obj2 ) {
	var obj3 = {};

	for ( var attrname in obj1 ) { obj3[attrname] = obj1[attrname]; }
	for ( var attrname in obj2 ) { obj3[attrname] = obj2[attrname]; }

	return obj3;
};

/**
 * Expects date object as parameter
 */
tests_class.prototype._format_date = function( date ) {
	return ( date.getMonth() + 1 ) + '/' + date.getDate() + '/' + date.getFullYear();
};

var tests;
jQuery(document).ready(function(){
	tests = new tests_class;
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
		options = tests._merge_objects( options, opts );
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