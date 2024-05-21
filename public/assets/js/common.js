// 自定义常用方法
const _utils = {
	// 随机数
	random: (min, max) => {
		return Math.floor(Math.random() * (max - min + 1) + min)
	},
	/**
	 * 去除字符串前后空格
	 * @param str  <String> 字符串
	 * @param pos <String> 去除那些位置的空格，可选为：both-默认值，去除两端空格，left-去除左边空格，right-去除右边空格，all-去除包括中间和两端的所有空格
	 * @returns {*}
	 */
	trim: (str, pos = 'both') => {
		if (pos == 'both') {
			return str.replace(/^\s+|\s+$/g, "");
		} else if (pos == "left") {
			return str.replace(/^\s*/, '');
		} else if (pos == 'right') {
			return str.replace(/(\s*$)/g, "");
		} else if (pos == 'all') {
			return str.replace(/\s+/g, "");
		} else {
			return str;
		}
	},

}


const _yz = {
	// 判断变量是否为空 (变量, 提示信息)
	empty: (value, msg = undefined) => {
		switch (typeof value) {
			case 'undefined':
				return true;
			case 'string':
				if (value.replace(/(^[ \t\n\r]*)|([ \t\n\r]*$)/g, '').length == 0) return true;
				break;
			case 'boolean':
				if (!value) return true;
				break;
			case 'number':
				if (0 === value || isNaN(value)) return true;
				break;
			case 'object':
				if (null === value || value.length === 0) return true;
				for (var i in value) {
					return false;
				}
				return true;
		}

		if (msg) {
			_uni.toast(msg)
		}
		return false;
	},
	// 是否为数组
	is_array: (value) => {
		if (typeof Array.isArray === "function") {
			return Array.isArray(value);
		} else {
			return Object.prototype.toString.call(value) === "[object Array]";
		}
	},
	// 是否为json字符串
	is_jsonstr: (value) =>{
		if (typeof value == 'string') {
			try {
				var obj = JSON.parse(value);
				if (typeof obj == 'object' && obj) {
					return true;
				} else {
					return false;
				}
			} catch (e) {
				return false;
			}
		}
		return false;
	},
	// 是否为对象
	is_obj: (value) => {
		return Object.prototype.toString.call(value) === '[object Object]';
	},
	// 是否为身份证
	is_idcard: (value) => {
		return /^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X)$/.test(
			value)
	},
	// 是否为网址
	is_url: (value) => {
		return /http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w-.\/?%&=]*)?/.test(value)
	},
	// 是否为手机号
	is_mobile: (value) => {
		return /^1[23456789]\d{9}$/.test(value)
	},
	is_email: (value) => {
		return /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/.test(value);
	}

}


