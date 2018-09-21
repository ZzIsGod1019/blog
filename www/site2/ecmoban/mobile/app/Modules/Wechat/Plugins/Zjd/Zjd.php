<?php

namespace App\Modules\Wechat\Plugins\Zjd;

use App\Modules\Wechat\Controllers\PluginController;


class Zjd extends PluginController
{
    
    protected $plugin_name = '';
    
    protected $wechat_id = 0;
    
    protected $ru_id = 0;
    
    protected $cfg = [];

    
    public function __construct($cfg = [])
    {
        parent::__construct();
        $this->plugin_name = strtolower(basename(__FILE__, '.php'));
        $this->cfg = $cfg;
        $this->ru_id = $this->cfg['ru_id'];
    }

    
    public function install()
    {
        
        if (!empty($this->cfg['handler']) && is_array($this->cfg['config'])) {
            
            if (!empty($this->cfg['config']['plugin_url'])) {
                $this->cfg['config']['plugin_url'] = html_out($this->cfg['config']['plugin_url']);
            }
            
            if (is_array($this->cfg['config']['prize_level']) && is_array($this->cfg['config']['prize_count']) && is_array($this->cfg['config']['prize_prob']) && is_array($this->cfg['config']['prize_name'])) {
                foreach ($this->cfg['config']['prize_level'] as $key => $val) {
                    $this->cfg['config']['prize'][] = [
                        'prize_level' => $val,
                        'prize_name' => $this->cfg['config']['prize_name'][$key],
                        'prize_count' => $this->cfg['config']['prize_count'][$key],
                        'prize_prob' => $this->cfg['config']['prize_prob'][$key]
                    ];
                }
            }
        }
        $this->plugin_display('install', $this->cfg);
    }

    
    public function returnData($fromusername, $info)
    {
        $articles = ['type' => 'text', 'content' => '未启用砸金蛋'];
        
        $config = $this->get_config($this->plugin_name);
        
        if (isset($config['media']) && !empty($config['media'])) {
            
            $articles = [];
            $articles['type'] = 'news';
            $articles['content'][0]['Title'] = $config['media']['title'];
            $articles['content'][0]['Description'] = empty($config['media']['digest']) ? sub_str($config['media']['content'], 100) : $config['media']['digest'];
            $articles['content'][0]['PicUrl'] = get_wechat_image_path($config['media']['file']);
            $articles['content'][0]['Url'] = html_out($config['media']['link']);
        }

        return $articles;
    }


    
    public function html_show()
    {
        
        $config = $this->get_config($this->plugin_name);

        if (empty($config)) {
            show_message('活动未配置', '', '', 'error');
        }

        if (!empty($config)) {
            $num = count($config['prize']);
            if ($num > 0) {
                foreach ($config['prize'] as $key => $val) {
                    
                    if ($key == ($num - 1)) {
                        unset($config['prize'][$key]);
                    }
                }
            }
        }

        $starttime = local_strtotime($config['starttime']);
        $endtime = local_strtotime($config['endtime']);
        $nowtime = gmtime();

        
        if ($this->ru_id > 0) {
            $openid = isset($_COOKIE['openid']) ? $_COOKIE['openid'] : $_SESSION['openid'];
        } else {
            $openid = $_SESSION['openid'];
        }

        if ($starttime > $nowtime || $endtime < $nowtime) {
            $config['prize_num'] = 0;
            if ($nowtime < $starttime) {
                $config['activity_status'] = 1; 
            }
            if ($nowtime > $endtime) {
                $config['activity_status'] = 2; 
            }
        } else {
            if ($config['point_status'] == 1) {
                $users = get_wechat_user_id($openid);
                $user_pay_points = dao('users')->where(['user_id' => $users['user_id']])->getField('pay_points');
                $config['user_pay_points'] = $user_pay_points; 
                
                $num = floor($user_pay_points / $config['point_value']);
                $config['prize_num'] = ($config['point_value'] > 0) ? ($num < 0 ? 0 : $num) : 0;
            } else {
                
                if ($this->ru_id > 0) {
                    $sql = "SELECT count(*) as num FROM {pre}wechat_prize WHERE wechat_id = '" . $this->wechat_id . "' AND openid = '" . $openid . "' AND activity_type = '" . $this->plugin_name . "' AND dateline between '" . $starttime . "' AND '" . $endtime . "'";
                    $num = $GLOBALS['db']->query($sql);
                } else {
                    
                    $sql = "SELECT count(*) as num FROM {pre}wechat_prize WHERE wechat_id = '" . $this->wechat_id . "' AND openid = '" . $openid . "' AND activity_type = '" . $this->plugin_name . "' AND dateline > '" . ($nowtime - $config['point_interval']) . "' ";
                    $num = $GLOBALS['db']->query($sql);
                }
                $count = isset($num[0]) ? $num[0]['num'] : 0;
                $config['prize_num'] = ($config['prize_num'] - $count) < 0 ? 0 : $config['prize_num'] - $count;
            }
            $config['activity_status'] = 0; 
        }

        
        $sql = 'SELECT u.nickname, p.prize_name, p.id FROM {pre}wechat_prize p LEFT JOIN {pre}wechat_user u ON p.openid = u.openid where p.wechat_id = "' . $this->wechat_id . '" and p.openid != "' . $openid . '" and dateline between "' . $starttime . '" and "' . $endtime . '" and p.prize_type = 1 and p.activity_type = "' . $this->plugin_name . '" ORDER BY dateline desc limit 10';
        $list = $GLOBALS['db']->query($sql);
        $this->assign('list', $list);

        
        $sql_one = 'SELECT u.nickname, p.prize_name, p.id, p.winner  FROM {pre}wechat_prize p LEFT JOIN {pre}wechat_user u ON p.openid = u.openid WHERE p.wechat_id = "' . $this->wechat_id . '" and p.openid = "' . $openid . '"  and dateline between "' . $starttime . '" and "' . $endtime . '" and p.prize_type = 1 and p.activity_type = "' . $this->plugin_name . '" ORDER BY dateline desc limit 1';
        $list_oneself = $GLOBALS['db']->query($sql_one);
        if (!empty($list_oneself)) {
            $list_oneself[0]['winner_url'] = url('wechat/index/plugin_action', ['name' => $this->plugin_name, 'id' => $list_oneself[0]['id'], 'ru_id' => $this->ru_id]);
        }
        $this->assign('list_oneself', $list_oneself);

        $config['description'] = nl2br($config['description']);
        $this->assign('prize_num', count($config['prize'])); 
        $this->assign('data', $config);

        $this->assign('ru_id', $this->ru_id);

        $is_wechat = (is_wechat_browser() && is_dir(APP_WECHAT_PATH)) ? 1 : 0;
        $this->assign('is_wechat', $is_wechat);
        
        $share_data = [
            'title' => $config['media']['title'], 
            'desc' => $config['media']['digest'], 
            'link' => html_out($config['media']['link']), 
            'img' => get_wechat_image_path($config['media']['file']), 
        ];
        $this->assign('share_data', $share_data);
        $this->show_display('index', $this->cfg);
    }

    
    public function executeAction()
    {
        
        $config = $this->get_config($this->plugin_name);
        
        if (IS_POST) {
            $id = I('post.id');
            $data = I('post.data');
            if (empty($id)) {
                show_message('请选择中奖的奖品', '', '', 'error');
            }
            if (empty($data['phone'])) {
                show_message('请填写手机号', '', '', 'error');
            }
            if (empty($data['address'])) {
                show_message('请填写详细地址', '', '', 'error');
            }
            $winner['winner'] = serialize($data);

            dao('wechat_prize')->data($winner)->where(['id' => $id, 'wechat_id' => $this->wechat_id])->save();
            show_message('资料提交成功，请等待发放奖品', '继续抽奖', url('wechat/index/plugin_show', ['name' => $this->plugin_name, 'ru_id' => $this->ru_id]));
            exit();
        }
        
        if (!empty($_GET['id']) && !IS_AJAX) {
            $id = I('get.id');
            if ($this->ru_id > 0) {
                $openid = isset($_COOKIE['openid']) ? $_COOKIE['openid'] : $_SESSION['openid'];
            } else {
                $openid = $_SESSION['openid'];
            }
            if (empty($openid)) {
                show_message('请先登录', '', '', 'error');
            }
            $rs = dao('wechat_prize')
                ->field('winner,issue_status')
                ->where(['openid' => $openid, 'id' => $id, 'wechat_id' => $this->wechat_id, 'prize_type' => 1, 'activity_type' => $this->plugin_name])
                ->find();
            $winner_result = [];

            if (!empty($rs) && $rs['issue_status'] != 1) {
                if (!empty($rs['winner'])) {
                    $winner_result = unserialize($rs['winner']);
                } else {
                    
                    $sql = "SELECT winner FROM {pre}wechat_prize WHERE wechat_id = '" . $this->wechat_id . "' AND openid = '" . $openid . "' AND activity_type = '" . $this->plugin_name . "'  AND prize_type = 1 AND id < '" . $id . "'  ORDER by dateline DESC LIMIT 1";
                    $rs1 = $GLOBALS['db']->query($sql);
                    if (!empty($rs1)) {
                        $previous_winner_result = unserialize($rs1[0]['winner']);
                    }
                }
            } else {
                show_message('本次奖品已经领取过了哦', '', '', 'error');
            }
            
            $winner_result = !empty($previous_winner_result) ? $previous_winner_result : $winner_result;

            if ($this->ru_id > 0) {
                $file = MODULE_PATH . 'Plugins/' . ucfirst($this->plugin_name) . '/Views_seller/user_info.html';
            } else {
                $file = MODULE_PATH . 'Plugins/' . ucfirst($this->plugin_name) . '/Views/user_info.html';
            }
            if (file_exists($file)) {
                require_once($file);
            }
            exit();
        }
        
        if (IS_GET && IS_AJAX) {
            $rs = [];
            
            if ($this->ru_id > 0) {
                $openid = isset($_COOKIE['openid']) ? $_COOKIE['openid'] : $_SESSION['openid'];
            } else {
                $openid = $_SESSION['openid'];
            }
            if (empty($openid)) {
                $rs['status'] = 2;
                $rs['msg'] = '请先登录';
                echo json_encode($rs);
                exit();
            }

            
            $starttime = local_strtotime($config['starttime']);
            $endtime = local_strtotime($config['endtime']);

            $nowtime = gmtime();
            if ($nowtime < $starttime) {
                $rs['status'] = 2;
                $rs['msg'] = '活动未开始';
                echo json_encode($rs);
                exit();
            }
            if ($nowtime > $endtime) {
                $rs['status'] = 2;
                $rs['msg'] = '活动已结束';
                echo json_encode($rs);
                exit();
            }
            
            
            if ($this->ru_id > 0) {
                $sql = "SELECT count(*) as num FROM {pre}wechat_prize WHERE wechat_id = '" . $this->wechat_id . "' AND openid = '" . $openid . "' AND activity_type = '" . $this->plugin_name . "' AND dateline between '" . $starttime . "' AND '" . $endtime . "'";
                $count_num = $GLOBALS['db']->query($sql);
                $num = isset($count_num[0]) ? $count_num[0]['num'] : 0;
                if ($num <= 0) {
                    $num = 1;
                } else {
                    $num = $num + 1;
                }
            } else {
                if ($config['point_status'] == 1) {
                    $users = get_wechat_user_id($openid);
                    $user_pay_points = dao('users')->where(['user_id' => $users['user_id']])->getField('pay_points');
                    $num = floor($user_pay_points / $config['point_value']) < 0 ? 0 : floor($user_pay_points / $config['point_value']);
                } else {
                    $sql = "SELECT count(*) as num FROM {pre}wechat_prize WHERE  wechat_id = '" . $this->wechat_id . "' AND openid = '" . $openid . "' AND activity_type = '" . $this->plugin_name . "' AND dateline > '" . ($nowtime - $config['point_interval']) . "'";
                    $count_num = $GLOBALS['db']->query($sql);
                    $num = isset($count_num[0]) ? $count_num[0]['num'] : 0;

                    if ($num <= 0) {
                        $num = 1;
                    } else {
                        $num = $num + 1;
                    }
                }
            }

            if ($config['point_status'] == 1) {
                
                $res = $this->do_takeout_point($openid, $config, $config['point_value']);
                if ($res === false) {
                    $rs['status'] = 2;
                    $rs['msg'] = "积分不够了";
                    echo json_encode($rs);
                    exit();
                }
            } else {
                if ($num > $config['prize_num']) {
                    $rs['status'] = 2;
                    $rs['num'] = 0;
                    $rs['msg'] = '你已经用光了抽奖次数';
                    echo json_encode($rs);
                    exit();
                }
            }

            $prize = $config['prize'];
            if (!empty($prize)) {
                $arr = [];
                $prize_name = [];

                $prob = 0;
                foreach ($prize as $key => $val) {
                    
                    $count = dao('wechat_prize')
                        ->where(['wechat_id' => $this->wechat_id, 'prize_name' => $val['prize_name'], 'activity_type' => $this->plugin_name])
                        ->count();
                    if ($val['prize_prob'] <= 0 || $count >= $val['prize_count']) {
                        unset($prize[$key]);
                    } else {
                        $arr[$val['prize_level']] = $val['prize_prob'];
                        $prize_name[$val['prize_level']] = $val['prize_name'];
                    }
                    
                    $prob = $prob + $val['prize_prob'];
                }
                
                if ($prob <= 0 || $prob > 100) {
                    exit();
                }
                
                $lastarr = end($prize);
                
                $level = $this->get_rand($arr);
                
                if ($level == $lastarr['prize_level']) {
                    $rs['status'] = 0;
                    $data['prize_type'] = 0;
                } else {
                    $rs['status'] = 1;
                    $data['prize_type'] = 1;
                }
                $rs['msg'] = $prize_name[$level];

                if ($config['point_status'] == 1) {
                    $rs['num'] = $num - 1;
                } else {
                    $rs['num'] = $config['prize_num'] - $num > 0 ? $config['prize_num'] - $num : 0;
                }

                
                $data['wechat_id'] = $this->wechat_id;
                $data['openid'] = $openid;
                $data['prize_name'] = $prize_name[$level];
                $data['dateline'] = gmtime();
                $data['activity_type'] = $this->plugin_name;
                $id = dao('wechat_prize')->data($data)->add();

                
                $extend_cfg = dao('wechat_extend')->where(['wechat_id' => $this->wechat_id, 'command' => $this->plugin_name, 'enable' => 1])->getField('config');
                if ($extend_cfg) {
                    $cfg_new = unserialize($extend_cfg);
                }
                $cfg_new['people_num'] = $cfg_new['people_num'] + 1;
                $cfg['config'] = serialize($cfg_new);
                dao('wechat_extend')->where(['wechat_id' => $this->wechat_id, 'command' => $this->plugin_name, 'enable' => 1])->data($cfg)->save();

                if ($level != $lastarr['prize_level'] && !empty($id)) {
                    
                    $rs['link'] = url('wechat/index/plugin_action', ['name' => $this->plugin_name, 'id' => $id, 'ru_id' => $this->ru_id]);
                    $rs['link'] = str_replace('&amp;', '&', $rs['link']);
                }
            }
            echo json_encode($rs);
            exit();
        }
    }

