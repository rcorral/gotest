var templates;

Templates = (function() {
	function Templates() {
	}

	Templates.prototype.mcsa = function( question ) {
		html = '';

		html += '<legend>' + question.title + '</legend>';
		// Assume that every media is an image for now...
		if ( question.media ) {
			html += '<img src="' + live_site + 'media/' + question.media + '" class="img-polaroid" />';
		};

		for ( var i = 0; i < question.options.length; i++ ) {
			option = question.options[i];

			html += '<div class="control-group">';
			html += '<label for="option_' +option.id+ '" class="radio">';
			html += '<input type="radio" name="option" value="' +option.id+ '" id="option_'
				+ option.id + '" /> ';
			html += option.title+ '</label>';
			html += '</div>';
		};

		return html;
	};

	Templates.prototype.mcma = function( question ) {
		html = '';

		html += '<legend>' + question.title + '</legend>';
		// Assume that every media is an image for now...
		if ( question.media ) {
			html += '<img src="' + live_site + 'media/' + question.media + '" class="img-polaroid" />';
		};

		for ( var i = 0; i < question.options.length; i++ ) {
			option = question.options[i];

			html += '<div class="control-group">';
			html += '<label for="option_' +option.id+ '" class="checkbox">';
			html += '<input type="checkbox" name="option" value="' +option.id+ '" id="option_'
				+ option.id + '" /> ';
			html += option.title+ '</label>';
			html += '</div>';
		};

		return html;
	};

	Templates.prototype.fitb = function( question ) {
		html = '';

		html += '<legend>' + question.title + '</legend>';
		// Assume that every media is an image for now...
		if ( question.media ) {
			if ( -1 != question.media.indexOf( 'http' ) ) {
				html += '<img src="' + question.media + '" class="img-polaroid" />';
			} else {
				html += '<img src="' + live_site + 'media/' + question.media + '" class="img-polaroid" />';
			}
		};

		html += '<div class="control-group">';
		html += '<input type="text" name="option" value="" placeholder="Answer..." class="input-xlarge" />';
		html += '</div>';

		return html;
	};

	Templates.prototype.fitbma = function( question ) {
		html = '';

		html += '<legend>' + question.title + '</legend>';
		// Assume that every media is an image for now...
		if ( question.media ) {
			if ( -1 != question.media.indexOf( 'http' ) ) {
				html += '<img src="' + question.media + '" class="img-polaroid" />';
			} else {
				html += '<img src="' + live_site + 'media/' + question.media + '" class="img-polaroid" />';
			}
		};

		html += '<div class="control-group">';
		html += '<input type="text" name="option" value="" placeholder="Answer..." class="input-xlarge" />';
		html += '</div>';

		return html;
	};

	Templates.prototype.essay = function( question ) {
		html = '';

		html += '<legend>' + question.title + '</legend>';
		// Assume that every media is an image for now...
		if ( question.media ) {
			if ( -1 != question.media.indexOf( 'http' ) ) {
				html += '<img src="' + question.media + '" class="img-polaroid" />';
			} else {
				html += '<img src="' + live_site + 'media/' + question.media + '" class="img-polaroid" />';
			}
		};

		html += '<div class="control-group">';
		html += '<textarea name="option" rows="10"></textarea>';
		html += '</div>';

		return html;
	};

	Templates.prototype.parse = function( type, question ) {
		switch ( type ) {
			case 'mcsa':
				return this.mcsa( question );
				break;

			case 'mcma':
				return this.mcma( question );
				break;

			case 'fitb':
				return this.fitb( question );
				break;

			case 'fitbma':
				return this.fitbma( question );
				break;

			case 'essay':
				return this.essay( question );
				break;

			default:
				return '';
				break;
		}
	};

	return Templates;
})();

templates = new Templates;