Language = (function() {
	function Language( lang ) {
		this.lang = lang;
	}

	Language.prototype._ = function( string ) {
		strings = {
			'no_nick': 'You must first set a nick.',
			'no_channel': 'I need a channel to say stuff on.',
			'not_member_channel': 'You are not part of this channel.'
		};

		if ( strings[string] ) {
			string = strings[string];
		}

		return string;
	};

	return Language;
})();

exports.setup = function(language)
{
	if ( !language ) {
		language = 'en-GB';
	}

	return new Language( language );
}