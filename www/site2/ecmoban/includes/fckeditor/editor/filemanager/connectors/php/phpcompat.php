<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
if (!isset($_SERVER)) {
	$_SERVER = $HTTP_SERVER_VARS;
}

if (!isset($_GET)) {
	$_GET = $HTTP_GET_VARS;
}

if (!isset($_FILES)) {
	$_FILES = $HTTP_POST_FILES;
}

if (!defined('DIRECTORY_SEPARATOR')) {
	define('DIRECTORY_SEPARATOR', strtoupper(substr(PHP_OS, 0, 3) == 'WIN') ? '\\' : '/');
}

?>