    public function updatePoint($fromusername, $info)
    {
    }


    
    private function get_config($code = '')
    {
        
        $config = [];
        if ($this->ru_id > 0) {
            $map = ['ru_id' => $this->ru_id, 'status' => 1];
        } else {
            $map = ['default_wx' => 1, 'status' => 1];
        }
        $this->wechat_id = dao('wechat')->where($map)->getField('id');
        if (!empty($this->wechat_id)) {
            $plugin_config = dao('wechat_extend')->field('name, command, config')->where(['wechat_id' => $this->wechat_id, 'command' => $code, 'enable' => 1])->find();
            if (!empty($plugin_config)) {
                $config = unserialize($plugin_config['config']);
                $config['name'] = $plugin_config['name'];
                $config['command'] = $plugin_config['command'];
                
                if (!empty($config['media_id'])) {
                    $media = dao('wechat_media')
                        ->field('id, title, file, file_name, type, digest, content, add_time, article_id, link')
                        ->where(['id' => $config['media_id'], 'wechat_id' => $this->wechat_id])
                        ->find();
                    
                    if (empty($media['article_id'])) {
                        $media['content'] = strip_tags(html_out($media['content']));
                        $config['media'] = $media;
                    }
                }
                
                if (!empty($config['plugin_url'])) {
                    $config['plugin_url'] = html_out($config['plugin_url']);
                }
                
                if (is_array($config['prize_level']) && is_array($config['prize_count']) && is_array($config['prize_prob']) && is_array($config['prize_name'])) {
                    foreach ($config['prize_level'] as $key => $val) {
                        $config['prize'][] = [
                            'prize_level' => $val,
                            'prize_name' => $config['prize_name'][$key],
                            'prize_count' => $config['prize_count'][$key],
                            'prize_prob' => $config['prize_prob'][$key]
                        ];
                    }
                }
            }
        }
        return $config;
    }
}
