<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Repositories\User;

class MemberPriceRepository implements \App\Contracts\Repositories\User\MemberPriceRepositoryInterface
{
	public function getMemberPriceByUid($rank, $goods_id)
	{
		$price = \App\Models\MemberPrice::where('user_rank', $rank)->where('goods_id', $goods_id)->pluck('user_price')->toArray();

		if (!empty($price)) {
			$price = $price[0];
		}

		return $price;
	}
}

?>
