<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
define('IN_ECS', true);
ini_set('display_errors', 1);
error_reporting(32767 ^ 8);
clearstatcache();
define('ROOT_PATH', str_replace('demo/includes/init.php', '', str_replace('\\', '/', __FILE__)));
require ROOT_PATH . 'includes/lib_common.php';
@include ROOT_PATH . 'includes/lib_base.php';
require ROOT_PATH . 'admin/includes/lib_main.php';
require ROOT_PATH . 'includes/lib_time.php';
clear_all_files();

if (file_exists(ROOT_PATH . 'data/config.php')) {
	include ROOT_PATH . 'data/config.php';
}
else if (file_exists(ROOT_PATH . 'includes/config.php')) {
	if (!rename(ROOT_PATH . 'includes/config.php', ROOT_PATH . 'data/config.php')) {
		exit('Can\'t move config.php, please move it from includes/ to data/ manually!');
	}

	include ROOT_PATH . 'data/config.php';
}
else {
	exit('Can\'t find config.php!');
}

require ROOT_PATH . 'includes/cls_ecshop.php';
require ROOT_PATH . 'includes/cls_mysql.php';
$ecs = new ECS($db_name, $prefix);
$mysql_charset = $ecshop_charset = '';
$tmp_link = @mysql_connect($db_host, $db_user, $db_pass);

if (!$tmp_link) {
	exit('Can\'t pConnect MySQL Server(' . $db_host . ')!');
}
else {
	mysql_select_db($db_name);
	($query = mysql_query(' SHOW CREATE TABLE ' . $ecs->table('users'), $tmp_link)) || exit(mysql_error());
	$tablestruct = mysql_fetch_row($query);
	preg_match('/CHARSET=(\\w+)/', $tablestruct[1], $m);

	if (strpos($m[1], 'utf') === 0) {
		$mysql_charset = str_replace('utf', 'utf-', $m[1]);
	}
	else {
		$mysql_charset = $m[1];
	}
}

if (defined('EC_CHARSET')) {
	$ecshop_charset = EC_CHARSET;
}

$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name, $ecshop_charset);
require ROOT_PATH . 'includes/cls_error.php';
$err = new ecs_error('message.dwt');
require ROOT_PATH . 'includes/cls_sql_executor.php';
require ROOT_PATH . 'demo/includes/cls_template.php';
$smarty = new template(ROOT_PATH . 'demo/templates/');
require ROOT_PATH . 'demo/includes/lib_updater.php';
@set_time_limit(360);

?>
