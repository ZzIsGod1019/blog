<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
function get_gd_version()
{
	include_once ROOT_PATH . 'includes/cls_image.php';
	return cls_image::gd_version();
}

function has_supported_gd()
{
	return get_gd_version() === 0 ? false : true;
}

function file_types_exists($file_types)
{
	global $_LANG;
	$msg = '';

	foreach ($file_types as $file_type => $file_path) {
		if (!file_exists($file_path)) {
			$msg .= $_LANG['cannt_support_' . $file_type] . ', ';
		}
	}

	$msg = preg_replace('/,\\s*$/', '', $msg);
	return $msg;
}

function get_system_info()
{
	global $_LANG;
	$system_info = array();
	$system_info[] = array($_LANG['php_os'], PHP_OS);
	$system_info[] = array($_LANG['php_ver'], PHP_VERSION);
	$mysql_enabled = function_exists('mysql_connect') ? $_LANG['support'] : $_LANG['not_support'];
	$system_info[] = array($_LANG['does_support_mysql'], $mysql_enabled);
	$gd_ver = get_gd_version();
	$gd_ver = empty($gd_ver) ? $_LANG['not_support'] : $gd_ver;

	if (0 < $gd_ver) {
		if ('4.3' <= PHP_VERSION && function_exists('gd_info')) {
			$gd_info = gd_info();
			$jpeg_enabled = $gd_info['JPG Support'] === true ? $_LANG['support'] : $_LANG['not_support'];
			$gif_enabled = $gd_info['GIF Create Support'] === true ? $_LANG['support'] : $_LANG['not_support'];
			$png_enabled = $gd_info['PNG Support'] === true ? $_LANG['support'] : $_LANG['not_support'];
		}
		else if (function_exists('imagetypes')) {
			$jpeg_enabled = 0 < (imagetypes() & IMG_JPG) ? $_LANG['support'] : $_LANG['not_support'];
			$gif_enabled = 0 < (imagetypes() & IMG_GIF) ? $_LANG['support'] : $_LANG['not_support'];
			$png_enabled = 0 < (imagetypes() & IMG_PNG) ? $_LANG['support'] : $_LANG['not_support'];
		}
		else {
			$jpeg_enabled = $_LANG['not_support'];
			$gif_enabled = $_LANG['not_support'];
			$png_enabled = $_LANG['not_support'];
		}
	}
	else {
		$jpeg_enabled = $_LANG['not_support'];
		$gif_enabled = $_LANG['not_support'];
		$png_enabled = $_LANG['not_support'];
	}

	$system_info[] = array($_LANG['gd_version'], $gd_ver);
	$system_info[] = array($_LANG['jpeg'], $jpeg_enabled);
	$system_info[] = array($_LANG['gif'], $gif_enabled);
	$system_info[] = array($_LANG['png'], $png_enabled);
	$file_types = array('dwt' => ROOT_PATH . 'themes/ecmoban_dsc/index.dwt', 'lbi' => ROOT_PATH . 'themes/ecmoban_dsc/library/member_info.lbi', 'dat' => ROOT_PATH . 'includes/codetable/ipdata.dat');
	$exists_info = file_types_exists($file_types);
	$exists_info = empty($exists_info) ? $_LANG['support_dld'] : $exists_info;
	$system_info[] = array($_LANG['does_support_dld'], $exists_info);
	$safe_mode = ini_get('safe_mode') == '1' ? $_LANG['safe_mode_on'] : $_LANG['safe_mode_off'];
	$system_info[] = array($_LANG['safe_mode'], $safe_mode);
	return $system_info;
}

function get_db_list($db_host, $db_port, $db_user, $db_pass)
{
	global $err;
	global $_LANG;
	$databases = array();
	$filter_dbs = array('information_schema', 'mysql');
	$db_host = construct_db_host($db_host, $db_port);
	$conn = @mysql_connect($db_host, $db_user, $db_pass);

	if ($conn === false) {
		$err->add($_LANG['connect_failed']);
		return false;
	}

	keep_right_conn($conn);
	$result = mysql_query('SHOW DATABASES', $conn);

	if ($result !== false) {
		while (($row = mysql_fetch_assoc($result)) !== false) {
			if (in_array($row['Database'], $filter_dbs)) {
				continue;
			}

			$databases[] = $row['Database'];
		}
	}
	else {
		$err->add($_LANG['query_failed']);
		return false;
	}

	@mysql_close($conn);
	return $databases;
}

