<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Http\Proxy;

class ShippingProxy
{
	/**
     * @var Http
     */
	private $http;
	/**
     * @var string
     */
	private $queryExpressUrl = 'https://m.kuaidi100.com/query?type=%s&postid=%s';

	public function __construct()
	{
		$this->http = new \App\Extensions\Http();
	}

	public function getExpress($com = '', $num = '')
	{
		$url = sprintf($this->queryExpressUrl, $com, $num);
		$cache_id = 'express_' . md5($url);
		$result = S($cache_id);

		if ($result !== false) {
			return $result;
		}

		$respose = $this->http->doGet($url, 5, $this->defaultHeader());
		$result = json_decode($respose, true);

		if ($result['message'] === 'ok') {
			S($cache_id, $result, 600);
			return $result;
		}
		else {
			return false;
		}
	}

	private function defaultHeader()
	{
		$header = "User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.%d Safari/537.%d\r\n";
		$header .= "Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n";
		$header .= "Accept-language: zh-cn,zh;q=0.5\r\n";
		$header .= "Accept-Charset: GB2312,utf-8;q=0.7,*;q=0.7\r\n";
		$header = sprintf($header, time(), time() + rand(1000, 9999));
		return $header;
	}
}


?>
