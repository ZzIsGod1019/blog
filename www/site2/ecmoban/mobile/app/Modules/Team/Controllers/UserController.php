<?php

namespace App\Modules\Team\Controllers;

use App\Modules\Base\Controllers\FrontendController;

class UserController extends FrontendController
{
    private $region_id = 0;
    private $area_info = [];

    
    public function __construct()
    {
        parent::__construct();
        L(require(LANG_PATH . C('shop.lang') . '/team.php'));
        $files = [
            'order',
            'clips',
            'payment',
            'transaction'
        ];
        $this->load_helper($files);

        $this->user_id = $_SESSION['user_id'];
        $this->page = 1;
        $this->size = 10;
        $this->check_login();
        $this->check_refund();
    }

    
    public function actionIndex()
    {
        $this->page = I('page', 1, 'intval');
        $type = I('type', 2, 'intval');
        if (IS_AJAX) {
            $goods_list = my_team_goods($this->user_id, $type, $this->page, $this->size);
            exit(json_encode(['list' => $goods_list['list'], 'totalPage' => $goods_list['totalpage']]));
        }
        $this->assign('type', $type);
        $this->assign('page_title', L('my_team_order'));
        $this->display();
    }

    
    private function check_refund(){

        $where = " and t.status < 1 and ('" . gmtime() . "' > (t.start_time+(tg.validity_time*3600)) || tg.is_team != 1)";
        $sql = "select o.order_id,o.order_sn,o.order_status,o.user_id,o.pay_status,o.goods_amount,o.shipping_fee,t.goods_id,t.team_id,t.start_time,t.status,tg.validity_time,tg.team_num,tg.team_price,tg.limit_num from " . $GLOBALS['ecs']->table('order_info') . " as o left join " . $GLOBALS['ecs']->table('team_log') . " as t on o.team_id = t.team_id left join " . $GLOBALS['ecs']->table('team_goods') . " as tg on t.t_id = tg.id " . " where  o.extension_code ='team_buy' and pay_status = '" . PS_PAYED . "'  and t.is_show = 1 $where  ORDER BY o.add_time ASC ";
        $goods_list = $GLOBALS['db']->getAll($sql);

        if($goods_list){
            foreach ($goods_list as $key => $vo) {
                $amount = $vo['goods_amount']+$vo['shipping_fee'];  

                
                $info ='拼团订单自动退款到余额，金钱：'. $amount;
                team_log_account_change($vo['user_id'], -$amount, 0, 0, 0, $info, ACT_TRANSFERRED);

                
                $order_status = 2;
                $action_note = '拼团订单自动退款';
                team_order_action_change($vo['order_id'],'admin',$order_status,0,0,$action_note);

            }
        }
    }


    
    private function check_login()
    {
        if (!($_SESSION['user_id'] > 0)) {
            $url = urlencode(__HOST__ . $_SERVER['REQUEST_URI']);
            if (IS_POST) {
                $url = urlencode($_SERVER['HTTP_REFERER']);
            }
            ecs_header("Location: " . url('user/login/index', ['back_act' => $url]));
            exit;
        }
    }
}
