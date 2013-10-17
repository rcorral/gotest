var templates;

Templates = (function()
{
	function Templates(){}

	Templates.prototype.mcsa = function( question )
	{
		html = '';

		html += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><legend class="lead">' + question.title + '</legend></div>';

		// Display media
		html += this.render_media( question );

		for ( var i = 0; i < question.options.length; i++ )
		{
			option = question.options[i];

			html += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
			html += '<div class="form-group">';
			html += '<div class="radio">';
			html += '<label for="option_' +option.id+ '">';
			html += '<input type="radio" name="answer" value="' +option.id+ '" id="option_' + option.id + '" /> ';
			html += option.title+ '</label>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
		};

		return html;
	};

	Templates.prototype.mcma = function( question )
	{
		html = '';

		html += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><legend class="lead">' + question.title + '</legend></div>';

		// Display media
		html += this.render_media( question );

		for ( var i = 0; i < question.options.length; i++ )
		{
			option = question.options[i];

			html += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
			html += '<div class="form-group">';
			html += '<div class="checkbox">';
			html += '<label for="option_' +option.id+ '">';
			html += '<input type="checkbox" name="answer[]" value="' +option.id+ '" id="option_' + option.id + '" /> ';
			html += option.title+ '</label>';
			html += '</div>';
			html += '</div>';
			html += '</div>';
		};

		return html;
	};

	Templates.prototype.fitb = function( question )
	{
		html = '';

		html += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><legend class="lead">' + question.title + '</legend></div>';

		// Display media
		html += this.render_media( question );

		html += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">';
		html += '<div class="form-group">';
		html += '<input type="text" name="answer" value="" placeholder="Answer..." class="input-xlarge form-control" />';
		html += '</div>';
		html += '</div>';

		return html;
	};

	Templates.prototype.fitbma = function( question )
	{
		html = '';

		html += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><legend class="lead">' + question.title + '</legend></div>';

		// Display media
		html += this.render_media( question );

		html += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-5">';
		html += '<div class="form-group">';
		html += '<input type="text" name="answer" value="" placeholder="Answer..." class="input-xlarge form-control" />';
		html += '</div>';
		html += '</div>';

		return html;
	};

	Templates.prototype.essay = function( question )
	{
		html = '';

		html += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><legend class="lead">' + question.title + '</legend></div>';

		// Display media
		html += this.render_media( question );

		html += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">';
		html += '<div class="form-group">';
		html += '<textarea name="answer" rows="10" placeholder="Answer...." class="form-control"></textarea>';
		html += '</div>';
		html += '</div>';

		return html;
	};

	Templates.prototype.render_media = function( question )
	{
		if ( !question.media ) return '';

		_return = '';
		switch ( question.media_type )
		{
			case 'link':
				_return = '<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7"><p><a href="' + question.media + '">'
					+ question.media + '</a></p></div><div class="clearfix"></div>';
				break;

			case 'image':
				_return = '<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7"><p><img src="'
					+ question.media + '" class="img-polaroid" /></p></div><div class="clearfix"></div>';
				break;

			case 'youtube':
				_return = '<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7"><div class="video-container">'
					+ '<iframe id="ytplayer" '
					+ 'src="https://www.youtube.com/embed/' +question.media+ '" '
					+ 'frameborder="0"></iframe>'
					+ '</div></div><div class="clearfix"></div>';
				break;

			default: return _return;
		}

		return _return;
	};

	Templates.prototype.parse = function( type, question )
	{
		switch ( type )
		{
			case 'mcsa':   return this.mcsa(question);   break;
			case 'mcma':   return this.mcma(question);   break;
			case 'fitb':   return this.fitb(question);   break;
			case 'fitbma': return this.fitbma(question); break;
			case 'essay':  return this.essay(question);  break;
			default: return ''; break;
		}
	};

	return Templates;
})();

templates = new Templates;