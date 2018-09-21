<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace app\model;

abstract class goodsModel extends \app\func\common
{
	private $alias_config;

	public function __construct()
	{
		$this->goodsModel();
	}

	public function goodsModel($table = '')
	{
		$this->alias_config = array('goods' => 'g', 'warehouse_goods' => 'wg', 'warehouse_area_goods' => 'wag', 'goods_gallery' => 'gll', 'goods_attr' => 'ga', 'goods_transport' => 'gtt', 'goods_transport_express' => 'gtes', 'goods_transport_extend' => 'gted');

		if ($table) {
			return $this->alias_config[$table];
		}
		else {
			return $this->alias_config;
		}
	}

	public function get_where($val = array(), $alias = '')
	{
		$where = 1;
		$where .= \app\func\base::get_where($val['goods_id'], $alias . 'goods_id');
		$where .= \app\func\base::get_where($val['goods_sn'], $alias . 'goods_sn');
		$where .= \app\func\base::get_where($val['bar_code'], $alias . 'bar_code');
		$where .= \app\func\base::get_where($val['cat_id'], $alias . 'cat_id');

		if (0 < $val['brand_id']) {
			$val['brand_id'] = \app\func\base::get_del_str_comma($val['brand_id']);
			$seller_brand = \app\func\base::get_link_seller_brand($val['brand_id']);

			if ($seller_brand) {
				$brand_id = $seller_brand['brand_id'] . ',' . $val['brand_id'];
				$brand_id = \app\func\base::get_del_str_comma($brand_id);
				$brand_id = explode(',', $brand_id);
				$val['brand_id'] = array_unique($brand_id);
			}
		}

		$where .= \app\func\base::get_where($val['brand_id'], $alias . 'brand_id');
		$where .= \app\func\base::get_where($val['user_cat'], $alias . 'user_cat');

		if (0 < $val['seller_type']) {
			$where .= \app\func\base::get_where($val['seller_id'], $alias . 'ru_id');
		}
		else {
			$where .= \app\func\base::get_where($val['seller_id'], $alias . 'user_id');
		}

		$where .= \app\func\base::get_where($val['w_id'], $alias . 'w_id');
		$where .= \app\func\base::get_where($val['a_id'], $alias . 'a_id');
		$where .= \app\func\base::get_where($val['region_id'], $alias . 'region_id');
		$where .= \app\func\base::get_where($val['region_sn'], $alias . 'region_sn');
		$where .= \app\func\base::get_where($val['img_id'], $alias . 'img_id');
		$where .= \app\func\base::get_where($val['attr_id'], $alias . 'attr_id');
		$where .= \app\func\base::get_where($val['goods_attr_id'], $alias . 'goods_attr_id');
		$where .= \app\func\base::get_where($val['tid'], $alias . 'tid');
		return $where;
	}

	public function get_select_list($table, $select, $where, $page_size, $page, $sort_by, $sort_order)
	{
		$sql = 'SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE ' . $where;
		$result['record_count'] = $GLOBALS['db']->getOne($sql);

		if ($sort_by) {
			$where .= ' ORDER BY ' . $sort_by . ' ' . $sort_order . ' ';
		}

		$where .= ' LIMIT ' . ($page - 1) * $page_size . (',' . $page_size);
		$sql = 'SELECT ' . $select . ' FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE ' . $where;
		$result['list'] = $GLOBALS['db']->getAll($sql);
		return $result;
	}

	public function get_join_select_list($table, $select, $where, $join_on = array())
	{
		$result = \app\func\base::get_join_table($table, $join_on, $select, $where, 1);
		return $result;
	}

	public function get_select_info($table, $select, $where)
	{
		$sql = 'SELECT ' . $select . ' FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE ' . $where . ' LIMIT 1';
		$goods = $GLOBALS['db']->getRow($sql);
		return $goods;
	}

	public function get_join_select_info($table, $select, $where, $join_on)
	{
		$goods = \app\func\base::get_join_table($table, $join_on, $select, $where, 2);
		return $goods;
	}

