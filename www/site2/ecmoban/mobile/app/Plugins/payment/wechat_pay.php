<?php
/**
 * ECSHOP 微信支付
 * $Author: https://dsc.shjm-wl.com $
 * $Id: wechat_pay.php 17063 2017-11-03 06:35:46Z https://dsc.shjm-wl.com $
 */
 
defined('IN_ECTOUCH') or die('Deny Access');

if (!defined('WXPAY_DEBUG'))
{
	define("WXPAY_DEBUG", false);
}
/**
 * 微信支付类
 */
class wechat_pay
{
	private $dir  ;
	private $site_url;

	
	/**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
	 function get_code($order, $payment, $go = 0)
	{
		$this->payment = $payment;
		include_once(BASE_PATH.'Helpers/payment_helper.php');
		
		if ( $this->is_wechat_browser() ){
				
			return $this->get_code_jspay($order, $payment, $go);
		}
		
		return $this->get_code_h5($order, $payment, $go);
	}
	
	function get_code_jspay($order, $payment, $go = 0)
	{
		
		
		$openId = $_SESSION['pay_openid'];
		
		if ( empty( $openId ) && $go == 0   ) {	
			$url = url('Dream/index/wechatPay', array('id'=>$order['log_id'],'pay'=>basename(__FILE__, '.php') ));

$url=__URL__.'/index.php?m=dream&a=wechatPay&pay=wechat_pay&id='.$order['log_id'];
			
			$html = '<a type="button" id="pay_wxpay" class="box-flex btn-submit" href="'. $url  .'">微信安全支付</a>';
			if( strtolower(MODULE_NAME) == 'user'  ){
				return $html;
			}
			Header("Location:$url");
			exit();
        }

		// 网页授权获取用户openid
        if (! isset($_SESSION['pay_openid']) || empty($_SESSION['pay_openid']) || $_SESSION['pay_openid'] == -1 ) {
			return $this->return_error('获取openid失败');
        }
		
		
		$sql = "select * from " . $GLOBALS['ecs']->table('pay_log') . "  WHERE log_id = '". $order['log_id'] ."' ";
		$pay_log = $GLOBALS['db']->getRow($sql);		
		if (!empty( $pay_log ) ){
			if ( $pay_log['order_type'] == 0 ){
				//$sql = "select goods_name from " . $GLOBALS['ecs']->table('order_goods') . "  WHERE order_id = '". $pay_log['order_id'] ."' ";
				//$body = $GLOBALS['db']->getOne($sql);	
				//$body = $this->msubstr($body,0, 20);
				$body = '购物订单号：'.$order['order_sn'];
			}
			elseif ( $pay_log['order_type'] == 1 ){
				$body = '在线充值';
			}else{
				$body = $order['order_sn'];
			}
		}
		
		$notify_url = notify_url(basename(__FILE__, '.php'));
		$return_url	= return_url(basename(__FILE__, '.php'));
		
		$out_trade_no = $order['order_sn'].'O'.$order['log_id'] .'O'.(date('is'));
		//$out_trade_no = $order['order_sn'].'O'.$order['log_id'] .'O'.( $order['order_amount'] * 100);
		
        $this->logResult("log::get_code::notify_url:".$notify_url);

        $this->setParameter("openid", $openId);
        $this->setParameter("body",	 $body);
        $this->setParameter("out_trade_no", $out_trade_no); 
        $this->setParameter("total_fee", $order['order_amount'] * 100); 
        $this->setParameter("notify_url", $notify_url);
        $this->setParameter("trade_type", "JSAPI");
        //$this->setParameter("input_charset", $charset);
		
		$this->setParameter("product_id", $order['order_sn']);
		$this->setParameter("attach", $order['log_id']);
		$this->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);


        $wxpay_order = $this->unifiedOrder();
		
		if ( $wxpay_order['return_code'] == 'FAIL' ){
			$error = $wxpay_order['return_msg'];
			return $this->return_error($error);
		}
		if ( $wxpay_order['result_code'] == 'FAIL' ){
			$error = $wxpay_order['err_code'].' '.$wxpay_order['err_code_des'];
			return $this->return_error($error);
		}
		$prepay_id = $wxpay_order["prepay_id"];
		
		$jsApiParameters = $this->getParameters($prepay_id);
		if ( empty($jsApiParameters) ){
			$error = '获取支付jsApiParameters失败';
			return $this->return_error($error);
		}
		
		$js = '<script type="text/javascript">
			function jsApiCall()
			{
				WeixinJSBridge.invoke(
					"getBrandWCPayRequest",
					'.$jsApiParameters.',
					function(res){
						//WeixinJSBridge.log(res.err_msg);
						if(res.err_msg == "get_brand_wcpay_request:ok"){
							//alert(res.err_code+res.err_desc+res.err_msg);
							window.location.href = "'. $return_url .'";
						}else{
							//返回跳转到订单详情页面
							//alert(支付失败);
							//window.location.href = "./index.php";
						}
					}
				);
			}
			function Callpay()
			{
				if (typeof WeixinJSBridge == "undefined"){
					if( document.addEventListener ){
						document.addEventListener("WeixinJSBridgeReady", jsApiCall, false);
					}else if (document.attachEvent){
						document.attachEvent("WeixinJSBridgeReady", jsApiCall); 
						document.attachEvent("onWeixinJSBridgeReady", jsApiCall);
					}
				}else{
					jsApiCall();
				}
			}
			</script>';
			if ( $go == 1 ){
				$js.='<script type="text/javascript">Callpay();</script>';
			}
		$html = '<a type="button" class="box-flex btn-submit" onclick="Callpay()">微信安全支付</a>'.$js;
  
        return $html;
	}
	
	
	function get_code_h5($order, $payment, $go = 0){
		
		if ( $go == 0 && ( strtolower(MODULE_NAME) == 'flow' || ( strtolower(MODULE_NAME) == 'user' && strtolower(CONTROLLER_NAME) == 'account' && strtolower(ACTION_NAME) == 'account'  )) ){
			$url = url('Dream/index/wechatPay', array('id'=>$order['log_id'],'pay'=>basename(__FILE__, '.php') ));
			Header("Location:$url");
			exit();
		}
		

		$sql = "select * from " . $GLOBALS['ecs']->table('pay_log') . "  WHERE log_id = '". $order['log_id'] ."' ";
		$pay_log = $GLOBALS['db']->getRow($sql);		
		if (!empty( $pay_log ) ){
			if ( $pay_log['order_type'] == 0 ){
				//$sql = "select goods_name from " . $GLOBALS['ecs']->table('order_goods') . "  WHERE order_id = '". $pay_log['order_id'] ."' ";
				//$body = $GLOBALS['db']->getOne($sql);	
				//$body = $this->msubstr($body,0, 20);
				$body = '购物订单号：'.$order['order_sn'];
			}
			elseif ( $pay_log['order_type'] == 1 ){
				$body = '在线充值';
			}else{
				$body = $order['order_sn'];
			}
		}
		
		$notify_url = notify_url(basename(__FILE__, '.php'));
		$return_url	= return_url(basename(__FILE__, '.php'));
		
		$out_trade_no = $order['order_sn'].'O'.$order['log_id'] .'O'.(date('is'));
		//$out_trade_no = $order['order_sn'].'O'.$order['log_id'] .'O'.( $order['order_amount'] * 100);
		
        $this->logResult("log::get_code::notify_url:".$notify_url);

        //$this->setParameter("openid", $openId);
        $this->setParameter("body",	 $body);
        $this->setParameter("out_trade_no", $out_trade_no); 
        $this->setParameter("total_fee", $order['order_amount'] * 100); 
        $this->setParameter("notify_url", $notify_url);
        $this->setParameter("trade_type", "MWEB");
        //$this->setParameter("input_charset", $charset);
		
		$this->setParameter("product_id", $order['order_sn']);
		$this->setParameter("attach", $order['log_id']);
		$this->setParameter("spbill_create_ip", $this->get_real_ip());

		$this->setParameter("scene_info", '{"h5_info": {"type":"Wap","wap_url": "'. __URL__ .'","wap_name": "https://dsc.shjm-wl.com"}}');

        $result = $this->unifiedOrder();
		
		if ( $result['return_code'] == 'FAIL' ){
			$error = $result['return_msg'];

			return $this->return_error($error);
		}
		if ( $result['result_code'] == 'FAIL' ){
			$error = $result['err_code'].' '.$result['err_code_des'];

			return $this->return_error($error);
		}
		
		if ( empty($result['mweb_url']) ){
			
			$error = '获取支付mweb_url失败';
			return $this->return_error($error);
		}
		$mweb_url = $result['mweb_url'];
		
		$script .='<script type="text/javascript">
				function get_wxpay_status( id ){
					jQuery.get("'. url('Dream/index/order_query') .'", "id="+id,function( result ){
						if ( result.error == 0 && result.is_paid == 1 ){
							window.location.href = "'. $return_url .'";
						}
					}, "json");
				}
				window.setInterval(function(){ get_wxpay_status("'. $order['log_id'] .'"); }, 2000); 
			</script>';

		$html = '<a id="pay_wxpay" class="box-flex btn-submit" href="'.$mweb_url.'" >微信安全支付</a>';
		return $html.$script;
		
		
	}
    /**
     * 响应操作
     */
    function callback($data)
    {
        return true;
		if ($data['status'] == 1) {
            return true;
        } else {
            return false;
        }
    }
	
	
	
