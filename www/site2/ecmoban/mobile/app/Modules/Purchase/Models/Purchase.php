<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Modules\Purchase\Models;

class Purchase extends \Think\Model
{
	static public function get_wholesale_child_cat($cat_id = 0, $type = 0)
	{
		if (0 < $cat_id) {
			$parent_id = \App\Models\WholesaleCat::select('parent_id')->where('cat_id', $cat_id)->limit(1)->first();

			if ($parent_id != array()) {
				$parent_id = $parent_id->toArray();
				$parent_id = $parent_id['parent_id'];
			}
		}
		else {
			$parent_id = 0;
		}

		$cat_id = \App\Models\WholesaleCat::select('cat_id')->where('parent_id', $parent_id)->where('is_show', 1)->limit(1)->first();

		if ($cat_id != array()) {
			$cat_id = $cat_id->toArray();
			$cat_id = $cat_id['cat_id'];
		}

		if (!empty($cat_id) || $parent_id == 0) {
			$res = \App\Models\WholesaleCat::select('cat_id', 'cat_name', 'parent_id', 'is_show', 'style_icon')->where('parent_id', $parent_id)->where('is_show', 1)->orderby('sort_order', 'ASC')->orderby('cat_id', 'ASC')->get()->toArray();
			$cat_arr = array();

			foreach ($res as $row) {
				$cat_arr[$row['cat_id']]['id'] = $row['cat_id'];
				$cat_arr[$row['cat_id']]['name'] = $row['cat_name'];
				$cat_arr[$row['cat_id']]['style_icon'] = $row['style_icon'];
				$cat_arr[$row['cat_id']]['url'] = url('purchase/index/list', array('id' => $row['cat_id']));

				if (isset($row['cat_id']) != NULL) {
					$cat_arr[$row['cat_id']]['cat_id'] = self::get_wholesale_child_tree($row['cat_id']);
				}
			}
		}

		return $cat_arr;
	}

	static private function get_wholesale_child_tree($tree_id = 0, $ru_id = 0)
	{
		$three_arr = array();
		$res = \App\Models\WholesaleCat::where('parent_id', $tree_id)->where('is_show', 1)->count();
		if (!empty($res) || $tree_id == 0) {
			$res = \App\Models\WholesaleCat::select('cat_id', 'cat_name', 'parent_id', 'is_show')->where('parent_id', $tree_id)->where('is_show', 1)->orderby('sort_order', 'ASC')->orderby('cat_id', 'ASC')->get()->toArray();

			foreach ($res as $row) {
				if ($row['is_show']) {
					$three_arr[$row['cat_id']]['id'] = $row['cat_id'];
				}

				$three_arr[$row['cat_id']]['name'] = $row['cat_name'];

				if ($ru_id) {
					$build_uri = array('cid' => $row['cat_id'], 'urid' => $ru_id, 'append' => $row['cat_name']);
					$domain_url = get_seller_domain_url($ru_id, $build_uri);
					$three_arr[$row['cat_id']]['url'] = $domain_url['domain_name'];
				}
				else {
					$three_arr[$row['cat_id']]['url'] = url('purchase/index/list', array('id' => $row['cat_id']));
				}

				if (isset($row['cat_id']) != NULL) {
					$three_arr[$row['cat_id']]['cat_id'] = self::get_wholesale_child_tree($row['cat_id']);
				}
			}
		}

		return $three_arr;
	}

