<?php
//zend by QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}

if (!defined('WXPAY_DEBUG'))
{
	define("WXPAY_DEBUG", true);
}
		
// 包含配置文件
$payment_lang = ROOT_PATH . 'languages/' .$GLOBALS['_CFG']['lang']. '/payment/'. basename(__FILE__, '.php') .'.php';

if (file_exists($payment_lang))
{
    global $_LANG;

    include_once($payment_lang);
}

/* 模块的基本信息 */
if (isset($set_modules) && $set_modules == TRUE)
{
    $i = isset($modules) ? count($modules) : 0;

    /* 代码 */
    $modules[$i]['code']    = basename(__FILE__, '.php');

    /* 描述对应的语言项 */
    $modules[$i]['desc']    = 'wechat_pay_desc';

    /* 是否支持货到付款 */
    $modules[$i]['is_cod']  = '0';

    /* 是否支持在线支付 */
    $modules[$i]['is_online']  = '1';

    /* 作者 */
    $modules[$i]['author']  = 'https://dsc.shjm-wl.com';

    /* 网址 */
    $modules[$i]['website'] = 'https://dsc.shjm-wl.com';

    /* 版本号 */
    $modules[$i]['version'] = '3.3.0';

    /* 配置信息 */
       $modules[$i]['config'] = array(
        // 微信公众号身份的唯一标识
        array(
            'name' => 'wechat_pay_appid',
            'type' => 'text',
            'value' => ''
        ),
        // JSAPI接口中获取openid，审核后在公众平台开启开发模式后可查看
        array(
            'name' => 'wechat_pay_appsecret',
            'type' => 'text',
            'value' => ''
        ),
        // 商户支付密钥Key
        array(
            'name' => 'wechat_pay_key',
            'type' => 'text',
            'value' => ''
        ),
        // 受理商ID
        array(
            'name' => 'wechat_pay_mchid',
            'type' => 'text',
            'value' => ''
        )
    );

    return;
}


/**
 * 类
 */
class wechat_pay
{

	
	private $parameters; // cft 参数
    private $payment; // 配置信息

	function _config( $payment )
	{
			
	}
	
	/**
     * 生成支付代码
     * @param   array   $order  订单信息
     * @param   array   $payment    支付方式信息
     */
	function get_code($order, $payment)
	{
		
		$root_url = str_replace('/seller/', '/', $GLOBALS['ecs']->url());
		$notify_url = $root_url.'wechat_pay_notify.php';
		
		$out_trade_no = $order['order_sn'].'O'.$order['log_id'] .'O'.(date('is'));
		//$out_trade_no = $order['order_sn'].'O'.$order['log_id'] .'O'.( $order['order_amount'] * 100);
		
		$body = $order['order_sn'];
		
		$sql = "select * from " . $GLOBALS['ecs']->table('pay_log') . "  WHERE log_id = '". $order['log_id'] ."' ";
		$pay_log = $GLOBALS['db']->getRow($sql);		
		if (!empty( $pay_log ) ){
			if ( $pay_log['order_type'] == 0 ){
				//$sql = "select goods_name from " . $GLOBALS['ecs']->table('order_goods') . "  WHERE order_id = '". $pay_log['order_id'] ."' ";
				//$body = $GLOBALS['db']->getOne($sql);
				//$body = $this->msubstr($body,0, 20);
				$body = '购物订单号：'.$order['order_sn']	;	
			}
			elseif ( $pay_log['order_type'] == 1 ){
				$body = '在线充值';
			}
		}
		
		$this->payment = $payment;
		
		$this->logResult("log::get_code::notify_url:".$notify_url);
		
        //设置必填参数
        $this->setParameter("body", $body);
        $this->setParameter("out_trade_no", $out_trade_no);
        $this->setParameter("total_fee", $order['order_amount'] * 100);
        $this->setParameter("notify_url", $notify_url);
        $this->setParameter("trade_type", "NATIVE");
		$this->setParameter("product_id", $order['order_sn']);
		$this->setParameter("attach", $order['log_id']);

        $result = $this->getResult();

		$error = '出错了'; 
		if ( empty( $result ) ){
			return $this->return_error($error);
		}
		if( $result["return_code"] == 'FAIL'){
			return  $this->return_error($result["return_msg"]);

		}
		if( $result["result_code"] == 'FAIL'){
			return  $this->return_error($result["err_code_des"]);		
		}
		$code_url = $result["code_url"];
		
		if ( empty( $result["code_url"] ) ){
			return  $this->return_error($error);
		}	
		

		$html = '<div class="wx_qrcode" style="text-align:center">';
		$html .= "</div>";

		$img = '<img alt="扫码支付" src="'.$root_url.'dream.php?act=qrcode&data='.urlencode($code_url).'" style=""/>';


		$html = '<div id="Dialog" style="display:none;"><div id="wxpay_dialog"><div style="text-align:center" id="Qrcode"><p>微信扫一扫，立即支付</p><div>'. $img .'</div></div><div id="WxPhone"></div><div style="clear:both"></div></div></div>';
		
		
		
		$html .='<script type="text/javascript">
			function get_wxpay_status( id ){
				if( false && typeof(Ajax)== "object" ){
					Ajax.call("'. $root_url .'dream.php", "id="+id, return_wxpay_order_status, "GET", "JSON");
				}else{
					jQuery.get("'. $root_url .'dream.php", "id="+id,function( result ){
						if ( result.error == 0 && result.is_paid == 1 ){
							window.location.href = result.url;
						}
					}, "json");
				}	
			}
			function return_wxpay_order_status(  result ){
				if ( result.error == 0 && result.is_paid == 1 ){
					window.location.href = result.url;
				}
			}
			window.setInterval(function(){ get_wxpay_status("'. $order['log_id'] .'"); }, 2000); 
			$(function(){
				//微信扫码
				$("#pay_wxpay").on("click",function(){
					var content = $("#Dialog").html();
					pb({
						id: "scanCode",
						title: "",
						width: 726,
						content: content,
						drag: true,
						foot: false,
						cl_cBtn: false,
						cBtn: false
					});
				});
				if ( typeof(pb) !== "function" ){
					$("#Dialog").show();
				}
			});
			
		</script>';
		$html .='<style>#wxpay_dialog{width:645px;margin:0 auto;}#WxPhone{float:left;width:320px;height:421px;padding-left:50px;background:url('. $root_url .'themes/ecmoban_dsc2017/images/pay/phone-bg.png) 50px 0 no-repeat}#Qrcode{display:block;float:left;margin-top:30px}#Qrcode img{height:259px;width:259px;padding:5px;border:1px solid #ddd}#Qrcode p{padding:15px 0;background:#157058;color:#fff;margin:10px 0}</style>';

         return '<a href="javascript:;" id="pay_wxpay" style="display:block;"><img src="'. $root_url .'themes/ecmoban_dsc2017/images/pay/wechat-pay-icon.png" alt="'. $GLOBALS['_LANG']['wechat_pay']  .'"></a>'.$html;
	}
	
