<?php

	namespace app\common\library;


	/**
	 * Author: ekr123
	 * Date: 2021/11/23
	 * Time: 11:18 上午
	 * Mail: zwk480314826@163.com
	 */
	class Curl
	{
		private $ch = NULL; // curl handle


		private $headers = []; // request header


		private $proxy = NULL; // http proxy


		private $timeout = 5; // connnect timeout


		private $httpParams = NULL;


		public function __construct()
		{
			$this->ch = curl_init();
		}


		/**
		 * 设置http header.
		 *
		 * @param $header
		 *
		 * @return $this
		 */
		public function setHeader($header)
		{
			if (is_array($header)) {
				curl_setopt($this->ch, CURLOPT_HTTPHEADER, $header);
			}


			return $this;
		}


		/**
		 * 设置http 超时.
		 *
		 * @param int $time
		 *
		 * @return $this
		 */
		public function setTimeout($time)
		{
			// 不能小于等于0


			if ($time <= 0) {
				$time = 5;
			}
			//只需要设置一个秒的数量就可以
			curl_setopt($this->ch, CURLOPT_TIMEOUT, $time);


			return $this;
		}


		/**
		 * 设置http 代理.
		 *
		 * @param string $proxy
		 *
		 * @return $this
		 */
		public function setProxy($proxy)
		{
			if ($proxy) {
				curl_setopt($this->ch, CURLOPT_PROXY, $proxy);
			}


			return $this;
		}


		/**
		 * 设置http 代理端口.
		 *
		 * @param int $port
		 *
		 * @return $this
		 */
		public function setProxyPort($port)
		{
			if (is_int($port)) {
				curl_setopt($this->ch, CURLOPT_PROXYPORT, $port);
			}


			return $this;
		}


		/**
		 * 设置来源页面.
		 *
		 * @param string $referer
		 *
		 * @return $this
		 */
		public function setReferer($referer = '')
		{
			if (!empty($referer)) {
				curl_setopt($this->ch, CURLOPT_REFERER, $referer);
			}


			return $this;
		}


		/**
		 * 设置用户代理.
		 *
		 * @param string $agent
		 *
		 * @return $this
		 */
		public function setUserAgent($agent = '')
		{
			if ($agent) {
				// 模拟用户使用的浏览器


				curl_setopt($this->ch, CURLOPT_USERAGENT, $agent);
			}


			return $this;
		}


		/**
		 * http响应中是否显示header，1表示显示.
		 *
		 * @param $show
		 *
		 * @return $this
		 */
		public function showResponseHeader($show)
		{
			curl_setopt($this->ch, CURLOPT_HEADER, $show);


			return $this;
		}


		/**
		 * 设置http请求的参数,get或post.
		 *
		 * @param array|string $params
		 *
		 * @return $this
		 */
		public function setParams($params)
		{
			$this->httpParams = $params;


			return $this;
		}


		/**
		 * 设置证书路径.
		 *
		 * @param $file
		 */
		public function setCainfo($file)
		{
			curl_setopt($this->ch, CURLOPT_CAINFO, $file);
		}


		/**
		 * 模拟GET请求
		 *
		 * @param string $url
		 * @param string $dataType
		 *
		 * @return bool|mixed
		 */
		public function get($url, $dataType = 'text')
		{
			if (FALSE !== stripos($url, 'https://')) {
				curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);


				curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);


				curl_setopt($this->ch, CURLOPT_SSLVERSION, 1);
			}
			// 设置get参数


			if (!empty($this->httpParams) && is_array($this->httpParams)) {
				if (FALSE !== strpos($url, '?')) {
					$url .= http_build_query($this->httpParams);
				} else {
					$url .= '?' . http_build_query($this->httpParams);
				}
			}
			// end 设置get参数


			curl_setopt($this->ch, CURLOPT_URL, $url);


			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);


			$content = curl_exec($this->ch);


			$status = curl_getinfo($this->ch);


			curl_close($this->ch);
			if (isset($status['http_code']) && 200 == $status['http_code']) {
				if ('json' == $dataType) {
					$content = json_decode($content, TRUE);
				}


				return $content;
			} else {
				return FALSE;
			}
		}


		/**
		 * 异步请求 无需拿返回值
		 */
		public function sock_data($url)
		{
			$host = parse_url($url, PHP_URL_HOST);

			$port = parse_url($url, PHP_URL_PORT);

			$port = $port ? $port : 80;

			$scheme = parse_url($url, PHP_URL_SCHEME);
			$path = parse_url($url, PHP_URL_PATH);
			$query = parse_url($url, PHP_URL_QUERY);
			if ($query) {
				$path .= '?' . $query;
			}
			if ('https' == $scheme) {
				$host = 'ssl://' . $host;
			}
			$fp = fsockopen($host, $port, $error_code, $error_msg, 1);
			if (!$fp) {
				return ['error_code' => $error_code, 'error_msg' => $error_msg];
			} else {
				stream_set_blocking($fp, 0); //开启了非阻塞模式
				stream_set_timeout($fp, 1); //设置超时
				$header = "GET $path HTTP/1.1\r\n";
				$header .= "Host: $host\r\n";
				$header .= "Connection: close\r\n\r\n"; //长连接关闭
				fwrite($fp, $header);
				usleep(1000); // 如果没有这延时，可能在nginx服务器上就无法执行成功
				fclose($fp);
				return ['error_code' => 0];
			}
		}


		/**
		 * 模拟POST请求
		 *
		 * @param string $url
		 * @param array  $fields
		 * @param string $dataType
		 *
		 * @return mixed
		 *
		 * HttpCurl::post('http://api.example.com/?a=123', array('abc'=>'123', 'efg'=>'567'), 'json');
		 * HttpCurl::post('http://api.example.com/', '这是post原始内容', 'json');
		 * 文件post上传
		 * HttpCurl::post('http://api.example.com/', array('abc'=>'123', 'file1'=>'@/data/1.jpg'), 'json');
		 */
		public function post($url, $dataType = 'text')
		{
			if (FALSE !== stripos($url, 'https://')) {
				curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);


				curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);


				curl_setopt($this->ch, CURLOPT_SSLVERSION, 1);
			}
			curl_setopt($this->ch, CURLOPT_URL, $url);


			// 设置post body


			if (!empty($this->httpParams)) {
				if (is_array($this->httpParams)) {
					curl_setopt($this->ch, CURLOPT_POSTFIELDS, http_build_query($this->httpParams));
				} elseif (is_string($this->httpParams)) {
					curl_setopt($this->ch, CURLOPT_POSTFIELDS, $this->httpParams);
				}
			}
			// end 设置post body


			curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);


			curl_setopt($this->ch, CURLOPT_POST, TRUE);


			$content = curl_exec($this->ch);

            dd($content);


			$status = curl_getinfo($this->ch);


			curl_close($this->ch);
			if (isset($status['http_code']) && 200 == $status['http_code']) {
				if ('json' == $dataType) {
					$content = json_decode($content, TRUE);
				}


				return $content;
			} else {
				return FALSE;
			}
		}
	}

