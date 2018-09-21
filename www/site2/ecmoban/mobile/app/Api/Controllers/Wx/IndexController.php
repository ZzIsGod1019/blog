<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Api\Controllers\Wx;

class IndexController extends \App\Api\Controllers\Controller
{
	/** @var IndexService  */
	private $indexService;

	public function __construct(\App\Services\IndexService $indexService)
	{
		$this->indexService = $indexService;
	}

	public function index()
	{
		$banners = $this->indexService->getBanners();
		$data['banner'] = $banners;
		$adsense = $this->indexService->getAdsense();
		$data['adsense'] = $adsense;
		$goodsList = $this->indexService->bestGoodsList();
		$data['goods_list'] = $goodsList;
		return $this->apiReturn($data);
	}
}

?>
