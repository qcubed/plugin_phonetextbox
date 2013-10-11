$.widget( "qcubed.phoneTextBox",  {
	options: {
		defaultAreaCode: null
	},
	_create: function() {
		this._on( this.element, {
			focus: function() {
				setDefault();
			},
			blur: function( event ) {
				checkChanged();
			}
		});
	},
	setDefault: function () {
		var input = this.element;

		if (input.value == '' && this.defaultAreaCode && this.defaultAreaCode != '') {
			input.value = '(' + defaultAreaCode + ') ';
			if (input.setSelectionRange) {
				input.focus();
				setTimeout(function() { input.setSelectionRange(input.value.length, input.value.length) }, 0);
			}
			else if (input.createTextRange) {
				var range = input.createTextRange();
				range.collapse(true);
				range.moveEnd('character', input.value.length);
				range.moveStart('character', input.value.length);
				range.select();
			}
		}
	},
	
	setDefault: checkChanged() {
		var el = this.element;

		if (el.value == '(' + this.defaultAreaCode + ') ') {
			el.value = '';
		}
		var str = el.value.replace(/[\(\)\-\ ]/g,'');
	
		if (str.length == 7) {
			str = "(" + this.defaultAreaCode + ") " + str.substr(0,3) + "-" +	str.substr(3,4);
			el.value = str;			
		}
		else if (str.length > 7 && str.substr(7,1) == 'x') {
			str = "(" + this.defaultAreaCode + ") " + str.substr(0,3) + "-" + str.substr(3,4) + " x" + str.substr (8);
			el.value =str;
		}
		else if (str.length == 10) {
			str = "(" + str.substr(0,3) + ") " + str.substr(3,3) + "-" +	str.substr(6,4);
			el.value = str;	
		}
		else if (str.length > 10 && str.substr(10,1) == 'x') {
			str = "(" + str.substr(0,3) + ") " + str.substr(3,3) + "-" + str.substr(6,4) + " x" + str.substr (11);
			el.value = str;
		}
	}		
});