    /**
     * 异步通知
     * @param $data
     * @return mixed
     */
    public function notify($data)
    {
        include_once(BASE_PATH.'Helpers/payment_helper.php');
		$this->logResult("log::notify::start:");
        $postStr = file_get_contents("php://input");

        if (!empty($postStr)) {
			
			$payment  = get_payment(basename(__FILE__, '.php'));
			$this->payment = $payment;
			
            $payment = get_payment($data['code']);
   
			
			$postdata =$this->xmlToArray($postStr);
			
            /* 检查插件文件是否存在，如果存在则验证支付是否成功，否则则返回失败信息 */
            // 微信端签名
			//部署后删除
            $this->logResult("log::notify::postdata",$postdata);
			
            $wxsign = $postdata['sign'];
            unset($postdata['sign']);
			
			//部署后删除
            $this->logResult("log::notify::wxsign",$wxsign);
			
			$sign=$this->getSign($postdata);
            //部署后删除
            $this->logResult("log::notify::sign:",$sign);
			
            
            // 验证成功
            if ($wxsign == $sign) {
                // 交易成功
                if ($postdata['result_code'] == 'SUCCESS') {
					
                    
					$transaction_id = $postdata['transaction_id'];
				 // 获取log_id
                    $out_trade_no	= explode('O', $postdata['out_trade_no']);
                    $order_sn		= $out_trade_no[0];
					$log_id			= (int)$out_trade_no[1]; // 订单号log_id
					$payment_amount = $postdata['total_fee']/100;
					$openid			= $postdata['openid'];
	
					
					/* 检查支付的金额是否相符 */
					if (!check_money($log_id, $payment_amount))
					{
						$returndata['return_code'] = 'FAIL';
						$returndata['return_msg'] = '支付金额不正确';
					}else{
						
					
						$sql = "update  " . $GLOBALS['ecs']->table('pay_log') . " set openid='$openid',transid='$transaction_id' WHERE log_id = '$log_id' ";
						//$GLOBALS['db']->query($sql);		
							
						$action_note = '支付订单号:' . $out_trade_no	
						. '交易号:' 
						. $transaction_id;
						
						//部署后删除
						$this->logResult("log::notify::out_trade_no:",$postdata['out_trade_no']);
						
						// 完成订单。
						order_paid($log_id, PS_PAYED, $action_note);
						$returndata['return_code'] = 'SUCCESS';
					}
                }else{
					$returndata['return_code'] = 'FAIL';
					$returndata['return_msg'] = '交易失败';
				}
                
            } else {
                $returndata['return_code'] = 'FAIL';
                $returndata['return_msg'] = '签名失败';
            }
        } else {
            $returndata['return_code'] = 'FAIL';
            $returndata['return_msg'] = '无数据返回';
        }
		
        //部署后删除
        $this->logResult("log::notify::returndata",$returndata['return_code']);
        $xml=$this->arrayToXml($returndata);
        //部署后删除
        $this->logResult("log::notify::returnxml",$xml);

        echo $xml;
        exit();
    }

	
	public function getOpenId($payment){
		
        $this->logResult("log::getOpenId::get:",$_GET);

		
        $this->logResult("log::getOpenId::payment:",$payment);
		
        if(isset($_GET['state']) && $_GET['state']=="getOpenid"){
            $code=$_GET["code"];
            if(!empty($code)){
                $wxJsonUrl="https://api.weixin.qq.com/sns/oauth2/access_token?";
                $wxJsonUrl.='appid='.$payment['wechat_pay_appid'];
                $wxJsonUrl.='&secret='.$payment['wechat_pay_appsecret'];
                $wxJsonUrl.='&code='.$code;
                $wxJsonUrl.='&grant_type=authorization_code';

                if (extension_loaded('curl') && function_exists('curl_init') && function_exists('curl_exec')){
                    $content=$this->curl_https($wxJsonUrl);
                }elseif(extension_loaded  ('openssl')){
                    $content = file_get_contents ( $wxJsonUrl );
                }else{
                    $_SESSION["pay_openid"]=-1;
                    setcookie("pay_openid","",1);
                    $this->logResult("error::getOpenId::curl或openssl未开启");
                }
                $re=json_decode($content,true);
                $this->logResult("log::getOpenId::wxJsonURL:",$wxJsonUrl);
                if(isset($re["openid"]) && !empty($re["openid"])){
                    $_SESSION["pay_openid"]=$re["openid"];
                    setcookie("pay_openid",$re["openid"],time()+3600*24*7);
                }else{
                    $this->logResult("error::getOpenId::getopenidbycode");
                    $_SESSION["pay_openid"]=-1;
                    setcookie("pay_openid","",1);
                }
                $this->logResult("log::getOpenId::openid:",$re);
				
				unset( $_GET["code"] );
                return $_SESSION["pay_openid"];
      /*          $url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                $this->logResult("log::getOpenId::refleshurl:",$url);
                ob_end_clean();
                ecs_header("Location: $url\n");
                exit;*/
                //redirect ( $callback . '&openid=' . $content ['wxpay_openid'] );
            }
        }else{
            $wxUrl='https://open.weixin.qq.com/connect/oauth2/authorize?';
			//$wxUrl= 'http://www.uhouse.shop/get-weixin-code.html?';
            $wxUrl.='appid='.$payment['wechat_pay_appid'];
            $wxUrl.='&redirect_uri='.urlencode( ($this->is_https()? 'https://' : 'http://').$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
            $wxUrl.='&response_type=code&scope=snsapi_base';
            $wxUrl.='&state=getOpenid';
            $wxUrl.='#wechat_redirect';
            $this->logResult("log::getOpenId::wxURl:",$wxUrl);
            ob_end_clean();
            ecs_header("Location: $wxUrl\n");
            exit;
        }
    }
	
	function is_https() {
		if (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') {
			return true;
		} elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
			return true;
		} elseif (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off') {
			return true;
		}

		return false;
	}
	