function get_timezone_list($lang)
{
	if (file_exists(ROOT_PATH . 'install/data/inc_timezones_' . $lang . '.php')) {
		include_once ROOT_PATH . 'install/data/inc_timezones_' . $lang . '.php';
	}
	else {
		include_once ROOT_PATH . 'install/data/inc_timezones_zh_cn.php';
	}

	return array_unique($timezones);
}

function get_local_timezone()
{
	if ('5.1' <= PHP_VERSION) {
		$local_timezone = @date_default_timezone_get('Asia/Shanghai');
	}
	else {
		$local_timezone = '';
	}

	return $local_timezone;
}

function create_database($db_host, $db_port, $db_user, $db_pass, $db_name)
{
	global $err;
	global $_LANG;
	$db_host = construct_db_host($db_host, $db_port);
	$conn = @mysql_connect($db_host, $db_user, $db_pass);

	if ($conn === false) {
		$err->add($_LANG['connect_failed']);
		return false;
	}

	$mysql_version = mysql_get_server_info($conn);
	keep_right_conn($conn, $mysql_version);

	if (mysql_select_db($db_name, $conn) === false) {
		$sql = '4.1' <= $mysql_version ? 'CREATE DATABASE ' . $db_name . ' DEFAULT CHARACTER SET ' . EC_DB_CHARSET : 'CREATE DATABASE ' . $db_name;

		if (mysql_query($sql, $conn) === false) {
			$err->add($_LANG['cannt_create_database']);
			return false;
		}
	}

	@mysql_close($conn);
	return true;
}

function keep_right_conn($conn, $mysql_version = '')
{
	if ($mysql_version === '') {
		$mysql_version = mysql_get_server_info($conn);
	}

	if ('4.1' <= $mysql_version) {
		mysql_query('SET character_set_connection=' . EC_DB_CHARSET . ', character_set_results=' . EC_DB_CHARSET . ', character_set_client=binary', $conn);

		if ('5.0.1' < $mysql_version) {
			mysql_query('SET sql_mode=\'\'', $conn);
		}
	}
}

function create_config_file($db_host, $db_port, $db_user, $db_pass, $db_name, $prefix, $timezone)
{
	global $err;
	global $_LANG;
	$db_host = construct_db_host($db_host, $db_port);
	$content = '<?' . "php\n";
	$content .= "// database host\n";
	$content .= '$db_host   = "' . $db_host . "\";\n\n";
	$content .= "// database name\n";
	$content .= '$db_name   = "' . $db_name . "\";\n\n";
	$content .= "// database username\n";
	$content .= '$db_user   = "' . $db_user . "\";\n\n";
	$content .= "// database password\n";
	$content .= '$db_pass   = "' . $db_pass . "\";\n\n";
	$content .= "// table prefix\n";
	$content .= '$prefix    = "' . $prefix . "\";\n\n";
	$content .= '$timezone    = "' . $timezone . "\";\n\n";
	$content .= "\$cookie_path    = \"/\";\n\n";
	$content .= "\$cookie_domain    = \"\";\n\n";
	$content .= "\$session = \"1440\";\n\n";
	$content .= 'define(\'EC_CHARSET\',\'' . EC_CHARSET . "');\n\n";
	$content .= "define('ADMIN_PATH','admin');\n\n";
	$content .= "define('SELLER_PATH','seller');\n\n";
	$content .= "define('STORES_PATH','stores');\n\n";
	$content .= "define('CACHE_MEMCACHED',0);\n\n";
	$content .= "define('AUTH_KEY', 'this is a key');\n\n";
	$content .= "define('OLD_AUTH_KEY', '');\n\n";
	$content .= "define('API_TIME', '');\n\n";
	$content .= "define('EC_TEMPLATE', 'ecmoban_dsc2017');\n\n";
	$content .= '?>';
	$fp = @fopen(ROOT_PATH . 'data/config.php', 'wb+');

	if (!$fp) {
		$err->add($_LANG['open_config_file_failed']);
		return false;
	}

	if (!@fwrite($fp, trim($content))) {
		$err->add($_LANG['write_config_file_failed']);
		return false;
	}

	@fclose($fp);
	return true;
}

