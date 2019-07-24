<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Repositories\Goods;

class GoodsRepository implements \App\Contracts\Repositories\Goods\GoodsRepositoryInterface
{
	protected $goods;
	private $field;
	private $userRankRepository;
	private $authService;
	private $memberPriceRepository;
	private $goodsAttrRepository;
	private $volumePriceRepository;
	private $shopConfigRepository;

	public function __construct(\App\Repositories\User\UserRankRepository $userRankRepository, \App\Services\AuthService $authService, \App\Repositories\User\MemberPriceRepository $memberPriceRepository, GoodsAttrRepository $goodsAttrRepository, VolumePriceRepository $volumePriceRepository, \App\Repositories\ShopConfig\ShopConfigRepository $shopConfigRepository)
	{
		$this->setField();
		$this->userRankRepository = $userRankRepository;
		$this->authService = $authService;
		$this->memberPriceRepository = $memberPriceRepository;
		$this->goodsAttrRepository = $goodsAttrRepository;
		$this->volumePriceRepository = $volumePriceRepository;
		$this->shopConfigRepository = $shopConfigRepository;
	}

	public function create(array $data)
	{
	}

	public function get($id)
	{
	}

	public function update(array $data)
	{
	}

	public function delete($id)
	{
	}

	public function search(array $data)
	{
	}

	public function sku($id)
	{
	}

	public function skuAdd()
	{
	}

	public function setField()
	{
		$this->field = array('category' => 'cat_id');
	}

	public function getField($field)
	{
		return $this->field[$field];
	}

	public function find($goods_id)
	{
		return \App\Models\Goods::select('*')->where('goods_id', $goods_id)->first()->toArray();
	}

	public function findBy($field, $value, $page = 1, $size = 10, $columns = array('*'), $keywords = '', $sortKey = '', $sortVal = '')
	{
		$field = $this->getField($field);
		$begin = ($page - 1) * $size;
		$goods = \App\Models\Goods::select($columns);

		if ($value != 0) {
			$goods->where($field, $value);
		}

		if (!empty($keywords)) {
			$goods->where('goods_name', 'like', '%' . $keywords . '%');
		}

		$sort = array('ASC', 'DESC');

		if (!empty($sortKey)) {
			switch ($sortKey) {
			case 0:
				$goods->orderby('goods_id', 'ASC');
				break;

			case 1:
				$goods->orderby('sales_volume', in_array($sortVal, $sort) ? $sortVal : 'ASC');
				$goods->orderby('goods_id', in_array($sortVal, $sort) ? $sortVal : 'ASC');
				break;

			case 2:
				$goods->orderby('shop_price', in_array($sortVal, $sort) ? $sortVal : 'ASC');
				$goods->orderby('goods_id', in_array($sortVal, $sort) ? $sortVal : 'ASC');
				break;
			}
		}

		$res = $goods->offset($begin)->where('is_on_sale', 1)->where('is_delete', 0)->limit($size)->get()->toArray();
		return $res;
	}

	public function findByType($type = 'best', $size = 10)
	{
		switch ($type) {
		case 'hot':
			$type = 'is_hot';
			break;

		case 'new':
			$type = 'is_new';
			break;

		default:
			$type = 'is_best';
			break;
		}

		$goods = \App\Models\Goods::select('goods_id', 'cat_id', 'user_cat', 'user_id', 'goods_sn', 'goods_name', 'click_count', 'brand_id', 'provider_name', 'goods_number', 'goods_weight', 'default_shipping', 'market_price', 'cost_price', 'shop_price', 'promote_price', 'promote_start_date', 'promote_end_date', 'warn_number', 'keywords', 'goods_brief', 'goods_desc', 'desc_mobile', 'goods_thumb', 'goods_img', 'original_img', 'is_real', 'extension_code', 'is_on_sale', 'is_alone_sale', 'is_shipping', 'integral', 'add_time', 'sort_order', 'is_delete', 'is_best', 'is_new', 'is_hot', 'is_promote', 'is_volume', 'is_fullcut', 'bonus_type_id', 'last_update', 'goods_type', 'seller_note', 'give_integral', 'rank_integral', 'suppliers_id', 'is_check', 'store_hot', 'store_new', 'store_best', 'group_number', 'is_xiangou', 'xiangou_start_date', 'xiangou_end_date', 'xiangou_num', 'review_status', 'review_content', 'goods_shipai', 'comments_number', 'sales_volume', 'comment_num', 'model_price', 'model_inventory', 'model_attr', 'largest_amount', 'pinyin_keyword', 'goods_product_tag', 'goods_tag', 'stages', 'stages_rate', 'freight', 'shipping_fee', 'tid', 'goods_unit', 'goods_cause', 'dis_commission', 'is_distribution')->where($type, 1)->where('is_on_sale', 1)->where('is_delete', 0)->orderby('goods_id', 'desc')->limit($size)->get()->toArray();
		return $goods;
	}

