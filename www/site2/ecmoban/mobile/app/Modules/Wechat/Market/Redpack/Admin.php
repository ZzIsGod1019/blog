<?php

namespace App\Modules\Wechat\Market\Redpack;

use Think\Image;
use App\Extensions\Form;
use App\Extensions\QRcode;
use App\Extensions\Wechat;
use App\Modules\Wechat\Controllers\PluginController;


class Admin extends PluginController
{
    protected $marketing_type = ''; 
    protected $wechat_id = 0; 
    protected $page_num = 10; 

    
    protected $cfg = [];

    public function __construct($cfg = [])
    {
        parent::__construct();

        $file = ['payment'];
        $this->load_helper($file);

        $this->cfg = $cfg;
        $this->cfg['plugin_path'] = 'Market';
        $this->plugin_name = $this->marketing_type = $cfg['keywords'];
        $this->wechat_id = $cfg['wechat_id'];
        $this->ru_id = isset($cfg['ru_id']) ? $cfg['ru_id'] : 0;
        $this->page_num = isset($cfg['page_num']) ? $cfg['page_num'] : 10;

        $this->assign('ru_id', $this->ru_id);
    }

    
    public function marketList()
    {
        $filter['type'] = $this->marketing_type;
        $offset = $this->pageLimit(url('market_list', $filter), $this->page_num);

        $total = dao('wechat_marketing')->where(['marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id])->count();

        $list = dao('wechat_marketing')->field('id, name, command, starttime, endtime, status')->where(['marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id])->order('id DESC')->limit($offset)->select();
        if ($list[0]['id']) {
            foreach ($list as $k => $v) {
                $list[$k]['starttime'] = local_date('Y-m-d H:i', $v['starttime']);
                $list[$k]['endtime'] = local_date('Y-m-d H:i', $v['endtime']);
                $config = $this->get_market_config($v['id'], $v['marketing_type']);
                $list[$k]['hb_type'] = $config['hb_type'] == 1 ? L('group_redpack') : L('normal_redpack');
                $status = get_status($v['starttime'], $v['endtime']); 
                if ($status == 0) {
                    $list[$k]['status'] = L('no_start');
                } elseif ($status == 1) {
                    $list[$k]['status'] = L('start');
                } elseif ($status == 2) {
                    $list[$k]['status'] = L('over');
                }
            }
        } else {
            $list = [];
        }

        $this->assign('page', $this->pageShow($total));
        $this->assign('list', $list);
        $this->plugin_display('market_list', $this->cfg);
    }

    
    public function marketEdit()
    {
        
        if (IS_POST) {
            $json_result = ['error' => 0, 'msg' => '', 'url' => '']; 

            $id = I('post.id', 0, 'intval');
            $data = I('post.data', '', 'trim');
            $config = I('post.config', '', 'trim');

            
            $payment = get_payment('wxpay');
            if (empty($payment)) {
                $json_result = ['error' => 1, 'msg' => '请先安装并配置微信支付'];
                exit(json_encode($json_result));
            }
            
            if (empty($data['name']) || strlen($data['name']) >= 32) {
                $json_result = ['error' => 1, 'msg' => '活动名称必填，并且须少于32个字符'];
                exit(json_encode($json_result));
            }
            
            if ($config['base_money'] < 1 || $config['base_money'] > 200) {
                $json_result = ['error' => 1, 'msg' => '红包金额必须在1元~200元之间，请重新填写'];
                exit(json_encode($json_result));
            }
            
            if ($config['hb_type'] == 0 && $config['total_num'] != 1) {
                $json_result = ['error' => 1, 'msg' => '红包发放总人数 普通红包固定为1人, 请重新填写'];
                exit(json_encode($json_result));
            }
            if ($config['hb_type'] == 1 && $config['total_num'] < 3) {
                $json_result = ['error' => 1, 'msg' => '红包发放总人数 裂变红包至少为3人, 请重新填写'];
                exit(json_encode($json_result));
            }
            
            if (empty($config['nick_name']) || strlen($config['nick_name']) >= 16) {
                $json_result = ['error' => 1, 'msg' => '提供方名称必填，并且须少于16个字符'];
                exit(json_encode($json_result));
            }
            
            if (empty($config['send_name']) || strlen($config['send_name']) >= 32) {
                $json_result = ['error' => 1, 'msg' => '红包发送方名称必填，并且须少于32个字符'];
                exit(json_encode($json_result));
            }
            $data['wechat_id'] = $this->wechat_id;
            $data['marketing_type'] = I('post.marketing_type');
            $data['starttime'] = local_strtotime($data['starttime']);
            $data['endtime'] = local_strtotime($data['endtime']);

            $data['status'] = get_status($data['starttime'], $data['endtime']); 

            $background_path = I('post.background_path', '', 'trim');
            
            $background_path = edit_upload_image($background_path);

            
            if ($_FILES['background']['name']) {
                
                $type = ['image/jpeg', 'image/png'];
                if ($_FILES['background']['type'] && !in_array($_FILES['background']['type'], $type)) {
                    
                    $json_result = ['error' => 1, 'msg' => L('not_file_type')];
                    exit(json_encode($json_result));
                }
                $result = $this->upload('data/attached/redpack', true);
                if ($result['error'] > 0) {
                    
                    $json_result = ['error' => 1, 'msg' => $result['message']];
                    exit(json_encode($json_result));
                }
            }
            
            if ($_FILES['background']['name'] && $result['url']) {
                $data['background'] = $result['url'];
            } else {
                $data['background'] = $background_path;
            }

            
            $form = new Form();
            if (!$form->isEmpty($data['background'], 1)) {
                
                $json_result = ['error' => 1, 'msg' => L('please_upload')];
                exit(json_encode($json_result));
            }

            
            if (!empty($payment)) {
                file_write("index.html", "");
                file_write("wxpay/index.html", "");
                file_write('wxpay/' . md5($payment['wxpay_appsecret']) . "_apiclient_cert.pem", $payment['sslcert']);
                file_write('wxpay/' . md5($payment['wxpay_appsecret']) . "_apiclient_key.pem", $payment['sslkey']);
            }
            
            if ($config) {
                $data['config'] = serialize($config);
            }
            
            if (strpos($data['background'], 'no_image') !== false) {
                unset($data['background']);
            }
            
            if ($id) {
                
                if ($data['background'] && $background_path != $data['background']) {
                    $background_path = strpos($background_path, 'no_image') == false ? $background_path : '';  
                    $this->remove($background_path);
                }
                $where = [
                    'id' => $id,
                    'wechat_id' => $this->wechat_id,
                    'marketing_type' => $data['marketing_type']
                ];
                dao('wechat_marketing')->data($data)->where($where)->save();
                
                $json_result = ['error' => 0, 'msg' => L('market_edit') . L('success'), 'url' => url('market_list', ['type' => $data['marketing_type']])];
                exit(json_encode($json_result));
            } else {
                
                $data['addtime'] = gmtime();
                $id = dao('wechat_marketing')->data($data)->add();
                
                $json_result = ['error' => 0, 'msg' => L('market_add') . L('success'), 'url' => url('market_list', ['type' => $data['marketing_type']])];
                exit(json_encode($json_result));
            }
        }

        
        $nowtime = gmtime();
        $info = [];
        $market_id = $this->cfg['market_id'];
        if (!empty($market_id)) {
            $info = dao('wechat_marketing')->field('id, name, command, logo, background, starttime, endtime, config, description, support')->where(['id' => $market_id, 'marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id])->find();
            if ($info) {
                $info['starttime'] = isset($info['starttime']) ? local_date('Y-m-d H:i:s', $info['starttime']) : local_date('Y-m-d H:i:s', $nowtime);
                $info['endtime'] = isset($info['endtime']) ? local_date('Y-m-d H:i:s', $info['endtime']) : local_date('Y-m-d H:i:s', local_strtotime("+1 months", $nowtime));
                $info['config'] = unserialize($info['config']);
                $info['background'] = get_wechat_image_path($info['background']);
            } else {
                $this->message('数据不存在', url('market_list', ['type' => $this->marketing_type]), 2);
            }
        } else {
            
            $info['starttime'] = local_date('Y-m-d H:i:s', $nowtime);
            $info['endtime'] = local_date('Y-m-d H:i:s', local_strtotime("+1 months", $nowtime));

            $info['config']['hb_type'] = 0;
            $info['config']['money_extra'] = 0;
            $info['config']['total_num'] = 1;

            
            $last_id = dao('wechat_marketing')->where(['wechat_id' => $this->wechat_id])->order('id desc')->getField('id');
            $market_id = !empty($last_id) ? $last_id + 1 : 1;
        }

        
        $info['url'] = __HOST__ . url('wechat/index/market_show', ['type' => 'redpack', 'function' => 'activity', 'market_id' => $market_id, 'ru_id' => $this->ru_id]);

        $this->assign('info', $info);
        $this->plugin_display('market_edit', $this->cfg);
    }

    
    public function marketShake()
    {
        $market_id = $this->cfg['market_id'];

        $function = I('get.function', '', 'trim');
        $handler = I('get.handler', '', 'trim');

        
        if ($handler && $handler == 'edit') {
            
            if (IS_POST) {
                $json_result = ['error' => 0, 'msg' => '', 'url' => '']; 

                $id = I('post.advertice_id', 0, 'intval');
                $data = I('post.advertice', '', 'trim');
                $icon_path = I('post.icon_path', '', 'trim');
                
                $form = new Form();
                if (!$form->isEmpty($data['content'], 1)) {
                    
                    $json_result = ['error' => 1, 'msg' => L('advertice_content')];
                    exit(json_encode($json_result));
                }
                
                if (substr($data['url'], 0, 4) !== 'http') {
                    
                    $json_result = ['error' => 1, 'msg' => L('link_err')];
                    exit(json_encode($json_result));
                }

                $icon_path = edit_upload_image($icon_path);
                
                $file = $_FILES['icon'];
                if ($file['name']) {
                    $type = ['image/jpeg', 'image/png'];
                    if (!in_array($file['type'], $type)) {
                        
                        $json_result = ['error' => 1, 'msg' => L('not_file_type')];
                        exit(json_encode($json_result));
                    }
                    $result = $this->upload('data/attached/redpack', true);
                    if ($result['error'] > 0) {
                        
                        $json_result = ['error' => 1, 'msg' => $result['message']];
                        exit(json_encode($json_result));
                    }
                    $data['icon'] = $result['url'];
                    $data['file_name'] = $file['name'];
                    $data['size'] = $file['size'];
                } else {
                    $data['icon'] = $icon_path;
                }

                if (!$form->isEmpty($data['icon'], 1)) {
                    
                    $json_result = ['error' => 1, 'msg' => L('please_upload')];
                    exit(json_encode($json_result));
                }
                
                if (strpos($data['icon'], 'no_image') !== false) {
                    unset($data['icon']);
                }
                
                if ($id) {
                    
                    if ($data['icon'] && $icon_path != $data['icon']) {
                        $icon_path = strpos($icon_path, 'no_image') == false ? $icon_path : '';  
                        $this->remove($icon_path);
                    }
                    $where = ['id' => $id, 'wechat_id' => $this->wechat_id];
                    dao('wechat_redpack_advertice')->data($data)->where($where)->save();
                    
                    $json_result = ['error' => 0, 'msg' => L('wechat_editor') . L('success')];
                    exit(json_encode($json_result));
                } else {
                    $data['wechat_id'] = $this->wechat_id;
                    dao('wechat_redpack_advertice')->data($data)->add();
                    
                    $json_result = ['error' => 0, 'msg' => L('add') . L('success')];
                    exit(json_encode($json_result));
                }
            }
            
            $advertices_id = I('get.advertice_id', 0, 'intval');
            if ($advertices_id) {
                $condition = [
                    'id' => $advertices_id,
                    'wechat_id' => $this->wechat_id
                ];
                $info = dao('wechat_redpack_advertice')->where($condition)->find();
                if (empty($info)) {
                    $this->message('数据不存在', url('data_list', ['type' => $this->marketing_type, 'function' => $function, 'id' => $market_id]), 2);
                }
                $info['icon'] = get_wechat_image_path($info['icon']);
            }
            $where = [
                'id' => $market_id,
                'wechat_id' => $this->wechat_id,
                'marketing_type' => $this->marketing_type,
            ];
            $info['act_name'] = dao('wechat_marketing')->where($where)->getField('name');
            $this->assign('act_name', $info['act_name']);

            $this->assign('info', $info);
            $this->plugin_display('market_shake_edit', $this->cfg);
        } else {
            
            
            $filter['type'] = $this->marketing_type;
            $filter['function'] = $function;
            $filter['id'] = $market_id;
            $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);

            $condition = [
                'market_id' => $market_id,
                'wechat_id' => $this->wechat_id
            ];
            $total = dao('wechat_redpack_advertice')->where($condition)->count();
            
            $this->assign('page', $this->pageShow($total));

            $list = dao('wechat_redpack_advertice')->where($condition)->order('id desc')->limit($offset)->select();
            if ($list) {
                foreach ($list as $key => $value) {
                    $list[$key]['icon'] = get_wechat_image_path($value['icon']);
                }
            }

            
            $where = [
                'id' => $market_id,
                'wechat_id' => $this->wechat_id,
                'marketing_type' => $this->marketing_type
            ];
            $act_name = dao('wechat_marketing')->where($where)->getField('name');
            $this->assign('act_name', $act_name);

            $this->assign('list', $list);
            $this->plugin_display('market_shake', $this->cfg);
        }
    }

    
    public function marketLogList()
    {
        $market_id = $this->cfg['market_id'];

        $function = I('get.function', '', 'trim');
        $handler = I('get.handler', '', 'trim');

        if ($handler && $handler == 'info') {
            
            $log_id = I('get.log_id', 0, 'intval');
            if ($log_id) {
                $condition = [
                    'id' => $log_id,
                    'wechat_id' => $this->wechat_id
                ];
                $info = dao('wechat_redpack_log')->where($condition)->find();
                $info['nickname'] = dao('wechat_user')->where(['wechat_id' => $this->wechat_id, 'openid' => $info['openid']])->getField('nickname');
                $info['time'] = !empty($info['time']) ? local_date('Y-m-d H:i:s', $info['time']) : '';

                
                if ($info['hassub'] == 1) {
                    $payment = get_payment('wxpay');
                    $options = [
                         'appid' => $payment['wxpay_appid'], 
                         'mch_id' => $payment['wxpay_mchid'], 
                         'key' => $payment['wxpay_key'] 
                     ];
                    $WxHongbao = new Wechat($options);
                    
                    $sslcert = ROOT_PATH . "storage/app/certs/wxpay/" . md5($payment['wxpay_appsecret']) . "_apiclient_cert.pem";
                    $sslkey = ROOT_PATH . "storage/app/certs/wxpay/" . md5($payment['wxpay_appsecret']) . "_apiclient_key.pem";

                    
                    $query_params = [
                        'mch_billno' => $info['mch_billno']
                    ];
                    $hb_type = $info['hb_type'] == 1 ? 'GROUP' : 'NORMAL';

                    $responseObj = $WxHongbao->QueryRedpack($query_params, $hb_type, $sslcert, $sslkey);
                    
                    $return_code = $responseObj->return_code;
                    $result_code = $responseObj->result_code;

                    if ($return_code == 'SUCCESS') {
                        if ($result_code == 'SUCCESS') {
                            
                            $info['status'] = $responseObj->status; 
                            $info['total_num'] = $responseObj->total_num; 
                            $info['hb_type'] = $responseObj->hb_type; 
                            $info['openid'] = $responseObj->openid; 
                            $info['send_time'] = $responseObj->send_time; 
                            $info['rcv_time'] = $responseObj->rcv_time;
                        } else {
                            
                            
                        }
                    } else {
                        
                        
                    }
                }

                $info['hb_type'] = $info['hb_type'] == 1 ? '裂变红包' : '普通红包';
                $info['hassub'] = $info['hassub'] == 1 ? '已领取' : '未领取';
            }

            $this->assign('info', $info);
            $this->plugin_display('market_log_info', $this->cfg);
        } else {
            
            
            $filter['type'] = $this->marketing_type;
            $filter['function'] = $function;
            $filter['id'] = $market_id;
            $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);
            $where = [
                'wechat_id' => $this->wechat_id,
                'market_id' => $market_id
            ];
            $total = dao('wechat_redpack_log')->where($where)->count();
            $list = dao('wechat_redpack_log')->where($where)->order('id desc')->limit($offset)->select();

            foreach ($list as $key => $value) {
                $list[$key]['nickname'] = dao('wechat_user')->where(['wechat_id' => $this->wechat_id, 'openid' => $value['openid']])->getField('nickname');
                $list[$key]['time'] = !empty($value['time']) ? local_date('Y-m-d H:i:s', $value['time']) : '';
            }
            $this->assign('page', $this->pageShow($total));
            $this->assign('market_id', $market_id);
            $this->assign('redpacks', $list);

            $this->plugin_display('market_log_list', $this->cfg);
        }
    }

    
    public function marketShare_setting()
    {
        $this->plugin_display('market_share_setting', $this->cfg);
    }


    
    public function marketQrcode()
    {
        $market_id = I('get.id', 0, 'intval');

        if (!empty($market_id)) {
            $url = __HOST__ . url('wechat/index/market_show', ['type' => 'redpack', 'function' => 'activity', 'market_id' => $market_id, 'ru_id' => $this->ru_id]);

            $info = dao('wechat_marketing')->field('qrcode')->where(['id' => $market_id, 'marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id])->find();

            
            
            $errorCorrectionLevel = 'M';
            
            $matrixPointSize = 8;
            
            $path = dirname(ROOT_PATH) . '/data/attached/redpack/';
            
            $water_logo = ROOT_PATH . 'public/img/shop_app_icon.png';
            $water_logo_out = $path . 'water_logo' . $market_id . '.png';

            
            $filename = $path . $errorCorrectionLevel . $matrixPointSize . $market_id . '.png';

            if (!is_dir($path)) {
                @mkdir($path);
            }
            QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

            
            $img = new Image();
            
            $img->open($water_logo)->thumb(80, 80)->save($water_logo_out);
            
            $img->open($filename)->water($water_logo_out, 5, 100)->save($filename);

            $qrcode_url = __HOST__ . '/data/attached/redpack/' . basename($filename) . '?t=' . time();
            $this->cfg['qrcode_url'] = $qrcode_url;
        }

        $this->plugin_display('market_qrcode', $this->cfg);
    }

    
    public function get_market_config($id, $marketing_type)
    {
        $info = dao('wechat_marketing')->field('config')->where(['id' => $id, 'marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id])->find();
        $result = unserialize($info['config']);
        return $result;
    }

    
    public function executeAction()
    {
        if (IS_AJAX) {
            $json_result = ['error' => 0, 'msg' => '', 'url' => ''];

            $handler = I('get.handler', '', 'trim');
            $market_id = I('get.market_id', 0, 'intval');

            
            if ($handler && $handler == 'log_delete') {
                $log_id = I('get.log_id', 0, 'intval');
                if (!empty($log_id)) {
                    dao('wechat_redpack_log')->where(['id' => $log_id, 'wechat_id' => $this->wechat_id, 'market_id' => $market_id])->delete();
                    $json_result['msg'] = '删除成功！';
                    exit(json_encode($json_result));
                } else {
                    $json_result['msg'] = '删除失败！';
                    exit(json_encode($json_result));
                }
            }

            
            if ($handler && $handler == 'search_nickname') {
                $keywords = I('nickname', '', 'trim');
                if (!empty($keywords)) {
                    $wechatUser = dao('wechat_user')
                        ->field('nickname, openid')
                        ->where('(nickname like "%' . $keywords . '%") and wechat_id = ' . $this->wechat_id)
                        ->order('uid DESC')
                        ->select();
                    if (!empty($wechatUser)) {
                        $json_result['status'] = 0;
                        $json_result['result'] = $wechatUser;
                        exit(json_encode($json_result));
                    } else {
                        $json_result['status'] = 1;
                        $json_result['msg'] = '未搜索到结果';
                        exit(json_encode($json_result));
                    }
                }
            }

            
            if ($handler && $handler == 'appoint_send_redpack') {
                $market_id = I('request.market_id', 0, 'intval');
                $re_openid = I('openid', '', 'trim');

                
                $data = dao('wechat_marketing')->field('name, starttime, endtime, config')->where(['id' => $market_id, 'marketing_type' => 'redpack', 'wechat_id' => $this->wechat_id])->find();

                $redpackinfo['config'] = unserialize($data['config']);

                $status = get_status($data['starttime'], $data['endtime']); 
                if ($status == 0) {
                    $json_result = [
                        'status' => 1,
                        'content' => '活动还没开始！',
                    ];
                    exit(json_encode($json_result));
                } elseif ($status == 2) {
                    $json_result = [
                        'status' => 1,
                        'content' => '活动已结束！',
                    ];
                    exit(json_encode($json_result));
                } else {
                    $payment = get_payment('wxpay');
                    
                    $options = [
                        'appid' => $payment['wxpay_appid'],
                        'mch_id' => $payment['wxpay_mchid'],
                        'key' => $payment['wxpay_key']
                    ];
                    $WxHongbao = new Wechat($options); 

                    $sslcert = ROOT_PATH . "storage/app/certs/wxpay/" . md5($payment['wxpay_appsecret']) . "_apiclient_cert.pem";
                    $sslkey = ROOT_PATH . "storage/app/certs/wxpay/" . md5($payment['wxpay_appsecret']) . "_apiclient_key.pem";

                    
                    $mch_billno = $payment['wxpay_mchid'] . date('YmdHis') . rand(1000, 9999);
                    
                    $money = $redpackinfo['config']['base_money'] + rand(0, $redpackinfo['config']['money_extra']);
                    $money = $money * 100; 
                    $hb_type = $redpackinfo['config']['hb_type'];
                    if ($hb_type == 0) {
                        $total_num = 1;
                    } else {
                        $total_num = $total_num > 3 ? $total_num : 3; 
                    }

                    $nick_name = $redpackinfo['config']['nick_name'];
                    $send_name = $redpackinfo['config']['send_name'];
                    $wishing = $redpackinfo['config']['wishing'];
                    $act_name = $redpackinfo['config']['act_name'];  
                    $remark = $redpackinfo['config']['remark'];
                    
                    $scene_id = strtoupper($redpackinfo['config']['scene_id']);

                    if ($hb_type == 0) {
                        $parameters = [
                            'mch_billno' => $mch_billno,
                            'nick_name' => $nick_name,
                            'send_name' => $send_name,
                            're_openid' => $re_openid,
                            'total_amount' => $total_amount,
                            'min_value' => $min_value,
                            'max_value' => $max_value,
                            'total_num' => $total_num,
                            'wishing' => $wishing,
                            'client_ip' => $_SERVER['REMOTE_ADDR'],
                            'act_name' => $act_name,
                            'remark' => $remark,
                        ];
                    } elseif ($hb_type == 1) {
                        $parameters = [
                            'mch_billno' => $mch_billno,
                            'nick_name' => $nick_name,
                            'send_name' => $send_name,
                            're_openid' => $re_openid,
                            'total_amount' => $total_amount,
                            'total_num' => $total_num,
                            'amt_type' => "ALL_RAND",
                            'wishing' => $wishing,
                            'act_name' => $act_name,
                            'remark' => $remark,
                        ];
                    }
                    
                    if ($scene_id && $scene_id > 0) {
                        $parameters["scene_id"] = $scene_id;
                    }
                    $responseObj = $WxHongbao->CreatSendRedpack($parameters, $hb_type, $sslcert, $sslkey);
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
                                'openid' => !empty($re_openid) ? $re_openid : $re_openid,
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
                            $json_result = [
                                'status' => 0,
                                'content' => "红包发放成功！金额为：" . $total_amount . "元！",
                            ];
                            exit(json_encode($json_result));
                            
                        }
                    } else {
                        if ($responseObj->err_code == 'NOTENOUGH') {
                            $json_result = [
                                'status' => 1,
                                'content' => "红包已经发放完！！",
                            ];
                            exit(json_encode($json_result));
                            
                        } elseif ($responseObj->err_code == 'TIME_LIMITED') {
                            $json_result = [
                                'status' => 1,
                                'content' => "现在非红包发放时间，请在北京时间0:00-8:00之外的时间前来领取",
                            ];
                            exit(json_encode($json_result));
                            
                        } elseif ($responseObj->err_code == 'SYSTEMERROR') {
                            $json_result = [
                                'status' => 1,
                                'content' => "系统繁忙，请稍后再试！",
                            ];
                            exit(json_encode($json_result));
                            
                        } elseif ($responseObj->err_code == 'DAY_OVER_LIMITED') {
                            $json_result = [
                                'status' => 1,
                                'content' => "今日红包已达上限，请明日再试！",
                            ];
                            exit(json_encode($json_result));
                            
                        } elseif ($responseObj->err_code == 'SECOND_OVER_LIMITED') {
                            $json_result = [
                                'status' => 1,
                                'content' => "每分钟红包已达上限，请稍后再试！",
                            ];
                            exit(json_encode($json_result));
                            
                        }
                        $json_result = [
                            'status' => 1,
                            'content' => "红包发放失败！" . $responseObj->return_msg . "！请稍后再试！",
                        ];
                        exit(json_encode($json_result));
                        
                    }
                }

            }

        }
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
}
