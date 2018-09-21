<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
function _encrypt($str, $key = AUTH_KEY)
{
	$coded = '';
	$keylength = strlen($key);
	$i = 0;

	for ($count = strlen($str); $i < $count; $i += $keylength) {
		$coded .= substr($str, $i, $keylength) ^ $key;
	}

	return str_replace('=', '', base64_encode($coded));
}

function _decrypt($str, $key = AUTH_KEY)
{
	$coded = '';
	$keylength = strlen($key);
	$str = base64_decode($str);
	$i = 0;

	for ($count = strlen($str); $i < $count; $i += $keylength) {
		$coded .= substr($str, $i, $keylength) ^ $key;
	}

	return $coded;
}


?>
