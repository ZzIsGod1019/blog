<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
define('IN_ECS', true);
define('INIT_NO_SMARTY', true);
require dirname(__FILE__) . '/includes/init.php';
require ROOT_PATH . 'includes/cls_captcha.php';
$img = new captcha(ROOT_PATH . 'data/captcha/', $_CFG['captcha_width'], $_CFG['captcha_height']);
@ob_end_clean();

if (isset($_REQUEST['is_login'])) {
	$img->session_word = 'captcha_login';
}
else if (isset($_REQUEST['is_discuss'])) {
	$img->session_word = 'captcha_discuss';
}
else if (isset($_REQUEST['is_user_comment'])) {
	$img->session_word = 'is_user_comment';
}

$img->generate_image();

?>