	static public function get_wholesale_limit()
	{
		$now = gmtime();
		$sql = 'SELECT w.*, g.goods_name, g.goods_thumb, g.goods_img, MIN(wvp.volume_number) AS volume_number, MAX(wvp.volume_price) AS volume_price, g.goods_unit FROM ' . $GLOBALS['ecs']->table('wholesale') . ' AS w' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON w.goods_id = g.goods_id ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('wholesale_volume_price') . ' AS wvp ON wvp.goods_id = g.goods_id ' . (' WHERE w.enabled = 1 AND w.review_status = 3 AND w.is_promote = 1 AND \'' . $now . '\' BETWEEN w.start_time AND w.end_time GROUP BY goods_id');
		$res = $GLOBALS['db']->getAll($sql);

		foreach ($res as $key => $row) {
			$res[$key]['formated_end_date'] = local_date($GLOBALS['_CFG']['date_format'], $row['end_time']);
			$res[$key]['small_time'] = $row['end_time'] - $now;
			$res[$key]['goods_name'] = $row['goods_name'];
			$res[$key]['goods_price'] = $row['goods_price'];
			$res[$key]['moq'] = $row['moq'];
			$res[$key]['volume_number'] = $row['volume_number'];
			$res[$key]['volume_price'] = $row['volume_price'];
			$res[$key]['goods_unit'] = $row['goods_unit'];
			$res[$key]['thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
			$res[$key]['goods_img'] = get_image_path($row['goods_id'], $row['goods_img']);
			$res[$key]['url'] = url('wholesale/index/goods', array('id' => $row['act_id']));
		}

		return $res;
	}

	static public function get_wholesale_cat()
	{
		$sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('wholesale_cat') . 'WHERE parent_id = 0 ORDER BY sort_order ASC ';
		$cat_res = $GLOBALS['db']->getAll($sql);

		foreach ($cat_res as $key => $row) {
			$cat_res[$key]['goods'] = self::get_business_goods($row['cat_id']);
			$cat_res[$key]['count_goods'] = count(self::get_business_goods($row['cat_id']));
			$cat_res[$key]['cat_url'] = url('wholesale/index/list', array('id' => $row['cat_id']));
		}

		return $cat_res;
	}

	static public function get_business_goods($cat_id)
	{
		$table = 'wholesale_cat';
		$type = 4;
		$children = get_children($cat_id, $type, 0, $table);
		$sql = 'SELECT w.*, g.goods_thumb, g.goods_img, MIN(wvp.volume_number) AS volume_number, MAX(wvp.volume_price) AS volume_price, g.goods_unit FROM ' . $GLOBALS['ecs']->table('wholesale') . ' AS w ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('goods') . ' AS g ON w.goods_id = g.goods_id ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('wholesale_volume_price') . ' AS wvp ON wvp.goods_id = g.goods_id ' . (' WHERE (' . $children . ' OR ') . self::get_wholesale_extension_goods($children, 'w.') . ') AND w.enabled = 1 AND w.review_status = 3 GROUP BY goods_id';
		$res = $GLOBALS['db']->getAll($sql);

		foreach ($res as $key => $row) {
			$res[$key]['goods_extend'] = self::get_wholesale_extend($row['goods_id']);
			$res[$key]['goods_sale'] = self::get_sale($row['goods_id']);
			$res[$key]['goods_price'] = $row['goods_price'];
			$res[$key]['moq'] = $row['moq'];
			$res[$key]['volume_number'] = $row['volume_number'];
			$res[$key]['volume_price'] = $row['volume_price'];
			$res[$key]['goods_unit'] = $row['goods_unit'];
			$res[$key]['goods_name'] = $row['goods_name'];
			$res[$key]['thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
			$res[$key]['goods_img'] = get_image_path($row['goods_id'], $row['goods_img']);
			$res[$key]['url'] = url('wholesale_goods', array('aid' => $row['act_id']), $row['goods_name']);
		}

		return $res;
	}

	static public function get_wholesale_extend($goods_id)
	{
		$extend_sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('wholesale_extend') . (' WHERE goods_id = \'' . $goods_id . '\'');
		return $GLOBALS['db']->getRow($extend_sql);
	}

	static public function get_wholesale_extension_goods($cats, $alias = 'w.')
	{
		$extension_goods_array = '';
		$sql = 'SELECT goods_id FROM ' . $GLOBALS['ecs']->table('wholesale') . (' AS w WHERE ' . $cats);
		$extension_goods_array = $GLOBALS['db']->getCol($sql);
		return db_create_in($extension_goods_array, $alias . 'goods_id');
	}

	static public function get_sale($goods_id = 0)
	{
		$sql = 'SELECT SUM(og.goods_number) FROM ' . $GLOBALS['ecs']->table('wholesale_order_info') . ' AS oi ' . ' LEFT JOIN ' . $GLOBALS['ecs']->table('wholesale_order_goods') . ' AS og ON og.order_id = oi.order_id ' . ' WHERE oi.main_order_id > 0 AND oi.is_delete = 0 AND oi.main_order_id > 0 AND og.goods_id=' . $goods_id;
		$count = $GLOBALS['db']->getOne($sql);
		return $count;
	}
}

?>
