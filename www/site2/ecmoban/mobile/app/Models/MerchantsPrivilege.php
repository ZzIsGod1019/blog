<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class MerchantsPrivilege extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'merchants_privilege';
	public $timestamps = false;
	protected $fillable = array('action_list', 'grade_id');
	protected $guarded = array();

	public function getActionList()
	{
		return $this->action_list;
	}

	public function getGradeId()
	{
		return $this->grade_id;
	}

	public function setActionList($value)
	{
		$this->action_list = $value;
		return $this;
	}

	public function setGradeId($value)
	{
		$this->grade_id = $value;
		return $this;
	}
}

?>