	public function goodsInfo($id)
	{
		$res = \App\Models\Goods::select('goods_id', 'goods_name', 'shop_price as goods_price', 'market_price', 'goods_number as stock', 'goods_desc', 'desc_mobile', 'sales_volume as sales', 'goods_thumb', 'model_attr', 'goods_type', 'user_id', 'is_on_sale', 'promote_price', 'product_price', 'product_promote_price', 'promote_start_date', 'promote_end_date')->where('goods_id', $id)->where('is_delete', 0)->first();

		if ($res === null) {
			return array();
		}

		return $res->toArray();
	}

	public function goodsProperties($goods_id, $warehouse_id = 0, $area_id = 0)
	{
		$res = $this->goodsAttrRepository->goodsAttr($goods_id);
		$group = $this->goodsAttrRepository->attrGroup($goods_id);

		if (!empty($group)) {
			$groups = explode('\\n', $group);
		}

		$attrTypeDesc = array('唯一属性', '单选属性');
		$properties = array();

		foreach ($res as $k => $v) {
			$v['attr_value'] = str_replace("\n", '<br />', $v['attr_value']);

			if ($v['attr_type'] == 0) {
				$group = isset($groups[$v['attr_group']]) ? $groups[$v['attr_group']] : '';
				$properties['spe'][$group][$v['attr_id']]['name'] = $v['attr_name'];
				$properties['spe'][$group][$v['attr_id']]['value'] = $v['attr_value'];
			}
			else {
				$properties['pro'][$v['attr_id']]['attr_type'] = $attrTypeDesc[$v['attr_type']];
				$properties['pro'][$v['attr_id']]['name'] = $v['attr_name'];
				$properties['pro'][$v['attr_id']]['values'][] = array('label' => $v['attr_value'], 'attr_sort' => $v['attr_sort'], 'price' => $v['attr_price'], 'format_price' => price_format(abs($v['attr_price']), false), 'id' => $v['goods_attr_id']);
			}
		}

		return $properties;
	}

	public function goodsGallery($id)
	{
		return \App\Models\GoodsGallery::select('img_url')->where('goods_id', $id)->get()->toArray();
	}

	public function goodsComment($id)
	{
		$res = \App\Models\Comment::select('comment_id as id', 'user_id', 'content', 'add_time', 'comment_rank')->where('id_value', $id)->orderby('comment_id', 'DESC')->get()->toArray();
		return $res;
	}

	public function getGoodsCommentUser($user_id)
	{
		$user = \App\Models\Users::select('nick_name', 'user_name')->where('user_id', $user_id)->first()->toArray();

		if ($user === null) {
			return array();
		}

		$user['nick_name'] = !empty($user['nick_name']) ? $user['nick_name'] : $user['user_name'];
		return $user['nick_name'];
	}

	public function getProductByGoods($goodsId, $goodsAttr)
	{
		$product = \App\Models\Products::select('product_id as id', 'product_sn')->where('goods_id', $goodsId)->where('goods_attr', $goodsAttr)->first();

		if ($product === null) {
			return array();
		}

		return $product->toArray();
	}

	public function cartGoods($rec_id)
	{
		$goods = \App\Models\Goods::join('cart', 'goods.goods_id', '=', 'cart.goods_id')->where('cart.rec_id', $rec_id)->select('goods.goods_name', 'goods.goods_number', 'cart.product_id')->first();

		if ($goods === null) {
			return array();
		}

		return $goods->toArray();
	}

