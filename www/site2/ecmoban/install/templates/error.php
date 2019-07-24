<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>";
echo $lang['install_error_title'];
echo "</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<link href=\"styles/install.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n</head>\r\n\r\n<body>\r\n";
include ROOT_PATH . 'install/templates/header.php';
echo "    <div class=\"wrapper\">\r\n    \t<div class=\"w1000\">\r\n        \t<div class=\"install_end\">\r\n            \t<div class=\"end_left\"><img src=\"./images/fail.png\" /></div>\r\n                <div class=\"end_right\">\r\n                        <h1>";

if ($exists == 1) {
	echo $lang['install_done_title'];
}
else {
	echo $lang['install_error_title'];
}

echo "</h1>\r\n                    <span></span>\r\n                    <p>";
echo $err_msg;
echo "</p>\r\n                </div>\r\n            </div>\r\n        </div>\r\n        <div class=\"footer\">\r\n            ";
include ROOT_PATH . 'install/templates/copyright.php';
echo "        </div>\r\n    </div>\r\n</body>\r\n</html>";

?>
