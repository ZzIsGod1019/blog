<?php

//zend by QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');
include_once(ROOT_PATH . '/includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);

$user_id = $_SESSION['user_id'];
$action  = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'default';

if ( $action =='default' ){
	

	$pay_id = intval($_GET['id']);
	
    include_once(ROOT_PATH .'includes/cls_json.php');
    $json = new JSON();

    $result = array('error'=>0, 'message'=>'', 'content'=>'');

    if(isset($_SESSION['last_order_query']))
    {
        if(time() - $_SESSION['last_order_query'] < 1)
        {
            $result['error'] = 1;
            $result['message'] = $_LANG['order_query_toofast'];
            die($json->encode($result));
        }
    }
    $_SESSION['last_order_query'] = time();

    if (empty($pay_id))
    {
        $result['error'] = 1;
        $result['message'] = $_LANG['invalid_order_sn'];
        die($json->encode($result));
    }
	$sql = "SELECT * ".
           " FROM " . $ecs->table('pay_log').
           " WHERE log_id = '$pay_id' LIMIT 1";
		   
    $row = $db->getRow($sql);
    if (empty($row))
    {
        $result['error'] = 1;
        $result['message'] = $_LANG['invalid_order_sn'];
        die($json->encode($result));
    }
	$order_type = $row['order_type'];
	$url = 'user.php?act=order_detail&order_id='.$row['order_id'];
	if ( $order_type == 1  ){
		$url = 'user.php?act=account_log';
	}
	if( $row['is_paid'] == 1){
		$result['url'] 		= $url;
	}
	$result['is_paid'] 	= $row['is_paid'];
    die($json->encode($result));
}
elseif( $action =='qrcode'  ){
	
	$data = isset($_GET['data']) ? trim($_GET['data'])  : 'https://dsc.shjm-wl.com' ;
	
	if(file_exists(ROOT_PATH . 'includes/phpqrcode/phpqrcode.php')){
		include_once(ROOT_PATH . 'includes/phpqrcode/phpqrcode.php');
	}elseif(file_exists(ROOT_PATH . 'includes/phpqrcode.php')){
		include_once(ROOT_PATH . 'includes/phpqrcode.php');
	}
	// 纠错级别：L、M、Q、H 
	$errorCorrectionLevel = 'Q';  
	// 点的大小：1到10 
	$matrixPointSize = 7;

	$QR = QRcode::png($data, false, $errorCorrectionLevel, $matrixPointSize, 2);
	
}

?>