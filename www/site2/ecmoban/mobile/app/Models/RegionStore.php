<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class RegionStore extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'region_store';
	protected $primaryKey = 'rs_id';
	public $timestamps = false;
	protected $fillable = array('rs_name');
	protected $guarded = array();

	public function getRsName()
	{
		return $this->rs_name;
	}

	public function setRsName($value)
	{
		$this->rs_name = $value;
		return $this;
	}
}

?>
