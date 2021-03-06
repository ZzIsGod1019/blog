<?php

namespace App\Modules\Wechat\Market\Qrpay;

use Think\Image;
use App\Extensions\Http;
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

        $total = dao('qrpay_manage')->where(['ru_id' => $this->ru_id])->count();

        $list = dao('qrpay_manage')->field('id, qrpay_name, type, discount_id, tag_id, qrpay_status, qrpay_code, add_time')->where(['ru_id' => $this->ru_id])->order('id DESC')->limit($offset)->select();
        if ($list[0]['id']) {
            foreach ($list as $k => $v) {
                $list[$k]['add_time'] = local_date('Y-m-d H:i:s', $v['add_time']);
                $list[$k]['type'] = $v['type'] == 1 ? '指定金额收款码' : '自助收款码';
                $list[$k]['qrpay_code'] = get_wechat_image_path($v['qrpay_code']);
                $list[$k]['discounts_name'] = $v['discount_id'] > 0 ? $this->get_qrpay_discounts($v['discount_id']) : '-';
                $list[$k]['tag_name'] = $v['tag_id'] > 0 ? $this->get_qrpay_tag($v['tag_id']) : '-';
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

            $id = I('post.id');
            $data = I('post.data');

            if (empty($data['qrpay_name']) || strlen($data['qrpay_name']) >= 32) {
                $json_result = ['error' => 1, 'msg' => '收款码名称必填，并且须少于32个字符'];
                exit(json_encode($json_result));
            }
            
            if ($data['type'] == 2) {
                if (empty($data['amount'])) {
                    $json_result = ['error' => 1, 'msg' => '指定收款码金额不能为空'];
                    exit(json_encode($json_result));
                }
            }

            
            $data['qrpay_code'] = $this->creatQrpayCode($id);

            
            if ($id) {
                dao('qrpay_manage')->data($data)->where(['id' => $id, 'ru_id' => $this->ru_id])->save();
                $json_result = ['error' => 0, 'msg' => L('market_edit') . L('success'), 'url' => url('market_list', ['type' => $data['marketing_type']])];
                exit(json_encode($json_result));
            } else {
                
                $data['add_time'] = gmtime();
                $data['ru_id'] = $this->ru_id;
                dao('qrpay_manage')->data($data)->add();
                $json_result = ['error' => 0, 'msg' => L('market_add') . L('success'), 'url' => url('market_list', ['type' => $data['marketing_type']])];
                exit(json_encode($json_result));
            }
        }

        
        $info = [];
        $id = $this->cfg['market_id'];
        if (!empty($id)) {
            $info = dao('qrpay_manage')->field('id, qrpay_name, type, amount, discount_id, tag_id, qrpay_status, qrpay_code, add_time')->where(['id' => $id, 'ru_id' => $this->ru_id])->find();
            if ($info) {
                $info['add_time'] = local_date('Y-m-d H:i:s', $info['add_time']);
            } else {
                $this->message('数据不存在', url('market_list', ['type' => $this->marketing_type]), 2, $this->ru_id);
            }
        } else {
            $info['type'] = 0;
        }

        $info['ru_id'] = $this->ru_id;

        $discounts_list = $this->get_qrpay_discounts();
        $tag_list = $this->get_qrpay_tag();
        $this->assign('discounts_list', $discounts_list);
        $this->assign('tag_list', $tag_list);

        $this->assign('info', $info);
        $this->plugin_display('market_edit', $this->cfg);
    }

    
    public function creatQrpayCode($id = 0)
    {
        $lastId = dao('qrpay_manage')->order('id DESC')->getField('id');
        $id = $id > 0 ?  $id : $lastId + 1;

        
        $url = url('qrpay/index/index', ['id' => $id], true, true);

        
        $errorCorrectionLevel = 'Q';
        
        $matrixPointSize = 8;
        $file = dirname(ROOT_PATH) . '/data/attached/qrpay/';
        if (!file_exists($file)) {
            make_dir($file, 0777);
        }
        
        $logo = ROOT_PATH . 'public/img/timg.jpg';
        $logo_out = $file . 'qrcode_logo.png';

        $filename = $file . 'qrpay_' . $this->ru_id . $id . $errorCorrectionLevel . $matrixPointSize . '.png';
        if (!file_exists($filename)) {
            $code = QRcode::png($url, $filename, $errorCorrectionLevel, $matrixPointSize, 2);
            
            $img = new Image();
            
            $img->open($logo)->thumb(80, 80)->save($logo_out);
            
            $img->open($filename)->water($logo_out, 5, 100)->save($filename);

            
            if (C('shop.open_oss') == 1) {
                $qrpay_code = $this->ossMirror($filename, 'data/attached/qrpay/');
            }
        }
        $qrpay_code = 'data/attached/qrpay/' . basename($filename);

        return $qrpay_code;
    }

    
    public function marketResetQrpay()
    {
        if (IS_AJAX) {
            $id = I('get.id', 0, 'intval');
            $res = dao('qrpay_manage')->field('qrpay_code, qrpay_name')->where(['id' => $id, 'ru_id' => $this->ru_id])->find();
            if (!empty($res['qrpay_code'])) {
                
                $this->remove($res['qrpay_code']);
                
                $data['qrpay_code'] = $this->creatQrpayCode($id);
                dao('qrpay_manage')->data($data)->where(['id' => $id, 'ru_id' => $this->ru_id])->save();

                $json_result = ['error' => 0, 'msg' => '重置二维码' . L('success')];
                exit(json_encode($json_result));
            }
        }
    }

    
    public function marketDownloadQrpay()
    {
        $id = I('get.id', 0, 'intval');
        $res = dao('qrpay_manage')->field('qrpay_code, qrpay_name')->where(['id' => $id, 'ru_id' => $this->ru_id])->find();

        if (empty($res)) {
            $this->message('数据不存在', null, 2, $this->ru_id);
        }

        $filename = dirname(ROOT_PATH) . '/' . $res['qrpay_code'];
        if (file_exists($filename)) {
            $type = pathinfo($filename, PATHINFO_EXTENSION);
            Http::download($filename, $res['qrpay_name'] . '.' . $type);
        } else {
            $this->message(L('file_not_exist'), null, 2, $this->ru_id);
        }
    }

    
    public function marketQrpayLogList()
    {
        $id =  input('id', 0, 'intval');
        $handler = input('get.handler', '', 'trim');
        $function = input('get.function', '', 'trim');

        if (IS_POST) {
            
            $keyword = input('keyword', '', 'trim');
            if ($keyword) {
                $map['pay_order_sn'] = ['like',['%'.$keyword.'%']];
            }
        }

        $qr_type = input('qr_type', 0, 'intval');
        if ($qr_type) {
            $map['type'] = $qr_type == 2 ? 0 : 1;
            $this->assign('qr_type', $qr_type);
        }

        $qr_tag = input('qr_tag', 0, 'intval');
        if ($qr_tag) {
            $map['tag_id'] = $qr_tag;
            $this->assign('qr_tag', $qr_tag);
        }

        $filter['type'] = $this->marketing_type;
        $filter['function'] = $function;
        $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);

        $map['l.ru_id'] = $this->ru_id;
        if ($id) {
            $map['l.qrpay_id'] = $id;
        }

        $total = dao('qrpay_log')->alias('l')
                ->join('LEFT JOIN '.C('DB_PREFIX').'qrpay_manage m on l.qrpay_id = m.id')
                ->where($map)
                ->count();

        $list = dao('qrpay_log')->alias('l')
                ->join('LEFT JOIN '.C('DB_PREFIX').'qrpay_manage m on l.qrpay_id = m.id')
                ->field('l.id, l.pay_order_sn, l.pay_amount, l.qrpay_id, l.add_time, l.pay_user_id, l.openid, l.payment_code, l.pay_status, m.type, m.tag_id, l.is_settlement, l.pay_desc')
                ->where($map)
                ->order('l.id desc, l.add_time desc')
                ->limit($offset)
                ->select();
        foreach ($list as $key => $value) {
            
            $list[$key]['add_time'] = local_date('Y-m-d H:i', $value['add_time']);
            $list[$key]['user_name'] = $this->get_user_name($value['pay_user_id'], $value['openid']);
            $list[$key]['payment_code'] = $this->get_payment_name($value['payment_code']);
            $list[$key]['pay_status'] = ($value['pay_status'] == 1) ? '已支付' : '未支付';
            $list[$key]['is_settlement'] = ($value['is_settlement'] == 1) ? '已结算' : '未结算';
            $list[$key]['qrpay_type'] = $value['type'] == 0 ? '自助收款码' : '指定金额收款码';
            $list[$key]['tag_name'] = $value['tag_id'] > 0 ? $this->get_qrpay_tag($value['tag_id']) : '-';
        }

        
        $tag_list = $this->get_qrpay_tag();
        $this->assign('tag_list', $tag_list);

        $this->assign('list', $list);
        $this->assign('page', $this->pageShow($total));
        $this->plugin_display('market_log_list', $this->cfg);
    }

    
    public function marketQrpayLogInfo()
    {
        if (IS_AJAX) {
            $json_result = ['error' => 0, 'msg' => '', 'data' => ''];

            $log_id = input('log_id', 0, 'intval');

            $info = dao('qrpay_log')->where(['id' => $log_id, 'ru_id' => $this->ru_id])->find();
            if (!empty($info)) {
                $data = unserialize($info['notify_data']);
                $data['trade_no'] = !empty($info['trade_no']) ? $info['trade_no'] : '';
                $data['amount'] = !empty($data['amount']) ? $data['amount'] : 0;
                $data['pay_time'] = !empty($data['pay_time']) ? $data['pay_time'] : '';
                $data['buyer_account'] = !empty($data['buyer_account']) ? $data['buyer_account'] : $data['buyer_id'];

                $json_result = ['error' => 0, 'msg' => '', 'data' => $data];
                exit(json_encode($json_result));
            } else {
                $json_result = ['error' => 1, 'msg' => '记录信息不存在'];
                exit(json_encode($json_result));
            }
        }
    }

    
    public function marketExportQrpayLog()
    {
        if (IS_POST) {
            $starttime = I('post.starttime', '', 'local_strtotime');
            $endtime = I('post.endtime', '', 'local_strtotime');
            $this->ru_id  = I('post.ru_id', 0, 'intval');
            if (empty($starttime) || empty($endtime)) {
                $this->message('选择时间不能为空', null, 2, $this->ru_id);
            }
            if ($starttime > $endtime) {
                $this->message('开始时间不能大于结束时间', null, 2, $this->ru_id);
            }

            if ($this->ru_id > 0) {
                $map['l.ru_id'] = $this->ru_id;
            }
            $map['l.add_time'] = ['between', [$starttime, $endtime]];

            $list = dao('qrpay_log')->alias('l')
                    ->join('LEFT JOIN '.C('DB_PREFIX').'qrpay_manage m on l.qrpay_id = m.id')
                    ->field('l.id, l.pay_order_sn, l.pay_amount, l.qrpay_id, l.add_time, l.pay_user_id, l.openid, l.payment_code, l.pay_status, m.type, m.tag_id')
                    ->where($map)
                    ->select();
            if ($list) {
                foreach ($list as $key => $value) {
                    
                    $list[$key]['add_time'] = local_date('Y-m-d H:i', $value['add_time']);
                    $list[$key]['user_name'] = $this->get_user_name($value['pay_user_id'], $value['openid']);
                    $list[$key]['payment_code'] = $this->get_payment_name($value['payment_code']);
                    $list[$key]['pay_status'] = ($value['pay_status'] == 1) ? '已支付' : '未支付';
                    if ($this->ru_id > 0) {
                        $list[$key]['is_settlement'] = ($value['is_settlement'] == 1) ? '已结算' : '未结算';
                    }
                    $list[$key]['qrpay_type'] = $value['type'] == 0 ? '自助收款码' : '指定金额收款码';
                    $list[$key]['tag_name'] = $value['tag_id'] > 0 ? $this->get_qrpay_tag($value['tag_id']) : '-';
                }
                $excel = new \PHPExcel();
                
                $excel->getActiveSheet()->getDefaultColumnDimension()->setAutoSize(true);
                
                $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $excel->getActiveSheet()->getColumnDimension('H')->setWidth(25);
                $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
                $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
                
                if ($this->ru_id > 0) {
                    $rowVal = [
                        0 => 'id',
                        1 => '收款订单号',
                        2 => '收款金额(元)',
                        3 => '收款码类型',
                        4 => '标签',
                        5 => '用户',
                        6 => '支付方式',
                        7 => '支付状态',
                        8 => '结算状态',
                        9 => '收款时间'
                    ];
                } else {
                    $rowVal = [
                        0 => 'id',
                        1 => '收款订单号',
                        2 => '收款金额(元)',
                        3 => '收款码类型',
                        4 => '标签',
                        5 => '用户',
                        6 => '支付方式',
                        7 => '支付状态',
                        8 => '收款时间'
                    ];
                }
                foreach ($rowVal as $k => $r) {
                    $excel->getActiveSheet()->getStyleByColumnAndRow($k, 1)->getFont()->setBold(true);
                    $excel->getActiveSheet()->getStyleByColumnAndRow($k, 1)->getAlignment(); 
                    $excel->getActiveSheet()->setCellValueByColumnAndRow($k, 1, $r);
                }
                
                $excel->setActiveSheetIndex(0);
                $objActSheet = $excel->getActiveSheet();
                
                $title = "收款码记录";
                $objActSheet->setTitle($title);
                
                foreach ($list as $k => $v) {
                    $num = $k + 2;
                    if ($this->ru_id > 0) {
                        $excel->setActiveSheetIndex(0)
                            
                            ->setCellValue('A' . $num, $v['id'])
                            ->setCellValue('B' . $num, $v['pay_order_sn'])
                            ->setCellValue('C' . $num, $v['pay_amount'])
                            ->setCellValue('D' . $num, $v['qrpay_type'])
                            ->setCellValue('E' . $num, $v['tag_name'])
                            ->setCellValue('F' . $num, $v['user_name'])
                            ->setCellValue('G' . $num, $v['payment_code'])
                            ->setCellValue('H' . $num, $v['pay_status'])
                            ->setCellValue('I' . $num, $v['is_settlement'])
                            ->setCellValue('J' . $num, $v['add_time']);
                    } else {
                        $excel->setActiveSheetIndex(0)
                            
                            ->setCellValue('A' . $num, $v['id'])
                            ->setCellValue('B' . $num, $v['pay_order_sn'])
                            ->setCellValue('C' . $num, $v['pay_amount'])
                            ->setCellValue('D' . $num, $v['qrpay_type'])
                            ->setCellValue('E' . $num, $v['tag_name'])
                            ->setCellValue('F' . $num, $v['user_name'])
                            ->setCellValue('G' . $num, $v['payment_code'])
                            ->setCellValue('H' . $num, $v['pay_status'])
                            ->setCellValue('I' . $num, $v['add_time']);
                    }
                }
                $name = date('Y-m-d'); 
                header("Content-Type: application/force-download");
                header("Content-Type: application/octet-stream");
                header("Content-Type: application/download");
                header("Content-Transfer-Encoding:utf-8");
                header("Pragma: no-cache");
                header('Content-Type: application/vnd.ms-e xcel');
                header('Content-Disposition: attachment;filename="' . $title . '_' . urlencode($name) . '.xls"');
                header('Cache-Control: max-age=0');
                $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel5');
                $objWriter->save('php://output');
                exit;
            } else {
                $this->message('该时间段没有要导出的数据，请重新选择', null, 2, $this->ru_id);
            }
        }

        $url = url('data_list', ['type' => $this->marketing_type, 'function' => 'qrpay_log_list']);
        $this->redirect($url);
    }

    
    public function marketQrpayDiscounts()
    {
        
        $handler = I('get.handler', '', 'trim');
        $function = I('get.function', '', 'trim');

        if ($handler && $handler == 'edit') {
            if (IS_POST) {
                $json_result = ['error' => 0, 'msg' => '', 'url' => '']; 

                $id = I('post.id', 0, 'intval');
                $data = I('post.data');

                
                if ($id) {
                    dao('qrpay_discounts')->data($data)->where(['id' => $id, 'ru_id' => $this->ru_id])->save();
                    $json_result = [
                        'error' => 0,
                        'msg' => L('wechat_editor') . L('success'),
                        'url' => url('data_list', ['type' => $this->marketing_type, 'function' => $function])
                    ];
                    exit(json_encode($json_result));
                } else {
                    $data['add_time'] = gmtime();
                    $data['ru_id'] = $this->ru_id;
                    $data['status'] = 1;
                    dao('qrpay_discounts')->data($data)->add();
                    $json_result = [
                        'error' => 0,
                        'msg' => L('add') . L('success'),
                        'url' => url('data_list', ['type' => $this->marketing_type, 'function' => $function])
                    ];
                    exit(json_encode($json_result));
                }
            }

            
            $id = I('id', 0, 'intval');
            $info = dao('qrpay_discounts')->where(['id' => $id, 'ru_id' => $this->ru_id])->find();
            if (!empty($id)) {
                if (empty($info)) {
                    $this->message('数据不存在', url('data_list', ['type' => $this->marketing_type, 'function' => $function]), 2, $this->ru_id);
                }
            }
            $info['ru_id'] = $this->ru_id;
            $this->assign('info', $info);
            $this->plugin_display('market_discounts_edit', $this->cfg);
            exit();
        }

        
        $filter['type'] = $this->marketing_type;
        $filter['function'] = $function;
        $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);

        $total = dao('qrpay_discounts')->where(['ru_id' => $this->ru_id])->count();

        $list = dao('qrpay_discounts')->where(['ru_id' => $this->ru_id])->order('id desc, add_time desc')->limit($offset)->select();
        foreach ($list as $key => $value) {
            $list[$key]['status_fromat'] = $value['status'] == 1 ? '正在进行' : '已失效';
            $list[$key]['dis_name'] = "满" . $value['min_amount'] . "减" . $value['discount_amount'];
        }

        
        $disabled_num = dao('qrpay_discounts')->where(['status' => 1, 'ru_id' => $this->ru_id])->count();
        $this->assign('disabled_num', $disabled_num);

        $this->assign('page', $this->pageShow($total));
        $this->assign('list', $list);
        $this->plugin_display('market_discounts', $this->cfg);
    }

    
    public function marketQrpayTagList()
    {
        
        $handler = I('get.handler', '', 'trim');
        $function = I('get.function', '', 'trim');

        if ($handler && $handler == 'edit') {
            if (IS_POST) {
                $json_result = ['error' => 0, 'msg' => '', 'url' => '']; 

                $id = I('post.id', 0, 'intval');
                $data = I('post.data');

                
                if ($id) {
                    dao('qrpay_tag')->data($data)->where(['id' => $id, 'ru_id' => $this->ru_id])->save();
                    $json_result = [
                        'error' => 0,
                        'msg' => L('wechat_editor') . L('success'),
                        'url' => url('data_list', ['type' => $this->marketing_type, 'function' => $function])
                    ];
                    exit(json_encode($json_result));
                } else {
                    $data['add_time'] = gmtime();
                    $data['ru_id'] = $this->ru_id;
                    dao('qrpay_tag')->data($data)->add();
                    $json_result = [
                        'error' => 0,
                        'msg' => L('add') . L('success'),
                        'url' => url('data_list', ['type' => $this->marketing_type, 'function' => $function])
                    ];
                    exit(json_encode($json_result));
                }
            }
            
            $id = I('get.id', 0, 'intval');
            $info = dao('qrpay_tag')->field('id, tag_name')->where(['id' => $id, 'ru_id' => $this->ru_id])->find();
            if (!empty($id)) {
                if (empty($info)) {
                    $this->message('数据不存在', url('data_list', ['type' => $this->marketing_type, 'function' => $function]), 2, $this->ru_id);
                }
            }
            $info['ru_id'] = $this->ru_id;
            $this->assign('info', $info);
            $this->plugin_display('market_tag_edit', $this->cfg);
            exit();
        }

        $filter['type'] = $this->marketing_type;
        $filter['function'] = $function;
        $offset = $this->pageLimit(url('data_list', $filter), $this->page_num);

        $total = dao('qrpay_tag')->where(['ru_id' => $this->ru_id])->count();
        
        $list = dao('qrpay_tag')->where(['ru_id' => $this->ru_id])->field('id, tag_name, add_time')->order('id desc, add_time desc')->limit($offset)->select();
        foreach ($list as $key => $value) {
            $list[$key]['add_time'] = local_date('Y-m-d H:i:s', $value['add_time']);
            $list[$key]['self_qrpay_num'] = $this->get_self_qrpay_num($value['id']);
            $list[$key]['fixed_qrpay_num'] = $this->get_fixed_qrpay_num($value['id']);
        }
        $this->assign('page', $this->pageShow($total));
        $this->assign('list', $list);
        $this->plugin_display('market_tag_list', $this->cfg);
    }

    
    public function executeAction()
    {
        if (IS_AJAX) {
            $json_result = ['error' => 0, 'msg' => '', 'url' => ''];

            $handler = I('get.handler', '', 'trim');

            
            if ($handler && $handler == 'qr_delete') {
                $id = I('get.id', 0, 'intval');
                if (!empty($id)) {
                    dao('qrpay_manage')->where(['id' => $id, 'ru_id' => $this->ru_id])->delete();
                    $json_result['msg'] = '删除成功！';
                    exit(json_encode($json_result));
                } else {
                    $json_result['msg'] = '删除失败！';
                    exit(json_encode($json_result));
                }
            }

            
            if ($handler && $handler == 'disabled') {
                $id = I('get.id', 0, 'intval');
                if (!empty($id)) {
                    dao('qrpay_discounts')->data(['status' => 0])->where(['id' => $id, 'ru_id' => $this->ru_id])->save();
                    exit(json_encode($json_result));
                }
            }

            
            if ($handler && $handler == 'tag_delete') {
                $id = I('get.id', 0, 'intval');
                if (!empty($id)) {
                    dao('qrpay_tag')->where(['id' => $id, 'ru_id' => $this->ru_id])->delete();
                    $json_result['msg'] = '删除成功！';
                    exit(json_encode($json_result));
                } else {
                    $json_result['msg'] = '删除失败！';
                    exit(json_encode($json_result));
                }
            }

            
            if ($handler && $handler == 'log_delete') {
                $log_id = I('get.log_id', 0, 'intval');
                if (!empty($log_id)) {
                    dao('qrpay_log')->where(['id' => $log_id, 'ru_id' => $this->ru_id])->delete();
                    $json_result['msg'] = '删除成功！';
                    exit(json_encode($json_result));
                } else {
                    $json_result['msg'] = '删除失败！';
                    exit(json_encode($json_result));
                }
            }

            
            if ($handler && $handler == 'is_settlement') {
                $log_id = I('get.log_id', 0, 'intval');
                if (!empty($log_id)) {
                    $re = insert_seller_account_log($log_id);
                    $json_result['msg'] = $re == true ? '结算成功！' : '结算失败！' ;
                    exit(json_encode($json_result));
                } else {
                    $json_result['msg'] = '结算失败！';
                    exit(json_encode($json_result));
                }
            }
        }
    }

    
    public function get_qrpay_tag($id = 0)
    {
        if ($id > 0) {
            $res = dao('qrpay_tag')->field('tag_name')->where(['id' => $id, 'ru_id' => $this->ru_id])->find();
            return $res['tag_name'];
        }

        $list = dao('qrpay_tag')
            ->where(['ru_id' => $this->ru_id])
            ->field('id, tag_name')
            ->order('id desc, add_time desc')
            ->select();
        return $list;
    }

    
    public function get_qrpay_discounts($id = 0)
    {
        if ($id > 0) {
            $res = dao('qrpay_discounts')
                ->field('min_amount, discount_amount, max_discount_amount')
                ->where(['status' => 1, 'id' => $id, 'ru_id' => $this->ru_id])
                ->find();
            if (empty($res)) {
                return '已失效';
            }
            return $res['dis_name'] = "满" . $res['min_amount'] . "减" . $res['discount_amount'];
        }

        $list = dao('qrpay_discounts')
            ->where(['ru_id' => $this->ru_id])
            ->field('id, min_amount, discount_amount, max_discount_amount')
            ->where(['status' => 1, 'ru_id' => $this->ru_id])
            ->select();
        foreach ($list as $key => $value) {
            $list[$key]['dis_name'] = "满" . $value['min_amount'] . "减" . $value['discount_amount'];
        }
        return $list;
    }

    
    public function get_self_qrpay_num($tag_id = 0)
    {
        $num = dao('qrpay_manage')->where(['ru_id' => $this->ru_id, 'tag_id' => $tag_id, 'type' => 0])->count();
        return $num;
    }

    
    public function get_fixed_qrpay_num($tag_id = 0)
    {
        $num = dao('qrpay_manage')->where(['ru_id' => $this->ru_id, 'tag_id' => $tag_id, 'type' => 1])->count();
        return $num;
    }

    
    public function get_user_name($user_id, $openid = '')
    {
        if (!empty($openid)) {
            $users = dao('users')->alias('u')
                ->join(C('DB_PREFIX').'wechat_user w ON w.ect_uid = u.user_id')
                ->field('user_name, nickname')
                ->where(['openid' => $openid])
                ->find();
        } else {
            $users = dao('users')->field('user_name, nick_name as nickname')->where(['user_id' => $user_id])->find();
        }

        if (!empty($users)) {
            $user_name = !empty($users['nickname']) ? $users['nickname'] : $users['user_name'];
        } else {
            $user_name = '匿名用户';
        }

        return $user_name;
    }

    
    public function get_payment_name($code)
    {
        return dao('payment')->where(['pay_code' => $code])->getField('pay_name');
    }

    
    public function get_qrpay_name($qrpay_id)
    {
        return dao('qrpay_manage')->where(['id' => $qrpay_id, 'ru_id' => $this->ru_id])->getField('qrpay_name');
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
