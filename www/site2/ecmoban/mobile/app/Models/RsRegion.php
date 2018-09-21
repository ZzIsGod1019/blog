<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class RsRegion extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'rs_region';
	public $timestamps = false;
	protected $fillable = array('rs_id', 'region_id');
	protected $guarded = array();

	public function getRsId()
	{
		return $this->rs_id;
	}

	public function getRegionId()
	{
		return $this->region_id;
	}

	public function setRsId($value)
	{
		$this->rs_id = $value;
		return $this;
	}

	public function setRegionId($value)
	{
		$this->region_id = $value;
		return $this;
	}
}

?>
