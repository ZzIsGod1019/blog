<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>";
echo $lang['demo_done_title'];
echo "</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n</head>\r\n\r\n<body style=\"background:#DDEEF2\">\r\n";
include ROOT_PATH . 'demo/templates/header.php';
echo "\r\n<div style=\"margin:10px;padding:20px;border: 1px solid #BBDDE5; background: #F4FAFB; \">\r\n\r\n  <p style=\"font-size: 14px; text-align: center\">";
printf($lang['done'], VERSION);
echo "</p>\r\n  <div align=\"center\">\r\n  <ul style=\"text-align:left; width: 260px\">\r\n    <li><a href=\"../\">";
echo $lang['go_to_view_my_ecshop'];
echo "</a></li>\r\n    <li><a href=\"../admin\">";
echo $lang['go_to_view_control_panel'];
echo "</a></li>\r\n  </ul>\r\n  </div>\r\n\r\n</div>\r\n\r\n";
include ROOT_PATH . 'demo/templates/copyright.php';
echo "</body>\r\n</html>\n";

?>
