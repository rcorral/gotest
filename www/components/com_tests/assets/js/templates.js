var templates;

Templates = (function() {
	function Templates() {
	}

	Templates.prototype.mcsa = function( question ) {
		html = '';

		html += '<div class="span12"><legend>' + question.title + '</legend></div>';

		// Display media
		html += this.render_media( question );

		for ( var i = 0; i < question.options.length; i++ ) {
			option = question.options[i];

			html += '<div class="span12">';
			html += '<div class="control-group">';
			html += '<label for="option_' +option.id+ '" class="radio">';
			html += '<input type="radio" name="answer" value="' +option.id+ '" id="option_'
				+ option.id + '" /> ';
			html += option.title+ '</label>';
			html += '</div>';
			html += '</div>';
		};

		return html;
	};

	Templates.prototype.mcma = function( question ) {
		html = '';

		html += '<div class="span12"><legend>' + question.title + '</legend></div>';

		// Display media
		html += this.render_media( question );

		for ( var i = 0; i < question.options.length; i++ ) {
			option = question.options[i];

			html += '<div class="span12">';
			html += '<div class="control-group">';
			html += '<label for="option_' +option.id+ '" class="checkbox">';
			html += '<input type="checkbox" name="answer[]" value="' +option.id+ '" id="option_'
				+ option.id + '" /> ';
			html += option.title+ '</label>';
			html += '</div>';
			html += '</div>';
		};

		return html;
	};

	Templates.prototype.fitb = function( question ) {
		html = '';

		html += '<div class="span12"><legend>' + question.title + '</legend></div>';

		// Display media
		html += this.render_media( question );

		html += '<div class="span12">';
		html += '<div class="control-group">';
		html += '<input type="text" name="answer" value="" placeholder="Answer..." class="input-xlarge" />';
		html += '</div>';
		html += '</div>';

		return html;
	};

	Templates.prototype.fitbma = function( question ) {
		html = '';

		html += '<div class="span12"><legend>' + question.title + '</legend></div>';

		// Display media
		html += this.render_media( question );

		html += '<div class="span12">';
		html += '<div class="control-group">';
		html += '<input type="text" name="answer" value="" placeholder="Answer..." class="input-xlarge" />';
		html += '</div>';
		html += '</div>';

		return html;
	};

	Templates.prototype.essay = function( question ) {
		html = '';

		html += '<div class="span12"><legend>' + question.title + '</legend></div>';

		// Display media
		html += this.render_media( question );

		html += '<div class="span12">';
		html += '<div class="control-group">';
		html += '<textarea name="answer" rows="10"></textarea>';
		html += '</div>';
		html += '</div>';

		return html;
	};

	Templates.prototype.render_media = function( question ) {
		if ( !question.media ) {
			return '';
		}

		_return = '';
		switch ( question.media_type ) {
			case 'link':
				_return = '<div class="span7"><p><a href="' + question.media + '">'
					+ question.media + '</a></p></div>';
				break;

			case 'image':
				_return = '<div class="span7"><p><img src="'
					+ question.media + '" class="img-polaroid" /></p></div>';
				break;

			case 'youtube':
				_return = '<div class="span7"><div class="video-container">'
					+ '<iframe id="ytplayer" '
					+ 'src="https://www.youtube.com/embed/' +question.media+ '" '
					+ 'frameborder="0"></iframe>'
					+ '</div></div>';
				break;

			default:
				return '';
				break;
		}

		return _return;
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