<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<form id=\"js-setting\">\r\n                        <div class=\"install_file install_config\">\r\n                        \t<div class=\"item\" style=\"display:none\">\r\n                                <div class=\"title\"><h1>";
echo $lang['mobile_check'];
echo "</h1></div>\r\n                                <div class=\"list\">\r\n                                    <div class=\"item\">\r\n                                        <div class=\"label\">";
echo $lang['mobile_num'];
echo "</div>\r\n                                        <div class=\"value\"><input type=\"text\" name=\"mobile\" style=\"width:200px;\" class=\"text\" value=\"\" />&nbsp;&nbsp;<input id=\"send_mobile_code\" class=\"send_mobile_code\" type=\"button\" value=\"";
echo $lang['send_mobile_code'];
echo "\" /></div>\r\n                                    </div>\r\n                                    <div class=\"item\">\r\n                                        <div class=\"label\">";
echo $lang['mobile_check_code'];
echo "</div> \r\n                                        <div class=\"value\"><input type=\"text\" name=\"mobile_code\"  value=\"\" class=\"text\"/><br/><span class=\"ts\">(";
echo $lang['mobile_code_explain'];
echo ")</span></div>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                            <div class=\"item\">\r\n                                <div class=\"title\"><h1>";
echo $lang['db_account'];
echo "</h1></div>\r\n                                <div class=\"list\">\r\n                                    <div class=\"item\">\r\n                                        <div class=\"label\">";
echo $lang['db_host'];
echo "</div>\r\n                                        <div class=\"value\"><input type=\"text\" name=\"js-db-host\" class=\"text\" value=\"localhost\" /></div>\r\n                                    </div>\r\n                                    <div class=\"item\">\r\n                                        <div class=\"label\">";
echo $lang['db_port'];
echo "</div> \r\n                                        <div class=\"value\"><input type=\"text\" name=\"js-db-port\"  value=\"3306\" class=\"text\"/></div>\r\n                                    </div>\r\n                                    <div class=\"item\">\r\n                                        <div class=\"label\">";
echo $lang['db_user'];
echo "</div> \r\n                                        <div class=\"value\"><input type=\"text\" name=\"js-db-user\" class=\"text\" value=\"root\" /></div>\r\n                                    </div>\r\n                                    <div class=\"item\">\r\n                                        <div class=\"label\">";
echo $lang['db_pass'];
echo "</div>\r\n                                        <div class=\"value\"><input type=\"password\" name=\"js-db-pass\" class=\"text\" value=\"\" /></div>\r\n                                    </div>\r\n                                    <div class=\"item\">\r\n                                        <div class=\"label\">";
echo $lang['db_name'];
echo "</div> \r\n                                        <div class=\"value\">\r\n                                        \t<input type=\"text\" name=\"js-db-name\" id=\"\" class=\"text\" value=\"\" />\r\n                                            <div class=\"rot\"><span>或</span><select id=\"sqlSelect\" name=\"js-db-list\"><option>";
echo $lang['db_list'];
echo "</option></select></div>\r\n                                        </div>\r\n                                    </div>\r\n                                    <div class=\"item\">\r\n                                        <div class=\"label\">";
echo $lang['db_prefix'];
echo "</div> \r\n                                        <div class=\"value\"><input type=\"text\" name=\"js-db-prefix\" class=\"text\" value=\"dsc_\" /><br/><span class=\"ts\">(";
echo $lang['change_prefix'];
echo ")</span></div>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                            <div class=\"item\">\r\n                                <div class=\"title\"><h1>";
echo $lang['admin_account'];
echo "</h1></div>\r\n                                <div class=\"list\">\r\n                                \t<div class=\"item\">\r\n                                    \t<div class=\"label\">";
echo $lang['admin_name'];
echo "</div> \r\n                                        <div class=\"value\"><input type=\"text\" name=\"js-admin-name\" class=\"text\" value=\"\" /></div>\r\n                                    </div>\r\n                                    <div class=\"item\">\r\n                                    \t<div class=\"label\">";
echo $lang['admin_password'];
echo "</div> \r\n                                        <div class=\"value\">\r\n                                        <input type=\"password\" name=\"js-admin-password\" class=\"text\" value=\"\" />\r\n                                        <span id=\"js-admin-password-result\"></span>\r\n                                        </div>\r\n                                        <div class=\"pwd-strength weak\" id=\"Safety_style\">\r\n                                        \t<span class=\"pwd-strength-weak\">";
echo $lang['pwd_lower'];
echo "</span>\r\n                                            <span class=\"pwd-strength-middle\">";
echo $lang['pwd_middle'];
echo "</span>\r\n                                            <span class=\"pwd-strength-strong\">";
echo $lang['pwd_high'];
echo "</span>\r\n                                        </div>\r\n                                    </div>\r\n                                    <div class=\"item\">\r\n                                    \t<div class=\"label\">";
echo $lang['admin_password2'];
echo "</div> \r\n                                        <div class=\"value\">\r\n                                        <input type=\"password\" name=\"js-admin-password2\" class=\"text\" value=\"\" />\r\n                                        <span id=\"js-admin-confirmpassword-result\"></span>\r\n                                        </div>\r\n                                    </div>\r\n                                    <div class=\"item\">\r\n                                    \t<div class=\"label\">";
echo $lang['admin_email'];
echo "</div> \r\n                                        <div class=\"value\"><input type=\"text\" name=\"js-admin-email\" class=\"text\" value=\"\" /></div>\r\n                                    </div>\r\n                                </div>\r\n                            </div>\r\n                            <div class=\"item last\">\r\n                                <div class=\"title\"><h1>";
echo $lang['mix_options'];
echo "</h1></div>\r\n                                <div class=\"list\">\r\n                                    <div class=\"item\">\r\n                                    \t<div class=\"label\">";
echo $lang['select_lang_package'];
echo "</div> \r\n                                        <div class=\"value\">\r\n                                        \t";