	public function getFinalPrice($goods_id, $goods_num = '1', $is_spec_price = false, $property = array(), $warehouse_id = 0, $area_id = 0)
	{
		$final_price = 0;
		$volume_price = 0;
		$promote_price = 0;
		$user_price = 0;
		$spec_price = 0;

		if ($is_spec_price) {
			$spec_price = $this->goodsPropertyPrice($goods_id, $property, $warehouse_id, $area_id);
		}

		$price_list = $this->getVolumePriceList($goods_id, '1');

		if (!empty($price_list)) {
			foreach ($price_list as $value) {
				if ($value['number'] <= $goods_num) {
					$volume_price = $value['price'];
				}
			}
		}

		$goods = \App\Models\Goods::from('goods as g')->select('g.promote_price', 'g.promote_start_date', 'g.promote_end_date', 'mp.user_price')->leftjoin('member_price as mp', 'mp.goods_id', '=', 'g.goods_id')->where('g.goods_id', $goods_id)->where('g.is_delete', 0)->first()->toArray();
		$member_price = $this->userRankRepository->getMemberRankPriceByGid($goods_id);
		$uid = $this->authService->authorization();
		$user_rank = \App\Models\Users::select('user_rank')->where('user_id', $uid)->first();

		if (!empty($user_rank)) {
			$user_rank = $user_rank->user_rank;
			$user_price = $this->memberPriceRepository->getMemberPriceByUid($user_rank, $goods_id);
			$goods['user_price'] = $user_price;
		}

		$goods['shop_price'] = isset($user_price) && !empty($user_price) ? $user_price : $member_price;

		if ($this->shopConfigRepository->getShopConfigByCode('add_shop_price') == 0) {
			$goods['promote_price'] = $this->goodsPropertyPrice($goods_id, $property, $warehouse_id, $area_id, 'product_promote_price');
		}

		if (is_array($goods) && array_key_exists('promote_price', $goods) && 0 < $goods['promote_price']) {
			$promote_price = $this->bargainPrice($goods['promote_price'], $goods['promote_start_date'], $goods['promote_end_date']);
		}
		else {
			$promote_price = 0;
		}

		$user_price = $goods['shop_price'];
		if (empty($volume_price) && empty($promote_price)) {
			$final_price = $user_price;
		}
		else {
			if (!empty($volume_price) && empty($promote_price)) {
				$final_price = min($volume_price, $user_price);
			}
			else {
				if (empty($volume_price) && !empty($promote_price)) {
					$final_price = min($promote_price, $user_price);
				}
				else {
					if (!empty($volume_price) && !empty($promote_price)) {
						$final_price = min($volume_price, $promote_price, $user_price);
					}
					else {
						$final_price = $user_price;
					}
				}
			}
		}

		if ($is_spec_price) {
			if (!empty($property)) {
				if ($this->shopConfigRepository->getShopConfigByCode('add_shop_price') == 1) {
					$final_price += $spec_price;
				}
			}
		}

		if ($this->shopConfigRepository->getShopConfigByCode('add_shop_price') == 0) {
			if ($promote_price == 0) {
				$final_price = $spec_price;
			}
		}

		return $final_price;
	}

	public function getVolumePriceList($goods_id, $price_type = '1')
	{
		$volume_price = array();
		$temp_index = '0';
		$res = $this->volumePriceRepository->allVolumes($goods_id, $price_type);

		foreach ($res as $k => $v) {
			$volume_price[$temp_index] = array();
			$volume_price[$temp_index]['number'] = $v['volume_number'];
			$volume_price[$temp_index]['price'] = $v['volume_price'];
			$volume_price[$temp_index]['format_price'] = price_format($v['volume_price']);
			$temp_index++;
		}

		return $volume_price;
	}

	public function bargainPrice($price, $start, $end)
	{
		if ($price == 0) {
			return 0;
		}
		else {
			$time = local_gettime();
			if ($start <= $time && $time <= $end) {
				return $price;
			}
			else {
				return 0;
			}
		}
	}

	public function getBrandIdByGoodsId($goodsId)
	{
		$brandId = \App\Models\Goods::where('goods_id', $goodsId)->pluck('brand_id');
		return !empty($brandId) ? $brandId[0] : 0;
	}

	public function goodsAttrNumber($goods_id, $attr_id, $warehouse_id = 0, $area_id = 0, $store_id = 0)
	{
		$goods = $this->goodsInfo($goods_id);
		$products = $this->getProductsAttrNumber($goods_id, $attr_id, $warehouse_id, $area_id, $goods['model_attr'], $store_id);
		$prod = $this->goodsWarehouseNumber($goods_id, $warehouse_id, $area_id, $goods['model_attr'], $store_id);

		if (empty($products)) {
			if (empty($prod)) {
				$attr_number = !empty($goods['stock']) ? $goods['stock'] : 0;
			}
			else {
				$attr_number = $prod['product_number'];
			}
		}
		else {
			$attr_number = $products['product_number'];
		}

		return !empty($attr_number) ? $attr_number : 0;
	}

