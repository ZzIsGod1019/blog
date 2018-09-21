<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<title>";
echo $lang['users_importto_ucenter'];
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
include ROOT_PATH . 'upgrade/templates/header.php';
echo "<form id=\"js-setup\" method=\"post\" onsubmit=\"return setupUCenter()\">\r\n";

if ($not_match) {
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"uc_table\">\r\n<tr>\r\n<td>";
	echo $lang['ucenter_not_match'];
	echo "</td>\r\n</tr>\r\n</table>\r\n";
}
else if ($noucdb) {
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"uc_table\">\r\n<tr>\r\n<td>";
	echo $lang['ucenter_no_database'];
	echo "</td>\r\n</tr>\r\n</table>\r\n";
}
else {
	echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"uc_table\">\r\n<tr>\r\n<td valign=\"top\">\r\n<div id=\"wrapper\">\r\n  <h3>";
	echo $lang['users_importto_ucenter'];
	echo "</h3>\r\n\r\n<table width=\"550\" class=\"list\">\r\n<tr>\r\n    <td colspan=\"2\">";
	printf($lang['user_startid_intro'], $maxuid, $maxuid);
	echo "</td>\r\n</tr>\r\n<tr>\r\n    <td width=\"125\" align=\"left\">";
	echo $lang['user_merge_method'];
	echo "</td>\r\n    <td align=\"left\"><input type=\"radio\" name=\"js-merge\" id=\"js-merge-1\" value=\"1\" checked=\"true\" /><label for=\"js-merge-1\">";
	echo $lang['user_merge_method_1'];
	echo '</label><br /><input name="js-merge" type="radio" value="2" id="js-merge-2" /><label for="js-merge-2">';
	echo $lang['user_merge_method_2'];
	echo "</label>\r\n      <span id=\"notice\" style=\"color:#FF0000\"></span></td>\r\n</tr>\r\n<tr><td>&nbsp;</td><td></td></tr>\r\n</table>\r\n\r\n\r\n  </div></td>\r\n<td width=\"227\" valign=\"top\" background=\"images/install-step3-";
	echo $installer_lang;
	echo ".gif\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n  <td>\r\n  <div id=\"install-btn\">\r\n  <input type=\"button\" class=\"button\" id=\"js-pre-step\" class=\"button\" value=\"";
	echo $lang['prev_step'];
	echo "\" />\r\n  <input id=\"js-submit-uc\" type=\"button\" class=\"button\" value=\"";
	echo $lang['next_step'];
	echo $lang['ucenter_import_members'];
	echo "\" />\r\n</div>\r\n  </td><td></td>\r\n</tr>\r\n</table>\r\n";
}

echo "<div id=\"js-monitor\" style=\"display:none;text-align:left;position:absolute;top:45%;left:35%;width:300px;z-index:1000;border:1px solid #000;\">\r\n    <h3 id=\"js-monitor-title\">";
echo $lang['monitor_title'];
echo "</h3>\r\n    <div style=\"background:#fff;padding-bottom:20px;\">\r\n        <img id=\"js-monitor-loading\" src='images/loading.gif' /><br /><br />\r\n        <strong id=\"js-monitor-wait-please\" style='color:blue;float:left;margin-left:3px;'></strong>\r\n        <strong id=\"js-monitor-rollback\" style=\"color:red;cursor:pointer;float:left;margin-left:25px;\"></strong>\r\n        <span id=\"js-monitor-view-detail\" style=\"color:gray;cursor:pointer;float:right;margin-right:3px;\"></span>\r\n    </div>\r\n    <iframe id=\"js-monitor-notice\" src=\"templates/notice.htm\" style=\"display:none;\"></iframe>\r\n    <img id=\"js-monitor-close\" src='./images/close.gif' style=\"position:absolute;top:10px;right:10px;cursor:pointer;\" />\r\n</div>\r\n<div id=\"copyright\">\r\n    <div id=\"copyright-inside\">\r\n\r\n      ";
include ROOT_PATH . 'install/templates/copyright.php';
echo "</div>\r\n</div>\r\n</form>\r\n</body>\r\n</html>";

?>