if (EC_CHARSET == 'gbk') {
	echo "                                        \t<div class=\"item-item\">\r\n                                            \t<input type=\"radio\" name=\"js-system-lang\" id=\"js-system-lang-zh_cn\" value=\"zh_cn\" checked='true'/>\r\n                                                <label for=\"chinese\">";
	echo $lang['simplified_chinese'];
	echo "</label>\r\n                                                <label class=\"yellow\" for=\"yzm\">(";
	echo $lang['current_version_lang'];
	echo ")</label>\r\n                                            </div>\r\n                                            ";
}
else if (EC_CHARSET == 'utf-8') {
	echo "                                            <div class=\"item-item\">\r\n                                            \t<input type=\"radio\" name=\"js-system-lang\" id=\"js-system-lang-zh_cn\" value=\"zh_cn\"/>\r\n                                                <label for=\"chinese\">";
	echo $lang['simplified_chinese'];
	echo "</label>\r\n                                                <label class=\"yellow\" for=\"yzm\">(";
	echo $lang['current_version_lang'];
	echo ")</label>\r\n                                            </div>\r\n                                            <div class=\"item-item\" style=\"display:none;\">\r\n                                            \t<input type=\"radio\" name=\"js-system-lang\" disabled id=\"js-system-lang-zh_tw\" value=\"zh_tw\" />\r\n                                                <label for=\"tchinese\">";
	echo $lang['traditional_chinese'];
	echo "</label>\r\n                                            </div>\r\n                                            <div class=\"item-item\" style=\"display:none;\">\r\n                                            \t<input type=\"radio\" name=\"js-system-lang\" disabled id=\"js-system-lang-en_us\" value=\"en_us\" />\r\n                                                <label for=\"english\">";
	echo $lang['americanese'];
	echo "</label>\r\n                                            </div>\r\n                                            ";
}
else if (EC_CHARSET == 'big5') {
	echo "                                            <div class=\"item-item\" style=\"display:none;\">\r\n                                            \t<input type=\"radio\" name=\"js-system-lang\" disabled id=\"js-system-lang-zh_tw\" value=\"zh_tw\" checked='true' />\r\n                                                <label for=\"tchinese\">";
	echo $lang['traditional_chinese'];
	echo "</label>\r\n                                            </div>\r\n                                            ";
}

echo "                                        </div>\r\n                                    </div>\r\n                                    ";

if ($show_timezone == 'yes') {
	echo "                                    <div class=\"item\">\r\n                                    \t<div class=\"label\">";
	echo $lang['set_timezone'];
	echo "</div> \r\n                                        <div class=\"value\">\r\n                                        \t<select name=\"js-timezones\" class=\"text\">\r\n                                            ";

	foreach ($timezones as $key => $item) {
		echo '                                                        <option value="';
		echo $key;
		echo '" ';

		if ($key == $local_timezone) {
			echo 'selected="true"';
			$found = true;
		}

		echo '>';
		echo $item;
		echo "</option>\r\n                                            ";
	}

	echo '                                            ';

	if (!$found) {
		echo '                                                        <option value="';
		echo $local_timezone;
		echo '" selected="true">';
		echo $local_timezone;
		echo "</option>\r\n                                            ";
	}

	echo "                                               </select>\r\n                                        </div>\r\n                                    </div>\r\n                                    ";
}

echo "                                    <div class=\"item\">\r\n                                    \t<div class=\"label\">";
echo $lang['disable_captcha'];
echo "</div> \r\n                                        <div class=\"value\">\r\n                                        \t<div class=\"check-item\">\r\n                                                <input type=\"checkbox\" id=\"yzm\" name=\"js-disable-captcha\" ";
echo $checked . $disabled;
echo " />\r\n                                                <label class=\"yellow\" for=\"yzm\">(";
echo $lang['captcha_notice'];
echo ")</label>\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                    ";

if (file_exists(ROOT_PATH . 'demo')) {
	echo "                                    <div class=\"item\">\r\n                                    \t<div class=\"label\">";
	echo $lang['install_demo'];
	echo "</div> \r\n                                        <div class=\"value\">\r\n                                        \t<div class=\"check-item\">\r\n                                        \t<input type=\"checkbox\" name=\"js-install-demo\" id=\"testData\"/>\r\n                                            <label class=\"yellow\" for=\"testData\">(";
	echo $lang['demo_notice'];
	echo ")</label>\r\n                                            </div>\r\n                                        </div>\r\n                                    </div>\r\n                                    ";
}

echo "                                </div>\r\n                            </div>\r\n                        </div>\r\n                        <div class=\"tfoot\">\r\n                            <div class=\"tfoot_right\">\r\n                            <input type=\"button\" id=\"js-pre-step\" class=\"btn\" value=\"";
echo $lang['prev_step'] . $lang['check_system_environment'];
echo "\" />\r\n                            <input id=\"js-install-at-once\" type=\"submit\" class=\"btn last btn-curr\" value=\"";
echo $lang['install_at_once'];
echo "\" />\r\n                            </div>\r\n                            <input name=\"userinterface\" type=\"hidden\" value=\"";
echo $userinterface;
echo "\" />\r\n                            <input name=\"ucapi\" type=\"hidden\" value=\"";
echo $ucapi;
echo "\" />\r\n                            <input name=\"ucfounderpw\" type=\"hidden\" value=\"";
echo $ucfounderpw;
echo "\" />\r\n                        </div>\r\n                        \r\n                    </form>\r\n";

?>
