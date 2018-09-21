<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\r\n<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<title>";
echo $lang['configure_uc'];
echo "</title>\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<script type=\"text/javascript\" src=\"../js/transport.js\" charset=\"";
echo EC_CHARSET;
echo "\"></script>\r\n<script type=\"text/javascript\" src=\"js/common.js\"></script>\r\n<script type=\"text/javascript\" src=\"js/uccheck.js\"></script>\r\n<script type=\"text/javascript\" src=\"js/draggable.js\"></script>\r\n<script type=\"text/javascript\">\r\nvar \$_LANG = {};\r\n";

foreach ($lang['js_languages'] as $key => $item) {
	echo '$_LANG["';
	echo $key;
	echo '"] = "';
	echo $item;
	echo "\";\r\n";
}

echo "</script>\r\n</head>\r\n<body id=\"checking\">\r\n";
include ROOT_PATH . 'upgrade/templates/header.php';
echo "<form id=\"js-setup\" method=\"post\" onsubmit=\"return setupUCenter()\">\r\n<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"uc_table\">\r\n<tr>\r\n<td valign=\"top\">\r\n<div id=\"wrapper\">\r\n  <h3>";
echo $lang['ucenter'];
echo "</h3>\r\n\r\n<table width=\"550\" class=\"list\">\r\n<tr>\r\n    <td colspan=\"2\">";
echo $lang['uc_intro'];
echo "</td>\r\n    </tr>\r\n<tr>\r\n    <td width=\"180\" align=\"right\">";
echo $lang['ucapi'];
echo "</td>\r\n    <td align=\"left\"><input name=\"js-ucapi\" type=\"text\" id=\"js-ucapi\"  value=\"";
echo $ucapi;
echo "\" size=\"40\" />  <span id=\"ucapinotice\" style=\"color:#FF0000\"></span></td>\r\n</tr>\r\n<tr id=\"ucip\" style=\"display:none\">\r\n    <td width=\"180\" align=\"right\" valign=\"top\">";
echo $lang['ucip'];
echo "</td>\r\n    <td align=\"left\"><input name=\"js-ucip\" type=\"text\" id=\"js-ucip\"  value=\"";
echo $ucip;
echo '" size="40" /><span id="ucipnotice" style="color:#FF0000">';
echo $lang['ucip_intro'];
echo "</span></td>\r\n</tr>\r\n<tr>\r\n\r\n            <td width=\"180\" align=\"right\">";
echo $lang['ucfounderpw'];
echo "</td>\r\n            <td align=\"left\"><input name=\"js-ucfounderpw\" type=\"password\" id=\"js-ucfounderpw\"  value=\"";
echo $ucfounderpw;
echo "\" size=\"40\" /> <span id=\"ucfounderpwnotice\" style=\"color:#FF0000\"></span></td>\r\n</tr>\r\n<tr><td>&nbsp;</td><td></td></tr>\r\n</table>\r\n\r\n\r\n  </div></td>\r\n<td width=\"227\" valign=\"top\" background=\"images/install-step3-";
echo $installer_lang;
echo ".gif\">&nbsp;</td>\r\n</tr>\r\n<tr>\r\n  <td><div id=\"install-btn\"><input type=\"button\" class=\"button\" id=\"js-pre-step\" class=\"button\" value=\"";
echo $lang['prev_step'];
echo $lang['welcome_page'];
echo '"  /> <input id="js-submit" type="button" class="button" value="';
echo $lang['check_ucenter'];
echo "\" /></div>\r\n  </td><td></td>\r\n</tr>\r\n</table>\r\n<div id=\"js-monitor\" style=\"display:none;text-align:left;position:absolute;top:45%;left:35%;width:300px;z-index:1000;border:1px solid #000;\">\r\n    <h3 id=\"js-monitor-title\">";
echo $lang['monitor_title'];
echo "</h3>\r\n    <div style=\"background:#fff;padding-bottom:20px;\">\r\n        <img id=\"js-monitor-loading\" src='images/loading.gif' /><br /><br />\r\n        <strong id=\"js-monitor-wait-please\" style='color:blue;width:65%;float:left;margin-left:3px;'></strong>\r\n        <span id=\"js-monitor-view-detail\" style=\"color:gray;cursor:pointer;;float:right;margin-right:3px;\"></span>\r\n    </div>\r\n    <iframe id=\"js-monitor-notice\" src=\"templates/notice.htm\" style=\"display:none;\"></iframe>\r\n    <img id=\"js-monitor-close\" src='./images/close.gif' style=\"position:absolute;top:10px;right:10px;cursor:pointer;\" />\r\n</div>\r\n<div id=\"copyright\">\r\n    <div id=\"copyright-inside\">\r\n\r\n      ";
include ROOT_PATH . 'install/templates/copyright.php';
echo "</div>\r\n</div>\r\n</form>\r\n</body>\r\n</html>";

?>
