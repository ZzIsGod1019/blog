<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Api\Controllers\Wx;

class GoodsController extends \App\Api\Controllers\Controller
{
	private $goodsService;
	private $authService;

	public function __construct(\App\Services\GoodsService $goodsService, \App\Services\AuthService $authService)
	{
		$this->goodsService = $goodsService;
		$this->authService = $authService;
	}

	public function goodsList(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('page' => 'required|int', 'warehouse_id' => 'required|integer', 'area_id' => 'required|integer'));
		$list = $this->goodsService->getGoodsList($request->get('id'), $request->get('keyword'), $request->get('page'), $request->get('per_page'), $request->get('sort_key'), $request->get('sort_value'), $request->get('warehouse_id'), $request->get('area_id'));
		return $this->apiReturn($list);
	}

	public function goodsDetail(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('id' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			$uid = 0;
		}

		$list = $this->goodsService->goodsDetail($request->get('id'), $uid);
		return $this->apiReturn($list, $list['error']);
	}

	public function property(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('id' => 'required|integer', 'num' => 'required|integer', 'warehouse_id' => 'required|integer', 'area_id' => 'required|integer'));
		$price = $this->goodsService->goodsPropertiesPrice($request->get('id'), $request->get('attr_id'), $request->get('num'), $request->get('warehouse_id'), $request->get('area_id'));
		return $this->apiReturn($price);
	}

	public function Share(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('id' => 'required|integer', 'path' => 'required|string'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		$share = $this->goodsService->goodsShare($request->get('id'), $uid, $request->get('path'));
		return $this->apiReturn($share);
	}

	public function Coupons(\Illuminate\Http\Request $request)
	{
		$this->validate($request, array('cou_id' => 'required|integer'));
		$uid = $this->authService->authorization();
		if (isset($uid['error']) && 0 < $uid['error']) {
			return $this->apiReturn($uid, 1);
		}

		$result = $this->goodsService->getCoupon($request->get('cou_id'), $uid);
		return $this->apiReturn($result);
	}
}

?>
