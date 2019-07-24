<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<title>";
echo $lang['checking_title'];
echo "</title>\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<script type=\"text/javascript\" src=\"../js/transport.js\" charset=\"";
echo EC_CHARSET;
echo "\"></script>\r\n<script type=\"text/javascript\" src=\"js/common.js\"></script>\r\n<script type=\"text/javascript\" src=\"js/draggable.js\"></script>\r\n<script type=\"text/javascript\" src=\"js/check.js\"></script>\r\n<script type=\"text/javascript\">\r\nvar \$_LANG = {};\r\n";

foreach ($lang['js_languages'] as $key => $item) {
	echo '$_LANG["';
	echo $key;
	echo '"] = "';
	echo $item;
	echo "\";\r\n";
}

echo "</script>\r\n</head>\r\n<body id=\"checking\">\r\n";
include ROOT_PATH . 'demo/templates/header.php';
echo "<form method=\"post\">\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"margin:0 auto;\">\r\n<tr>\r\n<td valign=\"top\"><div id=\"wrapper\">\r\n        <h3>";
echo $lang['basic_config'];
echo "</h3>\r\n        <div class=\"list\"> ";

foreach ($config_info as $config_item) {
	echo '          ';
	echo $config_item[0];
	echo "...........................................................................................\r\n                <span style=\"color:green;\">";
	echo $config_item[1];
	echo "</span><br />\r\n         ";
}

echo "        </div>\r\n        <h3>";
echo $lang['dir_priv_checking'];
echo "</h3>\r\n        <div class=\"list\"> ";

foreach ($dir_checking as $checking_item) {
	echo '          ';
	echo $checking_item[0];
	echo "...........................................................................................\r\n              ";

	if ($checking_item[1] == $lang['can_write']) {
		echo '                    <span style="color:green;">';
		echo $checking_item[1];
		echo "</span>\r\n             ";
	}
	else {
		echo '                <span style="color:red;">';
		echo $checking_item[1];
		echo "</span>\r\n              ";
	}

	echo "<br />\r\n         ";
}

echo "        </div>\r\n\r\n        <h3>";
echo $lang['template_writable_checking'];
echo "</h3>\r\n        <div class=\"list\">\r\n         ";

if ($has_unwritable_tpl == 'yes') {
	echo '              ';

	foreach ($template_checking as $checking_item) {
		echo '                            <span style="color:red;">';
		echo $checking_item;
		echo "</span><br />\r\n              ";
	}

	echo '          ';
}
else {
	echo '              <span style="color:green">';
	echo $template_checking;
	echo "</span>\r\n          ";
}

echo "</div>\r\n        ";

if (!empty($rename_priv)) {
	echo '        <h3>';
	echo $lang['rename_priv_checking'];
	echo "</h3>\r\n        <div class=\"list\">\r\n          ";

	foreach ($rename_priv as $checking_item) {
		echo '          <span style="color:red;">';
		echo $checking_item;
		echo "</span><br />\r\n          ";
	}

	echo "        </div>\r\n        ";
}

echo "</div></td>\r\n<td>\r\n<div id=\"js-monitor\" style=\"display:none;text-align:left;position:absolute;top:45%;left:35%;width:300px;z-index:1000;border:1px solid #000;\">\r\n    <h3 id=\"js-monitor-title\">";
echo $lang['monitor_title'];
echo "</h3>\r\n    <div style=\"background:#fff;padding-bottom:20px;\">\r\n        <img id=\"js-monitor-loading\" src='images/loading.gif' /><br /><br />\r\n        <strong id=\"js-monitor-wait-please\" style='color:blue;float:left;margin-left:3px;'></strong>\r\n        <strong id=\"js-monitor-rollback\" style=\"color:red;cursor:pointer;float:left;margin-left:25px;\"></strong>\r\n        <span id=\"js-monitor-view-detail\" style=\"color:gray;cursor:pointer;float:right;margin-right:3px;\"></span>\r\n    </div>\r\n    <iframe id=\"js-monitor-notice\" src=\"templates/notice.htm\" style=\"display:none;\"></iframe>\r\n    <img id=\"js-monitor-close\" src='./images/close.gif' style=\"position:absolute;top:10px;right:10px;cursor:pointer;\" />\r\n</div>\r\n</td>\r\n</tr>\r\n<tr>\r\n  <td>\r\n      <span id=\"install-btn\"><input type=\"button\" class=\"button\" value=\"";
echo $lang['prev_step'];
echo $lang['readme_page'];
echo "\"  onclick=\"location.href='index.php'\" />\r\n      <input type=\"button\" class=\"button\" value=\"";
echo $lang['recheck'];
echo "\" onclick=\"location.href='index.php?step=check'\" />\r\n      <input type=\"button\" id=\"js-submit\"  class=\"button\" value=\"";
echo $lang['update_now'];
echo '" ';
echo $disabled;
echo "  /></span>\r\n  </td>\r\n  <td></td>\r\n</tr>\r\n</table>\r\n<div id=\"copyright\">\r\n    <div id=\"copyright-inside\">\r\n      ";
include ROOT_PATH . 'demo/templates/copyright.php';
echo "</div>\r\n</div>\r\n</form>\r\n</body>\r\n</html>\r\n";

?>
