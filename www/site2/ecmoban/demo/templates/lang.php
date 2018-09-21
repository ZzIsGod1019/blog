<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
echo "<html>\r\n<head>\r\n<title> ";
echo $lang['select_language_title'];
echo " </title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=";
echo $ec_charset;
echo "\" />\r\n<link href=\"styles/general.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n<style type=\"text/css\">\r\n#wrapper { background: #F4FAFB; padding: 10px; border: 1px solid #BBDDE5; margin: 20px 0 20px; width: 95%;}\r\n#wrapper fieldset{border:1px solid #BBDDE5;padding:0.5em;}\r\n#wrapper fieldset legend{font-weight:bold;}\r\n#wrapper fieldset select{width:90%;}\r\n</style>\r\n</head>\r\n\r\n<body>\r\n";
include ROOT_PATH . 'demo/templates/header.php';
echo "\r\n<div id=\"wrapper\" style=\"text-align:left;\">\r\n<form target=\"_parent\" action=\"\" method=\"post\">\r\n<fieldset>\r\n    <legend dir=\"ltr\">";
echo $lang['lang_title'];
echo "</legend>\r\n    <select dir=\"ltr\" onchange=\"this.form.submit();\" name=\"lang\" style=\"width:300px;\">\r\n    ";

foreach ($lang['lang_charset'] as $key => $val) {
	if ($updater_lang . '_' . $ec_charset == $key) {
		$lang_selected = 'selected="selected" ';
	}
	else {
		$lang_selected = '';
	}

	echo '        <option ';
	echo $lang_selected;
	echo 'value="';
	echo $key;
	echo '">';
	echo $val;
	echo "</option>\r\n    ";
}

echo "    </select>\r\n</fieldset>\r\n</form>\r\n<form target=\"_parent\" action=\"\" method=\"post\">\r\n<fieldset>\r\n    <legend dir=\"ltr\">";
echo $lang['ui_title'];
echo "</legend>\r\n    <input type=\"radio\" id=\"ui_1\" name=\"ui\" value=\"ecshop\" checked=\"checked\" /><label for=\"ui_1\">";
echo $lang['ui_ecshop'];
echo "</label>\r\n    <!--<input type=\"radio\" id=\"ui_2\" name=\"ui\" value=\"ucenter\" /><label for=\"ui_2\">";
echo $lang['ui_ucenter'];
echo "</label>-->\r\n</fieldset>\r\n<fieldset>\r\n    <legend>";
echo $lang['lang_description'];
echo "</legend>\r\n    <ul>\r\n        ";

foreach ($lang['lang_desc'] as $desc) {
	echo '        <li>';
	echo $desc;
	echo "</li>\r\n        ";
}

echo "    </ul>\r\n    <input type=\"hidden\" name=\"step\" value=\"readme\" />\r\n    <input type=\"hidden\" name=\"lang\" value=\"";
echo $updater_lang . '_' . $ec_charset;
echo "\" />\r\n    <input type=\"submit\" class=\"button\" value=\"";
echo $lang['next_step'];
echo $lang['readme_page'];
echo "\" />\r\n    <!--<input type=\"button\" class=\"button btn-1\" value=\"";
echo $lang['goto_charset_convert'];
echo "\" onclick=\"top.location='convert.php'\" />\r\n    <input type=\"button\" class=\"button btn-1\" value=\"";
echo $lang['goto_members_import'];
echo "\" onclick=\"top.location='ucimport.php'\" />-->\r\n</fieldset>\r\n</form>\r\n\r\n</div>\r\n\r\n";
include ROOT_PATH . 'demo/templates/copyright.php';
echo "</body>\r\n</html>\r\n";

?>
