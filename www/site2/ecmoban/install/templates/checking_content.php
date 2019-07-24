<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<script type=\"text/javascript\" src=\"js/check.js\"></script>\r\n<form method=\"post\">\r\n<div class=\"install_file\">\r\n                    \t<div class=\"item\">\r\n                        \t<div class=\"title\"><h1>";
echo $lang['system_environment'];
echo "</h1></div>\r\n                            <div class=\"list\">\r\n                            \t<ul>\r\n                                ";

foreach ($system_info as $info_item) {
	echo "                                \t<li>\r\n                                    \t<div class=\"list-left\">";
	echo $info_item[0];
	echo "</div>\r\n                                        <div class=\"list-right\">";
	echo $info_item[1];
	echo "</div>\r\n                                    </li>\r\n                                ";
}

echo "                                </ul>\r\n                            </div>\r\n                        </div>\r\n                        <div class=\"item\">\r\n                        \t<div class=\"title\"><h1>";
echo $lang['dir_priv_checking'];
echo "</h1></div>\r\n                            <div class=\"list\">\r\n                            \t<ul>\r\n                                ";

foreach ($dir_checking as $checking_item) {
	echo "                                    <li>\r\n                                    \t<div class=\"list-left\">";
	echo $checking_item[0];
	echo "</div>\r\n                                        <div class=\"list-right green\">\r\n                                            ";

	if ($checking_item[1] == $lang['can_write']) {
		echo '                                               ';
		echo $checking_item[1];
		echo '                                            ';
	}
	else {
		echo '                                               ';
		echo $checking_item[1];
		echo '                                            ';
	}

	echo "                                        </div>\r\n                                    </li>\r\n                                ";
}

echo "                                </ul>\r\n                            </div>\r\n                        </div>\r\n                        <div class=\"item ";

if (empty($rename_priv)) {
	echo ' last ';
}

echo "\">\r\n                        \t<div class=\"title\"><h1>";
echo $lang['template_writable_checking'];
echo "</h1></div>\r\n                            <div class=\"list\">\r\n                            ";

if ($has_unwritable_tpl == 'yes') {
	echo '              					';

	foreach ($template_checking as $checking_item) {
		echo '                            		<p style="color:red;">';
		echo $checking_item;
		echo "</p>\r\n                                ";
	}

	echo '              				';
}
else {
	echo '                             		<p class="green">';
	echo $template_checking;
	echo "</p>\r\n          \t\t\t\t\t";
}

echo "                            </div>\r\n                        </div>\r\n                         ";

if (!empty($rename_priv)) {
	echo "                        <div class=\"item last\">\r\n                        \t<div class=\"title\"><h1>";
	echo $lang['rename_priv_checking'];
	echo "</h1></div>\r\n                            <div class=\"list\">\r\n                           \t\t";

	foreach ($rename_priv as $checking_item) {
		echo '                            		<p style="color:red;">';
		echo $checking_item;
		echo "</p>\r\n                             \t";
	}

	echo "                            </div>\r\n                        </div>\r\n                         ";
}

echo "                    </div>\r\n                    <div class=\"tfoot\">\r\n                    \t<div class=\"tfoot_left\">\r\n                        \t<input type=\"button\" class=\"btn aga\" id=\"js-recheck\" value=\"";
echo $lang['recheck'];
echo "\"  />\r\n                        </div>\r\n                        <div class=\"tfoot_right\">\r\n                        \t<input type=\"button\" class=\"btn\" id=\"js-pre-step\" value=\"";
echo $lang['prev_step'];
echo $lang['welcome_page'];
echo "\"  />\r\n                            <input type=\"submit\" class=\"btn last btn-curr\" id=\"js-submit\" value=\"";
echo $lang['next_step'] . $lang['config_system'];
echo '" ';
echo $disabled;
echo " />\r\n                        </div>\r\n                        <input name=\"userinterface\" id=\"userinterface\" type=\"hidden\" value=\"";
echo $userinterface;
echo "\" />\r\n                        <input name=\"ucapi\" type=\"hidden\" value=\"";
echo $ucapi;
echo "\" />\r\n                        <input name=\"ucfounderpw\" type=\"hidden\" value=\"";
echo $ucfounderpw;
echo "\" />\r\n                    </div>\r\n</form>";

?>
