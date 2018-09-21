<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace OSS\Result;

class AclResult extends Result
{
	protected function parseDataFromResponse()
	{
		$content = $this->rawResponse->body;

		if (empty($content)) {
			throw new \OSS\Core\OssException('body is null');
		}

		$xml = simplexml_load_string($content);

		if (isset($xml->AccessControlList->Grant)) {
			return strval($xml->AccessControlList->Grant);
		}
		else {
			throw new \OSS\Core\OssException('xml format exception');
		}
	}
}

?>
