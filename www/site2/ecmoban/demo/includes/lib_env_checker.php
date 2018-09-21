<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
function check_dirs_priv($checking_dirs)
{
	include_once ROOT_PATH . 'includes/lib_common.php';
	global $_LANG;
	$msgs = array(
		'result' => 'OK',
		'detail' => array()
		);

	foreach ($checking_dirs as $dir) {
		if (!file_exists(ROOT_PATH . $dir)) {
			$msgs['result'] = 'ERROR';
			$msgs['detail'][] = array($dir, $_LANG['not_exists']);
			continue;
		}

		if (file_mode_info(ROOT_PATH . $dir) < 2) {
			$msgs['result'] = 'ERROR';
			$msgs['detail'][] = array($dir, $_LANG['cannt_write']);
		}
		else {
			$msgs['detail'][] = array($dir, $_LANG['can_write']);
		}
	}

	return $msgs;
}

function check_templates_priv($templates_root)
{
	global $_LANG;
	$msgs = array();
	$filename = '';
	$filepath = '';

	foreach ($templates_root as $tpl_type => $tpl_root) {
		if (!file_exists($tpl_root)) {
			$msgs[] = str_replace(ROOT_PATH, '', $tpl_root . ' ' . $_LANG['not_exists']);
			continue;
		}

		$tpl_handle = @opendir($tpl_root);

		while (($filename = @readdir($tpl_handle)) !== false) {
			$filepath = $tpl_root . $filename;
			if (is_file($filepath) && strrpos($filename, '.' . $tpl_type) !== false && file_mode_info($filepath) < 7) {
				$msgs[] = str_replace(ROOT_PATH, '', $filepath . ' ' . $_LANG['cannt_write']);
			}
		}

		@closedir($tpl_handle);
	}

	return $msgs;
}

function check_rename_priv()
{
	$dir_list = array();
	$dir_list[] = 'templates/caches';
	$dir_list[] = 'templates/compiled';
	$dir_list[] = 'templates/compiled/admin';
	$folder = opendir(ROOT_PATH . 'images');

	while ($dir = readdir($folder)) {
		if (is_dir(ROOT_PATH . 'images/' . $dir) && preg_match('/^[0-9]{6}$/', $dir)) {
			$dir_list[] = 'images/' . $dir;
		}
	}

	closedir($folder);
	$msgs = array();

	foreach ($dir_list as $dir) {
		$mask = file_mode_info(ROOT_PATH . $dir);
		if (0 < ($mask & 2) && ($mask & 8) < 1) {
			$msgs[] = $dir . ' ' . $GLOBALS['_LANG']['cannt_modify'];
		}
	}

	return $msgs;
}


?>
