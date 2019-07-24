<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<html>\r\n<head>\r\n<title> ";
echo $lang['readme_title'];
echo " </title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<style type=\"text/css\">\r\n#logos { background: #278296; border-bottom: 1px solid #FFF; }\r\n#submenu-div { background: #80BDCB; height: 24px; border-bottom: 1px solid #FFF; }\r\n#wrapper { background: #F4FAFB; padding: 10px; border: 1px solid #BBDDE5; margin-top: 20px; width: 95%;}\r\n</style>\r\n</head>\r\n\r\n<body>\r\n";
include ROOT_PATH . 'demo/templates/header.php';
echo "\r\n<div id=\"wrapper\" style=\"text-align:left;\">\r\n\r\n<h3>";
echo $lang['method'];
echo "</h3>\r\n<p>";
printf($lang['notice'], $new_version);
echo "</p>\r\n<ol>\r\n    <li>";
echo $lang['usage1'];
echo "</li>\r\n    <li>";
echo $lang['usage2'];
echo "</li>\r\n    <li>";
printf($lang['usage3'], $old_version);
echo "</li>\r\n<!--    <li>";
printf($lang['usage4'], $new_version);
echo "</li>\r\n    <li>";
echo $lang['usage5'];
echo "</li>\r\n    <li>";
echo $lang['usage6'];
echo "</li>-->\r\n</ol>\r\n<!--\r\n<h3>";
echo $lang['faq'];
echo "</h3>\r\n<iframe src=\"templates/faq_";
echo $updater_lang;
echo '_';
echo $ec_charset;
echo ".htm\" width=\"730\" height=\"350\"></iframe>-->\r\n<div align=\"center\">\r\n<input type=\"submit\" id=\"js-submit\" class=\"button\" value=\"";
echo $lang['next_step'];
echo $lang['check_system_environment'];
echo '" onclick="top.location=\'index.php?step=check&ui=';
echo $ui;
echo "'\" />\r\n\r\n</div>\r\n</div>\r\n";
include ROOT_PATH . 'demo/templates/copyright.php';
echo "</body>\r\n</html>\r\n";

?>
