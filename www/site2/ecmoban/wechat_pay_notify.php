<?php
define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . 'includes/lib_payment.php');
require_once(ROOT_PATH .'includes/modules/payment/wechat_pay.php');
include_once(ROOT_PATH .'includes/lib_order.php');
$payment = new wechat_pay();
$payment->notify();
exit;
?>