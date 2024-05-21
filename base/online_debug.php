<?php

function _getClientIp() {
	if (getenv('HTTP_CLIENT_IP')) {
		$ip = getenv('HTTP_CLIENT_IP');
	}
	if (getenv('HTTP_X_REAL_IP')) {
		$ip = getenv('HTTP_X_REAL_IP');
	} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
		$ips = explode(',', $ip);
		$ip = $ips[0];
	} elseif (getenv('REMOTE_ADDR')) {
		$ip = getenv('REMOTE_ADDR');
	} else {
		$ip = '0.0.0.0';
	}

	return $ip;
}
$user_ip = _getClientIp();
if (ONLOAD_DEBUG && ONLOAD_DEBUG['debug']) {
	$secret = ONLOAD_DEBUG['secret'] ?? '';
	$wl = ONLOAD_DEBUG['wl'] ?? [];
	function _errror_page()
	{
		exit('<div style="text-align: center;margin-top: 120px">
<h1>很抱歉, 系统正在维护中! 请稍后再来</h1>
<h3>Sorry, the system is under maintenance! Please come back later</h3>
<p>' . date('Y/m/d H:i', time()) . '</p>
</div>');
	}
	$user_secret = $_SERVER['HTTP_SECRET'] ?? '';
	if (in_array($user_ip, $wl) || $user_secret == $secret) {
	} else {
		_errror_page();
	}
}
if (in_array($user_ip, BLACKLIST)) {
	function __errror_page()
	{
		exit('<div style="text-align: center;margin-top: 120px">
<h1>很抱歉, 系统繁忙! 请稍后再试</h1>
<h3>Sorry, the system is busy! Please try again later</h3>
<p>' . date('Y/m/d H:i', time()) . '</p>
</div>');
	}
	__errror_page();

}


if (APP_DEBUG) {
	ini_set("opcache.enable", 0);
}