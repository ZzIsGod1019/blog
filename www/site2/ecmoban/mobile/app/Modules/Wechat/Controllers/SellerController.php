<?php

namespace App\Modules\Wechat\Controllers;

use App\Extensions\Form;
use App\Extensions\Http;
use App\Extensions\Wechat;
use App\Modules\Base\Controllers\BackendSellerController;

class SellerController extends BackendSellerController
{
    
    protected $weObj = '';
    
    protected $wechat_id = 0;
    
    protected $plugin_name = '';
    protected $wechat_type = 0;
    
    protected $ru_id = 0;

    protected $market_type = '';  

    
    protected $page_num = 0;

    public function __construct()
    {
        parent::__construct();
        L(require(MODULE_PATH . 'Language/' . C('shop.lang') . '/wechat.php'));
        $this->assign('lang', array_change_key_case(L()));

        
        $seller = get_admin_ru_id_seller();
        if (!empty($seller) && $seller['ru_id'] > 0) {
            $this->ru_id = $seller['ru_id'];
        }

        
        $menu = set_seller_menu();
        $this->assign('seller_menu', $menu);
        
        $menu_select = get_select_menu();
        $this->assign('menu_select', $menu_select);

        
        $main_wechatinfo = dao('wechat')->where(['default_wx' => 1])->find();
        if (empty($main_wechatinfo)) {
            $this->message('请联系管理员配置平台微信通', null, 2, true);
        }

        $condition['ru_id'] = $this->ru_id;
        $condition['default_wx'] = 0;
        $wechatinfo = $this->model->table('wechat')->where($condition)->find();
        if (empty($wechatinfo)) {
            $data = [
                'time' => gmtime(),
                'type' => 2,
                'status' => 0,
                'default_wx' => 0,
                'ru_id' => $this->ru_id
            ];
            $this->model->table('wechat')->data($data)->add();
            $this->redirect('modify');
        }
        
        $this->plugin_name = I('get.ks', '', 'trim');
        $this->wechat_type = $wechatinfo['type'];
        $this->wechat_id = $wechatinfo['id'];

        
        $this->market_type = I('get.type', '', 'trim');

        
        $this->get_config();

        
        $this->page_num = 10;
        $this->assign('page_num', $this->page_num);
        
        $this->assign('ru_id', $this->ru_id);
        $this->assign('seller_name', $_SESSION['seller_name']);
    }

    
    public function actionIndex()
    {
        $this->redirect('modify');
    }

    
    

    
    public function actionAppend()
    {
        $this->redirect('index');
        
    }

    
    public function actionModify()
    {
        
        $this->seller_admin_priv('wechat_admin');

        $condition['id'] = $this->wechat_id;
        
        if (IS_POST) {
            $data = I('post.data', '', 'trim');
            
            $form = new Form();
            if (!$form->isEmpty($data['name'], 1)) {
                $this->message(L('must_name'), null, 2, true);
            }
            if (!$form->isEmpty($data['orgid'], 1)) {
                $this->message(L('must_id'), null, 2, true);
            }
            if (!$form->isEmpty($data['token'], 1)) {
                $this->message(L('must_token'), null, 2, true);
            }
            
            
            if (strpos($data['appsecret'], '*') == true) {
                unset($data['appsecret']);
            }
            $data['secret_key'] = md5($data['orgid'] . $data['appid']);
            $this->model->table('wechat')->data($data)->where($condition)->save();
            $this->message(L('wechat_editor') . L('success'), url('modify'), 1, true);
        }
        
        $data = $this->model->table('wechat')->where($condition)->find();
        $data['secret_key'] = isset($data['orgid']) && isset($data['appid']) ? $data['secret_key'] : '';
        $data['url'] = url('wechat/index/index', ['key' => $data['secret_key']], false, true);
        
        $data['appsecret'] = string_to_star($data['appsecret']);

        $this->assign('data', $data);
        
        $postion = ['ur_here' => L('edit_wechat')];
        $this->assign('postion', $postion);
        $this->display();
    }


    
    

    
    public function actionMenuList()
    {
        
        $this->seller_admin_priv('menu');

        $list = $this->model->table('wechat_menu')->where(['wechat_id' => $this->wechat_id])->order('sort asc')->select();
        $result = [];
        if (is_array($list)) {
            foreach ($list as $vo) {
                if ($vo['pid'] == 0) {
                    $vo['val'] = ($vo['type'] == 'click') ? $vo['key'] : $vo['url'];
                    $sub_button = [];
                    foreach ($list as $val) {
                        $val['val'] = ($val['type'] == 'click') ? $val['key'] : $val['url'];
                        if ($val['pid'] == $vo['id']) {
                            $sub_button[] = $val;
                        }
                    }
                    $vo['sub_button'] = $sub_button;
                    $result[] = $vo;
                }
            }
        }
        $this->assign('list', $result);
        
        $postion = ['ur_here' => L('menu')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionMenuEdit()
    {
        
        $this->seller_admin_priv('menu');

        if (IS_POST) {
            $id = I('post.id');
            $data = I('post.data');
            $data['wechat_id'] = $this->wechat_id;
            if ('click' == $data['type']) {
                if (empty($data['key'])) {
                    exit(json_encode(['status' => 0, 'msg' => L('menu_keyword') . L('empty')]));
                }
                $data['url'] = '';
            } else {
                if (empty($data['url'])) {
                    exit(json_encode(['status' => 0, 'msg' => L('menu_url') . L('empty')]));
                }
                if (substr($data['url'], 0, 4) !== 'http') {
                    exit(json_encode(['status' => 0, 'msg' => L('menu_url') . L('link_err')]));
                }
                if (strlen($data['url']) > 120) {
                    exit(json_encode(['status' => 0, 'msg' => L('menu_url_length')]));
                }
                $data['url'] = add_url_suffix($data['url'], ['ru_id' => $this->ru_id]);
                $data['key'] = '';
            }
            
            if (!empty($id)) {
                $this->model->table('wechat_menu')->data($data)->where(['id' => $id])->save();
            } else {
                
                $this->model->table('wechat_menu')->data($data)->add();
            }
            exit(json_encode(['status' => 1, 'msg' => L('menu_edit') . L('success')]));
            
        }
        $id = I('get.id');
        $info = [];
        
        $top_menu = $this->model->table('wechat_menu')->where(['pid' => 0, 'wechat_id' => $this->wechat_id])->select();
        if (!empty($id)) {
            $info = $this->model->table('wechat_menu')->where(['id' => $id])->find();
            
            $top_menu = $this->model->query("SELECT * FROM {pre}wechat_menu WHERE id <> $id AND pid = 0 AND wechat_id = $this->wechat_id");
        }
        if (empty($info)) {
            
            $info['status'] = 1;
            $info['sort'] = 0;
            $info['type'] = 'click';
        }

        $this->assign('top_menu', $top_menu);
        $this->assign('info', $info);

        
        $postion = ['ur_here' => L('menu')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionMenuDel()
    {
        
        $this->seller_admin_priv('menu');

        $id = I('get.id');
        if (empty($id)) {
            $this->message(L('menu_select_del'), null, 2, true);
        }
        $minfo = $this->model->table('wechat_menu')->field('id, pid')->where(['id' => $id])->find();
        
        if ($minfo['pid'] == 0) {
            $this->model->table('wechat_menu')->where(['pid' => $minfo['id']])->delete();
        }
        $this->model->table('wechat_menu')->where(['id' => $minfo['id']])->delete();
        $this->message(L('drop') . L('success'), url('menu_list'), 1, true);
    }

    
    public function actionSysMenu()
    {
        
        $this->seller_admin_priv('menu');

        $list = $this->model->table('wechat_menu')->where(['status' => 1, 'wechat_id' => $this->wechat_id])->order('sort asc')->select();
        if (empty($list)) {
            $this->message(L('menu_empty'), null, 2, true);
        }
        $data = [];
        if (is_array($list)) {
            foreach ($list as $val) {
                if ($val['pid'] == 0) {
                    $sub_button = [];
                    foreach ($list as $v) {
                        if ($v['pid'] == $val['id']) {
                            $sub_button[] = $v;
                        }
                    }
                    $val['sub_button'] = $sub_button;
                    $data[] = $val;
                }
            }
        }
        $menu_list = [];
        foreach ($data as $key => $val) {
            if (empty($val['sub_button'])) {
                $menu_list['button'][$key]['type'] = $val['type'];
                $menu_list['button'][$key]['name'] = $val['name'];
                if ('click' == $val['type']) {
                    $menu_list['button'][$key]['key'] = $val['key'];
                } else {
                    $menu_list['button'][$key]['url'] = html_out($val['url']);
                }
            } else {
                $menu_list['button'][$key]['name'] = $val['name'];
                foreach ($val['sub_button'] as $k => $v) {
                    $menu_list['button'][$key]['sub_button'][$k]['type'] = $v['type'];
                    $menu_list['button'][$key]['sub_button'][$k]['name'] = $v['name'];
                    if ('click' == $v['type']) {
                        $menu_list['button'][$key]['sub_button'][$k]['key'] = $v['key'];
                    } else {
                        $menu_list['button'][$key]['sub_button'][$k]['url'] = html_out($v['url']);
                    }
                }
            }
        }
        

        $rs = $this->weObj->createMenu($menu_list);
        if (empty($rs)) {
            $this->message(L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg, null, 2, true);
        }
        $this->message(L('menu_create') . L('success'), url('menu_list'), 1, true);
    }

    
    public function actionSubscribeList()
    {
        
        $this->seller_admin_priv('fans');

        
        $offset = $this->pageLimit(url('subscribe_list'), $this->page_num);

        $sql = 'SELECT count(*) as number FROM {pre}wechat_user u
            LEFT JOIN {pre}wechat_user_tag t ON u.openid = t.openid
            WHERE u.subscribe = 1 AND u.wechat_id = ' . $this->wechat_id . ' order by u.subscribe_time desc';
        $total = $this->model->query($sql);

        $sql = 'SELECT u.* FROM {pre}wechat_user u
            LEFT JOIN {pre}wechat_user_tag t ON u.openid = t.openid
            WHERE u.subscribe = 1 AND u.wechat_id = ' . $this->wechat_id . ' order by u.subscribe_time desc limit ' . $offset;
        $list = $this->model->query($sql);

        foreach ($list as $key => $value) {
            $list[$key]['taglist'] = $this->get_user_tag($value['openid']); 
            $list[$key]['from'] = get_wechat_user_from($value['from']);
        }
        
        $where['wechat_id'] = $this->wechat_id;
        $tag_list = $this->model->table('wechat_user_taglist')->field('id, tag_id, name, count')->where($where)->order('id, sort desc')->select();
        $this->assign('tag_list', $tag_list);

        
        $where1['wechat_id'] = $this->wechat_id;
        $group_list = $this->model->table('wechat_user_group')->field('id, group_id, name, count')->where($where1)->order('id, sort desc')->select();

        $this->assign('page', $this->pageShow($total[0]['number']));
        $this->assign('list', $list);
        $this->assign('group_list', $group_list);
        
        $postion = ['ur_here' => L('sub_title')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionSubscribeSearch()
    {
        
        $this->seller_admin_priv('fans');

        $keywords = I('request.keywords', '', 'trim');
        $group_id = I('request.group_id', 0, 'intval');
        $tag_id = I('request.tag_id', 0, 'intval');
        $where = '';
        $where1 = '';
        if (!empty($keywords)) {
            $where .= ' and (u.nickname like "%' . $keywords . '%" or u.city like "%' . $keywords . '%" or u.country like "%' . $keywords . '%" or u.province like "%' . $keywords . '%" )'; 
        }
        if (isset($group_id) && $group_id > 0) {
            $where .= ' and u.groupid = ' . $group_id;
        }
        if (isset($tag_id) && $tag_id > 0) {
            $where .= ' and t.tag_id = ' . $tag_id;
        }

        
        $filter['group_id'] = $group_id;
        $filter['tag_id'] = $tag_id;
        $filter['keywords'] = $keywords;
        $offset = $this->pageLimit(url('subscribe_search', $filter), $this->page_num);

        $sql = 'SELECT count(*) as number FROM {pre}wechat_user u
            LEFT JOIN {pre}wechat_user_tag t ON u.openid = t.openid
            WHERE u.subscribe = 1 AND u.wechat_id = ' . $this->wechat_id . $where . ' order by u.subscribe_time desc';
        $total = $this->model->query($sql);

        $sql1 = 'SELECT u.* FROM {pre}wechat_user u
            LEFT JOIN {pre}wechat_user_tag t ON u.openid = t.openid
            WHERE u.subscribe = 1 AND u.wechat_id = ' . $this->wechat_id . $where . ' order by u.subscribe_time desc limit ' . $offset;
        $list = $this->model->query($sql1);

        foreach ($list as $key => $value) {
            $list[$key]['taglist'] = $this->get_user_tag($value['openid']); 
            $list[$key]['from'] = get_wechat_user_from($value['from']);
        }

        
        $where2['wechat_id'] = $this->wechat_id;
        $group_list = $this->model->table('wechat_user_group')->field('id, group_id, name, count')->where($where2)->order('id, sort desc')->select();

        
        $where3['wechat_id'] = $this->wechat_id;
        $tag_list = $this->model->table('wechat_user_taglist')->field('id, tag_id, name, count')->where($where3)->order('id, sort desc')->select();
        $this->assign('tag_list', $tag_list);

        $this->assign('page', $this->pageShow($total[0]['number']));
        $this->assign('list', $list);
        $this->assign('group_id', $group_id); 
        $this->assign('group_list', $group_list);

        $this->assign('tag_id', $tag_id);
        $this->assign('tag_list', $tag_list);

        
        $postion = ['ur_here' => L('sub_title')];
        $this->assign('postion', $postion);

        $this->display('subscribelist');
    }

    
    private function get_user_tag($openid = '')
    {
        $sql = "SELECT tl.tag_id, tl.name FROM {pre}wechat_user_taglist tl LEFT JOIN {pre}wechat_user_tag t ON tl.tag_id = t.tag_id LEFT JOIN {pre}wechat_user u ON u.openid = t.openid where u.openid = '" . $openid . "' and u.subscribe = 1 and tl.wechat_id = '" . $this->wechat_id . "' ";
        $tags = $this->model->query($sql);
        return $tags;
    }

    
    public function actionSubscribeMove()
    {
        if (IS_POST) {
            if (empty($this->wechat_id)) {
                $this->message(L('wechat_empty'), null, 2, true);
            }
            $group_id = I('post.group_id', 0, 'intval');
            $openid = I('post.id');
            if (is_array($openid)) {
                foreach ($openid as $v) {
                    
                    $this->weObj->updateGroupMembers($group_id, $v);
                    
                    $this->model->table('wechat_user')->data(['groupid' => $group_id])->where(['openid' => $v, 'wechat_id' => $this->wechat_id])->save();
                }
                $this->message(L('sub_move_sucess'), url('subscribe_list'), 1, true);
            } else {
                $this->message(L('select_please'), null, 2, true);
            }
        }
    }

    
    public function actionSysfans()
    {
        
        $wechat_user = $this->weObj->getUserList();
        foreach ($wechat_user['data']['openid'] as $v) {
            $info = $this->weObj->getUserInfo($v);
            $info['wechat_id'] = $this->wechat_id;
            $this->model->table('wechat_user')->data($info)->add();
        }
        $this->redirect('subscribe_list', ['wechat_id' => $this->wechat_id]);
    }

    
    public function actionSubscribeUpdate()
    {
        
        $this->seller_admin_priv('fans');

        $total = dao('wechat_user')->field('openid')->where(['wechat_id' => $this->wechat_id])->count();

        
        $wechat_key = md5('wechat_' . $total);
        $wechat_user = S($wechat_key);
        if ($wechat_user === false) {
            $wechat_user = $this->weObj->getUserList();
            S($wechat_key, $wechat_user);
        }

        if ($wechat_user['total'] <= 10000) {
            $wechat_user_list = $wechat_user['data']['openid'];
        } else {
            $num = ceil($wechat_user['total'] / 10000);
            $wechat_user_list = $wechat_user['data']['openid'];
            for ($i = 0; $i <= $num; $i++) {
                $wechat_user1 = $this->weObj->getUserList($wechat_user['next_openid']);
                $wechat_user_list = array_merge($wechat_user_list, $wechat_user1['data']['openid']);
            }
        }

        if (IS_AJAX) {
            
            $page = input('page', 0, 'intval');

            $offset = $this->pageLimit(url('subscribe_update'), $this->page_num);

            
            $where['wechat_id'] = $this->wechat_id;
            $total = dao('wechat_user')->field('openid')->where($where)->count();
            $local_user = dao('wechat_user')->field('openid')->where($where)->limit($offset)->select();
            if (empty($local_user)) {
                $local_user = [];
            }
            $user_list = [];
            foreach ($local_user as $v) {
                $user_list[] = $v['openid'];
            }

            
            foreach ($local_user as $val) {
                
                if (in_array($val['openid'], $wechat_user_list)) {
                    $info = $this->weObj->getUserInfo($val['openid']);
                    $where1['openid'] = $val['openid'];
                    $where1['wechat_id'] = $this->wechat_id;
                    $this->model->table('wechat_user')->data($info)->where($where1)->save();
                } else {
                    $data['subscribe'] = 0;
                    $where2['openid'] = $val['openid'];
                    $where2['wechat_id'] = $this->wechat_id;
                    $this->model->table('wechat_user')->data($data)->where($where2)->save();
                }
            }
            
            
            
            
            
            
            
            
            
            

            $pager = $this->pageShow($total);

            $next_page = $page + 1;
            $totalpage = $pager['page_count'];
            $persent = intval($page / $totalpage * 100); 

            exit(json_encode(['status' => 0, 'msg' => 'success', 'persent' => $persent, 'next_page' => $next_page, 'totalpage' => $totalpage]));
        }

        $this->assign('request_url', url('subscribe_update')); 

        $this->assign('persent', 0);
        $this->display('update');
        
    }

    
    public function actionSendCustomMessage()
    {
        
        $this->seller_admin_priv('fans');

        if (IS_POST) {
            $data = I('post.data');
            $openid = I('post.openid');

            $media_id = I('media_id', 0, 'intval');
            $msgtype = I('msgtype', '', 'trim');

            $form = new Form();
            if (!$form->isEmpty($openid, 1)) {
                exit(json_encode(['status' => 0, 'msg' => L('select_openid')]));
            }

            if ($msgtype == 'text' && !$form->isEmpty($data['msg'], 1)) {
                exit(json_encode(['status' => 0, 'msg' => L('message_content') . L('empty')]));
            }
            if ($msgtype != 'text' && empty($media_id)) {
                exit(json_encode(['status' => 0, 'msg' => L('message_content') . L('empty')]));
            }
            $data['send_time'] = gmtime();
            $data['wechat_id'] = $this->wechat_id;
            $data['is_wechat_admin'] = 1; 

            
            if (!empty($media_id)) {
                $mediaInfo = dao('wechat_media')
                    ->field('id, title, command, digest, content, file, type, file_name, article_id, link')
                    ->where(['id' => $media_id])
                    ->find();
                
                if ($msgtype == 'image' || $msgtype == 'voice') {
                    
                    $filename = !empty($mediaInfo['command']) ? dirname(ROOT_PATH) . '/mobile/' . $mediaInfo['file'] : dirname(ROOT_PATH) . '/' . $mediaInfo['file'];
                    $rs = $this->weObj->uploadMedia(['media' => realpath_wechat($filename)], $msgtype);
                    if (empty($rs)) {
                        logResult($this->weObj->errMsg);
                    }
                    $msg = [
                        'touser' => $openid,
                        'msgtype' => $msgtype,
                        $msgtype => [
                            'media_id' => $rs['media_id']
                        ]
                    ];
                    $data['msg'] = $msgtype == 'voice' ? '语音' : '图片';
                } elseif ($msgtype == 'video') {
                    
                    $filename = !empty($mediaInfo['command']) ? dirname(ROOT_PATH) . '/mobile/' . $mediaInfo['file'] : dirname(ROOT_PATH) . '/' . $mediaInfo['file'];
                    $rs = $this->weObj->uploadMedia(['media' => realpath_wechat($filename)], $msgtype);
                    if (empty($rs)) {
                        logResult($this->weObj->errMsg);
                    }
                    
                    $msg = [
                        'touser' => $openid,
                        'msgtype' => $msgtype,
                        $msgtype => [
                            'media_id' => $rs['media_id'],
                            'thumb_media_id' => $rs['media_id'],
                            'title' => $mediaInfo['title'],
                            'description' => strip_tags($mediaInfo['content'])
                        ]
                    ];
                    $data['msg'] = '视频';
                } elseif ($msgtype == 'news') {
                    
                    $articles = [];
                    if (!empty($mediaInfo['article_id'])) {
                        $artids = explode(',', $mediaInfo['article_id']);
                        foreach ($artids as $key => $val) {
                            $artinfo = dao('wechat_media')
                                ->field('id, title, file, digest, content, link')
                                ->where(['id' => $val])
                                ->find();
                            $artinfo['content'] = sub_str(strip_tags(html_out($artinfo['content'])), 100);
                            $articles[$key]['title'] = $artinfo['title'];
                            $articles[$key]['description'] = empty($artinfo['digest']) ? $artinfo['content'] : $artinfo['digest'];
                            $articles[$key]['picurl'] = get_wechat_image_path($artinfo['file']);
                            $articles[$key]['url'] = empty($artinfo['link']) ? __HOST__ . url('article/index/wechat', ['id' => $artinfo['id']]) : strip_tags(html_out($artinfo['link']));
                        }
                    } else {
                        $articles[0]['title'] = $mediaInfo['title'];
                        $articles[0]['description'] = empty($mediaInfo['digest']) ? sub_str(strip_tags(html_out($mediaInfo['content'])), 100) : $mediaInfo['digest'];
                        $articles[0]['picurl'] = get_wechat_image_path($mediaInfo['file']);
                        $articles[0]['url'] = empty($mediaInfo['link']) ? __HOST__ . url('article/index/wechat', ['id' => $mediaInfo['id']]) : strip_tags(html_out($mediaInfo['link']));
                    }

                    
                    $msg = [
                        'touser' => $openid,
                        'msgtype' => 'news',
                        'news' => [
                            'articles' => $articles
                        ]
                    ];
                    $data['msg'] = '图文消息';
                } elseif ($msgtype == 'miniprogrampage') {
                    
                    $msg = [
                        'touser' => $openid,
                        'msgtype' => 'miniprogrampage',
                        'miniprogrampage' => [
                            'title' => $mediaInfo['title'],
                            'appid' => 'appid',
                            'pagepath' => $mediaInfo['pagepath'],
                            'thumb_media_id' => 'thumb_media_id'
                        ]
                    ];
                    $data['msg'] = '小程序';
                }
            } else {
                
                $msg = [
                    'touser' => $openid,
                    'msgtype' => 'text',
                    'text' => [
                        'content' => $data['msg']
                    ]
                ];
            }

            $rs = $this->weObj->sendCustomMessage($msg);
            if (empty($rs)) {
                $errmsg = $this->weObj->errCode . '发送失败！仅48小时内给公众号发送过信息的粉丝才能接收到信息';
                
                exit(json_encode(['status' => 0, 'msg' => $errmsg]));
            }
            
            dao('wechat_custom_message')->data($data)->add();
            exit(json_encode(['status' => 1]));
        }
        $uid = I('get.uid');
        $openid = I('get.openid');
        if ($openid) {
            $where['openid'] = $openid;
        } else {
            $where['uid'] = $uid;
        }
        $where['wechat_id'] = $this->wechat_id;
        $info = dao('wechat_user')->field('uid, headimgurl, nickname, openid')->where($where)->find();
        
        $n = strlen($info['headimgurl']) - strrpos($info['headimgurl'], '/') - 1;
        $info['headimgurl'] = substr($info['headimgurl'], 0, - $n) . '64';

        
        $list = dao('wechat_custom_message')
            ->field('msg, send_time, is_wechat_admin')
            ->where(['uid' => $uid, 'wechat_id' => $this->wechat_id])
            ->order('send_time DESC, id DESC')
            ->limit(6)
            ->select();
        $list = array_reverse($list); 
        foreach ($list as $key => $value) {
            $list[$key]['send_time'] = local_date('Y-m-d H:i:s', $value['send_time']);
            $list[$key]['headimgurl'] = $info['headimgurl'];
            $list[$key]['wechat_headimgurl'] = __TPL__ . '/img/shop_app_icon.png';
        }
        $this->assign('list', $list);
        $this->assign('info', $info);

        
        $postion = ['ur_here' => L('send_custom_message')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionCustomMessageList()
    {
        
        $this->seller_admin_priv('fans');

        $uid = I('get.uid', 0, 'intval');
        if (empty($uid)) {
            $this->message(L('select_openid'), null, 2, true);
        }
        $nickname = $this->model->table('wechat_user')->where(['uid' => $uid, 'wechat_id' => $this->wechat_id])->getField('nickname');
        
        $filter['uid'] = $uid;
        $offset = $this->pageLimit(url('custom_message_list', $filter), $this->page_num);
        $total = $this->model->table('wechat_custom_message')->where(['uid' => $uid, 'wechat_id' => $this->wechat_id])->order('send_time desc')->count();
        $list = $this->model->table('wechat_custom_message')
            ->field('msg, send_time, wechat_id')
            ->where(['uid' => $uid, 'wechat_id' => $this->wechat_id])
            ->order('send_time desc, id desc')
            ->limit($offset)
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['send_time'] = local_date('Y-m-d H:i:s', $value['send_time']);
        }

        $this->assign('page', $this->pageShow($total));
        $this->assign('list', $list);
        $this->assign('nickname', $nickname);

        
        $postion = ['ur_here' => L('custom_message_list')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionTagsList()
    {
        $where['wechat_id'] = $this->wechat_id;
        $tag_list = $this->model->table('wechat_user_taglist')
            ->where($where)
            ->order('id, sort desc')
            ->select();
        $this->assign('list', $tag_list);
        $this->display();
    }

    
    public function actionSysTags()
    {
        
        $this->seller_admin_priv('fans');

        
        $list = $this->weObj->getTags();
        if (empty($list)) {
            $this->message(L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg, null, 2, true);
        }
        
        $where['wechat_id'] = $this->wechat_id;
        $this->model->table('wechat_user_taglist')->where($where)->delete();
        foreach ($list['tags'] as $key => $val) {
            $data['wechat_id'] = $this->wechat_id;
            $data['tag_id'] = $val['id'];
            $data['name'] = $val['name'];
            $data['count'] = $val['count'];
            $this->model->table('wechat_user_taglist')->data($data)->add();
        }
        
        $this->actionUserTagUpdate();

        
    }

    
    public function actionUserTagUpdate()
    {
        
        $this->admin_priv('fans');

        if (IS_AJAX) {
            $page = input('page', 0, 'intval');

            $offset = $this->pageLimit(url('user_tag_update'), $this->page_num);

            
            $where['wechat_id'] = $this->wechat_id;
            $total = dao('wechat_user')->field('openid')->where($where)->count();
            $local_user = dao('wechat_user')->field('openid')->where($where)->limit($offset)->select();
            if (!empty($local_user)) {
                foreach ($local_user as $v) {
                    
                    $rs = $this->weObj->getUserTaglist($v['openid']);
                    $where['openid'] = $v['openid'];
                    $this->model->table('wechat_user_tag')->where($where)->delete();
                    if (!empty($rs)) {
                        foreach ($rs as $key => $val) {
                            $data['wechat_id'] = $this->wechat_id;
                            $data['tag_id'] = $val;
                            $data['openid'] = $v['openid'];
                            $this->model->table('wechat_user_tag')->data($data)->add();
                        }
                    }
                }
            }

            $pager = $this->pageShow($total);

            $next_page = $page + 1;
            $totalpage = $pager['page_count'];
            $persent = intval($page / $totalpage * 100); 

            exit(json_encode(['status' => 0, 'msg' => 'success', 'persent' => $persent, 'next_page' => $next_page, 'totalpage' => $totalpage]));
        }

        $this->assign('request_url', url('user_tag_update')); 

        $this->assign('persent', 0);
        $this->display('update');
    }

    
    public function actionTagsEdit()
    {
        
        $this->seller_admin_priv('fans');

        if (IS_POST) {
            $name = I('post.name');
            $id = I('post.id', 0, 'intval');
            $tag_id = I('post.tag_id', 0, 'intval');
            if (empty($name)) {
                exit(json_encode(['status' => 0, 'msg' => L('group_name') . L('empty')]));
            }
            $data['name'] = $name;
            if (!empty($id)) {
                
                $rs = $this->weObj->updateTags($tag_id, $name);
                if (empty($rs)) {
                    exit(json_encode(['status' => 0, 'msg' => L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg]));
                }
                
                $where['id'] = $id;
                $where['wechat_id'] = $this->wechat_id;
                $data['tag_id'] = !empty($rs['tag']['id']) ? $rs['tag']['id'] : $tag_id;
                $this->model->table('wechat_user_taglist')
                    ->data($data)
                    ->where($where)
                    ->save();
            } else {
                
                $rs = $this->weObj->createTags($name);
                if (empty($rs)) {
                    exit(json_encode(['status' => 0, 'msg' => L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg]));
                }

                
                $data['tag_id'] = !empty($rs['tag']['id']) ? $rs['tag']['id'] : $tag_id;
                $data['name'] = $rs['tag']['name'];
                $data['wechat_id'] = $this->wechat_id;
                $this->model->table('wechat_user_taglist')
                    ->data($data)
                    ->add();
            }
            exit(json_encode(['status' => 1]));
        }
        $id = I('get.id', 0, 'intval');
        $taglist = [];
        if (!empty($id)) {
            $where['id'] = $id;
            $where['wechat_id'] = $this->wechat_id;
            $taglist = $this->model->table('wechat_user_taglist')
                ->field('id, tag_id, name')
                ->where($where)
                ->find();
        }

        $this->assign('taglist', $taglist);
        $this->display();
    }

    
    public function actionTagsDelete()
    {
        
        $this->seller_admin_priv('fans');

        if (IS_AJAX) {
            $id = I('id', 0, 'intval');
            $tag_id = dao('wechat_user_taglist')->where(['id' => $id])->getField('tag_id');
            if (!empty($tag_id)) {
                
                $rs = $this->weObj->deleteTags($tag_id);
                if (empty($rs)) {
                    exit(json_encode(['status' => 0, 'msg' => L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg]));
                }
                
                $where['wechat_id'] = $this->wechat_id;
                $where['tag_id'] = $tag_id;
                dao('wechat_user_taglist')->where($where)->delete();
                
                dao('wechat_user_tag')->where($where)->delete();
                exit(json_encode(['error' => 0, 'msg' => '删除标签成功', 'url' => url('subscribe_list')]));
            } else {
                exit(json_encode(['error' => 1, 'msg' => '删除标签失败']));
            }
        }
    }

    
    public function actionBatchTagging()
    {
        
        $this->seller_admin_priv('fans');

        if (IS_POST) {
            $tag_id = I('post.tag_id', 0, 'intval');
            $openlist = I('post.id');
            if (is_array($openlist)) {
                
                $rs = $this->weObj->batchtaggingTagsMembers($tag_id, $openlist);
                if (empty($rs)) {
                    $this->message(L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg, url('subscribe_list'), 2, true);
                    
                }
                
                $is_true = 0;
                foreach ($openlist as $v) {
                    $sql = "SELECT u.uid, count(t.openid) as openid_num FROM {pre}wechat_user_tag t LEFT JOIN {pre}wechat_user u ON t.openid = u.openid WHERE u.openid = '" . $v . "' AND u.subscribe = 1 AND u.wechat_id = ' " . $this->wechat_id . "' ";
                    $res = $this->model->query($sql);
                    if (!empty($res)) {
                        
                        if ($res[0]['openid_num'] < 20) {
                            
                            $data = [
                                'wechat_id' => $this->wechat_id,
                                'tag_id' => $tag_id,
                                'openid' => $v
                            ];
                            $tag_num = $this->model->table('wechat_user_tag')->where($data)->count();
                            if ($tag_num == 0) {
                                $this->model->table('wechat_user_tag')->data($data)->add();
                            } else {
                                $is_true = 1;
                            }
                        } else {
                            $is_true = 3;
                        }
                    }
                }
                if ($is_true == 0) {
                    $this->message(L('tag_move_sucess'), url('subscribe_list'), 1, true);
                } elseif ($is_true == 1) {
                    $this->message(L('tag_move_fail') . ", " . L('tag_move_exit'), url('subscribe_list'), 2, true);
                } elseif ($is_true == 3) {
                    $this->message(L('tag_move_fail') . ", " . L('tag_move_three'), url('subscribe_list'), 2, true);
                }
            } else {
                $this->message(L('select_please'), null, 2, true);
            }
        }
    }

    
    public function actionBatchUnTagging()
    {
        
        $this->seller_admin_priv('fans');

        if (IS_POST) {
            $tag_id = I('post.tagid', 0, 'intval');
            $openid = I('post.openid');

            $openlist = ['0' => $openid];
            if (is_array($openlist)) {
                
                $rs = $this->weObj->batchuntaggingTagsMembers($tag_id, $openlist);
                if (empty($rs)) {
                    exit(json_encode(['status' => 0, 'msg' => L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg]));
                }
                
                foreach ($openlist as $v) {
                    $where = [
                        'wechat_id' => $this->wechat_id,
                        'tag_id' => $tag_id,
                        'openid' => $v
                    ];
                    dao('wechat_user_tag')->where($where)->delete();
                }
                exit(json_encode(['status' => 1, 'msg' => L('tag_move_sucess')]));
            } else {
                exit(json_encode(['status' => 0, 'msg' => L('select_please') . L('empty')]));
            }
        }
    }

    
    public function actionEditUserRemark()
    {
        
        $this->seller_admin_priv('fans');

        if (IS_POST) {
            $remark = input('remark', '', 'trim');
            $uid = input('uid', 0, 'intval');

            if (!empty($remark)) {
                $openid = dao('wechat_user')->where(['uid' => $uid, 'wechat_id' => $this->wechat_id])->getField('openid');
                $rs = $this->weObj->updateUserRemark($openid, $remark);
                if (empty($rs)) {
                    exit(json_encode(['status' => 0, 'msg' => L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg]));
                }
                dao('wechat_user')->data(['remark' => $remark])->where(['openid' => $openid, 'wechat_id' => $this->wechat_id])->save();
                exit(json_encode(['status' => 1, 'msg' => '修改备注名成功']));
            } else {
                exit(json_encode(['status' => 0, 'msg' => L('empty')]));
            }
        }
    }

    
    public function actionGroupsList()
    {
        $where['wechat_id'] = $this->wechat_id;
        $local_list = $this->model->table('wechat_user_group')
            ->where($where)
            ->order('id, sort desc')
            ->select();
        $this->assign('list', $local_list);
        $this->display();
    }

    
    public function actionSysGroups()
    {
        
        $list = $this->weObj->getGroup();
        if (empty($list)) {
            $this->message(L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg, null, 2, true);
        }
        
        $where['wechat_id'] = $this->wechat_id;
        $this->model->table('wechat_user_group')->where($where)->delete();
        foreach ($list['groups'] as $key => $val) {
            $data['wechat_id'] = $this->wechat_id;
            $data['group_id'] = $val['id'];
            $data['name'] = $val['name'];
            $data['count'] = $val['count'];
            $this->model->table('wechat_user_group')->data($data)->add();
        }
        $this->redirect('subscribe_list');
    }

    
    public function actionGroupsEdit()
    {
        if (IS_POST) {
            $name = I('post.name');
            $id = I('post.id', 0, 'intval');
            $group_id = I('post.group_id');
            if (empty($name)) {
                exit(json_encode(['status' => 0, 'msg' => L('group_name') . L('empty')]));
            }
            $data['name'] = $name;
            if (!empty($id)) {
                
                $rs = $this->weObj->updateGroup($group_id, $name);
                if (empty($rs)) {
                    exit(json_encode(['status' => 0, 'msg' => L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg]));
                }
                
                $where['id'] = $id;
                $where['wechat_id'] = $this->wechat_id;
                $this->model->table('wechat_user_group')
                    ->data($data)
                    ->where($where)
                    ->save();
            } else {
                
                $rs = $this->weObj->createGroup($name);
                if (empty($rs)) {
                    exit(json_encode(['status' => 0, 'msg' => L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg]));
                }
                
                $data['wechat_id'] = $this->wechat_id;
                $this->model->table('wechat_user_group')
                    ->data($data)
                    ->add();
            }
            exit(json_encode(['status' => 1]));
        }
        $id = I('get.id', 0, 'intval');
        $group = [];
        if (!empty($id)) {
            $where['id'] = $id;
            $where['wechat_id'] = $this->wechat_id;
            $group = $this->model->table('wechat_user_group')
                ->field('id, group_id, name')
                ->where($where)
                ->find();
        }

        $this->assign('group', $group);
        $this->display();
    }

    
    public function actionQrcodeList()
    {
        
        $this->seller_admin_priv('qrcode');

        
        $offset = $this->pageLimit(url('qrcode_list'), $this->page_num);
        $total = $this->model->query("SELECT count(*) as count FROM {pre}wechat_qrcode WHERE username is null or username = '' AND wechat_id = $this->wechat_id ");
        $list = $this->model->query("SELECT * FROM {pre}wechat_qrcode WHERE username is null or username = '' AND wechat_id = $this->wechat_id ORDER BY sort ASC, id DESC");

        $this->assign('page', $this->pageShow($total[0]['count']));
        $this->assign('list', $list);

        
        $postion = ['ur_here' => L('qrcode')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionQrcodeEdit()
    {
        
        $this->seller_admin_priv('qrcode');

        if (IS_POST) {
            $data = I('post.data');
            $data['wechat_id'] = $this->wechat_id;
            
            $form = new Form();
            if (!$form->isEmpty($data['function'], 1)) {
                exit(json_encode(['status' => 0, 'msg' => L('qrcode_function') . L('empty')]));
            }
            if (!$form->isEmpty($data['scene_id'], 1)) {
                exit(json_encode(['status' => 0, 'msg' => L('qrcode_scene_value') . L('empty')]));
            }
            if (!$form->isEmpty($data['expire_seconds'], 1)) {
                $data['expire_seconds'] = 1800; 
            }
            $rs = $this->model->table('wechat_qrcode')
                ->where(['scene_id' => $data['scene_id'], 'wechat_id' => $this->wechat_id])
                ->count();
            if ($rs > 0) {
                exit(json_encode(['status' => 0, 'msg' => L('qrcode_scene_value_limit')]));
            }
            $this->model->table('wechat_qrcode')
                ->data($data)
                ->add();
            exit(json_encode(['status' => 1, 'msg' => L('add') . L('success')]));
        }
        $id = I('get.id', 0, 'intval');
        if (!empty($id)) {
            $status = I('get.status', 0, 'intval');
            $this->model->table('wechat_qrcode')
                ->data(['status' => $status])
                ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
                ->save();
            $this->redirect('qrcode_list');
        }

        
        $keywords_list = get_keywords_list($this->wechat_id);
        $this->assign('keywords_list', $keywords_list);

        
        $postion = ['ur_here' => L('qrcode')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionShareList()
    {
        
        $this->seller_admin_priv('share');

        
        $offset = $this->pageLimit(url('share_list'), $this->page_num);
        $total = $this->model->query("SELECT count(*) as count FROM {pre}wechat_qrcode WHERE username is not null AND username != '' AND wechat_id = $this->wechat_id ");
        $list = $this->model->query("SELECT * FROM {pre}wechat_qrcode WHERE username is not null AND username != '' AND wechat_id = $this->wechat_id ORDER BY sort ASC, id DESC");

        
        if ($list) {
            foreach ($list as $key => $val) {
                $list[$key]['share_account'] = $this->model->table('affiliate_log')
                    ->where(['separate_type' => 0, 'user_id' => $val['scene_id']])
                    ->getField('sum(money)');
            }
        }
        $this->assign('page', $this->pageShow($total[0]['count']));
        $this->assign('list', $list);

        
        $postion = ['ur_here' => L('share')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionShareEdit()
    {
        
        $this->seller_admin_priv('qrcode');

        if (IS_POST) {
            $id = I('id', 0, 'intval');
            $data = I('post.data');
            $data['wechat_id'] = $this->wechat_id;
            
            $form = new Form();
            if (!$form->isEmpty($data['username'], 1)) {
                exit(json_encode(['status' => 0, 'msg' => L('share_name') . L('empty')]));
            }
            if (!$form->isEmpty($data['scene_id'], 1)) {
                exit(json_encode(['status' => 0, 'msg' => L('share_userid') . L('empty')]));
            }

            $data['type'] = empty($data['expire_seconds']) ? 1 : 0;

            if ($id) {
                $where = [
                    'id' => $id,
                    'wechat_id' => $this->wechat_id
                    ];
                $this->model->table('wechat_qrcode')->data($data)->where($where)->save();
                exit(json_encode(['status' => 1]));
            } else {
                $rs = $this->model->table('wechat_qrcode')
                    ->where(['scene_id' => $data['scene_id'], 'wechat_id' => $this->wechat_id])
                    ->count();
                if ($rs > 0) {
                    exit(json_encode(['status' => 0, 'msg' => L('qrcode_scene_limit')]));
                }

                $this->model->table('wechat_qrcode')->data($data)->add();
                exit(json_encode(['status' => 1]));
            }
        }

        $id = input('id', 0, 'intval');
        if (!empty($id)) {
            $where = [
                'id' => $id,
                'wechat_id' => $this->wechat_id
                ];
            $info = $this->model->table('wechat_qrcode')->where($where)->find();
        }
        $this->assign('info', $info);

        
        $keywords_list = get_keywords_list($this->wechat_id);
        $this->assign('keywords_list', $keywords_list);

        $this->display();
    }

    
    public function actiongetUserId()
    {
        if (IS_AJAX) {
            $username = input('username', '', 'trim');
            $user_id = dao('users')->where(['user_name' => $username])->getField('user_id');
            if (!empty($user_id)) {
                exit(json_encode(['error' => 0, 'user_id' => $user_id]));
            } else {
                exit(json_encode(['error' => 1, 'msg' => '用户名不存在']));
            }
        }
    }

    
    public function actionQrcodeDel()
    {
        
        $this->seller_admin_priv('qrcode');

        $id = I('get.id', 0, 'intval');
        if (empty($id)) {
            $this->message(L('select_please') . L('qrcode'), null, 2, true);
        }
        $this->model->table('wechat_qrcode')
            ->where(['id' => $id])
            ->delete();
        $url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : url('qrcode_list');
        $this->message(L('qrcode') . L('drop') . L('success'), $url, 1, true);
    }

    
    public function actionQrcodeGet()
    {
        
        $this->seller_admin_priv('qrcode');

        $id = I('get.id', 0, 'intval');
        if (empty($id)) {
            exit(json_encode(['status' => 0, 'msg' => L('select_please') . L('qrcode')]));
        }
        $rs = $this->model->table('wechat_qrcode')
            ->field('type, scene_id, username, expire_seconds, qrcode_url, status')
            ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
            ->find();
        if (empty($rs['status'])) {
            exit(json_encode(['status' => 0, 'msg' => L('qrcode_isdisabled')]));
        }
        if (empty($rs['qrcode_url'])) {
            
            if ($rs['type'] == 1 && !empty($rs['username'])) {
                $scene_id = "u=" . $rs['scene_id'];
                $ticket = $this->weObj->getQRCode($scene_id, 2, $rs['expire_seconds']); 
            } else {
                $ticket = $this->weObj->getQRCode((int)$rs['scene_id'], $rs['type'], $rs['expire_seconds']);
            }
            if (empty($ticket)) {
                exit(json_encode(['status' => 0, 'msg' => L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg]));
            }
            $data['ticket'] = $ticket['ticket'];
            $data['expire_seconds'] = $ticket['expire_seconds'];
            $data['endtime'] = gmtime() + $ticket['expire_seconds'];
            
            $qrcode_url = $this->weObj->getQRUrl($ticket['ticket']);
            $data['qrcode_url'] = $qrcode_url;

            $this->model->table('wechat_qrcode')
                ->data($data)
                ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
                ->save();
        } else {
            $qrcode_url = $rs['qrcode_url'];
        }
        
        $short_url = $this->weObj->getShortUrl($qrcode_url);
        $this->assign('short_url', $short_url);

        $this->assign('qrcode_url', $qrcode_url);
        $this->display();
    }

    
    public function actionArticle()
    {
        
        $this->seller_admin_priv('media');

        
        $this->page_num = 15;
        $offset = $this->pageLimit(url('article'), $this->page_num);
        $where['wechat_id'] = $this->wechat_id;
        $where['type'] = 'news';
        $total = $this->model->table('wechat_media')->where($where)->count();

        $list = $this->model->table('wechat_media')
            ->field('id, title, file, digest, content, add_time, sort, article_id')
            ->where($where)
            ->order('sort DESC, add_time DESC')
            ->limit($offset)
            ->select();
        foreach ((array)$list as $key => $val) {
            
            if (!empty($val['article_id'])) {
                $id = explode(',', $val['article_id']);
                foreach ($id as $k => $v) {
                    $list[$key]['articles'][] = $this->model->table('wechat_media')
                        ->field('id, title, file, add_time')
                        ->where(['id' => $v, 'wechat_id' => $this->wechat_id])
                        ->find();
                    $list[$key]['articles'][$k]['file'] = get_wechat_image_path($list[$key]['articles'][$k]['file']);
                }
            }
            
            if (!strstr($val['file'], 'public/assets/wechat/')) {
                $list[$key]['file'] = get_wechat_image_path($val['file']);
            } else {
                $list[$key]['is_prize'] = 1;
            }
            $list[$key]['content'] = empty($val['digest']) ? sub_str(strip_tags(html_out($val['content'])), 50) : $val['digest'];
        }
        $this->assign('page', $this->pageShow($total));
        $this->assign('list', $list);

        
        $postion = ['ur_here' => L('article')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionArticleEdit()
    {
        
        $this->seller_admin_priv('media');

        if (IS_POST) {
            $id = I('post.id');
            $data = I('post.data');
            $data['content'] = I('post.content', '', 'new_html_in');
            $pic_path = I('post.file_path');

            $form = new Form();
            if (!$form->isEmpty($data['title'], 1)) {
                $this->message(L('title') . L('empty'), null, 2, true);
            }
            if (!$form->isEmpty($data['content'], 1)) {
                $this->message(L('content') . L('empty'), null, 2, true);
            }
            
            $pic_path = edit_upload_image($pic_path);
            
            $cover = $_FILES['pic'];
            if ($cover['name']) {
                $type = ['image/jpeg', 'image/png'];
                if (!in_array($_FILES['pic']['type'], $type)) {
                    $this->message(L('not_file_type'), null, 2, true);
                }
                $result = $this->upload('data/attached/article', true);
                if ($result['error'] > 0) {
                    $this->message($result['message'], null, 2, true);
                }
                $data['file'] = $result['url'];
                $data['file_name'] = $cover['name'];
                $data['size'] = $cover['size'];
            } else {
                $data['file'] = $pic_path;
            }
            if (!$form->isEmpty($data['file'], 1)) {
                $this->message(L('please_upload'), null, 2, true);
            }
            $data['wechat_id'] = $this->wechat_id;
            $data['type'] = 'news';

            
            if (strpos($data['file'], 'no_image') !== false) {
                unset($data['file']);
            }
            
            if (empty($data['digest'])) {
                $data['digest'] = sub_str(strip_tags(html_out($data['content'])), 50);
            }

            if (!empty($id)) {
                
                if ($data['file'] && $pic_path != $data['file']) {
                    $pic_path = strpos($pic_path, 'no_image') == false ? $pic_path : ''; 
                    $this->remove($pic_path);
                }
                
                $host = str_replace(':' . $_SERVER['SERVER_PORT'], '', __HOST__);
                if (!strstr($data['link'], $host) && strstr($data['link'], 'mobile')) {
                    $http_prefix = strstr($data['link'], 'mobile', true);
                    if ($http !== $host) {
                        $http_suffix = strstr($data['link'], 'mobile');
                        $data['link'] = $host . '/' . $http_suffix;
                    }
                }
                $data['edit_time'] = gmtime();
                $this->model->table('wechat_media')->data($data)->where(['id' => $id])->save();
            } else {
                $data['add_time'] = gmtime();
                $this->model->table('wechat_media')->data($data)->add();
            }
            $this->message(L('wechat_editor') . L('success'), url('article'), 1, true);
        }
        $id = I('get.id');
        if (!empty($id)) {
            $article = $this->model->table('wechat_media')
                ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
                ->find();

            
            if (strstr($article['file'], 'app/modules/')) {
                $replace_str = 'app/modules/';
                $article['file'] = str_replace($replace_str, 'public/assets/', $article['file']);
                $article['file'] = str_replace('views/', '', $article['file']);
                
                $article['file'] = str_replace('wechatseller/', 'wechat/', $article['file']);
            }
            $article['file'] = get_wechat_image_path($article['file']);
            $this->assign('article', $article);
        }

        
        $postion = ['ur_here' => L('article_edit')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionGalleryAlbum()
    {
        
        $this->seller_admin_priv('media');

        $cache_key = md5('gallery_album0'.$this->ru_id);
        $gallery_album = S($cache_key);
        if (!$gallery_album) {
            $gallery_album = get_gallery_album_tree(0, $this->ru_id);
            S($cache_key, $gallery_album, 100);
        }

        $gallery_album_id = isset($gallery_album[0]['id']) ? $gallery_album[0]['id'] : 0;

        $album_id = input('album_id', 0, 'intval');
        $album_id = $album_id > 0 ? $album_id : $gallery_album_id; 

        
        $filter['album_id'] = $album_id;
        $offset = $this->pageLimit(url('gallery_album'), $this->page_num);

        $total = dao('pic_album')->where(['album_id' => $album_id, $ru_id => $this->ru_id])->count();

        $pic_album_list = dao('pic_album')->where(['album_id' => $album_id, $ru_id => $this->ru_id])->limit($offset)->order('pic_id DESC')->select();
        foreach ($pic_album_list as $key => $value) {
            $pic_album_list[$key]['pic_file'] = get_image_path($value['pic_file']);
        }

        $this->assign('gallery_album', $gallery_album);
        $this->assign('pic_album_list', $pic_album_list);
        $this->assign('album_id', $album_id);
        $this->assign('page', $this->pageShow($total));
        $this->display();
    }

    
    public function actionArticleEditNews()
    {
        
        $this->seller_admin_priv('media');

        if (IS_POST) {
            $id = I('post.id');
            $article_id = I('post.article');
            $data['sort'] = I('post.sort');
            if (is_array($article_id)) {
                $data['article_id'] = implode(',', $article_id);
                $data['wechat_id'] = $this->wechat_id;
                $data['type'] = 'news';

                if (!empty($id)) {
                    $data['edit_time'] = gmtime();
                    $this->model->table('wechat_media')
                        ->data($data)
                        ->where(['id' => $id])
                        ->save();
                } else {
                    $data['add_time'] = gmtime();
                    $this->model->table('wechat_media')
                        ->data($data)
                        ->add();
                }

                $this->redirect('article');
            } else {
                $this->message(L('please_add_again'), null, 2, true);
            }
        }
        $id = I('get.id');
        if (!empty($id)) {
            $rs = $this->model->table('wechat_media')
                ->field('article_id, sort')
                ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
                ->find();
            if (!empty($rs['article_id'])) {
                $articles = [];
                $art = explode(',', $rs['article_id']);
                foreach ($art as $key => $val) {
                    $articles[] = $this->model->table('wechat_media')
                        ->field('id, title, file, add_time')
                        ->where(['id' => $val])
                        ->find();
                    $articles[$key]['file'] = get_wechat_image_path($articles[$key]['file']);
                }
                $this->assign('articles', $articles);
            }
            $this->assign('sort', $rs['sort']);
        }

        $this->assign('id', $id);
        
        $postion = ['ur_here' => L('article_edit_news')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionArticlesList()
    {
        
        $this->page_num = isset($_COOKIE['ECSCP']['page_size']) && !empty($_COOKIE['ECSCP']['page_size']) ? $_COOKIE['ECSCP']['page_size'] : 4;
        $offset = $this->pageLimit(url('articles_list'), $this->page_num);
        $total =  $this->model->query("SELECT count(*) as count  FROM {pre}wechat_media WHERE wechat_id =  $this->wechat_id  and type = 'news' and (article_id is NULL or article_id = '') ");
        
        $article = $this->model->query("SELECT id, title, file, digest, content, add_time FROM {pre}wechat_media WHERE wechat_id =  $this->wechat_id  and type = 'news' and (article_id is NULL or article_id = '') ORDER BY sort DESC, add_time DESC limit $offset");
        if (!empty($article)) {
            foreach ($article as $k => $v) {
                $article[$k]['file'] = get_wechat_image_path($v['file']);
                $article[$k]['content'] = empty($v['digest']) ? sub_str(strip_tags(html_out($v['content'])), 50) : $v['digest'];
            }
        }

        $this->assign('page', $this->pageShow($total[0]['count']));
        $this->assign('page_num', $this->page_num);
        $this->assign('article', $article);
        $this->display();
    }

    
    public function actionArticleNewsDel()
    {
        $id = I('get.id');
        if (!empty($id)) {
            $this->model->table('wechat_media')
                ->data(['article_id' => 0])
                ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
                ->save();
        }
        $this->redirect('article_edit_news');
    }

    
    public function actionArticleDel()
    {
        
        $this->seller_admin_priv('media');

        $id = I('get.id');
        if (empty($id)) {
            $this->message(L('select_please') . L('article'), null, 2, true);
        }
        $pic = $this->model->table('wechat_media')
            ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
            ->getField('file');

        $this->model->table('wechat_media')
            ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
            ->delete();
        $this->remove($pic);

        $this->redirect('article');
    }

    
    public function actionPicture()
    {
        
        $this->seller_admin_priv('media');

        if (IS_POST) {
            if ($_FILES['pic']['name']) {
                $type = ['image/jpeg', 'image/png']; 
                if (!in_array($_FILES['pic']['type'], $type)) {
                    $this->message(L('not_file_type'), url('picture'), 2, true);
                }
                
                $size = round(($_FILES['pic']['size'] / (1024 * 1024)), 1);
                if ($size > 1) {
                    $this->message(L('file_size_limit'), null, 2);
                }
                $result = $this->upload('data/attached/article', true, 1);
                if ($result['error'] > 0) {
                    $this->message($result['message'], url('picture'), 2, true);
                }
                $data['file'] = $result['url'];
                $data['file_name'] = $_FILES['pic']['name'];
                $data['size'] = $_FILES['pic']['size'];
                $data['type'] = 'image';
                $data['add_time'] = gmtime();
                $data['wechat_id'] = $this->wechat_id;

                $this->model->table('wechat_media')
                    ->data($data)
                    ->add();

                $this->redirect('picture');
            }
        }
        
        $offset = $this->pageLimit(url('picture'), $this->page_num);

        $total = $this->model->query("SELECT count(*) as count FROM {pre}wechat_media WHERE wechat_id = $this->wechat_id and file is NOT NULL and (type = 'image')");
        $list = $this->model->query("SELECT id, file, file_name, thumb, size FROM {pre}wechat_media WHERE wechat_id = $this->wechat_id and file is NOT NULL and (type = 'image') order by sort DESC, add_time DESC limit $offset");
        if (empty($list)) {
            $list = [];
        }
        foreach ($list as $key => $val) {
            if ($val['size'] > (1024 * 1024)) {
                $list[$key]['size'] = round(($val['size'] / (1024 * 1024)), 1) . 'MB';
            } else {
                $list[$key]['size'] = round(($val['size'] / 1024), 1) . 'KB';
            }
            
            if (!strstr($val['file'], 'public/assets/wechat/')) {
                $list[$key]['file'] = get_wechat_image_path($val['file']);
            } else {
                $list[$key]['is_prize'] = 1;
            }
        }

        $this->assign('page', $this->pageShow($total[0]['count']));
        $this->assign('list', $list);

        
        $postion = ['ur_here' => L('picture')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionVoice()
    {
        
        $this->seller_admin_priv('media');

        if (IS_POST) {
            if ($_FILES['voice']['name']) {
                $type = ['audio/amr', 'audio/x-mpeg', 'audio/mp3']; 
                if (!in_array($_FILES['voice']['type'], $type)) {
                    $this->message(L('not_file_type'), url('voice'), 2, true);
                }
                $result = $this->upload('data/attached/voice', true);
                if ($result['error'] > 0) {
                    $this->message($result['message'], url('voice'), 2, true);
                }
                $data['file'] = $result['url'];
                $data['file_name'] = $_FILES['voice']['name'];
                $data['size'] = $_FILES['voice']['size'];
                $data['type'] = 'voice';
                $data['add_time'] = gmtime();
                $data['wechat_id'] = $this->wechat_id;
                $this->model->table('wechat_media')
                    ->data($data)
                    ->add();

                
                $this->redirect('voice');
            }
        }
        
        $offset = $this->pageLimit(url('voice'), $this->page_num);
        $total = $this->model->table('wechat_media')
            ->where(['wechat_id' => $this->wechat_id, 'type' => 'voice'])
            ->count();

        $list = $this->model->table('wechat_media')
            ->field('id, file, file_name, size')
            ->where(['wechat_id' => $this->wechat_id, 'type' => 'voice'])
            ->order('add_time desc, sort asc')
            ->limit($offset)
            ->select();
        if (empty($list)) {
            $list = [];
        }
        foreach ($list as $key => $val) {
            if ($val['size'] > (1024 * 1024)) {
                $list[$key]['size'] = round(($val['size'] / (1024 * 1024)), 1) . 'MB';
            } else {
                $list[$key]['size'] = round(($val['size'] / 1024), 1) . 'KB';
            }
        }
        $this->assign('page', $this->pageShow($total));
        $this->assign('list', $list);

        
        $postion = ['ur_here' => L('voice')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionVideo()
    {
        
        $this->seller_admin_priv('media');

        
        $offset = $this->pageLimit(url('video'), $this->page_num);
        $total = $this->model->table('wechat_media')
            ->where(['wechat_id' => $this->wechat_id, 'type' => 'video'])->count();

        $list = $this->model->table('wechat_media')
            ->field('id, file, file_name, size')
            ->where(['wechat_id' => $this->wechat_id, 'type' => 'video'])
            ->order('add_time desc, sort asc')
            ->limit($offset)
            ->select();
        if (empty($list)) {
            $list = [];
        }
        foreach ($list as $key => $val) {
            if ($val['size'] > (1024 * 1024)) {
                $list[$key]['size'] = round(($val['size'] / (1024 * 1024)), 1) . 'MB';
            } else {
                $list[$key]['size'] = round(($val['size'] / 1024), 1) . 'KB';
            }
        }

        $this->assign('page', $this->pageShow($total));
        $this->assign('list', $list);

        
        $postion = ['ur_here' => L('video')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionVideoEdit()
    {
        
        $this->seller_admin_priv('media');

        if (IS_POST) {
            $data = I('post.data');
            $id = I('post.id');

            if (empty($data['file']) || empty($data['file_name']) || empty($data['size'])) {
                $this->message(L('video_empty'), null, 2, true);
            }
            $size = round(($data['size'] / (1024 * 1024)), 1);
            if ($size > 2) {
                $this->message(L('file_size_limit'), null, 2, true);
            }
            if (empty($data['title'])) {
                $this->message(L('title') . L('empty'), null, 2, true);
            }
            $data['type'] = 'video';
            $data['wechat_id'] = $this->wechat_id;
            if (!empty($id)) {
                $data['edit_time'] = gmtime();
                $this->model->table('wechat_media')
                    ->data($data)
                    ->where(['id' => $id])
                    ->save();
            } else {
                $data['add_time'] = gmtime();
                $this->model->table('wechat_media')
                    ->data($data)
                    ->add();
            }
            $this->message(L('upload_video') . L('success'), url('video'), 1, true);
        }
        $id = I('get.id');
        if (!empty($id)) {
            $video = $this->model->table('wechat_media')
                ->field('id, file, file_name, size, title, content')
                ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
                ->find();

            $this->assign('video', $video);
        }
        $this->display();
    }

    
    public function actionVideoUpload()
    {
        if (IS_POST && !empty($_FILES['file']['name'])) {
            $vid = I('post.vid');
            if (!empty($vid)) {
                $file = $this->model->table('wechat_media')
                    ->where(['id' => $vid, 'wechat_id' => $this->wechat_id])
                    ->getField('file');
                $this->remove($file);
            }
            $result = $this->upload('data/attached/video', true, 2);
            if ($result['error'] > 0) {
                $data['errcode'] = 1;
                $data['errmsg'] = $result['message'];
                echo json_encode($data);
                exit();
            }
            $data['errcode'] = 0;
            $data['file'] = $result['url'];
            $data['file_name'] = $_FILES['file']['name'];
            $data['size'] = $_FILES['file']['size'];
            echo json_encode($data);
            exit();
        }
    }

    
    public function actionMediaEdit()
    {
        
        $this->seller_admin_priv('media');

        if (IS_POST) {
            $id = I('post.id');
            $pic_name = I('post.file_name');
            $form = new Form();
            if (!$form->isEmpty($id, 1)) {
                $this->message(L('empty'), null, 2, true);
            }
            if (!$form->isEmpty($pic_name, 1)) {
                $this->message(L('empty'), null, 2, true);
            }
            $data['file_name'] = $pic_name;
            $data['edit_time'] = gmtime();
            $num = $this->model->table('wechat_media')
                ->data($data)
                ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
                ->save();

            exit(json_encode(['status' => $num]));
        }
        $id = I('get.id');
        $pic = $this->model->table('wechat_media')
            ->field('id, file_name')
            ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
            ->find();
        if (empty($pic)) {
            redirect($_SERVER['HTTP_REFERER']);
        }
        $this->assign('pic', $pic);
        $this->display();
    }

    
    public function actionMediaDel()
    {
        
        $this->seller_admin_priv('media');

        $id = I('get.id');
        if (empty($id)) {
            $this->message(L('empty'), null, 2, true);
        }
        $pic = $this->model->table('wechat_media')
            ->field('file, thumb')
            ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
            ->find();
        if (!empty($pic)) {
            $this->model->table('wechat_media')
                ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
                ->delete();
        }
        
        $this->remove($pic['file']);
        $this->remove($pic['thumb']);

        redirect($_SERVER['HTTP_REFERER']);
    }

    
    public function actionDownload()
    {
        $id = I('get.id');
        $pic = $this->model->table('wechat_media')
            ->field('file, file_name')
            ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
            ->find();
        $filename = dirname(ROOT_PATH) . '/' . $pic['file'];
        if (file_exists($filename)) {
            Http::download($filename, $pic['file_name']);
        } else {
            $this->message(L('file_not_exist'), null, 2, true);
        }
    }

    
    public function actionMassList()
    {
        
        $this->seller_admin_priv('mass_message');

        
        $offset = $this->pageLimit(url('mass_list'), $this->page_num);
        $total = $this->model->table('wechat_mass_history')
            ->where(['wechat_id' => $this->wechat_id])
            ->count();
        $this->assign('page', $this->pageShow($total));

        $list = $this->model->table('wechat_mass_history')
            ->field('id, media_id, type, status, send_time, totalcount, sentcount, filtercount, errorcount')
            ->where(['wechat_id' => $this->wechat_id])
            ->order('send_time desc')
            ->limit($offset)
            ->select();
        foreach ((array)$list as $key => $val) {
            $media = $this->model->table('wechat_media')
                ->field('title, digest, content, file, article_id')
                ->where(['id' => $val['media_id']])
                ->find();
            if (!empty($media['article_id'])) {
                
                $artids = explode(',', $media['article_id']);
                $artinfo = $this->model->table('wechat_media')
                    ->field('title, digest, content, file')
                    ->where(['id' => $artids[0]])
                    ->find();
            } else {
                $artinfo = $media;
            }
            if ('news' == $val['type']) {
                $artinfo['type'] = '图文消息';
            }
            $artinfo['file'] = get_wechat_image_path($artinfo['file']);
            $artinfo['content'] = empty($artinfo['digest']) ? sub_str(strip_tags(html_out($artinfo['content'])), 50) : $artinfo['digest'];
            $list[$key]['artinfo'] = $artinfo;
        }
        
        $postion = ['ur_here' => L('mass_message')];
        $this->assign('postion', $postion);
        $this->assign('list', $list);
        $this->display();
    }

    
    public function actionMassMessage()
    {
        
        $this->seller_admin_priv('mass_message');

        if (IS_POST) {
            $tag_id = I('post.tag_id', 0, 'intval');
            $media_id = I('post.media_id');
            if (empty($tag_id) || $tag_id == 0 || empty($media_id)) {
                $this->message(L('please_select'), null, 2, true);
            }

            $article = [];
            $article_info = $this->model->table('wechat_media')
                ->field('id, title, command, author, file, is_show, digest, content, link, type, article_id')
                ->where(['id' => $media_id, 'wechat_id' => $this->wechat_id])
                ->find();
            
            if (!empty($article_info['article_id'])) {
                $articles = explode(',', $article_info['article_id']);
                foreach ($articles as $key => $val) {
                    $artinfo = $this->model->table('wechat_media')
                        ->field('title, command, author, file, is_show, digest, content, link')
                        ->where(['id' => $val, 'wechat_id' => $this->wechat_id])
                        ->find();
                    
                    $filename = !empty($artinfo['command']) ? dirname(ROOT_PATH) . '/mobile/' . $artinfo['file'] : dirname(ROOT_PATH) . '/' . $artinfo['file'];

                    
                    if (C('shop.open_oss') == 1) {
                        if (!file_exists($filename)) {
                            $image = basename($filename);
                            $imglist = ['0' => $image];
                            $file = str_replace(dirname(ROOT_PATH) . '/', '', $filename);
                            $path = str_replace($image, '', $file);
                            $this->BatchDownloadOss($imglist, $path);
                        }
                    }

                    $rs = $this->weObj->uploadMedia(['media' => realpath_wechat($filename)], 'image');
                    if (empty($rs)) {
                        $this->message(L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg, null, 2, true);
                    }

                    
                    

                    
                    $article[$key]['thumb_media_id'] = $rs['media_id'];
                    $article[$key]['author'] = $artinfo['author'];
                    $article[$key]['title'] = $artinfo['title'];
                    $article[$key]['content_source_url'] = $artinfo['link'];
                    $article[$key]['content'] = html_out($artinfo['content']);
                    $article[$key]['digest'] = $artinfo['digest'];
                    $article[$key]['show_cover_pic'] = $artinfo['is_show'];
                }
            } else {
                
                
                $filename = !empty($article_info['command']) ? dirname(ROOT_PATH) . '/mobile/' . $article_info['file'] : dirname(ROOT_PATH) . '/' . $article_info['file'];

                
                if (C('shop.open_oss') == 1) {
                    if (!file_exists($filename)) {
                        $image = basename($filename);
                        $imglist = ['0' => $image];
                        $file = str_replace(dirname(ROOT_PATH) . '/', '', $filename);
                        $path = str_replace($image, '', $file);
                        $this->BatchDownloadOss($imglist, $path);
                    }
                }

                $rs = $this->weObj->uploadMedia(['media' => realpath_wechat($filename)], 'image');
                if (empty($rs)) {
                    $this->message(L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg, null, 2, true);
                }
                
                

                
                
                $article[0]['thumb_media_id'] = $rs['media_id'];
                $article[0]['author'] = $article_info['author'];
                $article[0]['title'] = $article_info['title'];
                $article[0]['content_source_url'] = $article_info['link'];
                $article[0]['content'] = html_out($article_info['content']);
                $article[0]['digest'] = $article_info['digest'];
                $article[0]['show_cover_pic'] = $article_info['is_show'];
            }
            $article_list = ['articles' => $article];
            
            $rs1 = $this->weObj->uploadArticles($article_list);
            if (empty($rs1)) {
                $this->message(L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg, null, 2, true);
            }
            

            
            $mass_id = dao('wechat_mass_history')->where(['wechat_id' => $this->wechat_id])->order('id DESC')->getField('id');
            $clientmsgid = !empty($mass_id) ? $mass_id + 1 : 0;
            $massmsg = [
                'filter' => [
                    'is_to_all' => false,
                    'tag_id' => $tag_id
                ],
                'mpnews' => [
                    'media_id' => $rs1['media_id']
                ],
                'msgtype' => 'mpnews',
                'send_ignore_reprint' => 0,
                'clientmsgid' => $clientmsgid
            ];
            $rs2 = $this->weObj->sendGroupMassMessage($massmsg);
            if (empty($rs2)) {
                $this->message(L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg, null, 2, true);
            }

            
            $msg_data['wechat_id'] = $this->wechat_id;
            $msg_data['media_id'] = $article_info['id'];
            $msg_data['type'] = $article_info['type'];
            $msg_data['send_time'] = gmtime();
            $msg_data['msg_id'] = $rs2['msg_id'];
            $id = $this->model->table('wechat_mass_history')
                ->data($msg_data)
                ->add();

            $this->message(L('mass_sending_wait'), url('mass_message'), 1, true);
        }
        
        $tags = $this->model->table('wechat_user_taglist')
            ->field('tag_id, name')
            ->where(['wechat_id' => $this->wechat_id])
            ->order('tag_id')
            ->select();
        
        $article = $this->model->table('wechat_media')
            ->field('id, title, file, content, article_id, add_time')
            ->where(['wechat_id' => $this->wechat_id, 'type' => 'news'])
            ->order('sort asc, add_time desc')
            ->select();

        foreach ((array)$article as $key => $val) {
            if (!empty($val['article_id'])) {
                $id = explode(',', $val['article_id']);
                foreach ($id as $k => $v) {
                    $article[$key]['articles'][] = $this->model->table('wechat_media')
                        ->field('id, title, file, add_time')
                        ->where(['id' => $v])
                        ->find();
                    $article[$key]['articles'][$k]['file'] = get_wechat_image_path($article[$key]['articles'][$k]['file']);
                }
            }
            
            if (empty($val['article_id'])) {
                $article[$key]['file'] = get_wechat_image_path($val['file']);
            }
            $article[$key]['content'] = sub_str(strip_tags(html_out($val['content'])), 100);
        }
        $this->assign('tags', $tags);
        $this->assign('article', $article);
        
        $postion = ['ur_here' => L('mass_message')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionMassDel()
    {
        
        $this->seller_admin_priv('mass_message');

        $id = I('get.id');
        $msg_id = $this->model->table('wechat_mass_history')
            ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
            ->getField('msg_id');
        if (empty($msg_id)) {
            $this->message(L('massage_not_exist'), null, 2, true);
        }
        
        $delmass = [
            'msg_id' => $msg_id,
            'article_idx' => 0
        ];
        $rs = $this->weObj->deleteMassMessage($delmass);
        if (empty($rs)) {
            $this->message(L('errcode') . $this->weObj->errCode . L('errmsg') . $this->weObj->errMsg, null, 2, true);
        }

        $data['status'] = 'send success(已删除)';
        $this->model->table('wechat_mass_history')
            ->data($data)
            ->where(['id' => $id])
            ->save();
        $this->redirect('mass_list');
    }

    
    public function actionGetArticle()
    {
        if (IS_AJAX) {
            $data = I('post.article');
            $article = [];
            if (is_array($data)) {
                $id = implode(',', $data);
                $article = $this->model->query("SELECT id, title, file, link, digest, content, add_time FROM {pre}wechat_media WHERE id in ($id) AND wechat_id = $this->wechat_id ORDER BY sort DESC, add_time DESC");
                foreach ($article as $key => $val) {
                    $article[$key]['file'] = get_wechat_image_path($val['file']);
                    $article[$key]['add_time'] = date('Y年m月d日', $val['add_time']);
                    $article[$key]['content'] = empty($val['digest']) ? sub_str(strip_tags(html_out($val['content'])), 50) : $val['digest'];
                }
            }
            exit(json_encode($article));
        }
    }

    
    public function actionAutoReply()
    {
        
        $this->seller_admin_priv('auto_reply');

        
        $type = I('get.type');
        $reply_id = I('get.reply_id', 0, 'intval');
        if (!empty($type)) {
            
            $filter['type'] = $type;
            $filter['reply_id'] = $reply_id;
            $offset = $this->pageLimit(url('auto_reply', $filter), $this->page_num);
            if ('image' == $type) {
                $where = 'wechat_id = ' . $this->wechat_id . ' and file is NOT NULL and type = "image"';
                $list = $this->model->query('SELECT id, file, file_name, size, add_time, type FROM {pre}wechat_media WHERE ' . $where . ' ORDER BY sort DESC, add_time DESC limit ' . $offset);
            } elseif ('voice' == $type) {
                $where = 'wechat_id = ' . $this->wechat_id . ' and file is NOT NULL and type = "voice"';
                $list = $this->model->query('SELECT id, file, file_name, size, add_time, type FROM {pre}wechat_media WHERE ' . $where . ' ORDER BY sort DESC, add_time DESC limit ' . $offset);
            } elseif ('video' == $type) {
                $where = 'wechat_id = ' . $this->wechat_id . ' and file is NOT NULL and type = "video"';
                $list = $this->model->query('SELECT id, file, file_name, size, add_time, type FROM {pre}wechat_media WHERE ' . $where . ' ORDER BY sort DESC, add_time DESC limit ' . $offset);
            } elseif ('news' == $type) {
                
                $no_list = I('get.no_list', 0, 'intval');
                $this->assign('no_list', $no_list);
                if (!empty($no_list)) {
                    $where = "wechat_id = " . $this->wechat_id . " and type='news' and article_id is NULL or article_id = '' ";
                } else {
                    $where = "wechat_id = " . $this->wechat_id . " and type='news' ";
                }
                $list = $this->model->query('SELECT id, title, file, file_name, size, digest, content, add_time, type, article_id FROM {pre}wechat_media WHERE ' . $where . ' ORDER BY sort DESC, add_time DESC limit ' . $offset);
                foreach ((array)$list as $key => $val) {
                    if (!empty($val['article_id'])) {
                        $id = explode(',', $val['article_id']);
                        foreach ($id as $k => $v) {
                            $list[$key]['articles'][] = $this->model->table('wechat_media')
                                ->field('id, title, digest, file, add_time')
                                ->where(['id' => $v])
                                ->find();
                            $list[$key]['articles'][$k]['file'] = get_wechat_image_path($list[$key]['articles'][$k]['file']);
                        }
                    }
                    $list[$key]['content'] = empty($val['digest']) ? sub_str(strip_tags(html_out($val['content'])), 50) : $val['digest'];
                }
            }

            foreach ((array)$list as $key => $val) {
                if ($val['size'] > (1024 * 1024)) {
                    $list[$key]['size'] = round(($val['size'] / (1024 * 1024)), 1) . 'MB';
                } else {
                    $list[$key]['size'] = round(($val['size'] / 1024), 1) . 'KB';
                }
                
                if (empty($val['article_id'])) {
                    $list[$key]['file'] = get_wechat_image_path($val['file']);
                }
            }
            $total = $this->model->query("SELECT count(*) as count FROM {pre}wechat_media WHERE $where ");
            foreach ($total as $key => $value) {
                $num = $value['count'];
            }
            $this->assign('reply_id', $reply_id);
            $this->assign('page', $this->pageShow($num));
            $this->assign('list', $list);
            $this->assign('type', $type);

            
            $postion = ['ur_here' => L('autoreply_manage')];
            $this->assign('postion', $postion);
            $this->display();
        }
    }


    
    public function actionReplySubscribe()
    {
        
        $this->seller_admin_priv('auto_reply');

        if (IS_POST) {
            $content_type = I('post.content_type');
            if ($content_type == 'text') {
                $data['content'] = I('post.content', '', 'new_html_in');
                $data['media_id'] = 0;
            } else {
                $data['media_id'] = I('post.media_id');
                $data['content'] = '';
            }
            $data['type'] = 'subscribe';
            if (is_array($data) && (!empty($data['media_id']) || !empty($data['content']))) {
                $where['type'] = $data['type'];
                $where['wechat_id'] = $this->wechat_id;
                $id = $this->model->table('wechat_reply')
                    ->where($where)
                    ->getField('id');
                if (!empty($id)) {
                    $this->model->table('wechat_reply')
                        ->data($data)
                        ->where($where)
                        ->save();
                } else {
                    $data['wechat_id'] = $this->wechat_id;
                    $this->model->table('wechat_reply')
                        ->data($data)
                        ->add();
                }
                $this->message(L('wechat_editor') . L('success'), url('reply_subscribe'), 1, true);
            } else {
                $this->message(L('empty'), null, 2, true);
            }
        }
        
        $subscribe = $this->model->table('wechat_reply')
            ->where(['type' => 'subscribe', 'wechat_id' => $this->wechat_id])
            ->find();
        if (!empty($subscribe['media_id'])) {
            $subscribe['media'] = $this->model->table('wechat_media')
                ->field('file, type, file_name')
                ->where(['id' => $subscribe['media_id'], 'wechat_id' => $this->wechat_id])
                ->find();
            $subscribe['media']['file'] = get_wechat_image_path($subscribe['media']['file']);
        }
        $this->assign('subscribe', $subscribe);

        
        $postion = ['ur_here' => L('subscribe_autoreply')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionReplyMsg()
    {
        
        $this->seller_admin_priv('auto_reply');

        if (IS_POST) {
            $content_type = I('post.content_type');
            if ($content_type == 'text') {
                $data['content'] = I('post.content', '', 'new_html_in');
                $data['media_id'] = 0;
            } else {
                $data['media_id'] = I('post.media_id');
                $data['content'] = '';
            }
            $data['type'] = 'msg';
            if (is_array($data)) {
                $where['type'] = $data['type'];
                $where['wechat_id'] = $this->wechat_id;
                $id = $this->model->table('wechat_reply')
                    ->where($where)
                    ->getField('id');
                if (!empty($id)) {
                    $this->model->table('wechat_reply')
                        ->data($data)
                        ->where($where)
                        ->save();
                } else {
                    $data['wechat_id'] = $this->wechat_id;
                    $this->model->table('wechat_reply')
                        ->data($data)
                        ->add();
                }
                $this->message(L('wechat_editor') . L('success'), url('reply_msg'), 1, true);
            } else {
                $this->message(L('empty'), null, 2, true);
            }
        }
        
        $msg = $this->model->table('wechat_reply')
            ->where(['type' => 'msg', 'wechat_id' => $this->wechat_id])
            ->find();
        if (!empty($msg['media_id'])) {
            $msg['media'] = $this->model->table('wechat_media')
                ->field('file, type, file_name')
                ->where(['id' => $msg['media_id']])
                ->find();
            $msg['media']['file'] = get_wechat_image_path($msg['media']['file']);
        }
        $this->assign('msg', $msg);

        
        $postion = ['ur_here' => L('msg_autoreply')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionReplyKeywords()
    {
        
        $this->seller_admin_priv('auto_reply');

        $list = $this->model->table('wechat_reply')
            ->field('id, rule_name, content, media_id, reply_type')
            ->where(['type' => 'keywords', 'wechat_id' => $this->wechat_id])
            ->order('add_time desc')
            ->select();
        foreach ((array)$list as $key => $val) {
            
            if (!empty($val['media_id'])) {
                $media = $this->model->table('wechat_media')
                    ->field('title, file, file_name, type, digest, content, add_time, article_id')
                    ->where(['id' => $val['media_id'], 'wechat_id' => $this->wechat_id])
                    ->find();
                $media['file'] = get_wechat_image_path($media['file']);
                $media['content'] = empty($media['digest']) ? sub_str(strip_tags(html_out($media['content'])), 50) : $media['digest'];
                if (!empty($media['article_id'])) {
                    $artids = explode(',', $media['article_id']);
                    foreach ($artids as $k => $v) {
                        $list[$key]['medias'][] = $this->model->table('wechat_media')
                            ->field('title, file, add_time')
                            ->where(['id' => $v, 'wechat_id' => $this->wechat_id])
                            ->find();
                        $list[$key]['medias'][$k]['file'] = get_wechat_image_path($list[$key]['medias'][$k]['file']);
                    }
                } else {
                    $list[$key]['media'] = $media;
                }
            }
            $keywords = $this->model->table('wechat_rule_keywords')
                ->field('rule_keywords')
                ->where(['rid' => $val['id'], 'wechat_id' => $this->wechat_id])
                ->order('id desc')
                ->select();
            $list[$key]['rule_keywords'] = $keywords;
            
            if (!empty($keywords)) {
                $rule_keywords = [];
                foreach ($keywords as $k => $v) {
                    $rule_keywords[] = $v['rule_keywords'];
                }
                $rule_keywords = implode(',', $rule_keywords);
                $list[$key]['rule_keywords_string'] = $rule_keywords;
            }
        }
        $this->assign('list', $list);

        
        $postion = ['ur_here' => L('keywords_autoreply')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionRuleEdit()
    {
        if (IS_POST) {
            $id = I('post.id');
            $content_type = I('post.content_type');
            $rule_keywords = I('post.rule_keywords');
            
            $data['rule_name'] = I('post.rule_name', '', 'trim');
            $data['media_id'] = I('post.media_id', 0, 'intval');
            $data['content'] = I('post.content', '', 'new_html_in');
            $data['reply_type'] = $content_type;
            if ($content_type == 'text') {
                $data['media_id'] = 0;
            } else {
                $data['content'] = '';
            }

            $form = new Form();
            if (!$form->isEmpty($data['rule_name'], 1)) {
                $this->message(L('rule_name_empty'), null, 2, true);
            }
            if (!$form->isEmpty($rule_keywords, 1)) {
                $this->message(L('rule_keywords_empty'), null, 2, true);
            }
            if (empty($data['content']) && empty($data['media_id'])) {
                $this->message(L('rule_content_empty'), null, 2, true);
            }
            if (strlen($data['rule_name']) > 60) {
                $this->message(L('rule_name_length_limit'), null, 2, true);
            }

            
            $rule_keywords = explode(',', $rule_keywords);
            foreach ($rule_keywords as $val) {
                
                $where = [];
                if (!empty($id)) {
                    $where['rid'] = ['neq', $id];
                    $where['rule_keywords'] = $val;
                    $where['wechat_id'] = $this->wechat_id;
                } else {
                    
                    $where['rule_keywords'] = $val;
                    $where['wechat_id'] = $this->wechat_id;
                }
                $count = dao('wechat_rule_keywords')->where($where)->count();
                if ($count >= 1) {
                    $this->message('关键词' . $val . '已经存在了，请更换一个！', null, 2);
                }
            }

            $data['type'] = 'keywords';
            if (!empty($id)) {
                $this->model->table('wechat_reply')
                    ->data($data)
                    ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
                    ->save();
                $this->model->table('wechat_rule_keywords')
                    ->where(['rid' => $id, 'wechat_id' => $this->wechat_id])
                    ->delete();
            } else {
                $data['add_time'] = gmtime();
                $data['wechat_id'] = $this->wechat_id;
                $id = $this->model->table('wechat_reply')
                    ->data($data)
                    ->add();
            }
            
            
            foreach ($rule_keywords as $val) {
                $kdata['rid'] = $id;
                $kdata['rule_keywords'] = $val;
                $kdata['wechat_id'] = $this->wechat_id;
                $this->model->table('wechat_rule_keywords')->data($kdata)->add();
            }
            $this->message(L('wechat_editor') . L('success'), url('reply_keywords'), 1, true);
        }
    }

    
    public function actionReplyDel()
    {
        
        $this->seller_admin_priv('auto_reply');

        $id = I('get.id');
        if (empty($id)) {
            $this->message(L('empty'), null, 2, true);
        }
        $this->model->table('wechat_reply')
            ->where(['id' => $id, 'wechat_id' => $this->wechat_id])
            ->delete();
        
        $this->model->table('wechat_rule_keywords')
            ->where(['rid' => $id, 'wechat_id' => $this->wechat_id])
            ->delete();
        $this->redirect('reply_keywords');
    }

    
    public function actionMediaList()
    {
        $this->display();
    }

    
    public function actionRemind()
    {
        if (IS_POST) {
            $command = I('post.command');
            $data = I('post.data');
            $config = I('post.config');
            $info = Check::rule([
                Check::must($command),
                '关键词不正确'
            ]);
            if ($info !== true) {
                $this->message($info, null, 2, true);
            }
            if (!empty($config)) {
                $data['config'] = serialize($config);
            }
            $data['wechat_id'] = $this->wechat_id;
            $num = $this->model->table('wechat_extend')
                ->where('command = "' . $command . '" and wechat_id = ' . $this->wechat_id)
                ->count();
            if ($num > 0) {
                $this->model->table('wechat_extend')
                    ->data($data)
                    ->where('command = "' . $command . '" and wechat_id = ' . $this->wechat_id)
                    ->save();
            } else {
                $data['command'] = $command;
                $this->model->table('wechat_extend')
                    ->data($data)
                    ->add();
            }

            redirect($_SERVER['HTTP_REFERER']);
        }

        $order_remind = $this->model->table('wechat_extend')
            ->field('name, enable, config')
            ->where('command = "order_remind" and wechat_id = ' . $this->wechat_id)
            ->find();
        if ($order_remind['config']) {
            $order_remind['config'] = unserialize($order_remind['config']);
        }
        $pay_remind = $this->model->table('wechat_extend')
            ->field('name, enable, config')
            ->where('command = "pay_remind" and wechat_id = ' . $this->wechat_id)
            ->find();
        if ($pay_remind['config']) {
            $pay_remind['config'] = unserialize($pay_remind['config']);
        }
        $send_remind = $this->model->table('wechat_extend')
            ->field('name, enable, config')
            ->where('command = "send_remind" and wechat_id = ' . $this->wechat_id)
            ->find();
        if ($send_remind['config']) {
            $send_remind['config'] = unserialize($send_remind['config']);
        }
        $register_remind = $this->model->table('wechat_extend')
            ->field('name, enable, config')
            ->where('command = "register_remind" and wechat_id = ' . $this->wechat_id)
            ->find();
        if ($register_remind['config']) {
            $register_remind['config'] = unserialize($register_remind['config']);
        }
        $this->assign('order_remind', $order_remind);
        $this->assign('pay_remind', $pay_remind);
        $this->assign('send_remind', $send_remind);
        $this->assign('register_remind', $register_remind);
        $this->display();
    }

    
    public function actionCustomerService()
    {
        $command = 'kefu';
        if (IS_POST) {
            $data = I('post.data');
            $config = I('post.config');

            if (!empty($config)) {
                $data['config'] = serialize($config);
            }
            $num = $this->model->table('wechat_extend')
                ->where(['command' => $command, 'wechat_id' => $this->wechat_id])
                ->count();
            if ($num > 0) {
                $this->model->table('wechat_extend')
                    ->data($data)
                    ->where(['command' => $command, 'wechat_id' => $this->wechat_id])
                    ->save();
            } else {
                $data['wechat_id'] = $this->wechat_id;
                $data['command'] = $command;
                $data['name'] = '多客服';
                $this->model->table('wechat_extend')
                    ->data($data)
                    ->add();
            }

            redirect($_SERVER['HTTP_REFERER']);
        }

        $customer_service = $this->model->table('wechat_extend')
            ->field('name, enable, config')
            ->where(['command' => $command, 'wechat_id' => $this->wechat_id])
            ->find();
        if ($customer_service['config']) {
            $customer_service['config'] = unserialize($customer_service['config']);
        }
        $this->assign('customer_service', $customer_service);
        $this->display();
    }

    
    public function actionAddKf()
    {
        $account = 'test@gh_1ca465561479';
        $nickname = 'test';
        $password = '123123';
        $rs = $this->weObj->addKFAccount($account, $nickname, $password);
        if (empty($rs)) {
            logResult($this->weObj->errMsg);
        }
    }

    
    public function actionTemplate()
    {
        
        $this->seller_admin_priv('template');
        $condition['wechat_id'] = $this->wechat_id;
        $list = $this->model->table('wechat_template')->where($condition)->order('id asc')->select();
        if ($list) {
            foreach ($list as $key => $val) {
                $list[$key]['add_time'] = local_date('Y-m-d H:i', $val['add_time']);
            }
        }
        $this->assign('list', $list);

        
        $postion = ['ur_here' => L('templates')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionEditTemplate()
    {
        
        $this->seller_admin_priv('template');

        if (IS_AJAX) {
            $id = I('post.id');
            $data = I('post.data', '', 'trim');
            if ($id) {
                $condition['id'] = $id;
                $condition['wechat_id'] = $this->wechat_id;

                $data['add_time'] = gmtime();
                $this->model->table('wechat_template')->data($data)->where($condition)->save();
                exit(json_encode(['status' => 1]));
            } else {
                exit(json_encode(['status' => 0, 'msg' => L('template_edit_fail')]));
            }
        }
        $id = I('get.id', 0, 'intval');
        if ($id) {
            $condition['id'] = $id;
            $condition['wechat_id'] = $this->wechat_id;
            $template = $this->model->table('wechat_template')->where($condition)->find();
            $this->assign('template', $template);
        }

        $this->display();
    }

    
    public function actionSwitch()
    {
        
        $this->seller_admin_priv('template');

        $id = I('get.id', 0, 'intval');
        $status = I('get.status', 0, 'intval');
        if (empty($id)) {
            $this->message(L('empty'), null, 2, true);
        }
        $condition['id'] = $id;
        $condition['wechat_id'] = $this->wechat_id;

        $data = [];
        $data['add_time'] = gmtime();

        
        if ($status == 1) {
            
            $template = $this->model->table('wechat_template')->field('template_id, code')->where($condition)->find();
            if (empty($template['template_id'])) {
                $template_id = $this->weObj->addTemplateMessage($template['code']);
                
                if ($template_id) {
                    $data['template_id'] = $template_id;
                    $this->model->table('wechat_template')->data($data)->where($condition)->save();
                } else {
                    $this->message($this->weObj->errMsg, null, 2);
                }
            }
            
            $data['status'] = 1;
            $this->model->table('wechat_template')->data($data)->where($condition)->save();
        } else {
            
            $data['status'] = 0;
            $this->model->table('wechat_template')->data($data)->where($condition)->save();
        }
        $this->redirect('template');
    }

    
    public function actionShareCount()
    {
        
        $this->seller_admin_priv('fans');

        $keywords = I('request.keywords', '', 'trim');

        $where = '';
        if (!empty($keywords)) {
            $where .= ' and (u.nickname like "%' . $keywords . '%" )'; 
        }

        
        $offset = $this->pageLimit(url('share_count'), $this->page_num);

        $sql = 'SELECT count(*) as number FROM {pre}wechat_share_count sh
            LEFT JOIN {pre}wechat_user u ON u.openid = sh.openid
            WHERE sh.wechat_id = ' . $this->wechat_id . $where . ' order by sh.share_time desc';
        $total = $this->model->query($sql);

        $sql = 'SELECT sh.*, u.nickname FROM {pre}wechat_share_count sh
            LEFT JOIN {pre}wechat_user u ON u.openid = sh.openid
            WHERE sh.wechat_id = ' . $this->wechat_id . $where . ' order by sh.share_time desc limit ' . $offset;
        $list = $this->model->query($sql);
        foreach ($list as $key => $val) {
            $list[$key]['share_type'] = get_share_type($val['share_type']);
            $list[$key]['share_time'] = local_date('Y-m-d H:i:s', $val['share_time']);
        }
        $this->assign('page', $this->pageShow($total[0]['number']));
        $this->assign('list', $list);
        $this->display();
    }

    
    public function actionShareCountDelete()
    {
        
        $this->admin_priv('fans');

        $id = input('id', 0, 'intval');
        if (empty($id)) {
            $this->message(L('empty'), null, 2);
        } else {
            dao('wechat_share_count')->where(['id' => $id, 'wechat_id' => $this->wechat_id])->delete();
            $this->message(L('drop') . L('success'), url('share_count'));
        }
    }

    
    public function actionExtendIndex()
    {
        
        $this->seller_admin_priv('extend');

        
        $extends = $this->model->table('wechat_extend')
            ->field('name, keywords, command, config, enable, author, website')
            ->where(['type' => 'function', 'enable' => 1, 'wechat_id' => $this->wechat_id])
            ->order('id asc')
            ->select();
        if (!empty($extends)) {
            $kw = [];
            foreach ($extends as $key => $val) {
                $val['config'] = unserialize($val['config']);
                $kw[$val['command']] = $val;
            }
        }

        $plugins = $this->read_wechat();
        if (!empty($plugins)) {
            foreach ($plugins as $k => $v) {
                $ks = $v['command'];
                
                if (isset($kw[$v['command']])) {
                    $plugins[$k]['keywords'] = $kw[$ks]['keywords'];
                    $plugins[$k]['config'] = $kw[$ks]['config'];
                    $plugins[$k]['enable'] = $kw[$ks]['enable'];
                }
                if ($this->wechat_type == 0 || $this->wechat_type == 1) {
                    if ($plugins[$k]['command'] == 'bd' || $plugins[$k]['command'] == 'bonus' || $plugins[$k]['command'] == 'ddcx' || $plugins[$k]['command'] == 'jfcx' || $plugins[$k]['command'] == 'sign' || $plugins[$k]['command'] == 'wlcx' || $plugins[$k]['command'] == 'zjd' || $plugins[$k]['command'] == 'dzp' || $plugins[$k]['command'] == 'ggk') {
                        unset($plugins[$k]);
                    }
                }
                
                if (!empty($_SESSION['seller_id']) || $_SESSION['seller_id'] > 0) {
                    if ($plugins[$k]['command'] == 'bonus' || $plugins[$k]['command'] == 'ddcx' || $plugins[$k]['command'] == 'jfcx' || $plugins[$k]['command'] == 'sign' || $plugins[$k]['command'] == 'wlcx') {
                        unset($plugins[$k]);
                    }
                }
            }
        }
        $this->assign('plugins', $plugins);
        
        $postion = ['ur_here' => L('wechat_extend')];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionExtendEdit()
    {
        
        $this->seller_admin_priv('extend');

        if (IS_POST) {
            $handler = I('post.handler');
            $cfg_value = I('post.cfg_value');
            $data = I('post.data');
            if (empty($data['keywords'])) {
                $this->message('请填写扩展词', null, 2, true);
            }
            
            if (!empty($cfg_value) && $cfg_value['prize_name']) {
                foreach ($cfg_value['prize_name'] as $k => $n) {
                    $replace = ['&', '<', '>', '=', '"', "'", '“', '”“'];
                    $cfg_value['prize_name'][$k] = str_replace($replace, '', strip_tags(html_out($n)));
                }
            }
            $data['type'] = 'function';
            $data['wechat_id'] = $this->wechat_id;
            
            $rs = $this->model->table('wechat_extend')
                ->field('name, config, enable')
                ->where(['command' => $data['command'], 'wechat_id' => $this->wechat_id])
                ->find();
            if (!empty($rs)) {
                
                if (empty($handler) && !empty($rs['enable'])) {
                    $this->message('插件已安装', null, 2, true);
                } else {
                    
                    if (empty($cfg_value['media_id'])) {
                        $media_id = $this->model->table('wechat_media')->where(['command' => $this->plugin_name, 'wechat_id' => $this->wechat_id])->getField('id');
                        if ($media_id) {
                            $cfg_value['media_id'] = $media_id;
                        } else {
                            
                            $sql_file = MODULE_PATH . 'Plugins/' . ucfirst($this->plugin_name) . '/install.sql';
                            if (file_exists($sql_file)) {
                                
                                $sql = file_get_contents($sql_file);
                                $sql = str_replace(['ecs_wechat_media', '(0', 'http://', 'views/images'], ['{pre}wechat_media', '(' . $this->wechat_id, __HOST__ . url('wechat/index/plugin_show', ['name' => $this->plugin_name, 'ru_id' => $this->ru_id]), 'public/assets/wechat/' . $this->plugin_name . '/images'], $sql);
                                $this->model->query($sql);
                                
                                $cfg_value['media_id'] = $this->model->table('wechat_media')->where(['command' => $this->plugin_name, 'wechat_id' => $this->wechat_id])->getField('id');
                            }
                        }
                    }

                    $data['config'] = serialize($cfg_value);
                    $data['enable'] = 1;
                    $this->model->table('wechat_extend')
                        ->data($data)
                        ->where(['command' => $data['command'], 'wechat_id' => $this->wechat_id])
                        ->save();
                }
            } else {
                
                $sql_file = MODULE_PATH . 'Plugins/' . ucfirst($this->plugin_name) . '/install.sql';
                if (file_exists($sql_file)) {
                    
                    $sql = file_get_contents($sql_file);
                    $sql = str_replace(['ecs_wechat_media', '(0', 'http://', 'views/images'], ['{pre}wechat_media', '(' . $this->wechat_id, __HOST__ . url('wechat/index/plugin_show', ['name' => $this->plugin_name, 'ru_id' => $this->ru_id]), 'public/assets/wechat/' . $this->plugin_name . '/images'], $sql);
                    $this->model->query($sql);
                    
                    $cfg_value['media_id'] = $this->model->table('wechat_media')->where(['command' => $this->plugin_name, 'wechat_id' => $this->wechat_id])->getField('id');
                }
                $data['config'] = serialize($cfg_value);
                $data['enable'] = 1;
                $this->model->table('wechat_extend')->data($data)->add();
            }
            $this->message('安装编辑成功', url('extend_index'), 1, true);
        }
        $handler = I('get.handler', '', 'trim');
        
        if (!empty($handler)) {
            
            $info = $this->model->table('wechat_extend')
                ->field('name, keywords, command, config, enable, author, website')
                ->where(['command' => $this->plugin_name, 'wechat_id' => $this->wechat_id, 'enable' => 1])
                ->find();
            
            if (empty($info)) {
                $this->message('请选择要编辑的功能扩展', null, 2, true);
            }
            $info['config'] = unserialize($info['config']);
        }
        
        $postion = ['ur_here' => L('wechat_extend')];
        $this->assign('postion', $postion);

        $plugin = '\\App\\Modules\\Wechat\\Plugins\\' . ucfirst($this->plugin_name) . '\\' . ucfirst($this->plugin_name);
        if (class_exists($plugin)) {
            
            if (!empty($info['enable'])) {
                $config = $info;
                $config['handler'] = 'edit';
            } else {
                $config_file = MODULE_PATH . 'Plugins/' . ucfirst($this->plugin_name) . '/config.php';
                $config = require_once($config_file);
            }
            if (!is_array($config)) {
                $config = [];
            }
            
            $current_time = gmtime();
            $config['config']['starttime'] = empty($config['config']['starttime']) ? date('Y-m-d', $current_time) : $config['config']['starttime'];
            $config['config']['endtime'] = empty($config['config']['endtime']) ? date('Y-m-d', strtotime("+1 months")) : $config['config']['endtime'];
            $obj = new $plugin($config);
            $obj->install();
        }
    }


    
    public function actionExtendUninstall()
    {
        
        $this->seller_admin_priv('extend');

        $keywords = I('get.ks');
        if (empty($keywords)) {
            $this->message('请选择要卸载的功能扩展', null, 2, true);
        }
        $config = $this->model->table('wechat_extend')
            ->where(['command' => $keywords, 'wechat_id' => $this->wechat_id])
            ->getField('enable');
        $data['enable'] = 0;

        $this->model->table('wechat_extend')
            ->data($data)
            ->where(['command' => $keywords, 'wechat_id' => $this->wechat_id])
            ->save();
        
        $media_count = $this->model->table('wechat_media')->where(['command' => $keywords, 'wechat_id' => $this->wechat_id])->count();
        if ($media_count > 0) {
            $this->model->table('wechat_media')->where(['command' => $keywords, 'wechat_id' => $this->wechat_id])->delete();
        }

        $this->message('卸载成功', url('extend_index'), 1, true);
    }

    
    public function actionWinnerList()
    {
        
        $this->seller_admin_priv('extend');

        $ks = I('get.ks', '', 'trim');
        if (empty($ks)) {
            $this->message('请选择插件', null, 2, true);
        }
        
        $page_num = 10;
        $this->assign('page_num', $page_num);

        $keywords = I('request.keywords', '', 'trim'); 
        $where = '';
        if (!empty($keywords)) {
            $where .= ' and (u.nickname like "%' . $keywords . '%" )'; 
        }

        
        $filter['ks'] = $ks;
        $offset = $this->pageLimit(url('winner_list', $filter), $page_num);

        $sql_count = "SELECT count(*) as number FROM {pre}wechat_prize p LEFT JOIN {pre}wechat_user u ON p.openid = u.openid WHERE p.activity_type = '" . $ks . "' and p.prize_type = 1 and u.subscribe = 1 and u.wechat_id = '" . $this->wechat_id . "'  " . $where . "  ORDER BY dateline desc ";
        $total = $this->model->query($sql_count);

        $sql = 'SELECT p.id, p.prize_name, p.issue_status, p.winner, p.dateline, p.openid, u.nickname FROM {pre}wechat_prize p LEFT JOIN {pre}wechat_user u ON p.openid = u.openid WHERE p.activity_type = "' . $ks . '" and u.wechat_id = ' . $this->wechat_id . ' and p.prize_type = 1 and u.subscribe = 1 ' . $where . ' ORDER BY dateline desc  limit ' . $offset;
        $list = $this->model->query($sql);
        if (empty($list)) {
            $list = [];
        }
        foreach ($list as $key => $val) {
            $list[$key]['winner'] = unserialize($val['winner']);
            $list[$key]['dateline'] = local_date($GLOBALS['_CFG']['time_format'], $val['dateline']);
        }

        $this->assign('activity_type', $ks);

        $this->assign('page', $this->pageShow($total[0]['number']));
        $this->assign('list', $list);

        $extend_name = $this->model->table('wechat_extend')
            ->where(['command' => $ks, 'wechat_id' => $this->wechat_id])
            ->getField('name');
        
        $postion = ['ur_here' => $extend_name];
        $this->assign('postion', $postion);
        $this->display();
    }

    
    public function actionWinnerIssue()
    {
        
        $this->seller_admin_priv('extend');

        $id = I('get.id', 0, 'intval');
        $cancel = I('get.cancel');
        $activity_type = I('get.ks', '', 'trim');
        if (empty($id)) {
            $this->message('请选择中奖记录', null, 2, true);
        }
        if (!empty($cancel)) {
            $data['issue_status'] = 0;
            $this->model->table('wechat_prize')->data($data)->where(['id' => $id, 'wechat_id' => $this->wechat_id])->save();

            $this->message('取消成功', url('winner_list', ['ks' => $activity_type]), 1, true);
        } else {
            $data['issue_status'] = 1;
            $this->model->table('wechat_prize')->data($data)->where(['id' => $id, 'wechat_id' => $this->wechat_id])->save();

            $this->message('发放成功', url('winner_list', ['ks' => $activity_type]), 1, true);
        }
    }

    
    public function actionWinnerDel()
    {
        
        $this->seller_admin_priv('extend');

        $id = I('get.id', 0, 'intval');
        $activity_type = I('get.ks', '', 'trim');
        if (empty($id)) {
            $this->message('请选择中奖记录', null, 2, true);
        }
        $this->model->table('wechat_prize')->where(['id' => $id, 'wechat_id' => $this->wechat_id])->delete();

        $this->message('删除成功', url('winner_list', ['ks' => $activity_type]), 1, true);
    }

    
    private function read_wechat()
    {
        $plugins = glob(MODULE_PATH . 'Plugins/*/config.php');
        foreach ($plugins as $file) {
            $config[] = require_once($file);
        }
        return $config;
    }

    /**
     * 营销活动首页
     * @return
     */
    public function actionMarketIndex()
    {
        
        $this->seller_admin_priv('market');

        $markets = $this->read_markets();
        
        $arr = [];
        foreach ($markets as $key => $val) {
            $markets[$key]['url'] = url('market_list', ['type' => $val['keywords']]);

            $arr[] = $val['sort'];

            
            if (!empty($this->ru_id)) {
                if ($markets[$key]['keywords'] == 'redpack') {
                    unset($markets[$key]);
                }
            }
        }
        array_multisort($arr, SORT_ASC, $markets);

        
        $postion = ['ur_here' => L('wechat_market')];
        $this->assign('postion', $postion);

        $this->assign('list', $markets);
        $this->display();
    }

    
    public function actionMarketList()
    {
        
        $this->seller_admin_priv('market');

        $plugin = '\\App\\Modules\\Wechat\\Market\\' . ucfirst($this->market_type) . '\\' . 'Admin';
        if (class_exists($plugin)) {
            $config_file = MODULE_PATH . 'Market/' . ucfirst($this->market_type) . '/config.php';
            $config = require_once($config_file);

            $config['wechat_id'] = $this->wechat_id;
            $config['page_num'] = $this->page_num;
            $config['ru_id'] = $this->ru_id;

            
            $postion = ['ur_here' => L('wechat_market')];
            $this->assign('postion', $postion);

            $obj = new $plugin($config);
            $obj->marketList();
        }
    }

    
    public function actionMarketEdit()
    {
        
        $this->seller_admin_priv('market');

        
        $market_id = I('id', 0, 'intval');

        $plugin = '\\App\\Modules\\Wechat\\Market\\' . ucfirst($this->market_type) . '\\' . 'Admin';
        if (class_exists($plugin)) {
            $config_file = MODULE_PATH . 'Market/' . ucfirst($this->market_type) . '/config.php';
            $config = require_once($config_file);
            
            if (!empty($market_id)) {
                $config['market_id'] = $market_id;
                $config['handler'] = 'edit';
            }
            $config['wechat_id'] = $this->wechat_id;
            $config['ru_id'] = $this->ru_id;

            
            $key = dao('wechat_marketing')->where(['marketing_type' => $this->market_type, 'wechat_id' => $this->wechat_id])->count();
            $key = !empty($key) ? $key + 1 : 1;
            $config['command'] = $this->market_type . $this->wechat_id . $key;

            
            $postion = ['ur_here' => L('wechat_market')];
            $this->assign('postion', $postion);

            $obj = new $plugin($config);
            $obj->marketEdit();
        }
    }

    
    public function actionMarketDel()
    {
        
        $this->seller_admin_priv('market');

        $id = I('get.id', 0, 'intval');
        if (!$id) {
            $this->message(L('empty'), null, 2);
        }

        dao('wechat_marketing')->where(['id' => $id, 'wechat_id' => $this->wechat_id])->delete();
        $this->message(L('market_delete') . L('success'), url('list', ['type' => $this->market_type]));
    }

    
    public function actionDataList()
    {
        
        $this->seller_admin_priv('market');

        $market_id = I('get.id', 0, 'intval');

        $function = I('get.function', '', 'trim');

        $plugin = '\\App\\Modules\\Wechat\\Market\\' . ucfirst($this->market_type) . '\\' . 'Admin';
        if (class_exists($plugin) && !empty($function)) {
            $config_file = MODULE_PATH . 'Market/' . ucfirst($this->market_type) . '/config.php';

            $config = require_once($config_file);

            $config['wechat_id'] = $this->wechat_id;
            $config['page_num'] = $this->page_num;
            $config['ru_id'] = $this->ru_id;

            $config['market_id'] = $market_id;

            
            $postion = ['ur_here' => L('wechat_market')];
            $this->assign('postion', $postion);

            $obj = new $plugin($config);

            $function_name = 'market' . camel_cases($function, 1);

            $obj->$function_name();
        }
    }


    
    public function actionMarketQrcode()
    {
        
        $this->seller_admin_priv('market');

        $plugin = '\\App\\Modules\\Wechat\\Market\\' . ucfirst($this->market_type) . '\\' . 'Admin';
        if (class_exists($plugin)) {
            $config_file = MODULE_PATH . 'Market/' . ucfirst($this->market_type) . '/config.php';
            $config = require_once($config_file);

            $config['wechat_id'] = $this->wechat_id;
            $config['ru_id'] = $this->ru_id;

            
            $postion = ['ur_here' => L('wechat_market')];
            $this->assign('postion', $postion);

            $obj = new $plugin($config);
            $obj->marketQrcode();
        }
    }

    
    public function actionMarketAction()
    {
        
        $this->seller_admin_priv('market');

        $file = MODULE_PATH . 'Market/' . ucfirst($this->market_type) . '/' . 'Admin.php';
        if (file_exists($file)) {
            include_once($file);
            $market = '\\App\\Modules\\Wechat\\Market\\' . ucfirst($this->market_type) . '\\' . 'Admin';
            if (class_exists($market)) {
                $config_file = MODULE_PATH . 'Market/' . ucfirst($this->market_type) . '/config.php';
                $config = require_once($config_file);

                $config['wechat_id'] = $this->wechat_id;
                $config['ru_id'] = $this->ru_id;
            }

            
            $postion = ['ur_here' => L('wechat_market')];
            $this->assign('postion', $postion);

            $obj = new $market($config);
            $obj->executeAction();
        }
    }


    
    private function read_markets()
    {
        $markets = glob(MODULE_PATH . 'Market/*/config.php');
        foreach ($markets as $file) {
            $config[] = require_once($file);
        }
        return $config;
    }


    private function get_config()
    {
        $without = [
            'index',
            'append',
            'modify',
            'delete',
            'set_default'
        ];

        if (!in_array(strtolower(ACTION_NAME), $without)) {
            
            $where['id'] = $this->wechat_id;
            $where['ru_id'] = $this->ru_id;
            $wechat = $this->model->table('wechat')->field('token, appid, appsecret, type, status')->where($where)->find();
            if (empty($wechat)) {
                $wechat = [];
            }
            if (empty($wechat['status'])) {
                $this->message(L('open_wechat'), url('modify'), 2, true);
                exit;
            }
            $config = [];
            $config['token'] = $wechat['token'];
            $config['appid'] = $wechat['appid'];
            $config['appsecret'] = $wechat['appsecret'];

            $this->weObj = new Wechat($config);
            $this->assign('type', $wechat['type']);
        }
    }
}