	function respond()
	{
		return true;
	}
	
	
    /**
     * 异步通知
     * @param $data
     * @return mixed
     */
    public function notify($data)
    {
        
		$this->logResult("log::notify::start:");
        $postStr = file_get_contents("php://input");

        if (!empty($postStr)) {
			
			$payment  = get_payment(basename(__FILE__, '.php'));
			$this->payment = $payment;
			
            $payment = get_payment($data['code']);
   
			
			$postdata =$this->xmlToArray($postStr);
			
            /* 检查插件文件是否存在，如果存在则验证支付是否成功，否则则返回失败信息 */
            // 微信端签名
			// 部署后删除
            $this->logResult("log::notify::postdata",$postdata);
			
            $wxsign = $postdata['sign'];
            unset($postdata['sign']);
			
			// 部署后删除
            $this->logResult("log::notify::wxsign",$wxsign);
			
			$sign=$this->getSign($postdata);
            // 部署后删除
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
						
						// 部署后删除
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
		
        // 部署后删除
        $this->logResult("log::notify::returndata",$returndata['return_code']);
        $xml=$this->arrayToXml($returndata);
        // 部署后删除
        $this->logResult("log::notify::returnxml",$xml);

        echo $xml;
        exit();
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
		$root_url = str_replace('mobile/', '', $GLOBALS['ecs']->url());
		$html = '<a id="pay_wxpay"  onclick="javascript:alert(\''. $error .'\')" style="display:block;"><img src="'. $root_url .'includes/modules/payment/wxpay/wxpay-icon.png" alt="'. $GLOBALS['_LANG']['wxpay_native']  .'"></a>';
		return $html;
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

    /**
     * 获取结果
     */
    function getResult()
    {
        //设置接口链接
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        try
        {
            //检测必填参数
            if($this->parameters["out_trade_no"] == null){
                throw new Exception("缺少统一支付接口必填参数out_trade_no！"."<br>");
            }elseif($this->parameters["body"] == null){
                throw new Exception("缺少统一支付接口必填参数body！"."<br>");
            }elseif ($this->parameters["total_fee"] == null ) {
                throw new Exception("缺少统一支付接口必填参数total_fee！"."<br>");
            }elseif ($this->parameters["notify_url"] == null) {
                throw new Exception("缺少统一支付接口必填参数notify_url！"."<br>");
            }elseif ($this->parameters["trade_type"] == null) {
                throw new Exception("缺少统一支付接口必填参数trade_type！"."<br>");
            }elseif ($this->parameters["trade_type"] == "JSAPI" && $this->parameters["openid"] == NULL){
                throw new Exception("统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>");
            }
            $this->parameters["appid"] = $this->payment['wechat_pay_appid'];//公众账号ID
            $this->parameters["mch_id"] = $this->payment['wechat_pay_mchid'];//商户号
            $this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];//终端ip
            $this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串
            $this->parameters["sign"] = $this->getSign($this->parameters);//签名
            $xml = "<xml>";
            foreach ($this->parameters as $key=>$val)
            {
                 if (is_numeric($val))
                 {
                    $xml.="<".$key.">".$val."</".$key.">";

                 }
                 else
                 {
                    $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
                 }
            }
            $xml.="</xml>";
        }catch (Exception $e)
        {
            die($e->getMessage());
        }

        $response = $this->postXmlCurl($xml, $url, 30);
        $result = json_decode(json_encode(simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $result;
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
}

?>