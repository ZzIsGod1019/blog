<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>";
echo $lang['demo_error_title'];
echo "</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n</head>\r\n\r\n<body style=\"background:#DDEEF2\">\r\n";
include ROOT_PATH . 'demo/templates/header.php';
echo "<div style=\"margin:10px;padding:20px;border: 1px solid #BBDDE5; background: #F4FAFB; \">\r\n\r\n            <div style=\"font-size: 14px; text-align: center\">\r\n            ";
echo $err_msg;
echo "            </div>\r\n</div>\r\n                    \r\n            <div style=\"padding: 1em; border: 1px solid #BBDDE5; background: #F4FAFB; margin-top: 10px; text-align: center;\">\r\n            ";
include ROOT_PATH . 'demo/templates/copyright.php';
echo "            </div>\r\n</body>\r\n</html>\n";

?>
