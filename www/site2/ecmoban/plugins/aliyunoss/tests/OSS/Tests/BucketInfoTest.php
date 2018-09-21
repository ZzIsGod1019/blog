<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace OSS\Tests;

class BucketInfoTest extends \PHPUnit_Framework_TestCase
{
	public function testConstruct()
	{
		$bucketInfo = new \OSS\Model\BucketInfo('cn-beijing', 'name', 'today');
		$this->assertNotNull($bucketInfo);
		$this->assertEquals('cn-beijing', $bucketInfo->getLocation());
		$this->assertEquals('name', $bucketInfo->getName());
		$this->assertEquals('today', $bucketInfo->getCreateDate());
	}
}

?>
