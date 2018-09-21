<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Repositories\Goods;

class VolumePriceRepository implements \App\Contracts\Repositories\Goods\VolumePriceRepositoryInterface
{
	public function allVolumes($goods_id, $price_type)
	{
		$res = \App\Models\VolumePrice::where('goods_id', $goods_id)->where('price_type', $price_type)->orderBy('volume_number')->get()->toArray();
		return $res;
	}
}

?>
