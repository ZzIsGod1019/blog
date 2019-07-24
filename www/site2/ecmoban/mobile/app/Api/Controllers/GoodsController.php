<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Api\Controllers;

class GoodsController extends \App\Api\Foundation\Controller
{
	/** @var  $goodsService */
	protected $goodsService;
	/** @var  $goodsTransport */
	protected $goodsTransport;

	public function __construct(\App\Services\GoodsService $goodsService, \App\Models\GoodsTransport $goodsTransport)
	{
		parent::__construct();
		$this->goodsService = $goodsService;
		$this->goodsTransport = $goodsTransport;
	}

	public function actionList()
	{
	}

	public function actionDetail()
	{
	}

	public function actionSku()
	{
	}

	public function actionFittings()
	{
	}
}

?>
