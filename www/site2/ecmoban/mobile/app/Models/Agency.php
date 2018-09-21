<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class Agency extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'agency';
	protected $primaryKey = 'agency_id';
	public $timestamps = false;
	protected $fillable = array('agency_name', 'agency_desc');
	protected $guarded = array();

	public function getAgencyName()
	{
		return $this->agency_name;
	}

	public function getAgencyDesc()
	{
		return $this->agency_desc;
	}

	public function setAgencyName($value)
	{
		$this->agency_name = $value;
		return $this;
	}

	public function setAgencyDesc($value)
	{
		$this->agency_desc = $value;
		return $this;
	}
}

?>
