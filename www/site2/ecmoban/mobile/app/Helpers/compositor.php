<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
if (isset($modules)) {
	foreach ($modules as $k => $v) {
		if ($v['pay_code'] == 'epay') {
			$tenpay = $modules[$k];
			unset($modules[$k]);
			array_unshift($modules, $tenpay);
		}
	}

	foreach ($modules as $k => $v) {
		if ($v['pay_code'] == 'tenpay') {
			$tenpay = $modules[$k];
			unset($modules[$k]);
			array_unshift($modules, $tenpay);
		}
	}

	foreach ($modules as $k => $v) {
		if ($v['pay_code'] == 'syl') {
			$tenpay = $modules[$k];
			unset($modules[$k]);
			array_unshift($modules, $tenpay);
		}
	}

	foreach ($modules as $k => $v) {
		if ($v['pay_code'] == 'alipay') {
			$tenpay = $modules[$k];
			unset($modules[$k]);
			array_unshift($modules, $tenpay);
		}
	}
}

?>