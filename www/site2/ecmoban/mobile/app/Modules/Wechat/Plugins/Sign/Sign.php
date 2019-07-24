<?php

namespace App\Modules\Wechat\Plugins\Sign;

use App\Modules\Wechat\Controllers\PluginController;


class Sign extends PluginController
{
    
    protected $plugin_name = '';
    
    protected $cfg = [];

    
    public function __construct($cfg = [])
    {
        parent::__construct();
        $this->plugin_name = strtolower(basename(__FILE__, '.php'));
        $this->cfg = $cfg;
    }

    
    public function install()
    {
        $this->plugin_display('install', $this->cfg);
    }

    
    public function returnData($fromusername, $info)
    {
        $articles = ['type' => 'text', 'content' => '签到失败'];
        
        $config = [];
        $config = unserialize($info['config']);
        if (isset($config['point_status']) && $config['point_status'] == 1) {
            $users = get_wechat_user_id($fromusername);
            if ($users) {
                
                $condition['openid'] = $fromusername;
                $condition['keywords'] = $info['command'];
                $result = dao('wechat_point')->field('createtime')->where($condition)->order('log_id desc')->find();

                $nowtime_format = local_date('Y-m-d', gmtime());
                $createtime = local_date('Y-m-d', $result['createtime']);

                
                if (empty($result) || $createtime != $nowtime_format) {
                    if (!empty($config['rank_point_value']) || !empty($config['pay_point_value'])) {
                        
                        $rs = $this->updatePoint($fromusername, $info, $config['rank_point_value'], $config['pay_point_value']);
                        if ($rs == true) {
                            $tips = "系统赠送您 ";
                            $tips .= !empty($config['rank_point_value']) ? $config['rank_point_value'] . " 等级积分 " : '';
                            $tips .= !empty($config['pay_point_value']) ? $config['pay_point_value'] . " 消费积分 " : '';

                            $articles['content'] = '签到成功！' . $tips;
                        }
                    }
                } else {
                    $articles['content'] = '今日签到次数已用完，请明天再来';
                }
            } else {
                $articles['content'] = '尚未绑定商城会员,请先绑定';
            }
        } else {
            $articles['content'] = '未启用签到送积分';
        }
        return $articles;
    }

    
    public function updatePoint($fromusername, $info, $rank_point_value = 0, $pay_point_value = 0)
    {
        return $this->do_point($fromusername, $info, $rank_point_value, $pay_point_value);
    }

    
    public function executeAction()
    {
    }
}
