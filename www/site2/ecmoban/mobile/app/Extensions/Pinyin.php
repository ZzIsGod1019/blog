<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Extensions;

class Pinyin
{
	public function output($str, $utf8 = true)
	{
		$pinyin = new \Overtrue\Pinyin\Pinyin();
		return $pinyin->convert($str);
	}
}


?>