	public function getProductsAttrNumber($goods_id, $attr_id, $warehouse_id, $area_id, $model_attr = 0, $store_id = 0)
	{
		if (empty($attr_id)) {
			$attr_id = 0;
		}
		else {
			if (is_string($attr_id)) {
				$attr_arr = explode(',', $attr_id);
			}
			else {
				$attr_arr = $attr_id;
			}

			foreach ($attr_arr as $key => $val) {
				$attr_type = $this->getGoodsAttrId($val);
				if (($attr_type == 0 || $attr_type == 2) && $attr_arr[$key]) {
					unset($attr_arr[$key]);
				}
			}

			$attr_id = implode('|', $attr_arr);
		}

		if (0 < $store_id) {
			$product_number = \App\Models\StoreProducts::select('product_number')->where('goods_id', $goods_id)->where('goods_attr', $attr_id)->where('store_id', $store_id)->first();
		}
		else if ($model_attr == 1) {
			$product_number = \App\Models\ProductsWarehouse::select('product_number')->where('goods_id', $goods_id)->where('goods_attr', $attr_id)->where('warehouse_id', $warehouse_id)->first();
		}
		else if ($model_attr == 2) {
			$product_number = \App\Models\ProductsArea::select('product_number')->where('goods_id', $goods_id)->where('goods_attr', $attr_id)->where('area_id', $area_id)->first();
		}
		else {
			$product_number = \App\Models\Products::select('product_number')->where('goods_id', $goods_id)->where('goods_attr', $attr_id)->first();
		}

		if ($product_number === null) {
			return array();
		}

		return $product_number->toArray();
	}

	public function goodsWarehouseNumber($goods_id, $warehouse_id, $area_id, $model_attr = 0, $store_id = 0)
	{
		if (0 < $store_id) {
			$product_number = \App\Models\StoreGoods::select('goods_number')->where('goods_id', $goods_id)->where('goods_attr', $attr_id)->where('store_id', $store_id)->first();
		}
		else if ($model_attr == 1) {
			$product_number = \App\Models\WarehouseGoods::select('region_number as product_number')->where('goods_id', $goods_id)->where('region_id', $warehouse_id)->first();
		}
		else if ($model_attr == 2) {
			$product_number = \App\Models\WarehouseAreaGoods::select('region_number as product_number')->where('goods_id', $goods_id)->where('region_id', $area_id)->first();
		}
		else {
			$product_number = \App\Models\Goods::select('goods_number as product_number')->where('goods_id', $goods_id)->first();
		}

		if ($product_number === null) {
			return array();
		}

		return $product_number->toArray();
	}

	public function goodsPropertyPrice($goods_id, $attr_id, $warehouse_id = 0, $area_id = 0, $field = '')
	{
		$goods = $this->goodsInfo($goods_id);
		$products = $this->getProductsAttrPrice($goods_id, $attr_id, $warehouse_id, $area_id, $goods['model_attr']);
		$prod = $this->goodsWarehousePrice($goods_id, $warehouse_id, $area_id, $goods['model_attr']);

		if ($field == 'product_promote_price') {
			if (empty($products) || $products['product_promote_price'] <= 0) {
				if (empty($prod) || $prod['product_promote_price'] <= 0) {
					$attr_price = !empty($goods['promote_price']) ? $goods['promote_price'] : 0;
				}
				else {
					$attr_price = $prod['product_promote_price'];
				}
			}
			else {
				$attr_price = $products['product_promote_price'];
			}
		}
		else {
			if (empty($products) || $products['product_price'] <= 0) {
				if (empty($prod) || $prod['product_price'] <= 0) {
					$attr_price = !empty($goods['shop_price']) ? $goods['shop_price'] : 0;
				}
				else {
					$attr_price = $prod['product_price'];
				}
			}
			else {
				$attr_price = $products['product_price'];
			}
		}

		return !empty($attr_price) ? $attr_price : 0;
	}

	public function goodsMarketPrice($goods_id, $attr_id, $warehouse_id = 0, $area_id = 0)
	{
		$goods = $this->goodsInfo($goods_id);
		$products = $this->getProductsAttrPrice($goods_id, $attr_id, $warehouse_id, $area_id, $goods['model_attr']);
		if (empty($products) || $products['product_price'] <= 0) {
			$market_price = !empty($goods['market_price']) ? $goods['market_price'] : 0;
		}
		else {
			$attr_price = $products['product_price'];

			if ($this->shopConfigRepository->getShopConfigByCode('add_shop_price') == 1) {
				$market_price = $attr_price + $goods['market_price'];
			}
			else {
				$market_price = !empty($products['product_market_price']) ? $products['product_market_price'] : 0;
			}
		}

		return !empty($market_price) ? $market_price : 0;
	}

