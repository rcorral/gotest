Tests = (function() {
	function Tests() {
		// Active tests
		this.at = [];
	}

	Tests.prototype.validate_request_data = function( data, key, presenter ) {
		if ( !( data instanceof Object ) ) {
			return false;
		};

		if ( !data.test_id ) {
			return false;
		};

		if ( !data.uid ) {
			return false;
		};

		if ( 'undefined' != typeof key && key ) {
			if ( !data.key ) {
				return false;
			};
		};

		if ( 'undefined' != typeof presenter && presenter ) {
			if ( !data.isp ) {
				return false;
			};
		};

		return true;
	};

	// Incase we ever want to make the UID shorter, there is already a method that handles this
	Tests.prototype.get_short_uid = function( uid ) {
		return uid;
	};

	// Because JS is weird with arrays and numbers... (facepalm)
	Tests.prototype.get_test_id_arraykey = function( test_id ) {
		return 't' + test_id;
	};

	Tests.prototype.initialize_test = function( test_id, uid, psid ) {
		var m_test_id = this.get_test_id_arraykey( test_id );

		if ( 'undefined' == typeof this.at[m_test_id] ) {
			this.at[m_test_id] = [];
		}

		var uid = this.get_short_uid( uid );
		this.at[m_test_id][uid] = {
			initialized: true,
			started: false,
			start_date: new Date,
			presenter: psid,
			timer_action: '',
			cq: ''
			};

		return this.at[m_test_id][uid];
	};

	Tests.prototype.test_exists = function( test_id, uid ) {
		var m_test_id = this.get_test_id_arraykey( test_id );

		if ( 'undefined' == typeof this.at[m_test_id] ) {
			return false;
		};

		if ( 'undefined' == typeof this.at[m_test_id][uid] ) {
			return false;
		};

		return true;
	};

	Tests.prototype.get_test = function( test_id, uid ) {
		if ( !this.test_exists( test_id, uid ) ) {
			return false;
		};

		var m_test_id = this.get_test_id_arraykey( test_id );

		return this.at[m_test_id][uid];
	};

	Tests.prototype.set_question = function( test_id, uid, question ) {
		if ( !this.test_exists( test_id, uid ) ) {
			return false;
		};

		var m_test_id = this.get_test_id_arraykey( test_id );

		this.at[m_test_id][uid].started = true;
		this.at[m_test_id][uid].cq = question;
		this.at[m_test_id][uid].q_date = new Date;

		return this.at[m_test_id][uid];
	};

	Tests.prototype.set_test_var = function( test_id, uid, key, value ) {
		if ( !this.test_exists( test_id, uid ) ) {
			return false;
		};

		var m_test_id = this.get_test_id_arraykey( test_id );

		this.at[m_test_id][uid][key] = value;

		return true;
	};

	Tests.prototype.remove_test_var = function( test_id, uid, key ) {
		if ( !this.test_exists( test_id, uid ) ) {
			return false;
		};

		var m_test_id = this.get_test_id_arraykey( test_id );

		return delete this.at[m_test_id][uid][key];
	};

	Tests.prototype.remove_test = function( test_id, uid ) {
		if ( !this.test_exists( test_id, uid ) ) {
			return true;
		};

		var m_test_id = this.get_test_id_arraykey( test_id );

		return delete this.at[m_test_id][uid];
	};

	return Tests;
})();

exports.setup = function()
{
	return new Tests;
}