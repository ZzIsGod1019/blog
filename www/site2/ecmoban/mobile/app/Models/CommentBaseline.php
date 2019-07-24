<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class CommentBaseline extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'comment_baseline';
	public $timestamps = false;
	protected $fillable = array('goods', 'service', 'shipping');
	protected $guarded = array();

	public function getGoods()
	{
		return $this->goods;
	}

	public function getService()
	{
		return $this->service;
	}

	public function getShipping()
	{
		return $this->shipping;
	}

	public function setGoods($value)
	{
		$this->goods = $value;
		return $this;
	}

	public function setService($value)
	{
		$this->service = $value;
		return $this;
	}

	public function setShipping($value)
	{
		$this->shipping = $value;
		return $this;
	}
}

?>
