<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class CouponsUser extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'coupons_user';
	protected $primaryKey = 'uc_id';
	public $timestamps = false;
	protected $fillable = array('user_id', 'cou_id', 'is_use', 'uc_sn', 'order_id', 'is_use_time');
	protected $guarded = array();

	public function getUserId()
	{
		return $this->user_id;
	}

	public function getCouId()
	{
		return $this->cou_id;
	}

	public function getIsUse()
	{
		return $this->is_use;
	}

	public function getUcSn()
	{
		return $this->uc_sn;
	}

	public function getOrderId()
	{
		return $this->order_id;
	}

	public function getIsUseTime()
	{
		return $this->is_use_time;
	}

	public function setUserId($value)
	{
		$this->user_id = $value;
		return $this;
	}

	public function setCouId($value)
	{
		$this->cou_id = $value;
		return $this;
	}

	public function setIsUse($value)
	{
		$this->is_use = $value;
		return $this;
	}

	public function setUcSn($value)
	{
		$this->uc_sn = $value;
		return $this;
	}

	public function setOrderId($value)
	{
		$this->order_id = $value;
		return $this;
	}

	public function setIsUseTime($value)
	{
		$this->is_use_time = $value;
		return $this;
	}
}

?>
