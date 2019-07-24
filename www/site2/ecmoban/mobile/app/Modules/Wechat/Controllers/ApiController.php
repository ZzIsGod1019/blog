<?php

namespace App\Modules\Wechat\Controllers;

use App\Extensions\Wechat;
use App\Modules\Base\Controllers\FrontendController;

class ApiController extends FrontendController
{
    private $weObj = '';
    private $wechat_id = 0;

    
    public function __construct()
    {
        parent::__construct();
        
        if (isset($_COOKIE['ectouch_ru_id'])) {
            $ru_id = $_COOKIE['ectouch_ru_id'];
        } else {
            $ru_id = 0;
        }
        $wxConf = $this->getConfig($ru_id);
        $this->weObj = new Wechat($wxConf);

        $this->wechat_id = $wxConf['id'];
    }

    
    public function actionIndex()
    {
        $user_id = I('get.user_id', 0, 'intval');
        $code = I('get.code', '', 'trim');
        $pushData = I('get.pushData', '', 'trim');
        $url = I('get.url', '');
        $url = $url ? base64_decode(urldecode($url)) : '';

        if ($user_id && $code) {
            $pushData = stripslashes(urldecode($pushData));
            
            $pushData = unserialize($pushData);
            
            push_template($code, $pushData, $url, $user_id);
        }
    }

    
    public function actionJssdk()
    {
        $url = input('url', '', 'addslashes');
        if (!empty($url)) {
            $sdk = $this->weObj->getJsSign($url);
            $data = ['status' => '200', 'data' => $sdk];
        } else {
            $data = ['status' => '100', 'message' => '缺少参数'];
        }
        exit(json_encode($data));
    }

    
    public function actionCount()
    {
        $jsApiname = input('jsApiname', '', 'trim');
        $link = input('link', '', 'addslashes');

        $share_type = 0;
        switch ($jsApiname) {
            case 'shareTimeline':
                $share_type = 1;
                break;
            case 'sendAppMessage':
                $share_type = 2;
                break;
            case 'shareQQ':
                $share_type = 3;
                break;
            case 'shareQZone':
                $share_type = 4;
                break;
            default:
                break;
        }
        $openid = $_SESSION['openid'];
        if (!empty($share_type) && !empty($openid)) {
            $data = [
                'wechat_id' => $this->wechat_id,
                'openid' => $openid,
                'share_type' => $share_type,
                'link' => $link,
                'share_time' => gmtime(),
            ];
            dao('wechat_share_count')->data($data)->add();
            $result = ['status' => '200', 'msg' => '统计成功'];
        } else {
            $result = ['status' => 'fail', 'msg' => '统计失败'];
        }
        exit(json($result));
    }


    
    private function getConfig($ru_id = 0)
    {
        
        if ($ru_id > 0) {
            $where = ['ru_id' => $ru_id, 'status' => 1];
        } else {
            $where = ['default_wx' => 1, 'status' => 1];
        }
        $wxinfo = dao('wechat')->field('id, token, appid, appsecret')->where($where)->find();
        return $wxinfo;
    }
}
