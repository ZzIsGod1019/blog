<?php

namespace App\Modules\Wechat\Market\Wall;

use Think\Image;
use App\Extensions\Form;
use App\Extensions\QRcode;
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
                $list[$k]['starttime'] = local_date('Y-m-d', $v['starttime']);
                $list[$k]['endtime'] = local_date('Y-m-d', $v['endtime']);
                $res = $this->get_user_msg_count($v['id']);
                $list[$k]['user_count'] = $res['user_count'];  
                $list[$k]['msg_count'] = $res['msg_count'];  
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
            $handler = I('post.handler', '', 'trim');
            $data = I('post.data', '', 'trim');
            $config = I('post.config', '', 'trim');

            $form = new Form();
            if (!$form->isEmpty($data['name'], 1)) {
                
                $json_result = ['error' => 1, 'msg' => L('market_name') . L('empty'), 'url' => ''];
                exit(json_encode($json_result));
            }
            $data['wechat_id'] = $this->wechat_id;
            $data['marketing_type'] = I('post.marketing_type');
            $data['starttime'] = local_strtotime($data['starttime']);
            $data['endtime'] = local_strtotime($data['endtime']);

            $data['status'] = get_status($data['starttime'], $data['endtime']); 

            $logo_path = I('post.logo_path');
            $background_path = I('post.background_path');
            
            $logo_path = edit_upload_image($logo_path);
            $background_path = edit_upload_image($background_path);

            
            if ($_FILES['logo']['name'] || $_FILES['background']['name']) {
                
                $type = ['image/jpeg','image/png'];
                if (($_FILES['logo']['type'] && !in_array($_FILES['logo']['type'], $type)) || ($_FILES['background']['type'] && !in_array($_FILES['background']['type'], $type))) {
                    
                    $json_result = ['error' => 1, 'msg' => L('not_file_type'), 'url' => ''];
                    exit(json_encode($json_result));
                }
                $result = $this->upload('data/attached/wall', false, 5);
                if ($result['error'] > 0) {
                    
                    $json_result = ['error' => 1, 'msg' => $result['message'], 'url' => ''];
                    exit(json_encode($json_result));
                }
            }
            
            if ($_FILES['logo']['name'] && $result['url']['logo']['url']) {
                $data['logo'] = $result['url']['logo']['url'];
            } else {
                $data['logo'] = $logo_path;
            }
            
            if ($_FILES['background']['name'] && $result['url']['background']['url']) {
                $data['background'] = $result['url']['background']['url'];
            } else {
                $data['background'] =  $background_path;
            }

            if (!$form->isEmpty($data['logo'], 1)) {
                
                $json_result = ['error' => 1, 'msg' => L('please_upload'), 'url' => ''];
                exit(json_encode($json_result));
            }
            if (!$form->isEmpty($data['background'], 1)) {
                
                $json_result = ['error' => 1, 'msg' => L('please_upload'), 'url' => ''];
                exit(json_encode($json_result));
            }
            
            if ($config) {
                
                if (is_array($config['prize_level']) && is_array($config['prize_count']) && is_array($config['prize_name']) && is_array($config['prize_prob'])) {
                    foreach ($config['prize_level'] as $key => $val) {
                        $prize_arr[] = [
                            'prize_level' => $val,
                            'prize_name' => $config['prize_name'][$key],
                            'prize_count' => $config['prize_count'][$key],
                            'prize_prob' => $config['prize_prob'][$key]
                        ];
                    }
                }
                $data['config'] = serialize($prize_arr);
            }
            
            if (strpos($data['logo'], 'no_image') !== false) {
                unset($data['logo']);
            }
            if (strpos($data['background'], 'no_image') !== false) {
                unset($data['background']);
            }

            
            if ($id && $handler == 'edit') {
                
                if ($data['logo'] && $logo_path != $data['logo']) {
                    $logo_path = strpos($logo_path, 'no_image') == false ? $logo_path : ''; 
                    $this->remove($logo_path);
                }
                
                if ($data['background'] && $background_path != $data['background']) {
                    $background_path = strpos($background_path, 'no_image') == false ? $background_path : '';  
                    $this->remove($background_path);
                }
                $where = ['id' => $id, 'wechat_id' => $this->wechat_id];
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
        if (!empty($this->cfg['market_id'])) {
            $market_id = $this->cfg['market_id'];
            $info = dao('wechat_marketing')->field('id, name, command, logo, background, starttime, endtime, config, description, support')->where(['id' => $market_id, 'marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id])->find();
            if ($info) {
                $info['starttime'] = isset($info['starttime']) ? local_date('Y-m-d H:i:s', $info['starttime']) : local_date('Y-m-d H:i:s', $nowtime);
                $info['endtime'] = isset($info['endtime']) ? local_date('Y-m-d H:i:s', $info['endtime']) : local_date('Y-m-d H:i:s', local_strtotime("+1 months", $nowtime));
                $info['prize_arr'] = unserialize($info['config']);
                $info['logo'] = get_wechat_image_path($info['logo']);
                $info['background'] = get_wechat_image_path($info['background']);
            } else {
                $this->message('数据不存在', url('market_list', ['type' => $this->marketing_type]), 2, $this->ru_id);
            }
        } else {
            
            $info['starttime'] = local_date('Y-m-d H:i:s', $nowtime);
            $info['endtime'] = local_date('Y-m-d H:i:s', local_strtotime("+1 months", $nowtime));

            
            $last_id = dao('wechat_marketing')->where(['wechat_id' => $this->wechat_id])->order('id desc')->getField('id');
            $market_id = !empty($last_id) ? $last_id + 1 : 1;
        }

        
        $info['url'] = __HOST__ . url('wechat/index/market_show', ['type' => 'wall', 'function' => 'wall_user_wechat', 'wall_id' => $market_id, 'ru_id' => $this->ru_id]);

        $this->assign('info', $info);
        $this->plugin_display('market_edit', $this->cfg);
    }

    
    public function marketMessages()
    {
        $market_id = $this->cfg['market_id'];

        $this->cfg['status'] = $status = I('get.status', '', 'trim');

        $function = I('get.function', '', 'trim');

        $where = "";
        if (empty($status)) {
            $where = " AND m.status = 0";
        }

        $sql = "SELECT COUNT(*) as num FROM {pre}wechat_wall_msg m LEFT JOIN {pre}wechat_wall_user u ON m.user_id = u.id LEFT JOIN {pre}wechat_marketing mk ON m.wall_id = mk.id WHERE m.wall_id = ".$market_id . $where;
        $num  = $this->model->query($sql);
        
        $filter['type'] = $this->marketing_type;
        $filter['function'] = $function;
        $filter['id'] = $market_id;
        $filter['status'] = $status;
        $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);
        $total = $num[0]['num'];
        
        $this->assign('page', $this->pageShow($total));

        $sql = "SELECT m.id, m.user_id, m.content, m.addtime, m.checktime, m.status, u.nickname FROM {pre}wechat_wall_msg m LEFT JOIN {pre}wechat_wall_user u ON m.user_id = u.id LEFT JOIN {pre}wechat_marketing mk ON m.wall_id = mk.id WHERE m.wall_id = " .$market_id . $where . " ORDER BY m.addtime ASC LIMIT $offset";

        $list =  $this->model->query($sql);
        if ($list) {
            foreach ($list as $k=>$v) {
                if ($v['status'] == 1) {
                    $list[$k]['status'] = L('is_checked');
                    $list[$k]['handler'] = '';
                } else {
                    $list[$k]['status'] = L('no_check');
                    $list[$k]['handler'] = '<a class="button btn-info bg-green check" data-href="'.url('market_action', ['type' => $this->marketing_type, 'function' => 'messages', 'handler' => 'check','market_id' => $market_id, 'msg_id' => $v['id'], 'user_id' => $v['user_id'], 'status' => $status]).'" href="javascript:;" >' . L('check') . '</a>';
                }
                $list[$k]['addtime'] = $v['addtime'] ? local_date('Y-m-d H:i:s', $v['addtime']) : '';
                $list[$k]['checktime'] = $v['checktime'] ? local_date('Y-m-d H:i:s', $v['checktime']) : '';
            }
        }
        $this->assign('list', $list);
        $this->plugin_display('market_messages', $this->cfg);
    }

    
    public function marketUsers()
    {
        $market_id = $this->cfg['market_id'];

        $user_id = I('get.user_id', 0, 'intval');
        $function = I('get.function', '', 'trim');

        $list = [];
        
        if (empty($user_id)) {
            
            $filter['type'] = $this->marketing_type;
            $filter['function'] = $function;
            $filter['id'] = $market_id;
            $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);
            $total = dao('wechat_wall_user')->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id])->count();
            
            $this->assign('page', $this->pageShow($total));

            
            $sql = "SELECT id, nickname, sex, headimg, status, addtime FROM {pre}wechat_wall_user WHERE wall_id = '" . $market_id . "' AND wechat_id = '" .$this->wechat_id. "'  ORDER BY addtime DESC limit $offset";
            $list = $this->model->query($sql);
            if ($list[0]['id']) {
                foreach ($list as $k => $v) {
                    if ($v['sex'] == 1) {
                        $list[$k]['sex'] = '男';
                    } elseif ($v['sex'] == 2) {
                        $list[$k]['sex'] = '女';
                    } else {
                        $list[$k]['sex'] = '保密';
                    }
                    if ($v['status'] == 1) {
                        $list[$k]['status'] = L('is_checked');
                        $list[$k]['handler'] = '';
                    } else {
                        $list[$k]['status'] = L('no_check'); 
                        $list[$k]['handler'] = '<a class="button btn-info bg-green check" data-href="'.url('market_action', ['type' => $this->marketing_type, 'function' => 'users', 'handler' => 'check', 'market_id' => $market_id, 'user_id' => $v['id']]).'" href="javascript:;" >' . L('check') . '</a>';
                    }
                    $list[$k]['nocheck'] = dao('wechat_wall_msg')->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'status' => 0, 'user_id' => $v['id']])->count();
                    $list[$k]['addtime'] = $v['addtime'] ? local_date('Y-m-d H:i:s', $v['addtime']) : '';
                }
            }
            $this->assign('list', $list);
            $this->plugin_display('market_users', $this->cfg);
        } else {
            
            
            $filter['type'] = $this->marketing_type;
            $filter['function'] = $function;
            $filter['wall_id'] = $market_id;
            $filter['user_id'] = $user_id;
            $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);
            $total = dao('wechat_wall_msg')->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'user_id' => $user_id])->count();
            
            $this->assign('page', $this->pageShow($total));

            $list = dao('wechat_wall_msg')->field('id, content, addtime, checktime, status')->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'user_id' => $user_id])->order('addtime DESC, checktime DESC')->limit($offset)->select();
            if ($list) {
                foreach ($list as $k => $v) {
                    if ($v['status'] == 1) {
                        $list[$k]['status'] = L('is_checked');
                        $list[$k]['handler'] = '';
                    } else {
                        $list[$k]['status'] = L('no_check'); 
                        $list[$k]['handler'] = '<a class="button btn-info bg-green check" data-href="'.url('market_action', ['type' => $this->marketing_type, 'function' => 'users', 'handler' => 'check','market_id' => $market_id, 'msg_id'=> $v['id'], 'user_id' => $user_id]).'" href="javascript:;" >' .L('check'). '</a>';
                    }
                    $list[$k]['addtime'] = $v['addtime'] ? local_date('Y-m-d H:i:s', $v['addtime']) : '';
                    $list[$k]['checktime'] = $v['checktime'] ? local_date('Y-m-d H:i:s', $v['checktime']) : '';
                }
            }
            $this->assign('list', $list);
            $this->plugin_display('market_users_msg', $this->cfg);
        }
    }

    
    public function marketPrizes()
    {
        $market_id = I('get.id', 0, 'intval');
        $function = I('get.function', '', 'trim');

        
        $filter['type'] = $this->marketing_type;
        $filter['function'] = $function;
        $filter['id'] = $market_id;

        $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);

        $sql_count = 'SELECT count(*) as number FROM {pre}wechat_prize p LEFT JOIN {pre}wechat_user u ON p.openid = u.openid WHERE p.activity_type = "' . $this->marketing_type . '" and p.prize_type = 1 AND p.market_id = ' .$market_id. ' and u.subscribe = 1 and u.wechat_id = ' . $this->wechat_id . ' ORDER BY dateline desc ';
        $total = $this->model->query($sql_count);
        
        $this->assign('page', $this->pageShow($total[0]['number']));

        $sql = 'SELECT p.id, p.prize_name, p.issue_status, p.winner, p.dateline, p.openid, u.nickname FROM {pre}wechat_prize p LEFT JOIN {pre}wechat_user u ON p.openid = u.openid WHERE p.activity_type = "' . $this->marketing_type . '" and u.wechat_id = ' . $this->wechat_id . ' and p.prize_type = 1 AND p.market_id = ' .$market_id. ' and u.subscribe = 1 ORDER BY dateline desc  limit ' . $offset;
        $list = $this->model->query($sql);
        if (!empty($list)) {
            foreach ($list as $key => $val) {
                $list[$key]['winner'] = unserialize($val['winner']);
                $list[$key]['dateline'] = local_date(C('shop.time_format'), $val['dateline']);
                if ($val['issue_status'] == 1) {
                    $list[$key]['issue_status'] = L('is_sended');
                    $list[$key]['handler'] = '<a href="javascript:;"  data-href="'.url('market_action', ['type' => $this->marketing_type, 'handler' => 'winner_issue','id' => $val['id'], 'cancel' => 1]).'" class="btn_region winner_issue" ><i class="fa fa-send-o"></i>'.L('cancle_send').'</a>';
                } else {
                    $list[$key]['issue_status'] = L('no_send');
                    $list[$key]['handler'] = '<a href="javascript:;"  data-href="'.url('market_action', ['type' => $this->marketing_type, 'handler' => 'winner_issue','id' => $val['id']]).'" class="btn_region winner_issue" ><i class="fa fa-send-o"></i>'.L('send').'</a>';
                }
            }
        }
        $this->assign('list', $list);
        $this->plugin_display('market_prizes', $this->cfg);
    }

    
    public function marketQrcode()
    {
        $market_id = I('get.id', 0, 'intval');

        if (!empty($market_id)) {
            $url = __HOST__ . url('wechat/index/market_show', ['type' => 'wall', 'function' => 'wall_user_wechat', 'wall_id' => $market_id, 'ru_id' => $this->ru_id]);

            $wall = dao('wechat_marketing')->field('qrcode')->where(['id'=> $market_id, 'marketing_type' => $this->marketing_type, 'wechat_id' => $this->wechat_id])->find();

            
            
            $errorCorrectionLevel = 'M';
            
            $matrixPointSize = 8;
            
            $path = dirname(ROOT_PATH) .'/data/attached/wall/';
            
            $water_logo = ROOT_PATH . 'public/img/shop_app_icon.png';
            $water_logo_out = $path . 'water_logo' .$market_id. '.png';

            
            $filename = $path . $errorCorrectionLevel . $matrixPointSize . $market_id. '.png';

            if (!is_dir($path)) {
                @mkdir($path);
            }
            QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);

            
            $img = new Image();
            
            $img->open($water_logo)->thumb(80, 80)->save($water_logo_out);
            
            $img->open($filename)->water($water_logo_out, 5, 100)->save($filename);

            $qrcode_url = __HOST__ . '/data/attached/wall/' .basename($filename).'?t='.time();
            $this->cfg['qrcode_url'] = $qrcode_url;
        }

        $this->plugin_display('market_qrcode', $this->cfg);
    }

    
    private function get_user_msg_count($wall_id)
    {
        $sql = "SELECT count(DISTINCT u.id) as user_count, count(m.id) as msg_count FROM {pre}wechat_wall_user u LEFT JOIN {pre}wechat_wall_msg m ON u.id = m.user_id WHERE m.wall_id = '" .$wall_id. "' AND u.wechat_id = '" .$this->wechat_id. "' ";
        $res = $this->model->query($sql);
        return $res[0];
    }

    
    public function executeAction()
    {
        if (IS_AJAX) {
            $json_result = ['error' => 0, 'msg' => '', 'url' => ''];

            $handler = I('get.handler', '', 'trim');
            $function = I('get.function', '', 'trim');
            $market_id = I('get.market_id', 0, 'intval');

            $msg_id = I('get.msg_id', 0, 'intval');
            $user_id = I('get.user_id', 0, 'intval');
            
            if ($handler && $handler == 'check') {
                $checktime = gmtime();
                $data = ['status' => 1, 'checktime' => $checktime];
                
                if (!empty($market_id) && !empty($user_id) && empty($msg_id)) {
                    dao('wechat_wall_user')->data($data)->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'id' => $user_id, 'status' => 0])->save();
                    $json_result['msg'] = '用户审核成功';
                    $json_result['url'] = url('data_list', ['type' => $this->marketing_type, 'function' => $function, 'id' => $market_id]);
                    exit(json_encode($json_result));
                }

                
                if (!empty($market_id) && !empty($user_id) && !empty($msg_id)) {
                    dao('wechat_wall_msg')->data($data)->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'user_id' => $user_id, 'id' => $msg_id, 'status' => 0])->save();
                    
                    dao('wechat_wall_user')->data($data)->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'id' => $user_id, 'status' => 0])->save();

                    $json_result['msg'] = '留言审核成功';
                    if (isset($_GET['status'])) {
                        $status = I('get.status');
                        $json_result['url'] = url('data_list', ['type' => $this->marketing_type, 'function' => $function, 'id' => $market_id, 'status' => $status]);
                    }
                    exit(json_encode($json_result));
                }
            }

            
            if ($handler && $handler == 'move') {
                $checktime = gmtime();
                $data = ['status' => 0, 'checktime' => $checktime];
                
                if (!empty($market_id) && !empty($user_id) && !empty($msg_id)) {
                    dao('wechat_wall_msg')->data($data)->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'user_id' => $user_id, 'id' => $msg_id, 'status' => 1])->save();
                    
                    dao('wechat_wall_user')->data($data)->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'id' => $user_id, 'status' => 1])->save();

                    $json_result['msg'] = '移除审核成功';
                    if (isset($_GET['status'])) {
                        $status = I('get.status');
                        $json_result['url'] = url('data_list', ['type' => $this->marketing_type, 'function' => $function, 'id' => $market_id, 'status' => $status]);
                        exit(json_encode($json_result));
                    }
                    exit(json_encode($json_result));
                }
            }

            
            if ($handler && $handler == 'data_delete') {

                
                if (!empty($market_id) && !empty($msg_id)) {
                    dao('wechat_wall_msg')->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'id' => $msg_id])->delete();
                    $json_result['msg'] = '删除消息成功';
                    exit(json_encode($json_result));
                }
                
                if (!empty($market_id) && !empty($user_id) && empty($msg_id)) {
                    dao('wechat_wall_user')->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'id' => $user_id])->delete();
                    dao('wechat_wall_msg')->where(['wall_id' => $market_id, 'wechat_id' => $this->wechat_id, 'user_id' => $user_id])->delete();
                    $json_result['msg'] = '删除会员以及消息成功';
                    exit(json_encode($json_result));
                }
            }

            
            if ($handler && $handler == 'winner_issue') {
                $id = I('get.id', 0, 'intval');
                $cancel = I('get.cancel');

                if (!empty($id)) {
                    if (!empty($cancel)) {
                        $data['issue_status'] = 0;
                        dao('wechat_prize')->data($data)->where(['id' => $id, 'wechat_id' => $this->wechat_id])->save();
                        $json_result['msg'] = '已取消';
                        exit(json_encode($json_result));
                    } else {
                        $data['issue_status'] = 1;
                        dao('wechat_prize')->data($data)->where(['id' => $id, 'wechat_id' => $this->wechat_id])->save();
                        $json_result['msg'] = '发放标记成功';
                        exit(json_encode($json_result));
                    }
                }
            }

            
            if ($handler && $handler == 'winner_del') {
                $id = I('get.id', 0, 'intval');
                if (!empty($id)) {
                    dao('wechat_prize')->where(['id' => $id, 'wechat_id' => $this->wechat_id])->delete();
                    $json_result['msg'] = '删除成功';
                    exit(json_encode($json_result));
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
