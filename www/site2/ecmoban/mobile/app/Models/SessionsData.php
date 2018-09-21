<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class SessionsData extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'sessions_data';
	protected $primaryKey = 'sesskey';
	public $timestamps = false;
	protected $fillable = array('expiry', 'data');
	protected $guarded = array();

	public function getExpiry()
	{
		return $this->expiry;
	}

	public function getData()
	{
		return $this->data;
	}

	public function setExpiry($value)
	{
		$this->expiry = $value;
		return $this;
	}

	public function setData($value)
	{
		$this->data = $value;
		return $this;
	}
}

?>
