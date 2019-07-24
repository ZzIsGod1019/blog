<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace OSS\Result;

class InitiateMultipartUploadResult extends Result
{
	protected function parseDataFromResponse()
	{
		$content = $this->rawResponse->body;
		$xml = simplexml_load_string($content);

		if (isset($xml->UploadId)) {
			return strval($xml->UploadId);
		}

		throw new \OSS\Core\OssException('cannot get UploadId');
	}
}

?>
