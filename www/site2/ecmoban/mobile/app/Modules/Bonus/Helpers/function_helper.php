<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
function get_bonus_list($num = 10, $page = 1, $status = 4)
{
	$time = gmtime();
	$res = $GLOBALS['db']->getAll('select * from ' . $GLOBALS['ecs']->table('bonus_type') . ('where  send_type =  \'' . $status . '\' and  ' . $time . '<send_end_date and   ' . $time . '>send_start_date and review_status = 3'));
	$total = is_array($res) ? count($res) : 0;
	$start = ($page - 1) * $num;
	$sql = 'select bt.* ,s.shop_name from ' . $GLOBALS['ecs']->table('bonus_type') . ' as bt left join ' . $GLOBALS['ecs']->table('seller_shopinfo') . (" as s\r\n    on bt.user_id =s.ru_id where  bt.send_type = '" . $status . '\'  and  ' . $time . '< bt.send_end_date and bt.review_status = 3 and  ' . $time . ' > bt.send_start_date ') . (' limit ' . $start . ',' . $num);
	$tab = $GLOBALS['db']->getAll($sql);

	foreach ($tab as $k => $v) {
		$tab[$k]['begintime'] = local_date('Y/m/d ', $v['send_start_date']);
		$tab[$k]['endtime'] = local_date('Y/m/d ', $v['send_end_date']);
		$tab[$k]['min_goods_amount'] = price_format($v['min_goods_amount']);
		$tab[$k]['type_money'] = price_format($v['type_money']);
	}

	$tab_list = array('tab' => $tab, 'totalpage' => ceil($total / $num));
	return $tab_list;
}


?>
