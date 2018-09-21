<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\">\r\n<head>\r\n<title>";
echo $lang['install_done_title'];
echo "</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<script type=\"text/javascript\" src=\"js/common.js\"></script>\r\n<script type=\"text/javascript\" src=\"js/transport.js\"></script>\r\n</head>\r\n\r\n<body style=\"background:#DDEEF2\">\r\n<div id=\"logos\">\r\n  <div id=\"logos-inside\"> <img src=\"../admin/images/ecshop_logo.gif\" alt=\"ECSHOP\" width=\"160\" height=\"57\" /> </div>\r\n</div>\r\n<div id=\"content\">\r\n<p style=\"font-size:30px;text-align: center;margin-top:50px;\">\r\n";
echo $lang['loading'];
echo "</p>\r\n<img id=\"js-monitor-loading\" src='images/loading.gif' style=\"margin:30px 0 50px 0;\"/>\r\n</div>\r\n<div style=\"padding: 1em; border: 1px solid #BBDDE5; background: #F4FAFB; margin-top: 10px; text-align: center;\">\r\n";
include ROOT_PATH . 'install/templates/copyright.php';
echo "</div>\r\n<script type=\"text/javascript\">\r\nAjax.call('cloud.php?step=active','', active_api, 'GET', 'TEXT','FLASE');\r\nfunction active_api(result)\r\n{\r\n  if(result)\r\n  {\r\n      setInnerHTML('content',result);\r\n  }\r\n}\r\n</script>\r\n</body>\r\n</html>";

?>