	public function getProductsAttrPrice($goods_id, $attr_id, $warehouse_id, $area_id, $model_attr = 0)
	{
		if (empty($attr_id)) {
			$attr_id = 0;
		}
		else {
			if (is_string($attr_id)) {
				$attr_arr = explode(',', $attr_id);
			}
			else {
				$attr_arr = $attr_id;
			}

			foreach ($attr_arr as $key => $val) {
				$attr_type = $this->getGoodsAttrId($val);
				if (($attr_type == 0 || $attr_type == 2) && $attr_arr[$key]) {
					unset($attr_arr[$key]);
				}
			}

			$attr_id = implode('|', $attr_arr);
		}

		if ($this->shopConfigRepository->getShopConfigByCode('goods_attr_price') == 1) {
			if ($model_attr == 1) {
				$product_price = \App\Models\ProductsWarehouse::select('product_price', 'product_promote_price', 'product_market_price')->where('goods_id', $goods_id)->where('goods_attr', $attr_id)->where('warehouse_id', $warehouse_id)->first();
			}
			else if ($model_attr == 2) {
				$product_price = \App\Models\ProductsArea::select('product_price', 'product_promote_price', 'product_market_price')->where('goods_id', $goods_id)->where('goods_attr', $attr_id)->where('area_id', $area_id)->first();
			}
			else {
				$product_price = \App\Models\Products::select('product_price', 'product_promote_price', 'product_market_price')->where('goods_id', $goods_id)->where('goods_attr', $attr_id)->first();
			}

			if ($product_price === null) {
				return array();
			}

			return $product_price->toArray();
		}
	}

	public function goodsWarehousePrice($goods_id, $warehouse_id, $area_id, $model_attr = 0)
	{
		if ($model_attr == 1) {
			$product_price = \App\Models\WarehouseGoods::select('warehouse_price as product_price', 'warehouse_promote_price as product_promote_price')->where('goods_id', $goods_id)->where('region_id', $warehouse_id)->first();
		}
		else if ($model_attr == 2) {
			$product_price = \App\Models\WarehouseAreaGoods::select('region_price as product_price', 'region_promote_price as product_promote_price')->where('goods_id', $goods_id)->where('region_id', $area_id)->first();
		}
		else {
			$product_price = \App\Models\Goods::select('shop_price as product_price', 'promote_price as product_promote_price')->where('goods_id', $goods_id)->first();
		}

		if ($product_price === null) {
			return array();
		}

		return $product_price->toArray();
	}

	public function getGoodsAttrId($goods_attr_id)
	{
		$res = \App\Models\GoodsAttr::from('goods_attr as ga')->select('a.attr_type')->join('attribute as a', 'ga.attr_id', '=', 'a.attr_id')->where('ga.goods_attr_id', $goods_attr_id)->first();

		if ($res === null) {
			return array();
		}

		return $res['attr_type'];
	}

	public function getAttrImgFlie($goods_id, $attr_id = 0)
	{
		$attr_id = !empty($attr_id) ? $attr_id[0] : 0;
		$res = \App\Models\GoodsAttr::select('attr_img_flie')->where('goods_id', $goods_id)->where('goods_attr_id', $attr_id)->first();

		if ($res === null) {
			return array();
		}

		return $res->toArray();
	}

	public function getGoodsTransport($tid)
	{
		$res = $this->getTransport($tid);

		if (0 < count($res)) {
			return $res[0];
		}

		return array();
	}

	public function getTransport($tid)
	{
		$transportList = \Illuminate\Support\Facades\Cache::get('goods_transport_' . $tid);

		if (empty($transportList)) {
			$transportList = \App\Models\GoodsTransport::where('tid', $tid)->get()->toArray();
			\Illuminate\Support\Facades\Cache::put('goods_transport_' . $tid, $transportList, 60);
		}

		return $transportList;
	}

	public function getGoodsOneAttrPrice($goods_id)
	{
		$goods = $this->goodsInfo($goods_id);
		$goods_product = array('product_price' => $goods['product_price'], 'product_promote_price' => $goods['product_promote_price']);
		$products = array();
		$shop_price = $goods['goods_price'];
		$promote_price = $goods['promote_price'];
		if ($this->shopConfigRepository->getShopConfigByCode('add_shop_price') == 0 && $goods['model_attr'] == 0) {
			$time = gmtime();
			if ($goods_product && 0 < $goods_product['product_price']) {
				$user_rank = $this->userRankRepository->getUserRankByUid();
				if ($user_rank && $user_rank['discount']) {
					$shop_price = $goods_product['product_price'] * $user_rank['discount'];
				}
				else {
					$shop_price = $goods_product['product_price'];
				}

				if ($goods['promote_start_date'] <= $time && $time <= $goods['promote_end_date']) {
					$promote_price = $goods_product['product_promote_price'];
				}
			}
		}

		return 0 < $promote_price ? $promote_price : $shop_price;
	}
}

?>
