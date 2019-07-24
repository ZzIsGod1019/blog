<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>";
echo $lang['install_done_title'];
echo "</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<link href=\"styles/install.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<script type=\"text/javascript\" src=\"js/common.js\"></script>\r\n<script type=\"text/javascript\" src=\"js/transport.js\"></script>\r\n</head>\r\n\r\n<body>\r\n";
include ROOT_PATH . 'install/templates/header.php';
echo "    <div class=\"wrapper\">\r\n    \t<div class=\"w1000\" id=\"content\">\r\n        \t\r\n        </div>\r\n        <div class=\"footer\">\r\n            ";
include ROOT_PATH . 'install/templates/copyright.php';
echo "        </div>\r\n    </div>\r\n<script type=\"text/javascript\">\r\nAjax.call('cloud.php?step=done','', welcome_api, 'GET', 'TEXT','FLASE');\r\nfunction welcome_api(result)\r\n{\r\n  if(result)\r\n  {\r\n    setInnerHTML('content',result);\r\n  }\r\n}\r\n</script>\r\n\r\n</body>\r\n</html>";

?>
