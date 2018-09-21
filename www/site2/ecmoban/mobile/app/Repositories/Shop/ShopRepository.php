<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Repositories\Shop;

class ShopRepository implements \App\Contracts\Repositories\Shop\ShopRepositoryInterface
{
	public function get($id)
	{
		return $this->findBY('id', $id);
	}

	public function findBY($key, $val)
	{
		$list = \App\Models\SellerShopinfo::select('ru_id', 'shop_name', 'shop_logo', 'shopname_audit')->with(array('MerchantsShopInformation' => function($query) {
			$query->select('shoprz_brandName', 'user_id', 'shopNameSuffix', 'rz_shopName');
		}))->where($key, $val)->get()->toArray();

		if (empty($list)) {
			$list = array();
			return $list;
		}

		foreach ($list as $k => $v) {
			$list[$k]['brandName'] = $v['merchants_shop_information']['shoprz_brandName'];
			$list[$k]['shopNameSuffix'] = $v['merchants_shop_information']['shopNameSuffix'];
			$list[$k]['rz_shopName'] = $v['merchants_shop_information']['rz_shopName'];
			unset($list[$k]['merchants_shop_information']);
		}

		return $list;
	}

	public function getPositions($tc_type = 'weapp', $num = 3)
	{
		$time = local_gettime();
		$ads = \App\Models\TouchAd::select('ad_id', 'touch_ad.position_id', 'media_type', 'ad_link', 'ad_code', 'ad_name')->with(array('position'))->join('touch_ad_position', 'touch_ad_position.position_id', '=', 'touch_ad.position_id')->where('start_time', '<=', $time)->where('end_time', '>=', $time)->where('enabled', 1);

		if (gettype($tc_type) == 'string') {
			$ads->where('touch_ad_position.tc_type', $tc_type);
		}
		else {
			$ads->where('touch_ad_position.position_id', $tc_type);
		}

		$res = $ads->orderby('ad_id', 'desc')->limit($num)->get()->toArray();
		$res = array_map(function($v) {
			if (!empty($v['position'])) {
				$temp = array_merge($v, $v['position']);
				unset($temp['position']);
				return $temp;
			}
		}, $res);
		return $res;
	}

	public function get_select_find_in_set($is_db = 0, $select_id, $select_array = array(), $where = '', $table = '', $id = '', $replace = '')
	{
		if ($replace) {
			$replace = 'REPLACE (' . $id . ',\'' . $replace . '\',\',\')';
		}
		else {
			$replace = $id;
		}

		if ($select_array && is_array($select_array)) {
			$select = implode(',', $select_array);
		}
		else {
			$select = '*';
		}

		$prefix = \Illuminate\Support\Facades\Config::get('database.connections.mysql.prefix');
		$sql = 'SELECT ' . $select . ' FROM ' . $prefix . $table . ' WHERE find_in_set(\'' . $select_id . '\', ' . $replace . ') ' . $where;

		if ($is_db == 1) {
			return \Illuminate\Support\Facades\DB::select($sql);
		}
		else if ($is_db == 2) {
			$res = \Illuminate\Support\Facades\DB::select($sql);
			return isset($res[0]) ? json_decode(json_encode($res[0]), 1) : array();
		}
		else {
			$sql = trim($sql . ' LIMIT 1');
			$res = \Illuminate\Support\Facades\DB::select($sql);

			if ($res !== false) {
				$row = isset($res[0]) ? json_decode(json_encode($res[0]), 1) : array();

				if ($row !== false) {
					return reset($row);
				}
				else {
					return '';
				}
			}
			else {
				return array();
			}
		}
	}
}

?>
