<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
function api_request($url, $apiget)
{
	global $sc_charset;
	$install_api = 'http://dsc.ecmoban.com/ecmoban_sc/dsc_pms/sc_admin.php?c=Api&a=';
	$t = new Http();
	$api_comment = $t->doGet($install_api . $url . $apiget);
	$api_str = substr($api_comment, 3);
	$api_arr = array();
	$api_arr = json_decode($api_str, true);

	if (!empty($api_arr)) {
		if ($sc_charset != 'UTF-8') {
			$api_arr['content'] = sc_iconv('UTF-8', strtoupper(EC_CHARSET), $api_arr['content']);
		}

		return $api_arr;
	}
	else {
		return false;
	}
}

function INSTALL_GUID()
{
	if (function_exists('com_create_guid') === true) {
		return trim(com_create_guid(), '{}');
	}

	return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

define('IN_ECS', true);
if (isset($_REQUEST['dbhost']) || isset($_REQUEST['dbname']) || isset($_REQUEST['dbuser']) || isset($_REQUEST['dbpass']) || isset($_REQUEST['password']) || isset($_REQUEST['data'])) {
	include './auto_index.php';
	exit();
}

require dirname(__FILE__) . '/includes/init.php';
include_once ROOT_PATH . 'includes/cls_ecmac.php';
session_start();
require ROOT_PATH . 'includes/Http.class.php';
$installer_lang = isset($_REQUEST['lang']) ? trim($_REQUEST['lang']) : 'zh_cn';
if ($installer_lang != 'zh_cn' && $installer_lang != 'zh_tw' && $installer_lang != 'en_us') {
	$installer_lang = 'zh_cn';
}

$_SESSION['sc_lang'] = $installer_lang;
$installer_lang_package_path = ROOT_PATH . 'install/languages/' . $installer_lang . '.php';

if (file_exists($installer_lang_package_path)) {
	include_once $installer_lang_package_path;
	$smarty->assign('lang', $_LANG);
}
else {
	exit('Can\'t find language package!');
}

$step = isset($_REQUEST['step']) ? $_REQUEST['step'] : 'welcome';
if (file_exists(ROOT_PATH . 'data/install.lock.php') && $step != 'active') {
	$step = 'error';
	$err->add($_LANG['has_locked_installer']);
	if (isset($_REQUEST['IS_AJAX_REQUEST']) && $_REQUEST['IS_AJAX_REQUEST'] === 'yes') {
		exit(implode(',', $err->get_all()));
	}
}

switch ($step) {
case 'welcome':
	$_SESSION['welcome']['ucapi'] = $_POST['ucapi'];
	$_SESSION['welcome']['ucfounderpw'] = $_POST['ucfounderpw'];
	$_SESSION['welcome']['installer_lang'] = $installer_lang;
	$smarty->assign('ucapi', $_POST['ucapi']);
	$smarty->assign('ucfounderpw', $_POST['ucfounderpw']);
	$smarty->assign('installer_lang', $installer_lang);
	$smarty->display('welcome.php');
	break;

case 'uccheck':
	$smarty->assign('ucapi', $_POST['ucapi']);
	$smarty->assign('ucfounderpw', $_POST['ucfounderpw']);
	$smarty->assign('installer_lang', $installer_lang);
	$smarty->display('uc_check.php');
	break;

case 'check':
	include_once ROOT_PATH . 'install/includes/lib_env_checker.php';
	include_once ROOT_PATH . 'install/includes/checking_dirs.php';
	$dir_checking = check_dirs_priv($checking_dirs);
	$templates_root = array('dwt' => ROOT_PATH . 'themes/ecmoban_dsc/', 'lbi' => ROOT_PATH . 'themes/ecmoban_dsc/library/');
	$template_checking = check_templates_priv($templates_root);
	$rename_priv = check_rename_priv();
	$disabled = '';
	if ($dir_checking['result'] === 'ERROR' || !empty($template_checking) || !empty($rename_priv) || !function_exists('mysql_connect')) {
		$disabled = 'disabled="true"';
	}

	$has_unwritable_tpl = 'yes';

	if (empty($template_checking)) {
		$template_checking = $_LANG['all_are_writable'];
		$has_unwritable_tpl = 'no';
	}

	$ui = !empty($_POST['user_interface']) ? $_POST['user_interface'] : $_GET['ui'];
	$_SESSION['check']['ucapi'] = $_POST['ucapi'];
	$_SESSION['check']['ucfounderpw'] = $_POST['ucfounderpw'];
	$_SESSION['check']['installer_lang'] = $installer_lang;
	$_SESSION['check']['system_info'] = get_system_info();
	$_SESSION['check']['dir_checking'] = $dir_checking['detail'];
	$_SESSION['check']['has_unwritable_tpl'] = $has_unwritable_tpl;
	$_SESSION['check']['template_checking'] = $template_checking;
	$_SESSION['check']['rename_priv'] = $rename_priv;
	$_SESSION['check']['disabled'] = $disabled;
	$_SESSION['check']['userinterface'] = $ui;
	$smarty->assign('ucapi', $_POST['ucapi']);
	$smarty->assign('ucfounderpw', $_POST['ucfounderpw']);
	$smarty->assign('installer_lang', $installer_lang);
	$smarty->assign('system_info', get_system_info());
	$smarty->assign('dir_checking', $dir_checking['detail']);
	$smarty->assign('has_unwritable_tpl', $has_unwritable_tpl);
	$smarty->assign('template_checking', $template_checking);
	$smarty->assign('rename_priv', $rename_priv);
	$smarty->assign('disabled', $disabled);
	$smarty->assign('userinterface', $ui);
	$smarty->display('checking.php');
	break;

case 'setting_ui':
	$prefix = 'sc_';

	if (!has_supported_gd()) {
		$checked = 'checked="checked"';
		$disabled = 'disabled="true"';
	}
	else {
		$checked = '';
		$disabled = '';
	}

	$show_timezone = '5.1' <= PHP_VERSION ? 'yes' : 'no';
	$timezones = get_timezone_list($installer_lang);
	$_SESSION['setting_ui']['ucapi'] = $_POST['ucapi'];
	$_SESSION['setting_ui']['ucfounderpw'] = $_POST['ucfounderpw'];
	$_SESSION['setting_ui']['installer_lang'] = $installer_lang;
	$_SESSION['setting_ui']['checked'] = $checked;
	$_SESSION['setting_ui']['disabled'] = $disabled;
	$_SESSION['setting_ui']['show_timezone'] = $show_timezone;
	$_SESSION['setting_ui']['local_timezone'] = get_local_timezone();
	$_SESSION['setting_ui']['timezones'] = $timezones;
	$_SESSION['setting_ui']['userinterface'] = empty($_GET['ui']) ? 'ecshop' : $_GET['ui'];
	$smarty->assign('ucapi', $_POST['ucapi']);
	$smarty->assign('ucfounderpw', $_POST['ucfounderpw']);
	$smarty->assign('installer_lang', $installer_lang);
	$smarty->assign('checked', $checked);
	$smarty->assign('disabled', $disabled);
	$smarty->assign('show_timezone', $show_timezone);
	$smarty->assign('local_timezone', get_local_timezone());
	$smarty->assign('timezones', $timezones);
	$smarty->assign('userinterface', empty($_GET['ui']) ? 'ecshop' : $_GET['ui']);
	$smarty->display('setting.php');
	break;

case 'get_db_list':
	$db_host = isset($_POST['db_host']) ? trim($_POST['db_host']) : '';
	$db_port = isset($_POST['db_port']) ? trim($_POST['db_port']) : '';
	$db_user = isset($_POST['db_user']) ? trim($_POST['db_user']) : '';
	$db_pass = isset($_POST['db_pass']) ? trim($_POST['db_pass']) : '';
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$databases = get_db_list($db_host, $db_port, $db_user, $db_pass);

	if ($databases === false) {
		echo $json->encode(implode(',', $err->get_all()));
	}
	else {
		$result = array('msg' => 'OK', 'list' => implode(',', $databases));
		echo $json->encode($result);
	}

	break;

case 'create_config_file':
	$db_host = isset($_POST['db_host']) ? trim($_POST['db_host']) : '';
	$db_port = isset($_POST['db_port']) ? trim($_POST['db_port']) : '';
	$db_user = isset($_POST['db_user']) ? trim($_POST['db_user']) : '';
	$db_pass = isset($_POST['db_pass']) ? trim($_POST['db_pass']) : '';
	$db_name = isset($_POST['db_name']) ? trim($_POST['db_name']) : '';
	$prefix = isset($_POST['db_prefix']) ? trim($_POST['db_prefix']) : '';
	$timezone = isset($_POST['timezone']) ? trim($_POST['timezone']) : 'Asia/Shanghai';
	$result = create_config_file($db_host, $db_port, $db_user, $db_pass, $db_name, $prefix, $timezone);

	if ($result === false) {
		echo implode(',', $err->get_all());
	}
	else {
		echo 'OK';
	}

	break;

case 'setup_ucenter':
	include_once ROOT_PATH . 'includes/cls_json.php';
	$json = new JSON();
	$result = array('error' => 0, 'message' => '');
	$app_type = 'ECSHOP';
	$app_name = 'ECSHOP 网店';
	$app_url = url();
	$app_charset = EC_CHARSET;
	$app_dbcharset = EC_DB_CHARSET;
	$dns_error = false;
	$ucapi = !empty($_POST['ucapi']) ? trim($_POST['ucapi']) : '';
	$ucip = !empty($_POST['ucip']) ? trim($_POST['ucip']) : '';

	if (!$ucip) {
		$temp = @parse_url($ucapi);
		$ucip = gethostbyname($temp['host']);
		if (ip2long($ucip) == -1 || ip2long($ucip) === false) {
			$ucip = '';
			$dns_error = true;
		}
	}

	if ($dns_error) {
		$result['error'] = 2;
		$result['message'] = '';
		exit($json->encode($result));
	}

	$ucfounderpw = trim($_POST['ucfounderpw']);
	$app_tagtemplates = 'apptagtemplates[template]=' . urlencode('<a href="{url}" target="_blank">{goods_name}</a>') . '&' . 'apptagtemplates[fields][goods_name]=' . urlencode($_LANG['tagtemplates_goodsname']) . '&' . 'apptagtemplates[fields][uid]=' . urlencode($_LANG['tagtemplates_uid']) . '&' . 'apptagtemplates[fields][username]=' . urlencode($_LANG['tagtemplates_username']) . '&' . 'apptagtemplates[fields][dateline]=' . urlencode($_LANG['tagtemplates_dateline']) . '&' . 'apptagtemplates[fields][url]=' . urlencode($_LANG['tagtemplates_url']) . '&' . 'apptagtemplates[fields][image]=' . urlencode($_LANG['tagtemplates_image']) . '&' . 'apptagtemplates[fields][goods_price]=' . urlencode($_LANG['tagtemplates_price']);
	$postdata = 'm=app&a=add&ucfounder=&ucfounderpw=' . urlencode($ucfounderpw) . '&apptype=' . urlencode($app_type) . '&appname=' . urlencode($app_name) . '&appurl=' . urlencode($app_url) . '&appip=&appcharset=' . $app_charset . '&appdbcharset=' . $app_dbcharset . '&' . $app_tagtemplates;
	$ucconfig = dfopen($ucapi . '/index.php', 500, $postdata, '', 1, $ucip);

	if (empty($ucconfig)) {
		$result['error'] = 1;
		$result['message'] = $_LANG['ucenter_validation_fails'];
	}
	else if ($ucconfig == '-1') {
		$result['error'] = 1;
		$result['message'] = $_LANG['ucenter_creator_wrong_password'];
	}
	else {
		list($appauthkey, $appid) = explode('|', $ucconfig);
		if (empty($appauthkey) || empty($appid)) {
			$result['error'] = 1;
			$result['message'] = $_LANG['ucenter_data_error'];
		}
		else if ($succeed = save_uc_config($ucconfig . ('|' . $ucapi . '|' . $ucip))) {
			$result['error'] = 0;
			$result['message'] = 'OK';
		}
		else {
			$result['error'] = 1;
			$result['message'] = $_LANG['ucenter_config_error'];
		}
	}

	exit($json->encode($result));
	break;

case 'create_database':
	$db_host = isset($_POST['db_host']) ? trim($_POST['db_host']) : '';
	$db_port = isset($_POST['db_port']) ? trim($_POST['db_port']) : '';
	$db_user = isset($_POST['db_user']) ? trim($_POST['db_user']) : '';
	$db_pass = isset($_POST['db_pass']) ? trim($_POST['db_pass']) : '';
	$db_name = isset($_POST['db_name']) ? trim($_POST['db_name']) : '';
	$result = create_database($db_host, $db_port, $db_user, $db_pass, $db_name);

	if ($result === false) {
		echo implode(',', $err->get_all());
	}
	else {
		echo 'OK';
	}

	break;

case 'install_base_data':
	$system_lang = isset($_POST['system_lang']) ? $_POST['system_lang'] : 'zh_cn';

	if (file_exists(ROOT_PATH . 'install/data/data_' . $system_lang . '.sql')) {
		$data_path = ROOT_PATH . 'install/data/data_' . $system_lang . '.sql';
	}
	else {
		$data_path = ROOT_PATH . 'install/data/data_zh_cn.sql';
	}

	$sql_files = array(ROOT_PATH . 'install/data/structure.sql', $data_path);
	$result = install_data($sql_files);

	if ($result === false) {
		echo implode(',', $err->get_all());
	}
	else {
		echo 'OK';
	}

	break;

case 'create_admin_passport':
	$admin_name = isset($_POST['admin_name']) ? json_str_iconv(trim($_POST['admin_name'])) : '';
	$admin_password = isset($_POST['admin_password']) ? trim($_POST['admin_password']) : '';
	$admin_password2 = isset($_POST['admin_password2']) ? trim($_POST['admin_password2']) : '';
	$admin_email = isset($_POST['admin_email']) ? trim($_POST['admin_email']) : '';
	$result = create_admin_passport($admin_name, $admin_password, $admin_password2, $admin_email);

	if ($result === false) {
		echo implode(',', $err->get_all());
	}
	else {
		echo 'OK';
	}

	break;

case 'do_others':
	$system_lang = isset($_POST['system_lang']) ? $_POST['system_lang'] : 'zh_cn';
	$captcha = isset($_POST['disable_captcha']) ? intval($_POST['disable_captcha']) : '0';
	$install_demo = isset($_POST['install_demo']) ? $_POST['install_demo'] : 0;
	$integrate = isset($_POST['userinterface']) ? trim($_POST['userinterface']) : 'ecshop';
	$result = do_others($system_lang, $captcha, $install_demo, $integrate);

	if ($result === false) {
		echo implode(',', $err->get_all());
	}
	else {
		echo 'OK';
	}

	break;

case 'done':
	$mac = new cls_ecmac(PHP_OS);
	$result = deal_aftermath();

	if ($result === false) {
		$err_msg = implode(',', $err->get_all());
		$smarty->assign('err_msg', $err_msg);
		$smarty->display('error.php');
	}
	else {
		@unlink(ROOT_PATH . 'data/config_temp.php');
		$web_info = get_web_info();
		$appid = INSTALL_GUID();
		$install_mobile = '';
		$macaddr = $mac->mac_addr;
		$apiget = '&mobile=' . $install_mobile . '&macaddr=' . $macaddr . '&sc_version=' . $web_info['sc_version'] . '&domain=' . urlencode($web_info['user_domain']) . '&ip=' . $web_info['user_ip'] . '&os=' . $web_info['user_os'] . '&webserver=' . urlencode($web_info['user_webserver']) . '&phpversion=' . $web_info['user_phpversion'] . '&time=' . $web_info['install_time'] . '&appid=' . $appid;
		/*$content = api_request('record_info', $apiget);*/
		$smarty->display('done.php');
	}

	break;

case 'error':
	$err_msg = implode(',', $err->get_all());
	$exists = file_exists(ROOT_PATH . 'data/install.lock.php');
	$smarty->assign('err_msg', $err_msg);
	$smarty->assign('exists', $exists);
	$smarty->display('error.php');
	break;

default:
	exit('Error, unknown step!');
}

?>
