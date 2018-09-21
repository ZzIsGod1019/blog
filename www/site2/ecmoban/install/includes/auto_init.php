<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
@ini_set('display_errors', 1);
error_reporting(32767 ^ 8);
clearstatcache();
define('ROOT_PATH', str_replace('install/includes/auto_init.php', '', str_replace('\\', '/', __FILE__)));

if (isset($_SERVER['PHP_SELF'])) {
	define('PHP_SELF', $_SERVER['PHP_SELF']);
}
else {
	define('PHP_SELF', $_SERVER['SCRIPT_NAME']);
}

define('EC_CHARSET', 'utf-8');
define('EC_DB_CHARSET', 'utf8');
require ROOT_PATH . 'includes/lib_base.php';
require ROOT_PATH . 'includes/lib_common.php';
require ROOT_PATH . 'includes/lib_time.php';
require ROOT_PATH . 'includes/cls_error.php';
$err = new sc_error('message.dwt');
require ROOT_PATH . 'install/includes/cls_template.php';
$smarty = new template(ROOT_PATH . 'install/templates/');
require ROOT_PATH . 'install/includes/lib_auto_installer.php';
header('Content-type: text/html; charset=' . EC_CHARSET);
@set_time_limit(360);

?>
