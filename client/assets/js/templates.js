var templates;

Templates = (function() {
	function Templates() {
	}

	Templates.prototype.mcsa = function( question ) {
		html = '';

		html += '<h3>' + question.title + '</h3>';
		// Assume that every media is an image for now...
		if ( question.media ) {
			html += '<img src="' + live_iste + 'media/' + question.media + '" />';
		};

		html += '<div class="answers-wrapper">';
		for ( var i = 0; i < question.answers.length; i++ ) {
			answer = question.answers[i];
			html += '<span class="answer">';
			html += '<input type="radio" name="answer" value="' +answer.id+ '" id="answer_' +
				answer.id + '" /> ';
			html += '<label for="answer_' +answer.id+ '">' +answer.title+ '</label></span>';
		};
		html += '</div>';

		return html;
	};

	Templates.prototype.mcma = function( question ) {
		html = '';

		html += '<h3>' + question.title + '</h3>';
		// Assume that every media is an image for now...
		if ( question.media ) {
			html += '<img src="' + live_iste + 'media/' + question.media + '" />';
		};

		html += '<div class="answers-wrapper">';
		for ( var i = 0; i < question.answers.length; i++ ) {
			answer = question.answers[i];
			html += '<span class="answer">';
			html += '<input type="checkbox" name="answer" value="' +answer.id+ '" id="answer_' +
				answer.id + '" /> ';
			html += '<label for="answer_' +answer.id+ '">' +answer.title+ '</label></span>';
		};
		html += '</div>';

		return html;
	};

	Templates.prototype.fitb = function( question ) {
		html = '';

		html += '<h3>' + question.title + '</h3>';
		// Assume that every media is an image for now...
		if ( question.media ) {
			if ( -1 != question.media.indexOf( 'http' ) ) {
				html += '<img src="' + question.media + '" />';
			} else {
				html += '<img src="' + live_iste + 'media/' + question.media + '" />';
			}
		};

		html += '<div class="answers-wrapper">';
		html += '<span class="answer">';
		html += '<input type="text" name="answer" value="" id="answer" /> ';
		html += '</span>';
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
				break;

			case 'essay':
				break;

			default:
				return '';
				break;
		}
	};

	return Templates;
})();

templates = new Templates;