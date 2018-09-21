<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Notifications;

abstract class Notification
{
	protected $via = array();

	public function send()
	{
		foreach ($this->via as $via) {
		}
	}
}


?>
