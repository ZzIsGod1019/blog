<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Repositories\Share;

class ShareRepository implements \App\Contracts\Repositories\Share\ShareRepositoryInterface
{
	protected $share;

	public function token($app, $secret)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $app . '&secret=' . $secret;
		$token = file_get_contents($url);
		return $token;
	}
}

?>
