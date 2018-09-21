<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Services;

class PaymentService
{
	public $payList;
	private $orderRepository;
	private $shopConfigRepository;
	private $accountRepository;
	private $shopService;
	private $WxappConfigRepository;

	public function __construct(\App\Repositories\Order\OrderRepository $orderRepository, \App\Repositories\ShopConfig\ShopConfigRepository $shopConfigRepository, \App\Repositories\User\AccountRepository $accountRepository, ShopService $shopService, \App\Repositories\Wechat\WxappConfigRepository $WxappConfigRepository)
	{
		$this->orderRepository = $orderRepository;
		$this->shopConfigRepository = $shopConfigRepository;
		$this->accountRepository = $accountRepository;
		$this->shopService = $shopService;
		$this->WxappConfigRepository = $WxappConfigRepository;
		$this->payList = array('order' => 'order.pay', 'account' => 'account.pay');
	}

	public function payment($args)
	{
		$shopName = $this->shopConfigRepository->getShopConfigByCode('shop_name');
		$order = $this->orderRepository->find($args['id']);
		$orderGoods = $this->orderRepository->getOrderGoods($args['id']);
		$ruName = $this->shopService->getShopName($orderGoods[0]['ru_id']);

		switch ($args['code']) {
		case $this->payList['order']:
			$new = array('open_id' => $args['open_id'], 'body' => $ruName . '-订单编号' . $order['order_sn'], 'out_trade_no' => $order['order_sn'], 'total_fee' => $order['order_amount'] * 100);
			break;

		case $this->payList['account']:
			$account = $this->accountRepository->getDepositInfo($args['id']);
			$new = array('open_id' => $args['open_id'], 'body' => $shopName . '-订单编号' . $order['order_sn'], 'out_trade_no' => date('Ymd') . 'A' . str_pad($account['id'], 6, '0', STR_PAD_LEFT), 'total_fee' => $account['amount'] * 100);
			break;

		default:
			$new = array('open_id' => $args['open_id'], 'body' => $shopName . '-订单编号' . $order['order_sn'], 'out_trade_no' => 'out_trade_no', 'total_fee' => 'total_fee');
			break;
		}

		return $this->WxPay($new);
	}

	private function WxPay($args)
	{
		$wxpay = new Wxpay\WxPay();
		$code = 'wxpay';
		$config = array('app_id' => $this->WxappConfigRepository->getWxappConfigByCode('wx_appid'), 'app_secret' => $this->WxappConfigRepository->getWxappConfigByCode('wx_appsecret'), 'mch_key' => $this->WxappConfigRepository->getWxappConfigByCode('wx_mch_key'), 'mch_id' => $this->WxappConfigRepository->getWxappConfigByCode('wx_mch_id'));
		$wxpay->init($config['app_id'], $config['app_secret'], $config['mch_key']);
		$nonce_str = 'ibuaiVcKdpRxkhJA';
		$time_stamp = (string) gmtime();
		$inputParams = array('appid' => $config['app_id'], 'mch_id' => $config['mch_id'], 'openid' => $args['open_id'], 'device_info' => '1000', 'nonce_str' => $nonce_str, 'body' => $args['body'], 'attach' => $args['body'], 'out_trade_no' => $args['out_trade_no'], 'total_fee' => $args['total_fee'], 'spbill_create_ip' => app('request')->getClientIp(), 'notify_url' => \Illuminate\Support\Facades\URL::to('api/wx/payment/notify', array('code', $code)), 'trade_type' => 'JSAPI');
		$inputParams['sign'] = $wxpay->createMd5Sign($inputParams);
		$prepayid = $wxpay->sendPrepay($inputParams);
		$pack = 'prepay_id=' . $prepayid;
		$prePayParams = array('appId' => $config['app_id'], 'timeStamp' => $time_stamp, 'package' => $pack, 'nonceStr' => $nonce_str, 'signType' => 'MD5');
		$sign = $wxpay->createMd5Sign($prePayParams);
		$body = array('appid' => $config['app_id'], 'mch_id' => $config['mch_id'], 'prepay_id' => $prepayid, 'nonce_str' => $nonce_str, 'timestamp' => $time_stamp, 'packages' => $pack, 'sign' => $sign);
		return array('wxpay' => $body);
	}

	public function notify($args)
	{
		$uid = $args['uid'];
		$orderId = $args['id'];
		$idsArr = array();
		$order = $this->orderRepository->find($orderId);
		if (empty($order['user_id']) || $order['user_id'] != $uid) {
			return array('code' => 1, 'msg' => '不是你的订单');
		}

		array_unshift($idsArr, $orderId);

		if ($order['main_order_id'] == 0) {
			$ids = $this->orderRepository->getChildOrder($order['order_id']);
			$idsArr = array_column($ids, 'order_id');
		}

		$res = $this->orderRepository->orderPay($uid, $idsArr);

		if ($res === 0) {
			return array('code' => 1, 'msg' => '没有任何修改');
		}

		return $res;
	}
}


?>
