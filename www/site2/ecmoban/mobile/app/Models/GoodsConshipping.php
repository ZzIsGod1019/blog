<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class GoodsConshipping extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'goods_conshipping';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'sfull', 'sreduce');
	protected $guarded = array();

	public function getGoodsId()
	{
		return $this->goods_id;
	}

	public function getSfull()
	{
		return $this->sfull;
	}

	public function getSreduce()
	{
		return $this->sreduce;
	}

	public function setGoodsId($value)
	{
		$this->goods_id = $value;
		return $this;
	}

	public function setSfull($value)
	{
		$this->sfull = $value;
		return $this;
	}

	public function setSreduce($value)
	{
		$this->sreduce = $value;
		return $this;
	}
}

?>
