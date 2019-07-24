<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace OSS\Result;

class UploadPartResult extends Result
{
	protected function parseDataFromResponse()
	{
		$header = $this->rawResponse->header;

		if (isset($header['etag'])) {
			return $header['etag'];
		}

		throw new \OSS\Core\OssException('cannot get ETag');
	}
}

?>