function construct_db_host($db_host, $db_port)
{
	return $db_host . ':' . $db_port;
}

function install_data($sql_files)
{
	global $err;
	include ROOT_PATH . 'data/config.php';
	include_once ROOT_PATH . 'includes/cls_mysql.php';
	include_once ROOT_PATH . 'includes/cls_sql_executor.php';
	$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
	$se = new sql_executor($db, EC_DB_CHARSET, 'dsc_', $prefix);
	$result = $se->run_all($sql_files);

	if ($result === false) {
		$err->add($se->error);
		return false;
	}

	return true;
}

function create_admin_passport($admin_name, $admin_password, $admin_password2, $admin_email)
{
	if (trim($_REQUEST['lang']) != 'zh_cn') {
		global $err;
		$system_lang = isset($_REQUEST['lang']) ? $_REQUEST['lang'] : 'zh_cn';
		include ROOT_PATH . 'install/languages/' . $system_lang . '.php';
	}
	else {
		global $err;
		global $_LANG;
	}

	if ($admin_password === '') {
		$err->add($_LANG['password_empty_error']);
		return false;
	}

	if ($admin_password === '') {
		$err->add($_LANG['password_empty_error']);
		return false;
	}

	if (!(8 <= strlen($admin_password) && preg_match('/\\d+/', $admin_password) && preg_match('/[a-zA-Z]+/', $admin_password))) {
		$err->add($_LANG['js_languages']['password_invaild']);
		return false;
	}

	if ($admin_password !== $admin_password2) {
		$err->add($_LANG['passwords_not_eq']);
		return false;
	}

	include ROOT_PATH . 'data/config.php';
	include_once ROOT_PATH . 'includes/cls_mysql.php';
	include_once ROOT_PATH . 'includes/lib_common.php';
	$nav_list = join(',', $_LANG['admin_user']);
	$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
	$sql = 'INSERT INTO ' . $prefix . 'admin_user ' . '(user_name, email, password, add_time, action_list, nav_list)' . 'VALUES ' . ('(\'' . $admin_name . '\', \'' . $admin_email . '\', \'') . md5($admin_password) . '\', ' . gmtime() . (', \'all\', \'' . $nav_list . '\')');

	if (!$db->query($sql, 'SILENT')) {
		$err->add($_LANG['create_passport_failed']);
		return false;
	}

	return true;
}

function copy_files($source, $target)
{
	global $err;
	global $_LANG;

	if (!file_exists($target)) {
		if (!mkdir($target, 511)) {
			$err->add($_LANG['cannt_mk_dir']);
			return false;
		}

		@chmod($target, 511);
	}

	$dir = opendir($source);

	while (($file = @readdir($dir)) !== false) {
		if (is_file($source . $file)) {
			if (!copy($source . $file, $target . $file)) {
				$err->add($_LANG['cannt_copy_file']);
				return false;
			}

			@chmod($target . $file, 511);
		}
	}

	closedir($dir);
	return true;
}

function do_others($system_lang, $captcha, $install_demo, $integrate_code)
{
	global $err;
	global $_LANG;

	if (intval($install_demo)) {
		if (file_exists(ROOT_PATH . 'demo/' . $system_lang . '.sql')) {
			$sql_files = array(ROOT_PATH . 'demo/' . $system_lang . '.sql');
		}
		else {
			$sql_files = array(ROOT_PATH . 'demo/zh_cn.sql');
		}

		if (!install_data($sql_files)) {
			$err->add(implode('', $err->last_message()));
			return false;
		}
	}

	include ROOT_PATH . 'data/config.php';
	include_once ROOT_PATH . 'includes/cls_mysql.php';
	$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
	$sql = 'UPDATE ' . $prefix . 'shop_config SET value=\'' . $system_lang . '\' WHERE code=\'lang\'';

	if (!$db->query($sql, 'SILENT')) {
		$err->add($db->errno() . ' ' . $db->error());
		return false;
	}

	if (!empty($integrate_code)) {
		$sql = 'UPDATE ' . $prefix . 'shop_config SET value=\'' . $integrate_code . '\' WHERE code=\'integrate_code\'';

		if (!$db->query($sql, 'SILENT')) {
			$err->add($db->errno() . ' ' . $db->error());
			return false;
		}
	}

	if (!empty($captcha)) {
		$sql = 'UPDATE ' . $prefix . 'shop_config SET value = \'0\' WHERE code = \'captcha\'';

		if (!$db->query($sql, 'SILENT')) {
			$err->add($db->errno() . ' ' . $db->error());
			return false;
		}
	}

	if (file_exists(ROOT_PATH . 'data/config_temp.php')) {
		include ROOT_PATH . 'data/config_temp.php';
		$sql = 'UPDATE ' . $prefix . 'shop_config SET value = \'' . serialize($cfg) . '\' WHERE code = \'integrate_config\'';

		if (!$db->query($sql, 'SILENT')) {
			$err->add($db->errno() . ' ' . $db->error());
			return false;
		}
	}

	return true;
}

