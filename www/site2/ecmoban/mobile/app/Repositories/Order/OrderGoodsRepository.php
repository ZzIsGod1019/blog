<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Repositories\Order;

class OrderGoodsRepository implements \App\Contracts\Repositories\Order\OrderGoodsRepositoryInterface
{
	public function insertOrderGoods($goods, $orderId = 0)
	{
		foreach ($goods as $v) {
			if (empty($orderId)) {
				$newOrderId = $v['order_id'];
			}
			else {
				$newOrderId = $orderId;
			}

			$orderGoods = new \App\Models\OrderGoods();
			$orderGoods->order_id = $newOrderId;
			$orderGoods->goods_id = $v['goods_id'];
			$orderGoods->goods_name = $v['goods_name'];
			$orderGoods->goods_sn = $v['goods_sn'];
			$orderGoods->product_id = $v['product_id'];
			$orderGoods->goods_number = $v['goods_number'];
			$orderGoods->market_price = $v['market_price'];
			$orderGoods->goods_price = $v['goods_price'];
			$orderGoods->goods_attr = $v['goods_attr'];
			$orderGoods->is_real = $v['is_real'];
			$orderGoods->extension_code = $v['extension_code'];
			$orderGoods->parent_id = $v['parent_id'];
			$orderGoods->is_gift = $v['is_gift'];
			$orderGoods->ru_id = $v['ru_id'];
			$orderGoods->goods_attr_id = $v['goods_attr_id'];
			$orderGoods->save();
		}
	}

	public function orderGoodsByOidGid($oid, $gid)
	{
		$model = \App\Models\OrderGoods::where('order_id', $oid)->where('goods_id', $gid)->first();

		if ($model === null) {
			return array();
		}

		return $model->toArray();
	}
}

?>
