<?php

namespace App\Modules\Wechat\Market\Wall;

use App\Extensions\Wechat;
use App\Modules\Wechat\Controllers\PluginController;


class Wall extends PluginController
{
    private $weObj = '';
    private $wechat_id = 0;
    private $market_id = 0;
    private $marketing_type = 'wall';

    protected $config = [];

    
    public function __construct($config = [])
    {
        parent::__construct();

        $this->plugin_name = $this->marketing_type = strtolower(basename(__FILE__, '.php'));
        $this->config = $config;
        $this->ru_id = isset($this->config['ru_id']) ? $this->config['ru_id'] : 0;
        $this->config['plugin_path'] = 'Market';

        
        $this->wechat_id = dao('wechat')->where(['status' => 1, 'ru_id' => $this->ru_id])->getField('id');

        $this->market_id = I('wall_id', 0, 'intval');
        if (empty($this->market_id)) {
            $this->redirect(url('index/index/index'));
        }

        $this->plugin_themes = __ROOT__ . '/public/assets/wechat/' . $this->marketing_type;
        $this->assign('plugin_themes', $this->plugin_themes);
    }

    
    public function actionWallMsg()
    {
        
        $wall = dao('wechat_marketing')->field('id, name, logo, background, starttime, endtime, config, description, support')->where(['id' => $this->market_id, 'marketing_type' => 'wall', 'wechat_id' => $this->wechat_id])->find();

        $wall['status'] = get_status($wall['starttime'], $wall['endtime']); 

        $wall['logo'] = get_wechat_image_path($wall['logo']);
        $wall['background'] = get_wechat_image_path($wall['background']);

        if ($wall['status'] == 1) {
            $cache_key = md5('cache_0');
            $list = S($cache_key);
            if ($list === false) {
                
                $sql = "SELECT u.nickname, u.headimg, m.content, m.addtime FROM {pre}wechat_wall_msg m LEFT JOIN {pre}wechat_wall_user u ON m.user_id = u.id WHERE m.status = 1 and u.wall_id = " . $this->market_id . " AND u.wechat_id = " . $this->wechat_id . " ORDER BY m.addtime DESC LIMIT 0, 10";
                $data = $this->model->query($sql);
                if ($data) {
                    usort($data, function ($a, $b) {
                        if ($a['addtime'] == $b['addtime']) {
                            return 0;
                        }
                        return $a['addtime'] > $b['addtime'] ? 1 : -1;
                    });
                    foreach ($data as $k => $v) {
                        $data[$k]['addtime'] = local_date('Y-m-d H:i:s', $v['addtime']);
                    }
                }
                S($cache_key, $data, 10);
                $list = S($cache_key);
            }

            $sql = "SELECT count(*) as num FROM {pre}wechat_wall_msg m LEFT JOIN {pre}wechat_wall_user u ON m.user_id = u.id WHERE m.status = 1 AND u.wall_id = " . $this->market_id . "  AND u.wechat_id = " . $this->wechat_id . " ORDER BY m.addtime DESC";
            $num = $this->model->query($sql);
            $this->assign('msg_count', $num[0]['num']);
        }

        $this->assign('wall', $wall);
        $this->assign('list', $list);
        $this->show_display('wallmsg', $this->config);
    }

    
    public function actionWallUser()
    {
        
        $wall = dao('wechat_marketing')->field('id, name, logo, background, starttime, endtime, config, description, support,status')->where(['id' => $this->market_id, 'marketing_type' => 'wall', 'wechat_id' => $this->wechat_id])->find();

        $wall['status'] = get_status($wall['starttime'], $wall['endtime']); 

        $wall['logo'] = get_wechat_image_path($wall['logo']);
        $wall['background'] = get_wechat_image_path($wall['background']);

        
        $list = dao('wechat_wall_user')->field('nickname, headimg')->where(['wall_id' => $this->market_id, 'status' => 1, 'wechat_id' => $this->wechat_id])->order('addtime desc')->select();
        

        $this->assign('wall', $wall);
        $this->assign('list', $list);
        $this->show_display('walluser', $this->config);
    }

    
    public function actionWallPrize()
    {
        
        $wall = dao('wechat_marketing')->field('id, name, logo, background, starttime, endtime, config, description, support')->where(['id' => $this->market_id, 'marketing_type' => 'wall', 'wechat_id' => $this->wechat_id])->find();
        if ($wall) {
            $wall['config'] = unserialize($wall['config']);
            $wall['logo'] = get_wechat_image_path($wall['logo']);
            $wall['background'] = get_wechat_image_path($wall['background']);
        }

        
        $sql = "SELECT u.nickname, u.headimg, u.id, u.wechatname, u.headimgurl FROM {pre}wechat_prize p LEFT JOIN {pre}wechat_wall_user u ON u.openid = p.openid WHERE u.wall_id = " . $this->market_id . " AND u.status = 1 AND u.openid in (SELECT openid FROM {pre}wechat_prize WHERE market_id = " . $this->market_id . " AND wechat_id = " . $this->wechat_id . " AND activity_type = 'wall' AND prize_type = 1) GROUP BY u.id ORDER BY p.dateline ASC";
        $rs = $this->model->query($sql);
        $list = [];
        if ($rs) {
            foreach ($rs as $k => $v) {
                $list[$k + 1] = $v;
            }
        }
        $prize_user = count($rs);
        
        $total = dao('wechat_wall_user')->where(['status' => 1, 'wechat_id' => $this->wechat_id])->count();
        $total = $total - $prize_user;

        $this->assign('total', $total);
        $this->assign('prize_num', count($list));
        $this->assign('list', $list);
        $this->assign('wall', $wall);
        $this->show_display('wallprize', $this->config);
    }

    
    public function actionNoPrize()
    {
        if (IS_AJAX) {
            $result['errCode'] = 0;
            $result['errMsg'] = '';

            $wall_id = I('get.wall_id');
            if (empty($wall_id)) {
                $result['errCode'] = 1;
                exit(json_encode($result));
            }
            $wall = dao('wechat_marketing')->field('id, name, starttime, endtime, config')->where(['id' => $wall_id, 'marketing_type' => 'wall', 'wechat_id' => $this->wechat_id])->find();
            if (empty($wall)) {
                $result['errCode'] = 2;
                $result['errMsg'] = '活动不存在';
                exit(json_encode($result));
            }
            $wall['status'] = get_status($wall['starttime'], $wall['endtime']); 
            if ($wall['status'] != 1) {
                $result['errCode'] = 2;
                $result['errMsg'] = '活动尚未开始或者已结束';
                exit(json_encode($result));
            }
            
            $sql = "SELECT nickname, headimg, id, wechatname, headimgurl FROM {pre}wechat_wall_user WHERE wall_id = " . $wall_id . " AND status = 1 AND openid not in (SELECT openid FROM {pre}wechat_prize WHERE market_id = " . $wall_id . " AND wechat_id = " . $this->wechat_id . " AND activity_type = 'wall') ORDER BY addtime DESC";
            $no_prize = $this->model->query($sql);
            if (empty($no_prize)) {
                $result['errCode'] = 2;
                $result['errMsg'] = '暂无参与抽奖用户';
                exit(json_encode($result));
            }

            $result['data'] = $no_prize;
            exit(json_encode($result));
        }
    }

    
    public function actionStartDraw()
    {
        if (IS_AJAX) {
            $result['errCode'] = 0;
            $result['errMsg'] = '';

            $wall_id = I('get.wall_id');
            if (empty($wall_id)) {
                $result['errCode'] = 1;
                exit(json_encode($result));
            }
            $wall = dao('wechat_marketing')->field('id, name, starttime, endtime, config')->where(['id' => $wall_id, 'marketing_type' => 'wall', 'wechat_id' => $this->wechat_id])->find();
            if (empty($wall)) {
                $result['errCode'] = 2;
                $result['errMsg'] = '活动不存在';
                exit(json_encode($result));
            }
            $wall['status'] = get_status($wall['starttime'], $wall['endtime']); 
            if ($wall['status'] != 1) {
                $result['errCode'] = 2;
                $result['errMsg'] = '活动尚未开始或者已结束';
                exit(json_encode($result));
            }

            $sql = "SELECT u.nickname, u.headimg, u.openid, u.id, u.wechatname, u.headimgurl FROM {pre}wechat_wall_user u LEFT JOIN {pre}wechat_prize p ON u.openid = p.openid WHERE u.wall_id = '$wall_id' AND u.status = 1 AND u.openid not in (SELECT openid FROM {pre}wechat_prize WHERE market_id = '$wall_id' AND wechat_id = '$this->wechat_id' AND activity_type = 'wall') ORDER BY u.addtime DESC";
            $list = $this->model->query($sql);
            if ($list) {
                
                $key = mt_rand(0, count($list) - 1);
                $rs = isset($list[$key]) ? $list[$key] : $list[0];

                
                $prize = unserialize($wall['config']);
                if (!empty($prize)) {
                    $arr = [];
                    $prize_name = [];

                    $prob = 0;
                    foreach ($prize as $key => $val) {
                        
                        $count = dao('wechat_prize')
                            ->where(['wechat_id' => $this->wechat_id, 'prize_name' => $val['prize_name'], 'activity_type' => 'wall'])
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
                    
                    if ($prob < 100) {
                        $prob = 100 - $prob;
                        $arr['not'] = $prob;
                    }
                    
                    $level = $this->get_rand($arr);
                    if ($level == 'not') {
                        $data['prize_type'] = 0;
                        $data['prize_name'] = '没有中奖';
                    } else {
                        $data['prize_type'] = 1;
                        $data['prize_name'] = $prize_name[$level];
                    }
                }

                
                if ($data['prize_type'] == 1) {
                    $data['wechat_id'] = $this->wechat_id;
                    $data['openid'] = $rs['openid'];
                    $data['issue_status'] = 0;
                    $data['dateline'] = gmtime();
                    $data['activity_type'] = 'wall';
                    $data['market_id'] = $wall_id;
                    dao('wechat_prize')->data($data)->add();
                }

                
                $rs['prize_num'] = dao('wechat_prize')->where(['market_id' => $wall_id, 'wechat_id' => $this->wechat_id, 'activity_type' => 'wall', 'prize_type' => 1])->count();
                
                $rs['prize_name'] = $data['prize_name'];
                
                $total = dao('wechat_wall_user')->where(['status' => 1, 'wechat_id' => $this->wechat_id])->count();
                $rs['total_num'] = $total - $rs['prize_num'];

                $rs['prize_type'] = $data['prize_type'];

                $result['data'] = $rs;
                exit(json_encode($result));
            }
        }
        $result['errCode'] = 2;
        $result['errMsg'] = '暂无数据';
        exit(json_encode($result));
    }

    
    public function actionResetDraw()
    {
        if (IS_AJAX) {
            $result['errCode'] = 0;
            $result['errMsg'] = '';

            $wall_id = I('get.wall_id');
            if (empty($wall_id)) {
                $result['errCode'] = 1;
                exit(json_encode($result));
            }
            $wall = dao('wechat_marketing')->field('id, name, starttime, endtime, config')->where(['id' => $wall_id, 'marketing_type' => 'wall', 'wechat_id' => $this->wechat_id])->find();
            if (empty($wall)) {
                $result['errCode'] = 2;
                $result['errMsg'] = '活动不存在';
                exit(json_encode($result));
            }
            $wall['status'] = get_status($wall['starttime'], $wall['endtime']); 
            if ($wall['status'] != 1) {
                $result['errCode'] = 2;
                $result['errMsg'] = '活动尚未开始或者已结束';
                exit(json_encode($result));
            }
            
            dao('wechat_prize')->where(['market_id' => $wall_id, 'activity_type'=>'wall', 'wechat_id' => $this->wechat_id])->delete();
            
            
            exit(json_encode($result));
        }
        $result['errCode'] = 2;
        $result['errMsg'] = '无效的请求';
        exit(json_encode($result));
    }

    
    public function actionWallUserWechat()
    {
        if (!empty($_SESSION['openid'])) {
            if (IS_POST) {
                $wall_id = I('post.wall_id');
                $user_id = I('post.user_id');

                if (empty($wall_id)) {
                    show_message("请选择对应的活动");
                }
                $data['nickname'] = I('post.nickname');
                $data['headimg'] = I('post.headimg');
                $data['sex'] = I('post.sex');
                $data['openid'] = $_SESSION['openid'];
                $data['wechatname'] = !empty($_SESSION['nickname']) ? $_SESSION['nickname'] : $data['nickname'];
                $data['headimgurl'] = !empty($_SESSION['headimgurl']) ? $_SESSION['headimgurl'] : $data['headimg'];

                $data['wall_id'] = $wall_id;
                $data['wechat_id'] = $this->wechat_id;
                $data['addtime'] = gmtime();

                $wechat_user = dao('wechat_wall_user')->where(['wall_id' => $wall_id, 'openid' => $_SESSION['openid'], 'wechat_id' => $this->wechat_id])->find();
                if (empty($wechat_user)) {
                    dao('wechat_wall_user')->data($data)->add();
                }
                
                show_message('确认成功', '进入聊天室', url('wechat/index/market_show', ['type' => 'wall', 'function' => 'wall_msg_wechat', 'wall_id' => $wall_id, 'ru_id' => $this->ru_id]), 'success');
                exit;
            }
            
            $wall_id = $this->market_id;
            
            
            $wechat_user = dao('wechat_wall_user')->where(['wall_id' => $wall_id, 'openid' => $_SESSION['openid'], 'wechat_id' => $this->wechat_id])->find();

            if (empty($wechat_user)) {
                $wechat_user = [
                    'id' => $_SESSION['user_id'],
                    'headimgurl' => $_SESSION['headimgurl'],
                    'nickname' => $_SESSION['nickname'],
                    'sex' => $_SESSION['sex'],
                ];
            } else {
                
                $this->redirect(url('wechat/index/market_show', ['type' => 'wall', 'function' => 'wall_msg_wechat', 'wall_id' => $wall_id, 'ru_id' => $this->ru_id]));
            }

            $this->assign('user', $wechat_user);
            $this->assign('wall_id', $wall_id);
            $this->show_display('walluserwechat', $this->config);
        }
    }

    
    public function actionWallMsgWechat()
    {
        if (!empty($_SESSION['openid'])) {
            if (IS_POST && IS_AJAX) {
                $wall_id = I('wall_id');
                if (empty($wall_id)) {
                    exit(json_encode(['code' => 1, 'errMsg' => '请选择对应的活动']));
                }
                $data['user_id'] = I('post.user_id');
                $data['content'] = I('post.content', '', 'trim,htmlspecialchars');
                if (empty($data['user_id']) || empty($data['content'])) {
                    exit(json_encode(['code' => 1, 'errMsg' => '请先登录或者发表的内容不能为空']));
                }
                $data['addtime'] = gmtime();
                $data['wall_id'] = $wall_id;
                $data['wechat_id'] = $this->wechat_id;

                dao('wechat_wall_msg')->data($data)->add();
                
                exit(json_encode(['code' => 0, 'errMsg' => '发送成功！']));
            }

            $wall_id = I('wall_id');
            if (empty($wall_id)) {
                $this->redirect(url('index/index'));
            }
            
            $openid = $_SESSION['openid'];
            $wechat_user = dao('wechat_wall_user')->field('id, status')->where(['openid' => $openid, 'wall_id' => $wall_id, 'wechat_id' => $this->wechat_id])->find();

            
            $user_num = dao('wechat_wall_msg')->field("COUNT(DISTINCT user_id) as num")->where(['wall_id' => $wall_id, 'wechat_id' => $this->wechat_id])->find();

            
            $cache_key = md5('cache_wechat_0');
            $list = S($cache_key);
            if ($list === false) {
                $sql = "SELECT m.content, m.addtime, u.nickname, u.headimg, u.id FROM {pre}wechat_wall_msg m LEFT JOIN {pre}wechat_wall_user u ON m.user_id = u.id WHERE (m.status = 1 OR u.openid = '".$openid."') AND u.wall_id = " . $wall_id . " AND u.wechat_id = " . $this->wechat_id . " ORDER BY m.addtime DESC LIMIT 0, 10";
                $data = $this->model->query($sql);

                if ($data) {
                    usort($data, function ($a, $b) {
                        if ($a['addtime'] == $b['addtime']) {
                            return 0;
                        }
                        return $a['addtime'] > $b['addtime'] ? 1 : -1;
                    });
                    foreach ($data as $k => $v) {
                        $data[$k]['addtime'] = local_date('Y-m-d', gmtime()) == local_date('Y-m-d', $v['addtime']) ? local_date('H:i:s', $v['addtime']) : local_date('Y-m-d H:i:s', $v['addtime']);
                    }
                }
                S($cache_key, $data, 10);
                $list = S($cache_key);
            }

            
            $sql = "SELECT count(*) as num FROM {pre}wechat_wall_msg m LEFT JOIN {pre}wechat_wall_user u ON m.user_id = u.id WHERE (m.status = 1 OR u.openid = '".$openid."') AND u.wall_id = " . $wall_id . " AND u.wechat_id = " . $this->wechat_id . " ORDER BY m.addtime DESC";
            $num = $this->model->query($sql);

            $this->assign('list', $list);
            $this->assign('msg_count', $num[0]['num']);
            $this->assign('user_num', $user_num['num']);
            $this->assign('user', $wechat_user);
            $this->assign('wall_id', $wall_id);
            $this->show_display('wallmsgwechat', $this->config);
        }
    }

    
    public function actionGetWallMsg()
    {
        if (IS_AJAX && IS_GET) {
            $start = I('get.start', 0, 'intval');
            $num = I('get.num', 5);
            $wall_id = I('get.wall_id');
            if ((!empty($start) || $start == 0) && $num) {
                $cache_key = md5('cache_' . $start);
                
                if (isset($_SESSION) && !empty($_SESSION['openid'])) {
                    $cache_key = md5('cache_wechat_' . $start);
                }
                $list = S($cache_key);
                if ($list === false) {
                    $sql = "SELECT m.content, m.addtime, u.nickname, u.headimg, u.id, m.status FROM {pre}wechat_wall_msg m LEFT JOIN {pre}wechat_wall_user u ON m.user_id = u.id WHERE m.status = 1 AND u.wall_id = " . $wall_id . " AND u.wechat_id = " . $this->wechat_id . " ORDER BY m.addtime ASC LIMIT " . $start . ", " . $num;
                    if (isset($_SESSION) && !empty($_SESSION['openid'])) {
                        $openid = $_SESSION['openid'];
                        $sql = "SELECT m.content, m.addtime, u.nickname, u.headimg, u.id, m.status FROM {pre}wechat_wall_msg m LEFT JOIN {pre}wechat_wall_user u ON m.user_id = u.id WHERE (m.status = 1 OR u.openid = '".$openid."') AND u.wall_id = " . $wall_id . " AND u.wechat_id = " . $this->wechat_id . " ORDER BY m.addtime ASC LIMIT " . $start . ", " . $num;
                    }
                    $data = $this->model->query($sql);
                    foreach ($data as $k => $v) {
                        $data[$k]['addtime'] = local_date('Y-m-d', gmtime()) == local_date('Y-m-d', $v['addtime']) ? local_date('H:i:s', $v['addtime']) : local_date('Y-m-d H:i:s', $v['addtime']);
                    }
                    S($cache_key, $data, 10);
                    $list = S($cache_key);
                }

                
                $user_num = dao('wechat_wall_msg')->field("COUNT(DISTINCT user_id) as num")->where(['wall_id' => $wall_id, 'wechat_id' => $this->wechat_id])->find();
                $result = ['code' => 0, 'user_num' => $user_num['num'], 'data' => $list];
                exit(json_encode($result));
            }
        } else {
            $result = ['code' => 1, 'errMsg' => '请求不合法'];
            exit(json_encode($result));
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

    
    public function executeAction()
    {
    }
}
