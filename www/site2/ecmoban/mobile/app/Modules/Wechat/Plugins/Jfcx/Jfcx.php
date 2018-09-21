<?php

namespace App\Modules\Wechat\Plugins\Jfcx;

use App\Modules\Wechat\Controllers\PluginController;


class Jfcx extends PluginController
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
        
        $articles = ['type' => 'text', 'content' => '暂无积分信息'];
        $users = get_wechat_user_id($fromusername);
        if (!empty($users)) {
            $data = dao('users')->field('rank_points, pay_points, user_money')->where(['user_id' => $users['user_id']])->find();
            if (!empty($data)) {
                $data['user_money'] = strip_tags(price_format($data['user_money'], false));
                $articles['content'] = '余额：' . $data['user_money'] . "\r\n" . '等级积分：' . $data['rank_points'] . "\r\n" . '消费积分：' . $data['pay_points'];
                
                $this->updatePoint($fromusername, $info);
            }
        }
        return $articles;
    }

    
    public function updatePoint($fromusername, $info)
    {
        if (!empty($info)) {
            
            $config = [];
            $config = unserialize($info['config']);
            
            if (isset($config['point_status']) && $config['point_status'] == 1) {
                $where = 'openid = "' . $fromusername . '" and keywords = "' . $info['command'] . '" and createtime > (UNIX_TIMESTAMP(NOW())- ' . $config['point_interval'] . ')';
                $sql = 'SELECT count(*) as num FROM {pre}wechat_point WHERE ' . $where . 'ORDER BY createtime DESC';
                $num = $GLOBALS['db']->query($sql);
                
                if ($num[0]['num'] < $config['point_num']) {
                    $this->do_point($fromusername, $info, $config['point_value']);
                }
            }
        }
    }

    
    public function executeAction()
    {
    }
}
