<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class DeliveryGoods extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'delivery_goods';
	protected $primaryKey = 'rec_id';
	public $timestamps = false;
	protected $fillable = array('delivery_id', 'goods_id', 'product_id', 'product_sn', 'goods_name', 'brand_name', 'goods_sn', 'is_real', 'extension_code', 'parent_id', 'send_number', 'goods_attr');
	protected $guarded = array();

	public function getDeliveryId()
	{
		return $this->delivery_id;
	}

	public function getGoodsId()
	{
		return $this->goods_id;
	}

	public function getProductId()
	{
		return $this->product_id;
	}

	public function getProductSn()
	{
		return $this->product_sn;
	}

	public function getGoodsName()
	{
		return $this->goods_name;
	}

	public function getBrandName()
	{
		return $this->brand_name;
	}

	public function getGoodsSn()
	{
		return $this->goods_sn;
	}

	public function getIsReal()
	{
		return $this->is_real;
	}

	public function getExtensionCode()
	{
		return $this->extension_code;
	}

	public function getParentId()
	{
		return $this->parent_id;
	}

	public function getSendNumber()
	{
		return $this->send_number;
	}

	public function getGoodsAttr()
	{
		return $this->goods_attr;
	}

	public function setDeliveryId($value)
	{
		$this->delivery_id = $value;
		return $this;
	}

	public function setGoodsId($value)
	{
		$this->goods_id = $value;
		return $this;
	}

	public function setProductId($value)
	{
		$this->product_id = $value;
		return $this;
	}

	public function setProductSn($value)
	{
		$this->product_sn = $value;
		return $this;
	}

	public function setGoodsName($value)
	{
		$this->goods_name = $value;
		return $this;
	}

	public function setBrandName($value)
	{
		$this->brand_name = $value;
		return $this;
	}

	public function setGoodsSn($value)
	{
		$this->goods_sn = $value;
		return $this;
	}

	public function setIsReal($value)
	{
		$this->is_real = $value;
		return $this;
	}

	public function setExtensionCode($value)
	{
		$this->extension_code = $value;
		return $this;
	}

	public function setParentId($value)
	{
		$this->parent_id = $value;
		return $this;
	}

	public function setSendNumber($value)
	{
		$this->send_number = $value;
		return $this;
	}

	public function setGoodsAttr($value)
	{
		$this->goods_attr = $value;
		return $this;
	}
}

?>