function deal_aftermath()
{
	global $err;
	global $_LANG;
	include ROOT_PATH . 'data/config.php';
	include_once ROOT_PATH . 'includes/cls_ecshop.php';
	include_once ROOT_PATH . 'includes/cls_mysql.php';
	include_once ROOT_PATH . 'includes/cls_ecmac.php';
	include_once ROOT_PATH . 'includes/lib_ecmoban.php';
	$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
	$sql = 'UPDATE ' . $prefix . 'shop_config SET value=\'' . time() . '\' WHERE code=\'install_date\'';

	if (!$db->query($sql, 'SILENT')) {
		$err->add($db->errno() . ' ' . $db->error());
	}

	$sql = 'UPDATE ' . $prefix . 'shop_config SET value=\'' . VERSION . '\' WHERE code=\'sc_version\'';

	if (!$db->query($sql, 'SILENT')) {
		$err->add($db->errno() . ' ' . $db->error());
		return false;
	}

	$hash_code = md5(md5(time()) . md5($db->dbhash) . md5(time()));
	$sql = 'UPDATE ' . $prefix . ('shop_config SET value = \'' . $hash_code . '\' WHERE code = \'hash_code\' AND value = \'\'');

	if (!$db->query($sql, 'SILENT')) {
		$err->add($db->errno() . ' ' . $db->error());
		return false;
	}

	$mac = new cls_ecmac(PHP_OS);
	$mac = json_encode($mac);
	$mac = json_decode($mac, true);
	$macIp = get_server_ip();

	if ($macIp) {
		@file_put_contents(ROOT_PATH . 'data/install.lock.php', $macIp);
	}
	else {
		@file_put_contents(ROOT_PATH . 'data/install.lock.php', $mac['mac_addr']);
	}

	return true;
}

function get_spt_code()
{
	include ROOT_PATH . 'data/config.php';
	include_once ROOT_PATH . 'includes/cls_ecshop.php';
	include_once ROOT_PATH . 'includes/cls_mysql.php';
	$db = new cls_mysql($db_host, $db_user, $db_pass, $db_name);
	$ecs = new ECS($db_name, $prefix);
	$hash_code = $db->getOne('SELECT value FROM ' . $ecs->table('shop_config') . ' WHERE code=\'hash_code\'');
	$spt = '<script type="text/javascript" src="http://api.ecmoban.com/record.php?';
	$spt .= 'url=' . urlencode($ecs->url()) . '&mod=install&version=' . VERSION . '&hash_code=' . $hash_code . '&charset=' . EC_CHARSET . '&language=' . $GLOBALS['installer_lang'] . '"></script>';
	return $spt;
}

function get_web_info()
{
	$web_info = array('sc_version' => VERSION, 'user_domain' => get_domain(), 'user_ip' => $_SERVER['SERVER_ADDR'], 'user_os' => PHP_OS, 'user_webserver' => $_SERVER['SERVER_SOFTWARE'], 'user_phpversion' => PHP_VERSION, 'install_time' => time());
	return $web_info;
}

function get_domain()
{
	$protocol = http();

	if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
		$host = $_SERVER['HTTP_X_FORWARDED_HOST'];
	}
	else if (isset($_SERVER['HTTP_HOST'])) {
		$host = $_SERVER['HTTP_HOST'];
	}
	else {
		if (isset($_SERVER['SERVER_PORT'])) {
			$port = ':' . $_SERVER['SERVER_PORT'];
			if (':80' == $port && 'http://' == $protocol || ':443' == $port && 'https://' == $protocol) {
				$port = '';
			}
		}
		else {
			$port = '';
		}

		if (isset($_SERVER['SERVER_NAME'])) {
			$host = $_SERVER['SERVER_NAME'] . $port;
		}
		else if (isset($_SERVER['SERVER_ADDR'])) {
			$host = $_SERVER['SERVER_ADDR'] . $port;
		}
	}

	return $protocol . $host;
}

