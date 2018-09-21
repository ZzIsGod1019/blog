<?php

namespace App\Modules\Wechat\Controllers;

use App\Extensions\Wechat;
use App\Modules\Base\Controllers\FrontendController;

class IndexController extends FrontendController
{
    private $weObj = '';
    private $secret_key = '';
    private $wechat_id = 0;
    private $ru_id = 0;

    
    public function __construct()
    {
        parent::__construct();
        
        $this->secret_key = I('get.key', '', 'trim');
        if ($this->secret_key) {
            $this->load_helper('passport');
            $wxinfo = $this->get_config($this->secret_key);
            $this->wechat_id = $wxinfo['id'];
            $this->ru_id = $wxinfo['ru_id'];
            if ($this->ru_id) {
                cookie('ectouch_ru_id', $this->ru_id, gmtime() + 3600 * 24);
            } else {
                cookie('ectouch_ru_id', null);
            }
            $config['token'] = $wxinfo['token'];
            $config['appid'] = $wxinfo['appid'];
            $config['appsecret'] = $wxinfo['appsecret'];
            $config['encodingaeskey'] = $wxinfo['encodingaeskey'];
            
            $this->weObj = new Wechat($config);
            $this->weObj->valid();
        }
    }

    
    public function actionIndex()
    {
        
        $type = $this->weObj->getRev()->getRevType();
        $wedata = $this->weObj->getRev()->getRevData();
        $keywords = '';
        
        $event_data = ['subscribe', 'unsubscribe', 'LOCATION', 'VIEW', 'SCAN'];
        if (!in_array($wedata['Event'], $event_data)) {
            if (!empty($wedata['EventKey']) || !empty($wedata['Content'])) {
                message_log_alignment_add($wedata, $this->wechat_id);
            }
        }
        
        update_wechatuser_subscribe($wedata['FromUserName'], $this->wechat_id, $this->weObj);

        $scene_id = '';
        $flag = false;
        
        switch ($type) {
            
            case Wechat::MSGTYPE_TEXT:
                $keywords = $wedata['Content'];
                break;
            
            case Wechat::MSGTYPE_EVENT:
                
                if ($wedata['Event'] == Wechat::EVENT_SUBSCRIBE) {
                    
                    $this->msg_reply('subscribe');
                    
                    if (isset($wedata['Ticket']) && !empty($wedata['Ticket'])) {
                        $scene_id = $this->weObj->getRevSceneId();
                        $flag = true;
                        
                        $this->subscribe($wedata['FromUserName'], $scene_id);
                    } else {
                        
                        $this->subscribe($wedata['FromUserName']);
                    }
                } elseif ($wedata['Event'] == Wechat::EVENT_UNSUBSCRIBE) {
                    
                    $this->unsubscribe($wedata['FromUserName']);
                    exit();
                } elseif ($wedata['Event'] == Wechat::EVENT_SCAN) {
                    
                    $scene_id = $this->weObj->getRevSceneId();
                } elseif ($wedata['Event'] == Wechat::EVENT_MENU_CLICK) {
                    
                    $keywords = $wedata['EventKey'];
                } elseif ($wedata['Event'] == Wechat::EVENT_MENU_VIEW) {
                    
                    redirect($wedata['EventKey']);
                } elseif ($wedata['Event'] == Wechat::EVENT_LOCATION) {
                    
                    exit();
                } elseif ($wedata['Event'] == 'kf_create_session') {
                    
                } elseif ($wedata['Event'] == 'kf_close_session') {
                    
                } elseif ($wedata['Event'] == 'kf_switch_session') {
                    
                } elseif ($wedata['Event'] == 'MASSSENDJOBFINISH') {
                    
                    $data['status'] = $wedata['Status'];
                    $data['totalcount'] = $wedata['TotalCount'];
                    $data['filtercount'] = $wedata['FilterCount'];
                    $data['sentcount'] = $wedata['SentCount'];
                    $data['errorcount'] = $wedata['ErrorCount'];
                    
                    dao('wechat_mass_history')->data($data)->where(['msg_id' => $wedata['MsgID'], 'wechat_id' => $this->wechat_id])->save();
                    exit();
                } elseif ($wedata['Event'] == 'TEMPLATESENDJOBFINISH') {
                    
                    if ($wedata['Status'] == 'success') {
                        
                        $data = ['status' => 1];
                    } elseif ($wedata['Status'] == 'failed:user block') {
                        
                        $data = ['status' => 2];
                    } else {
                        
                        $data = ['status' => 0]; 
                    }
                    
                    dao('wechat_template_log')->data($data)->where(['msgid' => $wedata['MsgID'], 'openid' => $wedata['FromUserName'], 'wechat_id' => $this->wechat_id])->save();
                    exit();
                }
                break;
            
            case Wechat::MSGTYPE_IMAGE:
                exit();
                break;
            
            case Wechat::MSGTYPE_VOICE:
                exit();
                break;
            
            case Wechat::MSGTYPE_VIDEO:
                exit();
                break;
            
            case Wechat::MSGTYPE_SHORTVIDEO:
                exit();
                break;
            
            case Wechat::MSGTYPE_LOCATION:
                exit();
                break;
            
            case Wechat::MSGTYPE_LINK:
                exit();
                break;
            default:
                $this->msg_reply('msg'); 
                exit();
        }

        
        if (!empty($scene_id)) {
            $keywords = $this->do_qrcode_subscribe($scene_id);
            if (!empty($keywords)) {
                
                $rs1 = $this->get_function($wedata['FromUserName'], $keywords, $flag);
                if (empty($rs1)) {
                    
                    $rs2 = $this->keywords_reply($keywords, $flag);
                }
            }
        }

        
        
        if ($wedata['MsgType'] == 'event') {
            $where = [
                'wechat_id' => $this->wechat_id,
                'fromusername' => $wedata['FromUserName'],
                'createtime' => $wedata['CreateTime'],
                'keywords' => $keywords,
                'is_send' => 0
            ];
        } else {
            $where = [
                'wechat_id' => $this->wechat_id,
                'msgid' => $wedata['MsgId'],
                'keywords' => $keywords,
                'is_send' => 0
            ];
        }
        $contents = dao('wechat_message_log')->field('fromusername, createtime, keywords, msgid, msgtype')->where($where)->find();
        if (!empty($contents) && !empty($contents['keywords'])) {
            $keyword = html_in($contents['keywords']);
            $fromusername = $contents['fromusername'];
            
            $rs = $this->customer_service($fromusername, $keyword);
            if (empty($rs)) {
                
                $rs1 = $this->get_function($fromusername, $keyword);
                
                $rs3 = $this->get_marketing($fromusername, $keyword);
                if (empty($rs1) || empty($rs3)) {
                    
                    $rs2 = $this->keywords_reply($keyword);
                    if (empty($rs2)) {
                        
                        $this->msg_reply('msg');
                    }
                }
            }
            
            $this->record_msg($fromusername, $keyword);
            
            message_log_alignment_send($contents, $this->wechat_id);
        }
    }

    
    private function subscribe($openid = '', $scene_id = '')
    {
        if (!empty($openid)) {
            
            $info = $this->weObj->getUserInfo($openid);
            if (empty($info)) {
                $this->weObj->resetAuth();
                exit('null');
            }

            
            $data['wechat_id'] = $this->wechat_id;
            $data['subscribe'] = $info['subscribe'];
            $data['openid'] = $info['openid'];
            $data['nickname'] = $info['nickname'];
            $data['sex'] = $info['sex'];
            $data['language'] = $info['language'];
            $data['city'] = $info['city'];
            $data['province'] = $info['province'];
            $data['country'] = $info['country'];
            $data['headimgurl'] = $info['headimgurl'];
            $data['subscribe_time'] = $info['subscribe_time'];
            $data['remark'] = $info['remark'];
            $data['groupid'] = isset($info['groupid']) ? $info['groupid'] : $this->weObj->getUserGroup($openid);
            $data['unionid'] = isset($info['unionid']) ? $info['unionid'] : '';

            
            if ($this->ru_id == 0 && empty($data['unionid'])) {
                exit('null');
            }
            
            if ($this->ru_id == 0) {
                update_wechat_unionid($info, $this->wechat_id); 
            }
            $condition = ['unionid' => $data['unionid'], 'wechat_id' => $this->wechat_id];
            $result = dao('wechat_user')->field('ect_uid, openid, unionid')->where($condition)->find();
            
            if (isset($result)) {
                $users = dao('users')->where(['user_id' => $result['ect_uid']])->find();
                if (empty($users) || empty($result['ect_uid'])) {
                    dao('wechat_user')->where($condition)->delete();
                    $result = [];
                    unset($_SESSION['user_id']);
                }
            }

            
            if (empty($result)) {
                
                $old_users = dao('users')->field('user_id, parent_id')->where(['aite_id' => 'wechat_' . $data['unionid']])->find();
                if (!empty($old_users)) {
                    
                    dao('users')->data(['aite_id' => ''])->where(['user_id' => $older_user['user_id']])->save();
                    
                    $res = [
                        'unionid' => $data['unionid'],
                        'user_id' => $old_users['user_id']
                    ];
                    update_connnect_user($res, 'wechat');
                }
                
                $userinfo = get_connect_user($data['unionid']);
                
                if (empty($userinfo) && $this->ru_id == 0) {
                    
                    $username = get_wechat_username($data['unionid'], 'wechat');
                    $password = mt_rand(100000, 999999);
                    $email = $username . '@qq.com';
                    
                    $extend = [
                        'nick_name' => $data['nickname'],
                        'sex' => $data['sex'],
                        'user_picture' => $data['headimgurl']
                    ];
                    
                    $scenes = return_is_drp($scene_id);
                    if ($scenes['is_drp'] == true) {
                        $extend['drp_parent_id'] = $scenes['drp_parent_id'];
                    } else {
                        $extend['parent_id'] = $scenes['parent_id'];
                    }
                    if (register($username, $password, $email, $extend) !== false) {
                        
                        $res = [
                            'unionid' => $data['unionid'],
                            'user_id' => $_SESSION['user_id'],
                            'nickname' => $data['nickname'],
                            'sex' => $data['sex'],
                            'province' => $data['province'],
                            'city' => $data['city'],
                            'country' => $data['country'],
                            'headimgurl' => $data['headimgurl'],
                        ];
                        update_connnect_user($res, 'wechat');
                        
                        $parent_id = $extend['parent_id'];
                        if ($is_drp == false && !empty($parent_id)) {
                            $qrcode = dao('wechat_qrcode')->field('username')->where(['scene_id' => $parent_id, 'wechat_id' => $this->wechat_id])->find();
                            if (!empty($qrcode['username'])) {
                                dao('wechat_qrcode')->where(['scene_id' => $parent_id, 'wechat_id' => $this->wechat_id])->setInc('scan_num', 1);
                            }
                        }
                        
                        $data['ect_uid'] = $_SESSION['user_id'];
                        $data['from'] = 0; 
                    } else {
                        exit('null');
                    }
                }

                
                dao('wechat_user')->data($data)->add();
                if ($this->ru_id == 0) {
                    
                    $data1['user_id'] = $_SESSION['user_id'];
                    $bonus_num = dao('user_bonus')->where($data1)->count();
                    if ($bonus_num <= 0) {
                        $content = $this->send_message($openid, 'bonus', $this->weObj, 1);
                        $bonus_msg = empty($content) ? '' : $content['content'];
                        if (!empty($bonus_msg)) {
                            
                            $this->send_custom_message($openid, 'text', $bonus_msg);
                        }
                    }
                }
            } else {
                if (empty($scene_id)) {
                    $template = $data['nickname'] . '，欢迎您再次回来';
                    
                    $this->send_custom_message($openid, 'text', $template);
                }
                
                dao('wechat_user')->data($data)->where($condition)->save();
            }

            
            check_template_log($data['openid'], $this->wechat_id, $this->weObj);
        }
    }

    
    public function unsubscribe($openid = '')
    {
        
        $where['openid'] = $openid;
        $where['wechat_id'] = $this->wechat_id;
        $rs = dao('wechat_user')->where($where)->count();
        
        if ($rs > 0) {
            $data['subscribe'] = 0;
            dao('wechat_user')->data($data)->where($where)->save();

            
            dao('wechat_user_tag')->where($where)->delete();
        }
    }

    
    private function do_qrcode_subscribe($scene_id)
    {
        
        if (strpos($scene_id, 'u') == 0) {
            $scene_id = str_replace('u=', '', $scene_id);
        }
        $scene_id = intval($scene_id);
        $qrcode = dao('wechat_qrcode')->field('function, username')->where(['scene_id' => $scene_id, 'wechat_id' => $this->wechat_id])->find();
        
        if (empty($qrcode['username'])) {
            dao('wechat_qrcode')->where(['scene_id' => $scene_id, 'wechat_id' => $this->wechat_id])->setInc('scan_num', 1);
        }
        return $qrcode['function'];
    }

    
    private function msg_reply($type, $return = 0)
    {
        $replyInfo = $this->db->table('wechat_reply')
            ->field('content, media_id')
            ->where(['type' => $type, 'wechat_id' => $this->wechat_id])
            ->find();
        if (!empty($replyInfo)) {
            if (!empty($replyInfo['media_id'])) {
                $replyInfo['media'] = $this->db->table('wechat_media')
                    ->field('title, command, content, file, type, file_name')
                    ->where(['id' => $replyInfo['media_id']])
                    ->find();
                if ($replyInfo['media']['type'] == 'news') {
                    $replyInfo['media']['type'] = 'image';
                }
                
                $filename = !empty($replyInfo['media']['command']) ? dirname(ROOT_PATH) . '/mobile/' . $replyInfo['media']['file'] : dirname(ROOT_PATH) . '/' . $replyInfo['media']['file'];
                $rs = $this->weObj->uploadMedia(['media' => realpath_wechat($filename)], $replyInfo['media']['type']);
                if (empty($rs)) {
                    logResult($this->weObj->errMsg);
                }
                
                if ($rs['type'] == 'image' || $rs['type'] == 'voice') {
                    $replyData = [
                        'ToUserName' => $this->weObj->getRev()->getRevFrom(),
                        'FromUserName' => $this->weObj->getRev()->getRevTo(),
                        'CreateTime' => time(),
                        'MsgType' => $rs['type'],
                        ucfirst($rs['type']) => ['MediaId' => $rs['media_id']]
                    ];
                } elseif ('video' == $rs['type']) {
                    $replyData = [
                        'ToUserName' => $this->weObj->getRev()->getRevFrom(),
                        'FromUserName' => $this->weObj->getRev()->getRevTo(),
                        'CreateTime' => time(),
                        'MsgType' => $rs['type'],
                        ucfirst($rs['type']) => [
                            'MediaId' => $rs['media_id'],
                            'Title' => $replyInfo['media']['title'],
                            'Description' => strip_tags($replyInfo['media']['content'])
                        ]
                    ];
                }
                if ($return) {
                    return ['type' => 'media', 'content' => $replyData];
                }
                $this->weObj->reply($replyData);
                
                $this->record_msg($this->weObj->getRev()->getRevTo(), '图文信息', 1);
            } else {
                
                $replyInfo['content'] = html_out($replyInfo['content']);
                if ($return) {
                    return ['type' => 'text', 'content' => $replyInfo['content']];
                }
                $this->weObj->text($replyInfo['content'])->reply();
                
                $this->record_msg($this->weObj->getRev()->getRevTo(), $replyInfo['content'], 1);
            }
        }
    }

    
    private function keywords_reply($keywords, $flag = false)
    {
        $endrs = false;
        $sql = 'SELECT r.content, r.media_id, r.reply_type FROM {pre}wechat_reply r LEFT JOIN {pre}wechat_rule_keywords k ON r.id = k.rid WHERE k.rule_keywords = "' . $keywords . '" and r.wechat_id = ' . $this->wechat_id . ' order by r.add_time desc LIMIT 1';
        $result = $this->db->query($sql);
        if (!empty($result)) {
            
            if (!empty($result[0]['media_id'])) {
                $mediaInfo = $this->db->table('wechat_media')
                    ->field('id, title, command, digest, content, file, type, file_name, article_id, link')
                    ->where(['id' => $result[0]['media_id']])
                    ->find();
                
                if ($result[0]['reply_type'] == 'image' || $result[0]['reply_type'] == 'voice') {
                    
                    $filename = !empty($mediaInfo['command']) ? dirname(ROOT_PATH) . '/mobile/' . $mediaInfo['file'] : dirname(ROOT_PATH) . '/' . $mediaInfo['file'];
                    $rs = $this->weObj->uploadMedia(['media' => realpath_wechat($filename)], $result[0]['reply_type']);
                    if (empty($rs)) {
                        logResult($this->weObj->errMsg);
                    }
                    $replyData = [
                        'ToUserName' => $this->weObj->getRev()->getRevFrom(),
                        'FromUserName' => $this->weObj->getRev()->getRevTo(),
                        'CreateTime' => time(),
                        'MsgType' => $rs['type'],
                        ucfirst($rs['type']) => ['MediaId' => $rs['media_id']]
                    ];
                    
                    if ($flag == true) {
                        $this->send_custom_message($this->weObj->getRev()->getRevFrom(), $result[0]['reply_type'], $rs['media_id']);
                    } else {
                        $this->weObj->reply($replyData);
                    }
                    
                    $record_cotent = $result[0]['reply_type'] == 'voice' ? '语音' : '图片';
                    $this->record_msg($this->weObj->getRev()->getRevTo(), $record_cotent, 1);
                    $endrs = true;
                } elseif ('video' == $result[0]['reply_type']) {
                    
                    $filename = !empty($mediaInfo['command']) ? dirname(ROOT_PATH) . '/mobile/' . $mediaInfo['file'] : dirname(ROOT_PATH) . '/' . $mediaInfo['file'];
                    $rs = $this->weObj->uploadMedia(['media' => realpath_wechat($filename)], $result[0]['reply_type']);
                    if (empty($rs)) {
                        logResult($this->weObj->errMsg);
                    }
                    $replyData = [
                        'ToUserName' => $this->weObj->getRev()->getRevFrom(),
                        'FromUserName' => $this->weObj->getRev()->getRevTo(),
                        'CreateTime' => time(),
                        'MsgType' => $rs['type'],
                        ucfirst($rs['type']) => [
                            'MediaId' => $rs['media_id'],
                            'Title' => $mediaInfo['title'],
                            'Description' => strip_tags($mediaInfo['content'])
                        ]
                    ];
                    
                    if ($flag == true) {
                        $replyData = [
                            'media_id' => $rs['media_id'],
                            'title' => $mediaInfo['title'],
                            'description' => strip_tags($mediaInfo['content']),
                        ];
                        $this->send_custom_message($this->weObj->getRev()->getRevFrom(), $result[0]['reply_type'], $replyData);
                    } else {
                        $this->weObj->reply($replyData);
                    }
                    
                    $this->record_msg($this->weObj->getRev()->getRevTo(), '视频', 1);
                    $endrs = true;
                } elseif ('news' == $result[0]['reply_type']) {
                    
                    $articles = [];
                    if (!empty($mediaInfo['article_id'])) {
                        $artids = explode(',', $mediaInfo['article_id']);
                        foreach ($artids as $key => $val) {
                            $artinfo = $this->db->table('wechat_media')
                                ->field('id, title, file, digest, content, link')
                                ->where(['id' => $val])
                                ->find();
                            $artinfo['content'] = sub_str(strip_tags(html_out($artinfo['content'])), 100);
                            $articles[$key]['Title'] = $artinfo['title'];
                            $articles[$key]['Description'] = empty($artinfo['digest']) ? $artinfo['content'] : $artinfo['digest'];
                            $articles[$key]['PicUrl'] = get_wechat_image_path($artinfo['file']);
                            $articles[$key]['Url'] = empty($artinfo['link']) ? __HOST__ . url('article/index/wechat', ['id' => $artinfo['id']]) : strip_tags(html_out($artinfo['link']));
                        }
                    } else {
                        $articles[0]['Title'] = $mediaInfo['title'];
                        $articles[0]['Description'] = empty($mediaInfo['digest']) ? sub_str(strip_tags(html_out($mediaInfo['content'])), 100) : $mediaInfo['digest'];
                        $articles[0]['PicUrl'] = get_wechat_image_path($mediaInfo['file']);
                        $articles[0]['Url'] = empty($mediaInfo['link']) ? __HOST__ . url('article/index/wechat', ['id' => $mediaInfo['id']]) : strip_tags(html_out($mediaInfo['link']));
                    }
                    
                    if ($flag == true) {
                        $this->send_custom_message($this->weObj->getRev()->getRevFrom(), $result[0]['reply_type'], $articles);
                    } else {
                        $this->weObj->news($articles)->reply();
                    }
                    
                    $this->record_msg($this->weObj->getRev()->getRevTo(), '图文信息', 1);
                    $endrs = true;
                }
            } else {
                
                $result[0]['content'] = strip_tags(html_out($result[0]['content']));

                if ($flag == true) {
                    $this->send_custom_message($this->weObj->getRev()->getRevFrom(), 'text', $result[0]['content']);
                } else {
                    $this->weObj->text($result[0]['content'])->reply();
                }
                
                $this->record_msg($this->weObj->getRev()->getRevTo(), $result[0]['content'], 1);
                $endrs = true;
            }
        }
        return $endrs;
    }

    
    public function get_function($fromusername, $keywords, $flag = false)
    {
        $return = false;
        $rs = dao('wechat_extend')
            ->field('name, keywords, command, config')
            ->where('(keywords like "%' . $keywords . '%" or command like "%' . $keywords . '%") and enable = 1 and wechat_id = ' . $this->wechat_id)
            ->order('id asc')
            ->select();
        if (empty($rs)) {
            $rs = $this->db->query("SELECT name, keywords, command, config FROM {pre}wechat_extend WHERE command = 'search' and enable = 1 and wechat_id = '" . $this->wechat_id . "' ");
        }
        $info = reset($rs);
        $info['user_keywords'] = $keywords;
        
        $file = MODULE_PATH . 'Plugins/' . ucfirst($info['command']) . '/' . ucfirst($info['command']) . '.php';
        if (file_exists($file)) {
            require_once($file);
            $new_command = '\\App\\Modules\\Wechat\\Plugins\\' . ucfirst($info['command']) . '\\' . ucfirst($info['command']);
            $cfg = ['ru_id' => $this->ru_id];
            $wechat = new $new_command($cfg);
            $data = $wechat->returnData($fromusername, $info);
            if (!empty($data)) {
                
                if ($data['type'] == 'text') {
                    $data['content'] = strip_tags(html_out($data['content']));
                    if ($flag == true) {
                        $this->send_custom_message($fromusername, $data['type'], $data['content']);
                    } else {
                        $this->weObj->text($data['content'])->reply();
                    }
                    
                    $this->record_msg($fromusername, $data['content'], 1);
                } elseif ($data['type'] == 'news') {
                    if ($flag == true) {
                        $this->send_custom_message($fromusername, $data['type'], $data['content']);
                    } else {
                        $this->weObj->news($data['content'])->reply();
                    }
                    
                    $this->record_msg($fromusername, '图文消息', 1);
                } elseif ($data['type'] == 'image') {
                    
                    $filename = dirname(ROOT_PATH) . '/' . $data['path'];
                    $rs = $this->weObj->uploadMedia(['media' => realpath_wechat($filename)], 'image');
                    if (empty($rs)) {
                        logResult($this->weObj->errMsg);
                    }
                    if ($flag == true) {
                        $this->send_custom_message($fromusername, $data['type'], $rs['media_id']);
                    } else {
                        $this->weObj->image($rs['media_id'])->reply();
                    }
                    
                    $this->record_msg($fromusername, '图片', 1);
                }
                $return = true;
            }
        }
        return $return;
    }

    
    public function get_marketing($fromusername, $keywords)
    {
        $return = false;
        $sql = "SELECT id, name, command, status FROM {pre}wechat_marketing WHERE (command = '" . $keywords . "') AND wechat_id = '" . $this->wechat_id . "' ORDER BY id DESC ";
        $rs = $this->db->query($sql);

        $rs = reset($rs);
        if ($rs) {
            
            
            
            
            $where = [
                'id' => $rs['id'],
                'command' => $rs['command'],
                'wechat_id' => $this->wechat_id,
            ];
            $result = dao('wechat_marketing')->field('id, name, background, description, status, url')->where($where)->find();
        }
        if ($result) {
            $articles = ['type' => 'text', 'content' => '活动未启用'];
            if ($result['status'] == 1) {
                $articles = [];
                
                $articles['type'] = 'news';
                $articles['content'][0]['Title'] = $result['name'];
                $articles['content'][0]['Description'] = $result['description'];
                $articles['content'][0]['PicUrl'] = get_wechat_image_path($result['background']);
                $articles['content'][0]['Url'] = strip_tags(html_out($result['url']));
            }

            
            if ($articles['type'] == 'text') {
                $articles['content'] = strip_tags(html_out($articles['content']));
                $this->weObj->text($articles['content'])->reply();
                
                $this->record_msg($fromusername, $articles['content'], 1);
            } elseif ($articles['type'] == 'news') {
                $this->weObj->news($articles['content'])->reply();
                
                $this->record_msg($fromusername, '图文消息', 1);
            }
            $return = true;
        }

        return $return;
    }

    
    public function send_message($fromusername, $keywords, $weObj, $return = 0)
    {
        $result = false;
        $condition = ['command' => $keywords, 'enable' => 1, 'wechat_id' => $this->wechat_id];
        $rs = dao('wechat_extend')->field('name, command, config')->where($condition)->find();
        $file = MODULE_PATH . 'Plugins/' . ucfirst($rs['command']) . '/' . ucfirst($rs['command']) . '.php';
        if (file_exists($file)) {
            require_once($file);
            $new_command = '\\App\\Modules\\Wechat\\Plugins\\' . ucfirst($rs['command']) . '\\' . ucfirst($rs['command']);
            $cfg = ['ru_id' => $this->ru_id];
            $wechat = new $new_command($cfg);
            $data = $wechat->returnData($fromusername, $rs);
            if (!empty($data)) {
                $data['content'] = strip_tags(html_out($data['content']));
                if ($return) {
                    $result = $data;
                } else {
                    $weObj->sendCustomMessage($data['content']);
                    $result = true;
                }
            }
        }
        return $result;
    }

    
    public function customer_service($fromusername, $keywords)
    {
        $result = false;
        
        $kfsession = $this->weObj->getKFSession($fromusername);
        if (empty($kfsession) || empty($kfsession['kf_account'])) {
            $kefu = dao('wechat_user')->where(['openid' => $fromusername, 'wechat_id' => $this->wechat_id])->getField('openid');
            if ($kefu && $keywords == 'kefu') {
                $rs = $this->db->table('wechat_extend')->where(['command' => 'kefu', 'enable' => 1, 'wechat_id' => $this->wechat_id])->getField('config');
                if (!empty($rs)) {
                    $config = unserialize($rs);
                    $msg = [
                        'touser' => $fromusername,
                        'msgtype' => 'text',
                        'text' => [
                            'content' => '欢迎进入多客服系统'
                        ]
                    ];
                    $this->weObj->sendCustomMessage($msg);
                    
                    $this->record_msg($fromusername, $msg['text']['content'], 1);
                    
                    $online_list = $this->weObj->getCustomServiceOnlineKFlist();
                    if ($online_list['kf_online_list']) {
                        foreach ($online_list['kf_online_list'] as $key => $val) {
                            if ($config['customer'] == $val['kf_account'] && $val['status'] > 0 && $val['accepted_case'] < $val['auto_accept']) {
                                $customer = $config['customer'];
                            } else {
                                $customer = '';
                            }
                        }
                    }
                    
                    $this->weObj->transfer_customer_service($customer)->reply();
                    $result = true;
                }
            }
        }
        return $result;
    }

    
    public function close_kf($openid, $keywords)
    {
        $openid = $this->model->table('wechat_user')->where(['openid' => $openid, 'wechat_id' => $this->wechat_id])->getField('openid');
        if ($openid) {
            $kfsession = $this->weObj->getKFSession($openid);
            if ($keywords == 'q' && isset($kfsession['kf_account']) && !empty($kfsession['kf_account'])) {
                $rs = $this->weObj->closeKFSession($openid, $kfsession['kf_account'], '客户已主动关闭多客服');
                if ($rs) {
                    $msg = [
                        'touser' => $openid,
                        'msgtype' => 'text',
                        'text' => [
                            'content' => '您已退出多客服系统'
                        ]
                    ];
                    $this->weObj->sendCustomMessage($msg);
                    return true;
                }
            }
        }
        return false;
    }

    
    public function record_msg($fromusername, $keywords, $is_wechat_admin = 0)
    {
        $uid = dao('wechat_user')->where(['openid' => $fromusername, 'wechat_id' => $this->wechat_id])->getField('uid');
        if ($uid) {
            $data['uid'] = $uid;
            $data['msg'] = $keywords;
            $data['wechat_id'] = $this->wechat_id;
            $data['send_time'] = gmtime();
            
            if ($is_wechat_admin) {
                $data['is_wechat_admin'] = $is_wechat_admin;
            }
            dao('wechat_custom_message')->data($data)->add();
        }
    }

    
    public function actionPluginShow()
    {
        if (is_wechat_browser() && (!isset($_SESSION['unionid']) || empty($_SESSION['unionid']) || empty($_SESSION['openid']))) {
            $back_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : __HOST__ . $_SERVER['REQUEST_URI'];
            $this->redirect('oauth/index/index', ['type' => 'wechat', 'back_url' => urlencode($back_url)]);
        }
        $plugin_name = I('get.name', '', 'trim');
        $ru_id = I('ru_id', 0, 'intval');
        $ru_id = !empty($ru_id) ? $ru_id : $_COOKIE['ectouch_ru_id'];
        $file = MODULE_PATH . 'Plugins/' . ucfirst($plugin_name) . '/' . ucfirst($plugin_name) . '.php';
        if (file_exists($file)) {
            include_once($file);
            $new_plugin = '\\App\\Modules\\Wechat\\Plugins\\' . ucfirst($plugin_name) . '\\' . ucfirst($plugin_name);
            $cfg = ['ru_id' => $ru_id];
            $wechat = new $new_plugin($cfg);
            $wechat->html_show();
        }
    }

    
    public function actionPluginAction()
    {
        $plugin_name = I('get.name', '', 'trim');
        $ru_id = I('ru_id', 0, 'intval');
        $ru_id = !empty($ru_id) ? $ru_id : $_COOKIE['ectouch_ru_id'];
        $file = MODULE_PATH . 'Plugins/' . ucfirst($plugin_name) . '/' . ucfirst($plugin_name) . '.php';
        if (file_exists($file)) {
            include_once($file);
            $new_plugin = '\\App\\Modules\\Wechat\\Plugins\\' . ucfirst($plugin_name) . '\\' . ucfirst($plugin_name);
            $cfg = ['ru_id' => $ru_id];
            $wechat = new $new_plugin($cfg);
            $wechat->executeAction();
        }
    }

    
    public function actionMarketShow()
    {
        if (is_wechat_browser() && (!isset($_SESSION['unionid']) || empty($_SESSION['unionid']) || empty($_SESSION['openid']))) {
            $back_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : __HOST__ . $_SERVER['REQUEST_URI'];
            $this->redirect('oauth/index/index', ['type' => 'wechat', 'back_url' => urlencode($back_url)]);
        }
        $market_type = I('request.type', '', 'trim');
        $function = I('request.function', '', 'trim');
        $ru_id = I('request.ru_id', 0, 'intval');
        $ru_id = !empty($ru_id) ? $ru_id : $_COOKIE['ectouch_ru_id'];

        $file = MODULE_PATH . 'Market/' . ucfirst($market_type) . '/' . ucfirst($market_type) . '.php';
        if (file_exists($file) && !empty($function)) {
            include_once($file);
            $market = '\\App\\Modules\\Wechat\\Market\\' . ucfirst($market_type) . '\\' . ucfirst($market_type);

            $cfg['ru_id'] = $ru_id;

            $wechat = new $market($cfg);

            $function_name = 'action' . camel_cases($function, 1);

            $wechat->$function_name();
        }
    }

    
    private function get_config($secret_key = '')
    {
        $config = dao('wechat')
            ->field('id, token, appid, appsecret, encodingaeskey, ru_id')
            ->where(['secret_key' => $secret_key, 'status' => 1])
            ->find();
        if (empty($config)) {
            $config = [];
        }
        return $config;
    }

    
    public function check_auth()
    {
        $appid = I('get.appid');
        $appsecret = I('get.appsecret');
        if (empty($appid) || empty($appsecret)) {
            echo json_encode(['errmsg' => '信息不完整，请提供完整信息', 'errcode' => 1]);
            exit;
        }
        $config = dao('wechat')
            ->field('token, appid, appsecret')
            ->where(['appid' => $appid, 'appsecret' => $appsecret, 'status' => 1])
            ->find();
        if (empty($config)) {
            echo json_encode(['errmsg' => '信息错误，请检查提供的信息', 'errcode' => 1]);
            exit;
        }
        $obj = new Wechat($config);
        $access_token = $obj->checkAuth();
        if ($access_token) {
            echo json_encode(['access_token' => $access_token, 'errcode' => 0]);
            exit;
        } else {
            echo json_encode(['errmsg' => $obj->errmsg, 'errcode' => $obj->errcode]);
            exit;
        }
    }

    
    public static function snsapi_base($ru_id = 0)
    {
        $where = ['ru_id' => $ru_id, 'status' => 1];
        $wxinfo = dao('wechat')->field('token, appid, appsecret')->where($where)->find();

        $callback = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : __HOST__ . $_SERVER['REQUEST_URI'];
        
        $info = parse_url($callback);
        $back_url = url('/', [], false, true);
        if (isset($info['query'])) {
            parse_str($info['query'], $param);
            
            unset($param['code']);
            unset($param['state']);
            $back_url .= '?'. http_build_query($param, '', '&');
        }

        if (!empty($wxinfo) && !empty($wxinfo['appid']) && is_wechat_browser() && (empty($_SESSION['openid']) || empty($_COOKIE['openid']))) {
            $config = [
                'appid' => $wxinfo['appid'],
                'appsecret' => $wxinfo['appsecret'],
                'token' => $wxinfo['token'],
            ];
            $obj = new Wechat($config);
            
            if (isset($_GET['code']) && $_GET['state'] == 'repeat') {
                $token = $obj->getOauthAccessToken();
                $_SESSION['openid'] = $token['openid'];
                cookie('openid', $token['openid'], gmtime() + 3600 * 24);

                $userinfo['openid'] = $token['openid'];
                
                
                self::update_seller_wechat($userinfo, $ru_id);
                redirect($back_url);
                return;
            }
            $url = $obj->getOauthRedirect($callback, 'repeat', 'snsapi_base');
            
            redirect($url);
        }
    }

    
    public function send_custom_message($openid = 0, $msgtype = '', $data)
    {
        $msg = [];
        if ($msgtype == 'text') {
            $msg = [
                'touser' => $openid,
                'msgtype' => 'text',
                'text' => [
                    'content' => $data
                ]
            ];
        } elseif ($msgtype == 'image') {
            $msg = [
                'touser' => $openid,
                'msgtype' => 'image',
                'image' => [
                    'media_id' => $data
                ]
            ];
        } elseif ($msgtype == 'voice') {
            $msg = [
                'touser' => $openid,
                'msgtype' => 'voice',
                'voice' => [
                    'media_id' => $data
                ]
            ];
        } elseif ($msgtype == 'video') {
            $msg = [
                'touser' => $openid,
                'msgtype' => 'video',
                'video' => [
                    'media_id' => $data['media_id'],
                    'thumb_media_id' => $data['media_id'],
                    'title' => $data['title'],
                    'description' => $data['description']
                ]
            ];
        } elseif ($msgtype == 'music') {
            $msg = [
                'touser' => $openid,
                'msgtype' => 'music',
                'music' => [
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'musicurl' => $data['musicurl'],
                    'hqmusicurl' => $data['hqmusicurl'],
                    'thumb_media_id' => $data['thumb_media_id']
                ]
            ];
        } elseif ($msgtype == 'news') {
            
            array_key_case($data); 
            $newsData = $data;
            $msg = [
                'touser' => $openid,
                'msgtype' => 'news',
                'news' => [
                    'articles' => $newsData,
                ]
            ];
        }

        $this->weObj->sendCustomMessage($msg);
    }

    
    public function update_seller_wechat($info, $ru_id = 0)
    {
        
        if ($ru_id > 0) {
            $wechat_id = dao('wechat')->where(['status' => 1, 'ru_id' => $ru_id])->getField('id');
            
            $info['wechat_id'] = $wechat_id;
            $where = ['openid' => $info['openid'], 'wechat_id' => $wechat_id];
            $res = dao('wechat_user')->where($where)->find();
            
            if (!empty($res)) {
                dao('wechat_user')->data($info)->where($where)->save();
            } else {
                dao('wechat_user')->data($info)->add();
            }
        }
    }

}