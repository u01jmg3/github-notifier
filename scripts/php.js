//https://raw.github.com/kvz/phpjs/master/functions/strings/str_pad.js
function str_pad(input, pad_length, pad_string, pad_type){
	var half = '',
	pad_to_go;

	var str_pad_repeater = function (s, len){
		var collect = '', i;

		while (collect.length < len) {
			collect += s;
		}
		collect = collect.substr(0, len);

		return collect;
	};

	input += '';
	pad_string = pad_string !== undefined ? pad_string : ' ';

	if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') {
		pad_type = 'STR_PAD_RIGHT';
	}

	if ((pad_to_go = pad_length - input.length) > 0) {
		if (pad_type == 'STR_PAD_LEFT') {
			input = str_pad_repeater(pad_string, pad_to_go) + input;
		} else if (pad_type == 'STR_PAD_RIGHT') {
			input = input + str_pad_repeater(pad_string, pad_to_go);
		} else if (pad_type == 'STR_PAD_BOTH') {
			half = str_pad_repeater(pad_string, Math.ceil(pad_to_go / 2));
			input = half + input + half;
			input = input.substr(0, pad_length);
		}
	}

	return input;
}

//https://raw.github.com/kvz/phpjs/master/functions/array/array_pad.js
function array_pad(input, pad_size, pad_value){
	var pad = [],
	newArray = [],
	newLength,
	diff = 0,
	i = 0;

	if (Object.prototype.toString.call(input) === '[object Array]' && !isNaN(pad_size)){
		newLength = ((pad_size < 0) ? (pad_size * -1) : pad_size);
		diff = newLength - input.length;

		if (diff > 0){
			for (i = 0; i < diff; i++){
				newArray[i] = pad_value;
			}
			pad = ((pad_size < 0) ? newArray.concat(input) : input.concat(newArray));
		} else {
			pad = input;
		}
	}

	return pad;
}

//https://raw.github.com/kvz/phpjs/master/functions/array/in_array.js
function in_array(needle, haystack, argStrict){
	var key = '',
	strict = !! argStrict;

	if(strict){
		for(key in haystack){
			if(haystack[key] === needle)
				return true;
		}
	} else {
		for(key in haystack){
			if (haystack[key] == needle)
				return true;
		}
	}

	return false;
}