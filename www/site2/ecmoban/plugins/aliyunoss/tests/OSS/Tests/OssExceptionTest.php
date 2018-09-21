<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace OSS\Tests;

class OssExceptionTest extends \PHPUnit_Framework_TestCase
{
	public function testOSS_exception()
	{
		try {
			throw new \OSS\Core\OssException('ERR');
			$this->assertTrue(false);
		}
		catch (\OSS\Core\OssException $e) {
			$this->assertNotNull($e);
			$this->assertEquals($e->getMessage(), 'ERR');
		}
	}
}

?>