function url()
{
	$PHP_SELF = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	$ecserver = 'http://' . $_SERVER['HTTP_HOST'] . ($_SERVER['SERVER_PORT'] && $_SERVER['SERVER_PORT'] != 80 ? ':' . $_SERVER['SERVER_PORT'] : '');
	$default_appurl = $ecserver . substr($PHP_SELF, 0, strpos($PHP_SELF, 'install/') - 1);
	return $default_appurl;
}

function http()
{
	return isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) != 'off' ? 'https://' : 'http://';
}

function insertconfig($s, $find, $replace)
{
	if (preg_match($find, $s)) {
		$s = preg_replace($find, $replace, $s);
	}
	else {
		$s .= "\r\n" . $replace;
	}

	return $s;
}

function getgpc($k, $var = 'G')
{
	switch ($var) {
	case 'G':
		$var = &$_GET;
		break;

	case 'P':
		$var = &$_POST;
		break;

	case 'C':
		$var = &$_COOKIE;
		break;

	case 'R':
		$var = &$_REQUEST;
		break;
	}

	return isset($var[$k]) ? $var[$k] : '';
}

function var_to_hidden($k, $v)
{
	return '<input type="hidden" name="' . $k . '" value="' . $v . '" />';
}

function dfopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = false, $ip = '', $timeout = 15, $block = true)
{
	$return = '';
	$matches = parse_url($url);
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'] . '?' . $matches['query'] . ($matches['fragment'] ? '#' . $matches['fragment'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;

	if ($post) {
		$out = 'POST ' . $path . " HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
		$out .= 'Host: ' . $host . "\r\n";
		$out .= 'Content-Length: ' . strlen($post) . "\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= 'Cookie: ' . $cookie . "\r\n\r\n";
		$out .= $post;
	}
	else {
		$out = 'GET ' . $path . " HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= 'User-Agent: ' . $_SERVER['HTTP_USER_AGENT'] . "\r\n";
		$out .= 'Host: ' . $host . "\r\n";
		$out .= "Connection: Close\r\n";
		$out .= 'Cookie: ' . $cookie . "\r\n\r\n";
	}

	$fp = @fsockopen($ip ? $ip : $host, $port, $errno, $errstr, $timeout);

	if (!$fp) {
		return '';
	}
	else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);

		if (!$status['timed_out']) {
			while (!feof($fp)) {
				if (($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n")) {
					break;
				}
			}

			$stop = false;

			while (!feof($fp) && !$stop) {
				$data = fread($fp, $limit == 0 || 8192 < $limit ? 8192 : $limit);
				$return .= $data;

				if ($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}

		@fclose($fp);
		return $return;
	}
}

function save_uc_config($config)
{
	$success = false;
	list($appauthkey, $appid, $ucdbhost, $ucdbname, $ucdbuser, $ucdbpw, $ucdbcharset, $uctablepre, $uccharset, $ucapi, $ucip) = explode('|', $config);
	$cfg = array('uc_id' => $appid, 'uc_key' => $appauthkey, 'uc_url' => $ucapi, 'uc_ip' => $ucip, 'uc_connect' => 'mysql', 'uc_charset' => $uccharset, 'db_host' => $ucdbhost, 'db_user' => $ucdbuser, 'db_name' => $ucdbname, 'db_pass' => $ucdbpw, 'db_pre' => $uctablepre, 'db_charset' => $ucdbcharset);
	$content = "<?php\r\n";
	$content .= '$cfg = ' . var_export($cfg, true) . ";\r\n";
	$content .= '?>';
	$fp = @fopen(ROOT_PATH . 'data/config_temp.php', 'wb+');

	if (!$fp) {
		$result['error'] = 1;
		$result['message'] = $_LANG['ucenter_datadir_access'];
		exit($GLOBALS['json']->encode($result));
	}

	if (!@fwrite($fp, $content)) {
		$result['error'] = 1;
		$result['message'] = $_LANG['ucenter_tmp_config_error'];
		exit($GLOBALS['json']->encode($result));
	}

	@fclose($fp);
	return true;
}

if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

?>
