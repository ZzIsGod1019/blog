<?php

namespace App\Modules\Wechat\Controllers;

use App\Modules\Base\Controllers\BackendController;

class WxappController extends BackendController
{
    public function __construct()
    {
        parent::__construct();
        L(require(MODULE_PATH . 'Language/' . C('shop.lang') . '/wechat.php'));
        $this->assign('lang', array_change_key_case(L()));
        
        $this->init_params();
    }

    
    private function init_params()
    {

    }

    
    public function actionIndex()
    {
        
        $this->admin_priv('wxapp_config');

        
        if (IS_POST) {
            $id = I('id', 0, 'intval');
            $data = I('post.data', '', 'trim');
            
            if (empty($data['wx_appid'])) {
                $this->message(L('must_appid'), null, 2);
            }
            if (empty($data['wx_appsecret'])) {
                $this->message(L('must_appsecret'), null, 2);
            }
            if (empty($data['wx_mch_id'])) {
                $this->message(L('must_mch_id'), null, 2);
            }
            if (empty($data['wx_mch_key'])) {
                $this->message(L('must_mch_key'), null, 2);
            }
            if (empty($data['token_secret'])) {
                $this->message(L('must_token_secret'), null, 2);
            }
            
            if (!empty($id)) {
                
                if (strpos($data['wx_appsecret'], '*') == true) {
                    unset($data['wx_appsecret']);
                }
                dao('wxapp_config')->data($data)->where(['id' => $id])->save();
            } else {
                $data['add_time'] = gmtime();
                dao('wxapp_config')->data($data)->add();
            }
            $this->message(L('wechat_editor') . L('success'), url('index'));
        }

        
        $info = dao('wxapp_config')->find();
        if (!empty($info)) {
            
            $info['wx_appsecret'] = string_to_star($info['wx_appsecret']);
        }

        $this->assign('data', $info);
        $this->display();
    }

    
    public function actionAppend()
    {

    }

    
    public function actionDelete()
    {
        $condition['id'] = input('id', 0, 'intval');
        dao('wxapp_config')->where($condition)->delete();
    }


}
