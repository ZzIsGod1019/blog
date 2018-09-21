<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
function mc_new_order($str = '', $goods, $goods_number, $comment_num)
{
	if (!$str) {
		return false;
	}

	$str = preg_replace("/\r\n/", '*', $str);
	$str_arr = array_filter(explode('*', $str));
	$goodsCnt = get_goods_amount($goods, $goods_number);
	$arr = array();
	$other = array();

	if (1 < $comment_num) {
		$str_arr = get_array_rand_return($str_arr);
	}

	for ($i = 0; $i < $comment_num; $i++) {
		$array_goods[$i] = $goods;

		if (1 < $comment_num) {
			$array_goods[$i] = get_array_rand_return($array_goods[$i]);
		}

		$arr[$i] = str_iconv($str_arr[$i]);
		$arr[$i] = explode('|', trim($arr[$i]));

		if (!empty($arr[$i][2])) {
			$region = explode('--', $arr[$i][2]);
			$region_name = explode(',', $region[0]);
		}

		$user_id = get_infoCnt('users', 'user_id', 'user_name = \'' . $arr[$i][0] . '\'');
		$province = get_infoCnt('region', 'region_id', 'region_name = \'' . $region_name[0] . '\'');
		$city = get_infoCnt('region', 'region_id', 'region_name = \'' . $region_name[1] . '\'');
		$district = get_infoCnt('region', 'region_id', 'region_name = \'' . $region_name[2] . '\'');
		$shipping_id = get_infoCnt('shipping', 'shipping_id', 'shipping_name = \'' . $arr[$i][7] . '\'');
		$pay_id = get_infoCnt('payment', 'pay_id', 'pay_name = \'' . $arr[$i][8] . '\'');
		$rand_time = rand(1, 1000000);
		$nowTime = gmtime();
		$time = $nowTime - $rand_time;
		$other = array('user_id' => $user_id, 'order_sn' => mc_get_order_sn(), 'consignee' => $arr[$i][1], 'country' => 1, 'province' => $province, 'city' => $city, 'district' => $district, 'address' => $region[1], 'zipcode' => $arr[$i][6], 'tel' => $arr[$i][3], 'mobile' => $arr[$i][4], 'email' => $arr[$i][5], 'shipping_id' => $shipping_id, 'shipping_name' => $arr[$i][7], 'pay_id' => $pay_id, 'pay_name' => $arr[$i][8], 'goods_amount' => $goodsCnt['goods_amount'], 'shipping_fee' => $arr[$i][9], 'order_amount' => $goodsCnt['goods_amount'] + $arr[$i][9], 'add_time' => $time);

		if (0 < $comment_num) {
			$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_info'), $other, 'INSERT');
			$order_id = $GLOBALS['db']->insert_id();

			for ($j = 0; $j < count($array_goods[$i]); $j++) {
				if (!empty($array_goods[$i][$j])) {
					$goodsText = explode('-', $array_goods[$i][$j]);
					$goods_id = $goodsText[0];
					$attr_price = $goodsText[1];
					$goodsFiles = 'goods_id, goods_sn, goods_name, shop_price, promote_price, promote_start_date, promote_end_date, is_promote, market_price, is_real';
					$goods_info = get_infoCnt('goods', $goodsFiles, 'goods_id = \'' . $goods_id . '\'', 2);
					$time = gmtime();

					if ($goods_info['is_promote'] == 1) {
						if ($goods_info['promote_start_date'] <= $time && $time <= $goods_info['promote_end_date']) {
							$goods_info['goods_price'] = $goods_info['promote_price'] + $attr_price;
						}
						else {
							$goods_info['goods_price'] = $goods_info['shop_price'] + $attr_price;
						}
					}
					else {
						$goods_info['goods_price'] = $goods_info['shop_price'] + $attr_price;
					}

					$goods_other = array('order_id' => $order_id, 'goods_id' => $goods_info['goods_id'], 'goods_sn' => $goods_info['goods_sn'], 'goods_name' => $goods_info['goods_name'], 'goods_number' => $goods_number, 'goods_price' => $goods_info['goods_price'], 'market_price' => $goods_info['market_price'], 'is_real' => $goods_info['is_real']);

					if (0 < count($goods[$j])) {
						$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_goods'), $goods_other, 'INSERT');
					}
				}
			}
		}
	}
}

function mc_get_order_sn()
{
	mt_srand((double) microtime() * 1000000);
	return date('Ymd') . str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
}

function get_goods_amount($goods, $goods_number)
{
	$time = gmtime();
	$price = '';
	$arr = array();

	for ($i = 0; $i < count($goods); $i++) {
		$goods[$i] = explode('-', $goods[$i]);
		$goods_id = $goods[$i][0];
		$attr_price = $goods[$i][1];
		$goodsCnt = 'goods_id, goods_sn, goods_name, shop_price, promote_price, promote_start_date, promote_end_date, is_promote';
		$goods_info = get_infoCnt('goods', $goodsCnt, 'goods_id = \'' . $goods_id . '\'', 2);

		if ($goods_info['is_promote'] == 1) {
			if ($goods_info['promote_start_date'] <= $time && $time <= $goods_info['promote_end_date']) {
				$arr[$i]['goods_price'] = ($goods_info['promote_price'] + $attr_price) * $goods_number;
			}
			else {
				$arr[$i]['goods_price'] = ($goods_info['shop_price'] + $attr_price) * $goods_number;
			}
		}
		else {
			$arr[$i]['goods_price'] = ($goods_info['shop_price'] + $attr_price) * $goods_number;
		}

		$arr['goods_amount'] += $arr[$i]['goods_price'];
	}

	return $arr;
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
require 'mc_function.php';

if (empty($_REQUEST['act'])) {
	$_REQUEST['act'] = 'list';
}
else {
	$_REQUEST['act'] = trim($_REQUEST['act']);
}

admin_priv('batch_add_order');

if ($_REQUEST['act'] == 'mc_add') {
	$link[] = array('text' => $_LANG['go_back'], 'href' => 'mc_order.php');
	$goods = isset($_REQUEST['comment_id']) ? trim($_REQUEST['comment_id']) : '';
	$goods_number = isset($_REQUEST['goods_number']) ? intval($_REQUEST['goods_number']) : 1;
	$_REQUEST['comment_num'] = trim($_REQUEST['comment_num']);
	$comment_num = intval($_REQUEST['comment_num']);

	if ($comment_num < 1) {
		$comment_num = 1;
	}

	$goods = preg_replace("/\r\n/", ',', $goods);
	$goods = explode(',', $goods);

	if (count($goods) < 0) {
		sys_msg('需购买商品ID不能为空,请检查;', 0, $link);
	}

	if (!$_FILES['upfile']) {
		sys_msg('没有上传用户的文件;', 0, $link);
	}

	$path = '../mc_upfile/' . date('Ym') . '/';
	$file_chk = uploadfile('upfile', $path, 'mc_order.php', 1024000, 'txt');

	if ($file_chk) {
		$filename = $path . $file_chk[0];
		$user_str = mc_read_txt($filename);

		if (!empty($user_str)) {
			mc_new_order($user_str, $goods, $goods_number, $comment_num);
		}
		else {
			sys_msg('读取用户名文件出错;', 0, $link);
		}
	}
	else {
		sys_msg('文件未上传成功;', 0, $link);
	}

	sys_msg('恭喜，批量添加订单成功！', 0, $link);
}
else {
	$smarty->assign('ur_here', $_LANG['batch_add_order']);
	$smarty->display('mc_order.dwt');
}

?>
