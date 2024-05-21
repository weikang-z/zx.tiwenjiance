<?php

namespace app\common\library\wechat;


use app\common\library\Curl;
use think\Env;

class Miniapp
{


	private static function getAccessToken()
	{
		$cache_key = 'miniapp_accesstoken';
		if (cache($cache_key)) {
			return cache($cache_key);
		} else {
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . WX_MINI_APPID . '&secret=' . WX_MINI_APPSECRET;
			$curl = new Curl();
			$ret = $curl->get($url, 'json');
			if (isset($ret['access_token'])) {
				cache($cache_key, $ret['access_token'], 7000);
				return $ret['access_token'];
			} else {
				return NULL;
			}
		}
	}

	public static function getopenid(string $code = NULL)
	{
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . WX_MINI_APPID . '&secret=' . WX_MINI_APPSECRET . '&js_code=' . $code . '&grant_type=authorization_code';
		try {
			$res = json_decode(file_get_contents($url), 1);
			if (!isset($res['openid'])) {
				throw new \Exception('');
			}
			return [TRUE, $res];
		} catch (\Exception $e) {
			return [FALSE, '获取授权失败, 请联系管理员'];
		}
	}


	public static function getMobileV2(string $code): array
	{

		$url  = sprintf("https://api.weixin.qq.com/wxa/business/getuserphonenumber?access_token=%s", self::getAccessToken());
		$ret = (new Curl)->setParams(json_encode(['code' => $code]))->post($url, 'json');
		if ($ret['errcode'] === 0) {
			return [true, $ret['phone_info']['purePhoneNumber']];
		} else {
			return [false, ""];
		}
	}


	public static function getMobile(string $session_key, array $encryptedData)
	{
		$pc = new WXBizDataCrypt(WX_MINI_APPID, $session_key);
		$data = "";
		$errCode = $pc->decryptData($encryptedData['encryptedData'], $encryptedData['iv'], $data);
		if ($errCode <> 0) {
			return [FALSE, '授权手机号失败, 请重试!'];
		} else {
			$mobile = json_decode($data, 1)['phoneNumber'];
		}
		if (!$mobile) {
			return [FALSE, '请授权手机号'];
		}
		return [TRUE, $mobile];
	}

	/**
	 * 小程序发送订阅消息
	 *
	 * @param string      $template_id
	 * @param string      $openid
	 * @param string|null $page              点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
	 * @param array       $data              模板内容，格式形如 { "key1": { "value": any }, "key2": { "value": any } }
	 * @param string      $miniprogram_state 跳转小程序类型：developer为开发版；trial为体验版；formal为正式版；默认为正式版
	 */
	public static function sendTemplateMessage(
		string $template_id,
		string $openid,
		array  $data,
		string $page = NULL,
		string $miniprogram_state = 'formal'
	) {
		$url = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . self::getAccessToken();
		$param = [
			'touser' => $openid,
			'template_id' => $template_id,
			'miniprogram_state' => $miniprogram_state,
			'data' => $data
		];
		if ($page) {
			$param['page'] = $page;
		}
		$ret = (new Curl())->setParams(json_encode($param, 256))->post($url, 'json');
		file_put_contents(ROOT_PATH . 'miniapp_template_log.log', datetime(time()) . json_encode($ret) . "\n\n", FILE_APPEND);
		//			d($ret);
		// 不处理返回了 爱发不发
	}

	/**
	 * 获取小程序 scheme 码
	 * 适用于短信、邮件、外部网页、微信内等拉起小程序的业务场景。
	 * 文档地址: https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/url-scheme/urlscheme.generate.html
	 */
	static function urlscheme()
	{
		$url = 'https://api.weixin.qq.com/wxa/generatescheme?access_token=' . self::getAccessToken();
		$param = [
			'jump_wxa' => [
				'path' => 'pages/index/index',
				'query' => 'code=' . $code,
				'env_version' => 'trial'
			]
		];
		$ret = (new Curl())->setParams(json_encode($param, 256))->post($url, 'json');
		if ($ret['errmsg'] == 'ok') {
			return [TRUE, $ret['openlink']];
		} else {
			return [FALSE, ''];
		}
	}

	/**
	 * 获取小程序码，适用于需要的码数量极多的业务场景。
	 * 文档地址: https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
	 */
	/**
	 * 获取小程序码，适用于需要的码数量极多的业务场景。
	 * 文档地址: https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html
	 * 要打开的小程序版本。正式版为 release，体验版为 trial，开发版为 develop
	 */
	static function getUnlimited($scene = '', $save_path, $page = '/pages/index/index', $env_version = 'release')
	{
		$url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . self::getAccessToken();
		$param = [
			'page' => $page,
			'scene' => $scene,
			'env_version' => $env_version,
			'is_hyaline' => TRUE
		];
		$ret = (new Curl())->setParams(json_encode($param, 256))->post($url);
		file_put_contents($save_path, $ret);
	}


	/**
	 * 获取小程序 URL Link，适用于短信、邮件、网页、微信内等拉起小程序的业务场景。
	 * 文档地址: https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/url-link/urllink.generate.html
	 */
	static function generate()
	{
	}
}
