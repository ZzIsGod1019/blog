<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Modules\Api\Controllers;

class RegionController extends \App\Modules\Api\Foundation\Controller
{
	private $authService;
	private $regionService;

	public function __construct(\App\Services\AuthService $authService, \App\Services\RegionService $regionService)
	{
		$this->authService = $authService;
		$this->regionService = $regionService;
	}

	public function regionList(Request $request)
	{
		$this->validate($request, array('id' => 'integer'));
		$args = $request->all();
		$list = $this->regionService->regionList($args);
		return $this->apiReturn($list);
	}
}

?>
