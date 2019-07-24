<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class GoodsReportType extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'goods_report_type';
	protected $primaryKey = 'type_id';
	public $timestamps = false;
	protected $fillable = array('type_name', 'type_desc', 'is_show');
	protected $guarded = array();

	public function getTypeName()
	{
		return $this->type_name;
	}

	public function getTypeDesc()
	{
		return $this->type_desc;
	}

	public function getIsShow()
	{
		return $this->is_show;
	}

	public function setTypeName($value)
	{
		$this->type_name = $value;
		return $this;
	}

	public function setTypeDesc($value)
	{
		$this->type_desc = $value;
		return $this;
	}

	public function setIsShow($value)
	{
		$this->is_show = $value;
		return $this;
	}
}

?>
