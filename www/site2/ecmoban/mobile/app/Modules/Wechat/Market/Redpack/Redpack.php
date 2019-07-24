<?php

namespace App\Modules\Wechat\Market\Redpack;

use App\Extensions\Wechat;
use App\Modules\Wechat\Controllers\PluginController;



class Redpack extends PluginController
{
    private $weObj = '';
    private $wechat_id = 0;
    private $market_id = 0;
    private $marketing_type = 'redpack';

    protected $config = [];

    private $parameters; 

    
    public function __construct($config = [])
    {
        parent::__construct();
        $file = ['payment'];
        $this->load_helper($file);

        $this->plugin_name = $this->marketing_type = strtolower(basename(__FILE__, '.php'));
        $this->config = $config;
        $this->config['plugin_path'] = 'Market';

        $this->market_id = I('market_id', 0, 'intval');
        $ru_id = I('request.ru_id', 0, 'intval');

        $this->ru_id = isset($this->config['ru_id']) ? $this->config['ru_id'] : $ru_id;

        
        $this->wechat_id = dao('wechat')->where(['status' => 1, 'ru_id' => $this->ru_id])->getField('id');

        
        $this->plugin_themes = __ROOT__ . '/public/assets/wechat/' . $this->marketing_type;
        $this->assign('plugin_themes', $this->plugin_themes);

        
        $data = dao('wechat_marketing')->field('name, starttime, endtime, config')->where(['id' => $this->market_id, 'marketing_type' => 'redpack', 'wechat_id' => $this->wechat_id])->find();

        $this->config['config'] = unserialize($data['config']);
        $this->config['config']['act_name'] = $data['name'];
        $this->config['starttime'] = $data['starttime'];
        $this->config['endtime'] = $data['endtime'];
    }

    
    public function actionActivity()
    {
        
        $info = dao('wechat_marketing')->field('id, name, logo, background, description, support')->where(['id' => $this->market_id, 'marketing_type' => 'redpack', 'wechat_id' => $this->wechat_id])->find();

        if (!empty($info)) {
            $info['background'] = get_wechat_image_path($info['background']); 
            if (strpos($info['background'], 'no_image') !== false) {
                unset($info['background']);
            }

            $status = get_status($this->config['starttime'], $this->config['endtime']); 

            if ($status == 0) {
                $flag = "活动未开始"; 
            }
            if ($status == 2) {
                $flag = "活动已结束"; 
            }

            $is_subscribe = dao('wechat_user')->where(['openid' => $_SESSION['openid'], 'wechat_id' => $this->wechat_id])->getField('subscribe');
            if ($is_subscribe == 0) {
                $flag = "请先关注微信公众号！";
            }
            $this->assign('flag', $flag);
            $this->assign('is_subscribe', $is_subscribe);

            $shake_url = __HOST__ . url('wechat/index/market_show', ['type' => 'redpack', 'function' => 'shake', 'market_id' => $this->market_id]); 
            $this->assign('shake_url', $shake_url);

            
            $page_title = $info['name'];
            $description = $info['description'];
            
            $page_img = get_wechat_image_path($info['background']);
        }

        $this->assign('info', $info);
        $this->assign('page_title', $page_title);
        $this->assign('description', $description);
        $this->assign('link', $link);
        $this->assign('page_img', $page_img);
        $this->show_display('activity', $this->config);
    }

    
    public function actionShake()
    {
        if (IS_POST) {
            $time = I('time');
            $last = I('last');

            $openid = $_SESSION['openid'];
            if (empty($openid) || $openid == '' || $openid == null) {
                $result = [
                    'icon' => $this->plugin_themes . "/images/error.png",
                    'content' => '请关注微信公众号或在微信客户端中打开！！',
                    'url' => ''
                ];
                exit(json_encode($result));
            }

            $cha = $time - $last; 
            if ($cha < 4000) {
                $result = [
                    'status' => 0,
                    'icon' => $this->plugin_themes . "/images/icon.jpg",
                    'content' => "歇一会，您摇得过于频繁了！请隔4秒以上再试 ~~",
                    'url' => ''
                ];
                exit(json_encode($result));
            }
            
            $min = $this->config['config']['randmin'];
            $max = $this->config['config']['randmax'];
            $sendNum = $this->config['config']['sendnum'];
            $sendArr = explode(',', $sendNum);
            $rand = rand($min, $max);
            $isInclude = in_array($rand, $sendArr);

            $hb_type = $this->config['config']['hb_type'];

            if ($isInclude) {
                $status = get_status($this->config['starttime'], $this->config['endtime']); 
                if ($status == 0) {
                    $result = [
                        'status' => 0,
                        'icon' => $this->plugin_themes . "/images/icon.jpg",
                        'content' => '您来早了，活动还没开始！！！',
                        'url' => ''
                    ];
                    exit(json_encode($result));
                } elseif ($status == 2) {
                    $result = [
                        'status' => 0,
                        'icon' => $this->plugin_themes . "/images/icon.jpg",
                        'content' => '您来迟了，活动已结束！！！',
                        'url' => ''
                    ];
                    exit(json_encode($result));
                } else {
                    $log = dao('wechat_redpack_log')->field('hassub')->where(['wechat_id' => $this->wechat_id, 'market_id' => $this->market_id, 'openid' => $openid])->find();
                    if (count($log) == 1) {
                        
                        if ($log['hassub'] == 1) {
                            $temp = "您已参与过本活动，请不要重复操作！";
                            $result = [
                                'status' => 0,
                                'icon' => $this->plugin_themes . "/images/icon.jpg",
                                'content' => $temp,
                                'url' => ''
                            ];
                        } else {
                            
                            $temp = $this->sendRedpack($openid, $hb_type);
                            $result = [
                                'status' => 1,
                                'icon' => $this->plugin_themes . "/images/icon.jpg",
                                'content' => $temp,
                                'url' => ''
                            ];
                        }
                    } elseif (count($log) == 0) {
                        
                        $data = [
                            'wechat_id' => $this->wechat_id,
                            'market_id' => $this->market_id,
                            'hb_type' => $hb_type,
                            'openid' => $openid,
                            'hassub' => 0,
                        ];
                        dao('wechat_redpack_log')->data($data)->add();

                        $temp = $this->sendRedpack($openid, $hb_type);
                        $result = [
                            'status' => 1,
                            'icon' => $this->plugin_themes . "/images/icon.jpg",
                            'content' => $temp,
                            'url' => ''
                        ];
                    }
                    exit(json_encode($result));
                }
            } else {
                
                $total = dao('wechat_redpack_advertice')->where(['wechat_id' => $this->wechat_id, 'market_id' => $this->market_id])->count();
                if ($total == 0) {
                    $result = [
                        'icon' => $this->plugin_themes . "/images/icon.jpg",
                        'content' => '什么都没摇到~~~',
                        'url' => ''
                    ];
                    exit(json_encode($result));
                }
                
                $pageindex = rand(0, $total - 1);
                $temp = dao('wechat_redpack_advertice')->field('icon, content, url')->where(['wechat_id' => $this->wechat_id, 'market_id' => $this->market_id])->limit($pageindex, 1)->select();
                $temp = reset($temp);
                $temp['icon'] = get_wechat_image_path($temp['icon']);

                $result = [
                    'icon' => $temp['icon'],
                    'content' => $temp['content'],
                    'url' => $temp['url']
                ];
                exit(json_encode($result));
            }
        }

        $this->assign('back_url', __HOST__ . url('wechat/index/market_show', ['type' => 'redpack', 'function' => 'activity', 'market_id' => $this->market_id]));
        $this->assign('market_id', $this->market_id);
        $this->assign('page_title', "微信摇一摇");
        $this->show_display('shake', $this->config);
    }

    
    public function sendRedpack($param_openid, $hb_type = 0)
    {
        
        
        
        

        
        
        

        $payment = get_payment('wxpay');
        if (!empty($payment)) {
            $isInclude = true;
        } else {
            $isInclude = false;
        }

        if ($isInclude) {
            
            $options = [
                 'appid' => $payment['wxpay_appid'],
                 'mch_id' => $payment['wxpay_mchid'],
                 'key' => $payment['wxpay_key']
             ];
            $WxHongbao = new Wechat($options); 
            
            $sslcert = ROOT_PATH . "storage/app/certs/wxpay/" . md5($payment['wxpay_appsecret']) . "_apiclient_cert.pem";
            $sslkey = ROOT_PATH . "storage/app/certs/wxpay/" . md5($payment['wxpay_appsecret']) . "_apiclient_key.pem";

            
            $mch_billno = $payment['wxpay_mchid'] . date('YmdHis') . rand(1000, 9999);
            
            $money = $this->config['config']['base_money'] + rand(0, $this->config['config']['money_extra']);
            $money = $money * 100; 
            if ($hb_type == 0) {
                $total_num = 1;
            } else {
                $total_num = $total_num > 3 ? $total_num : 3; 
            }

            $nick_name = $this->config['config']['nick_name'];
            $send_name = $this->config['config']['send_name'];
            $wishing = $this->config['config']['wishing'];
            $act_name = $this->config['config']['act_name'];  
            $remark = $this->config['config']['remark'];
            
            $scene_id = strtoupper($this->config['config']['scene_id']);

            if ($hb_type == 0) {
                
                $this->setParameter("mch_billno", $mch_billno); 
                $this->setParameter("nick_name", $nick_name); 
                $this->setParameter("send_name", $send_name); 
                $this->setParameter("re_openid", $param_openid); 
                $this->setParameter("total_amount", $money); 
                $this->setParameter("min_value", $money); 
                $this->setParameter("max_value", $money); 
                $this->setParameter("total_num", $total_num); 
                $this->setParameter("wishing", $wishing); 
                $this->setParameter("client_ip", $_SERVER['REMOTE_ADDR']);
                $this->setParameter("act_name", $act_name); 
                $this->setParameter("remark", $remark); 
            } elseif ($hb_type == 1) {
                
                $this->setParameter("mch_billno", $mch_billno); 
                $this->setParameter("nick_name", $nick_name); 
                $this->setParameter("send_name", $send_name); 
                $this->setParameter("re_openid", $param_openid); 
                $this->setParameter("total_amount", $money); 
                
                
                $this->setParameter("total_num", $total_num); 
                $this->setParameter("amt_type", 'ALL_RAND'); 
                $this->setParameter("wishing", $wishing); 
                $this->setParameter("act_name", $act_name); 
                $this->setParameter("remark", $remark); 
            }
            
            if ($scene_id && $scene_id > 0) {
                $this->setParameter("scene_id", $scene_id);
            }
            $hb_type = $hb_type == 1 ? 'GROUP' : 'NORMAL';
            $responseObj = $WxHongbao->CreatSendRedpack($this->parameters, $hb_type, $sslcert, $sslkey);
            

            if ($responseObj->return_code == 'SUCCESS') {
                if ($responseObj->result_code == 'SUCCESS') {
                    $total_amount = $responseObj->total_amount * 1.0 / 100;
                    $re_openid = $responseObj->re_openid;
                    $mch_billno = $responseObj->mch_billno;
                    $mch_id = $responseObj->mch_id;
                    $wxappid = $responseObj->wxappid;

                    
                    $where = [
                        'wechat_id' => $this->wechat_id,
                        'market_id' => $this->market_id,
                        'openid' => !empty($re_openid) ? $re_openid : $param_openid,
                    ];
                    $data = [
                        'hassub' => 1,
                        'money' => $total_amount,
                        'time' => gmtime(),
                        'mch_billno' => $mch_billno,
                        'mch_id' => $mch_id,
                        'wxappid' => $wxappid,
                        'bill_type' => 'MCHT',
                        'notify_data' => serialize($responseObj),
                    ];
                    $result = dao('wechat_redpack_log')->data($data)->where($where)->save();

                    return "红包发放成功！金额为：" . $total_amount . "元！拆开发放的红包即可领取红包！";
                }
            } else {
                if ($responseObj->err_code == 'NOTENOUGH') {
                    return "您来迟了，红包已经发放完！！!";
                } elseif ($responseObj->err_code == 'TIME_LIMITED') {
                    return "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取";
                } elseif ($responseObj->err_code == 'SYSTEMERROR') {
                    return "系统繁忙，请稍后再试！";
                } elseif ($responseObj->err_code == 'DAY_OVER_LIMITED') {
                    return "今日红包已达上限，请明日再试！";
                } elseif ($responseObj->err_code == 'SECOND_OVER_LIMITED') {
                    return "每分钟红包已达上限，请稍后再试！";
                }
                return "红包发放失败！" . $responseObj->return_msg . "！请稍后再试！";
            }
        } else {
            $where = [
                'wechat_id' => $this->wechat_id,
                'market_id' => $this->market_id,
                'openid' => $param_openid,
            ];
            $data = [
                'hassub' => 1,
                'money' => 0,
                'time' => gmtime(),
            ];
            $result = dao('wechat_redpack_log')->data($data)->where($where)->save();
            return "很遗憾，您没有抢到红包！感谢您的参与！";
        }
    }


    
    protected function setParameter($parameter, $parameterValue)
    {
        $this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
    }

    protected function trimString($value)
    {
        $ret = null;
        if (null != $value) {
            $ret = $value;
            if (strlen($ret) == 0) {
                $ret = null;
            }
        }
        return $ret;
    }

    
    public function returnData($fromusername, $info)
    {
    }

    
    public function updatePoint($fromusername, $info)
    {
    }

    
    public function html_show()
    {
    }

    
    public function executeAction()
    {
    }
}
