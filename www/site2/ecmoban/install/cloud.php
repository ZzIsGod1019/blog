<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
function api_request($url, $apiget)
{
	global $sc_charset;
	$install_api = 'http://dsc.ecmoban.com/sc_admin.php?c=Api&a=';
	$t = new Http();
	$api_comment = $t->doGet($install_api . $url . $apiget);
	$api_str = substr($api_comment, 3);
	$api_arr = array();
	$api_arr = json_decode($api_str, true);

	if (!empty($api_arr)) {
		if ($sc_charset != 'UTF-8') {
			$api_arr['content'] = sc_iconv('UTF-8', $sc_charset, $api_arr['content']);
		}

		return $api_arr;
	}
	else {
		return false;
	}
}

define('IN_ECS', true);
require dirname(__FILE__) . '/includes/init.php';
session_start();
require ROOT_PATH . 'includes/cls_ecshop.php';
require ROOT_PATH . 'includes/Http.class.php';
require ROOT_PATH . 'includes/cls_ecmac.php';
$data['api_ver'] = '1.0';
$data['version'] = VERSION;
$data['charset'] = strtoupper(EC_CHARSET);
$sc_charset = $data['charset'];
$data['sc_lang'] = !empty($_SESSION['sc_lang']) ? $_SESSION['sc_lang'] : 'zh_cn';
$data['release'] = RELEASE;
$step = isset($_REQUEST['step']) ? trim($_REQUEST['step']) : '';
$step_arr = array('welcome', 'check', 'setting_ui', 'done', 'active', 'send_code', 'right_ad', 'update_mend', 'check_code');

if (!in_array($step, $step_arr)) {
	@header('Location: index.php');
}

$apiget = '&step= ' . $step . ' &sc_lang= ' . $data['sc_lang'] . ' &release= ' . $data['release'] . ' &version= ' . $data['version'] . '&charset= ' . $data['charset'] . ' &api_ver= ' . $data['api_ver'];

if ($_SESSION[$step]) {
	foreach ($_SESSION[$step] as $k => $v) {
		$smarty->assign($k, $v);
		$GLOBALS[$k] = $v;
	}
}

$installer_lang_package_path = ROOT_PATH . 'install/languages/' . $data['sc_lang'] . '.php';

if (file_exists($installer_lang_package_path)) {
	include_once $installer_lang_package_path;
	$lang = $_LANG;
	$smarty->assign('lang', $_LANG);
}

if ($step == 'welcome') {
	$content = api_request('install_agree', $apiget);
	if ($content['error'] == 0 && !empty($content)) {
		echo $content['content'];
		exit();
	}
	else {
		$smarty->display($data['sc_lang'] . '_welcome_content.php');
	}
}
else if ($step == 'right_ad') {
	$content = api_request('install_ad', $apiget);
	if ($content['error'] == 0 && !empty($content)) {
		echo $content['content'];
		exit();
	}
	else {
		$smarty->display($data['sc_lang'] . '_right_ad.php');
	}
}
else if ($step == 'update_mend') {
	$apiget = '&version=' . $data['version'];
	$content = api_request('update_mend', $apiget);
	if ($content['error'] == 0 && !empty($content)) {
		echo $content['content'];
		exit();
	}
	else {
		echo '';
		exit();
	}
}
else if ($step == 'check') {
	if ($content) {
		eval ($content);
	}
	else {
		$smarty->display('checking_content.php');
	}
}
else if ($step == 'setting_ui') {
	if ($content) {
		eval ($content);
	}
	else {
		$smarty->display('setting_content.php');
	}
}
else if ($step == 'done') {
	if ($content) {
		eval ($content);
	}
	else {
		$smarty->display('done_content.php');
	}
}
else if ($step == 'active') {
	if ($content) {
		eval ($content);
	}
	else {
		$smarty->display('active_content.php');
	}
}
else if ($step == 'check_code') {
	$mobile = !empty($_POST['mobile']) ? trim($_POST['mobile']) : '';
	$code = !empty($_POST['mobile_code']) ? trim($_POST['mobile_code']) : '';
	$mac = new cls_ecmac(PHP_OS);
	$apiget = '&mobile=' . $mobile . '&macaddr=' . $mac . '&code=' . $code . '&type=1';
	$content = api_request('check_code', $apiget);

	if ($content) {
		echo json_encode($content);
		exit();
	}
	else {
		return false;
	}
}
else if ($step == 'send_code') {
	$mobile = !empty($_POST['mobile']) ? trim($_POST['mobile']) : '';
	$mac = new cls_ecmac(PHP_OS);
	$_SESSION['install_mobile'] = $mobile;
	$_SESSION['install_macaddr'] = $mac->mac_addr;
	$apiget = '&mobile=' . $mobile . '&macaddr=' . $mac . '&type=1';
	$content = api_request('send_code', $apiget);

	if ($content) {
		echo json_encode($content);
		exit();
	}
	else {
		exit('false');
	}
}

?>
