<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class GoodsConsumption extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'goods_consumption';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'cfull', 'creduce');
	protected $guarded = array();

	public function getGoodsId()
	{
		return $this->goods_id;
	}

	public function getCfull()
	{
		return $this->cfull;
	}

	public function getCreduce()
	{
		return $this->creduce;
	}

	public function setGoodsId($value)
	{
		$this->goods_id = $value;
		return $this;
	}

	public function setCfull($value)
	{
		$this->cfull = $value;
		return $this;
	}

	public function setCreduce($value)
	{
		$this->creduce = $value;
		return $this;
	}
}

?>
