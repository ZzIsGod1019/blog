<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Modules\Api\Controllers\V2;

class BrandController extends \App\Modules\Api\Foundation\Controller
{
	/** @var  $brand */
	protected $brand;
	/** @var $brandTransformer */
	protected $brandTransformer;

	public function __construct(\App\Repositories\Brand\BrandRepository $brand, \App\Modules\Api\Transformers\BrandTransformer $brandTransformer)
	{
		parent::__construct();
		$this->brand = $brand;
		$this->brandTransformer = $brandTransformer;
	}

	public function actionList()
	{
		$data = $this->brand->getAllBrands();
		$this->apiReturn($data);
	}

	public function actionGet($id)
	{
		$data = $this->brand->getBrandDetail($id);
		$this->apiReturn($data);
	}
}

?>