	/**
	 * 字符串截取，支持中文和其他编码
	 * @static
	 * @access public
	 * @param string $str 需要转换的字符串
	 * @param string $start 开始位置
	 * @param string $length 截取长度
	 * @param string $charset 编码格式
	 * @param string $suffix 截断显示字符
	 * @return string
	 */
	function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
		if(function_exists("mb_substr"))
			$slice = mb_substr($str, $start, $length, $charset);
		elseif(function_exists('iconv_substr')) {
			$slice = iconv_substr($str,$start,$length,$charset);
		}else{
			$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
			$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
			$re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
			$re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
			preg_match_all($re[$charset], $str, $match);
			$slice = join("",array_slice($match[0], $start, $length));
		}
		return $suffix ? $slice.'...' : $slice;
	}
	
	function return_error( $error ){
		
		$html = '<a type="button"  id="pay_wxpay" class="box-flex btn-submit" href="javascript:alert(\''. $error  .'\');" >微信安全支付</a>';
	
		return $html;
	}
	
	
	function is_wechat_browser(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($user_agent, 'MicroMessenger') === false){

		  return false;
		} else {
		  //preg_match('/.*?(MicroMessenger\/([0-9.]+))\s*/', $user_agent, $matches);
		  //echo '<br>你的微信版本号为:'.$matches[2];
		  return true;
		}
	}
	
	
	function trimString($value)
    {
        $ret = null;
        if (null != $value)
        {
            $ret = $value;
            if (strlen($ret) == 0)
            {
                $ret = null;
            }
        }
        return $ret;
    }

    /**
     *  作用：产生随机字符串，不长于32位
     */
    public function createNoncestr( $length = 32 )
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str ="";
        for ( $i = 0; $i < $length; $i++ )  {
            $str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
        }
        return $str;
    }

    /**
     *  作用：设置请求参数
     */
    function setParameter($parameter, $parameterValue)
    {
        $this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }

    /**
     *  作用：生成签名
     */
    public function getSign($Obj)
    {
        foreach ($Obj as $k => $v)
        {
            $Parameters[$k] = $v;
        }
        //签名步骤一：按字典序排序参数
        ksort($Parameters);

        $buff = "";
        foreach ($Parameters as $k => $v)
        {
            $buff .= $k . "=" . $v . "&";
        }
        $String;
        if (strlen($buff) > 0)
        {
            $String = substr($buff, 0, strlen($buff)-1);
        }
        //echo '【string1】'.$String.'</br>';
        //签名步骤二：在string后加入KEY
        $String = $String."&key=".$this->payment['wechat_pay_key'];
        //echo "【string2】".$String."</br>";
        //签名步骤三：MD5加密
        $String = md5($String);
        //echo "【string3】 ".$String."</br>";
        //签名步骤四：所有字符转为大写
        $result_ = strtoupper($String);
        //echo "【result】 ".$result_."</br>";
        return $result_;
    }

    /**
     *  作用：以post方式提交xml到对应的接口url
     */
    public function postXmlCurl($xml,$url,$second=30)
    {
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        //这里设置代理，如果有的话
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data)
        {
            curl_close($ch);
            return $data;
        }
        else
        {
            $error = curl_errno($ch);
            echo "curl出错，错误码:$error"."<br>";
            echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
            return false;
        }
    }

    function curl_https($url, $header=array(), $timeout=30){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 跳过证书检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);  // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $response = curl_exec($ch);
        if(!$response){
            $error=curl_error($ch);
            $this->logResult("error::curl_https::error_code".$error);
        }
        curl_close($ch);

        return $response;

    }
	
	/**
     * 统一下单
     */
    function unifiedOrder()
    {
        // 设置接口链接
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";

        if ($this->parameters["out_trade_no"] == null) {

            $this->logResult("error::getPrepayId::缺少统一支付接口必填参数out_trade_no");
        } elseif ($this->parameters["body"] == null) {
            $this->logResult("error::getPrepayId::缺少统一支付接口必填参数body");
        } elseif ($this->parameters["total_fee"] == null) {
            $this->logResult("error::getPrepayId::缺少统一支付接口必填参数total_fee");
        } elseif ($this->parameters["notify_url"] == null) {
            $this->logResult("error::getPrepayId::缺少统一支付接口必填参数notify_url");
        } elseif ($this->parameters["trade_type"] == null) {
            $this->logResult("error::getPrepayId::缺少统一支付接口必填参数trade_type");
        } elseif ($this->parameters["trade_type"] == "JSAPI" && $this->parameters["openid"] == NULL) {
            $this->logResult("error::getPrepayId::统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！");
        }
        $this->parameters["appid"] = $this->payment['wechat_pay_appid']; // 公众账号ID
        $this->parameters["mch_id"] = $this->payment['wechat_pay_mchid']; // 商户号
        //$this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR']; // 终端ip
        $this->parameters["nonce_str"] = $this->createNoncestr(); // 随机字符串
        $this->parameters["sign"] = $this->getSign($this->parameters); // 签名

        $xml=$this->arrayToXml($this->parameters);


        $response = $this->postXmlCurl($xml, $url, 30);
        //todo 部署后删除
        $this->logResult("log::getPrepayId::response",$response);
        //$response = Http::curlPost($url, $xml, 30);
        $result =$this->xmlToArray($response);
       // $prepay_id = $result["prepay_id"];
        return $result;
    }
	
   
    /**
     * 作用：设置jsapi的参数
     */
    public function getParameters($prepay_id)
    {
        $jsApiObj["appId"] = $this->payment['wechat_pay_appid'];
        $timeStamp = time();
        $jsApiObj["timeStamp"] = "$timeStamp";
        $jsApiObj["nonceStr"] = $this->createNoncestr();
        $jsApiObj["package"] = "prepay_id=$prepay_id";
        $jsApiObj["signType"] = "MD5";
        $jsApiObj["paySign"] = $this->getSign($jsApiObj);
        $this->parameters = json_encode($jsApiObj);

        return $this->parameters;
    }
	
	/**
     * 	作用：将xml转为array
     */
    public function xmlToArray($xml)
    {
        //将XML转为array        
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $array_data;
    }
    /**
     * 	作用：array转xml
     */
    function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
            if (is_numeric($val))
            {
                $xml.="<".$key.">".$val."</".$key.">";

            }
            else
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
    }
	
    function logResult($word = '',$var=array()) {
        if(!WXPAY_DEBUG){
            return true;
        }
        $output= strftime("%Y%m%d %H:%M:%S", time()) . "\n" ;
        $output .= $word."\n" ;
        if(!empty($var)){
            $output .= print_r($var, true)."\n";
        }
        $output.="\n";
		
       $r =  file_put_contents(ROOT_PATH . "storage/logs/". basename(__FILE__, '.php') .".txt", $output, FILE_APPEND | LOCK_EX);

    }
	function get_real_ip() {
		//static $realip;
		if (isset($_SERVER)) {
			if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$realip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
				$realip = $_SERVER['HTTP_CLIENT_IP'];
			} else {
				$realip = $_SERVER['REMOTE_ADDR'];
			}
		} else {
			if (getenv('HTTP_X_FORWARDED_FOR')) {
				$realip = getenv('HTTP_X_FORWARDED_FOR');
			} else if (getenv('HTTP_CLIENT_IP')) {
				$realip = getenv('HTTP_CLIENT_IP');
			} else {
				$realip = getenv('REMOTE_ADDR');
			}
		}
		if (strstr($realip, ',')) 
		{
			$realips = explode(',', $realip);
			$realip = $realips[0];
		}	
		return $realip;
	}
}

?>