	public function get_insert($table, $select, $format)
	{
		$goodsLang = \languages\goodsLang::lang_goods_insert();
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $select, 'INSERT');
		$id = $GLOBALS['db']->insert_id();
		$common_data = array('result' => empty($id) ? 'failure' : 'success', 'msg' => empty($id) ? $goodsLang['msg_failure']['failure'] : $goodsLang['msg_success']['success'], 'error' => empty($id) ? $goodsLang['msg_failure']['error'] : $goodsLang['msg_success']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_more_insert($table, $select, $format)
	{
		$goodsLang = \languages\goodsLang::lang_goods_insert();
		$first_table = $table[0];
		$first_select = $select[0];
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($first_table), $first_select, 'INSERT');
		$tid = $GLOBALS['db']->insert_id();

		for ($i = 0; $i < count($table); $i++) {
			if (0 < $i && $table[$i]) {
				if ($select[$i]) {
					$select[$i]['tid'] = $tid;
				}

				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table[$i]), $select[$i], 'INSERT');
			}
		}

		$common_data = array('result' => empty($tid) ? 'failure' : 'success', 'msg' => empty($tid) ? $goodsLang['msg_failure']['failure'] : $goodsLang['msg_success']['success'], 'error' => empty($tid) ? $goodsLang['msg_failure']['error'] : $goodsLang['msg_success']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_update($table, $select, $where, $format)
	{
		$goodsLang = \languages\goodsLang::lang_goods_update();

		if (strlen($where) != 1) {
			$info = $this->get_select_info($table, '*', $where);

			if (!$info) {
				$common_data = array('result' => 'failure', 'msg' => $goodsLang['null_failure']['failure'], 'error' => $goodsLang['null_failure']['error'], 'format' => $format);
			}
			else {
				$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table), $select, 'UPDATE', $where);
				$common_data = array('result' => empty($select) ? 'failure' : 'success', 'msg' => empty($select) ? $goodsLang['msg_failure']['failure'] : $goodsLang['msg_success']['success'], 'error' => empty($select) ? $goodsLang['msg_failure']['error'] : $goodsLang['msg_success']['error'], 'format' => $format);
			}
		}
		else {
			$common_data = array('result' => 'failure', 'msg' => $goodsLang['where_failure']['failure'], 'error' => $goodsLang['where_failure']['error'], 'format' => $format);
		}

		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_more_update($table, $select, $where, $format)
	{
		$goodsLang = \languages\goodsLang::lang_goods_update();

		if (strlen($where) != 1) {
			$first_table = $table[0];
			$first_select = $select[0];
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($first_table), $first_select, 'UPDATE', $where);

			for ($i = 0; $i < count($table); $i++) {
				if (0 < $i && $table[$i]) {
					if ($select[$i]) {
						$select[$i]['tid'] = $this->tid;
					}

					$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table($table[$i]), $select[$i], 'UPDATE', $where);
				}
			}

			$common_data = array('result' => empty($select) ? 'failure' : 'success', 'msg' => empty($select) ? $goodsLang['msg_failure']['failure'] : $goodsLang['msg_success']['success'], 'error' => empty($select) ? $goodsLang['msg_failure']['error'] : $goodsLang['msg_success']['error'], 'format' => $format);
		}
		else {
			$common_data = array('result' => 'failure', 'msg' => $goodsLang['where_failure']['failure'], 'error' => $goodsLang['where_failure']['error'], 'format' => $format);
		}

		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_delete($table, $where, $format)
	{
		$goodsLang = \languages\goodsLang::lang_goods_delete();

		if (strlen($where) != 1) {
			$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table($table) . ' WHERE ' . $where;
			$GLOBALS['db']->query($sql);
			$common_data = array('result' => 'success', 'msg' => $goodsLang['msg_success']['success'], 'error' => $goodsLang['msg_success']['error'], 'format' => $format);
		}
		else {
			$common_data = array('result' => 'failure', 'msg' => $goodsLang['where_failure']['failure'], 'error' => $goodsLang['where_failure']['error'], 'format' => $format);
		}

		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_more_delete($table, $where, $format)
	{
		$goodsLang = \languages\goodsLang::lang_goods_delete();

		if (strlen($where) != 1) {
			for ($i = 0; $i < count($table); $i++) {
				$sql = 'DELETE FROM ' . $GLOBALS['ecs']->table($table[$i]) . ' WHERE ' . $where;
				$GLOBALS['db']->query($sql);
			}

			$common_data = array('result' => 'success', 'msg' => $goodsLang['msg_success']['success'], 'error' => $goodsLang['msg_success']['error'], 'format' => $format);
		}
		else {
			$common_data = array('result' => 'failure', 'msg' => $goodsLang['where_failure']['failure'], 'error' => $goodsLang['where_failure']['error'], 'format' => $format);
		}

		\app\func\common::common($common_data);
		return \app\func\common::data_back();
	}

	public function get_list_common_data($result, $page_size, $page, $goodsLang, $format)
	{
		$common_data = array('page_size' => $page_size, 'page' => $page, 'result' => empty($result['record_count']) ? 'failure' : 'success', 'msg' => empty($result['record_count']) ? $goodsLang['msg_failure']['failure'] : $goodsLang['msg_success']['success'], 'error' => empty($result['record_count']) ? $goodsLang['msg_failure']['error'] : $goodsLang['msg_success']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		$result = \app\func\common::data_back($result, 1);
		return $result;
	}

	public function get_info_common_data_fs($goods, $goodsLang, $format)
	{
		$common_data = array('result' => empty($goods) ? 'failure' : 'success', 'msg' => empty($goods) ? $goodsLang['msg_failure']['failure'] : $goodsLang['msg_success']['success'], 'error' => empty($goods) ? $goodsLang['msg_failure']['error'] : $goodsLang['msg_success']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		$goods = \app\func\common::data_back($goods);
		return $goods;
	}

	public function get_info_common_data_f($goodsLang, $format)
	{
		$goods = array();
		$common_data = array('result' => 'failure', 'msg' => $goodsLang['where_failure']['failure'], 'error' => $goodsLang['where_failure']['error'], 'format' => $format);
		\app\func\common::common($common_data);
		$goods = \app\func\common::data_back($goods);
		return $goods;
	}
}

?>
