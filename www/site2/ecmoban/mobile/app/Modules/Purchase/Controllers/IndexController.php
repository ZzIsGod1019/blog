<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Modules\Purchase\Controllers;

class IndexController extends \App\Modules\Base\Controllers\FrontendController
{
	public function actionIndex()
	{
		$this->assign('title', '批发首页');
		$this->assign('action', 'index');
		$wholesale_cat = \App\Modules\Purchase\Models\Purchase::get_wholesale_child_cat();
		$this->assign('wholesale_cat', $wholesale_cat);
		$wholesale_limit = \App\Modules\Purchase\Models\Purchase::get_wholesale_limit();
		$this->assign('wholesale_limit', $wholesale_limit);
		$goodsList = \App\Modules\Purchase\Models\Purchase::get_wholesale_cat();
		$this->assign('get_wholesale_cat', $goodsList);
		$this->display();
	}

	public function actionList()
	{
		$this->assign('title', '批发列表');
		$this->assign('action', 'list');
		$this->display();
	}

	public function actionGoods()
	{
		$this->assign('title', '批发详情');
		$this->assign('action', 'goods');
		$this->display();
	}

	public function actionCart()
	{
		$this->assign('title', '进货单');
		$this->assign('action', 'cart');
		$this->display();
	}

	public function actionInfo()
	{
		$this->assign('title', '批发首页');
		$this->assign('action', 'info');
		$result = array();
		$this->ajaxReturn($result);
	}

	public function actionShow()
	{
		$this->assign('title', '求购信息');
		$this->assign('action', 'show');
		$this->display();
	}
}

?>
