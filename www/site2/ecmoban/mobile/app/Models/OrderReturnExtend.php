<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class OrderReturnExtend extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'order_return_extend';
	public $timestamps = false;
	protected $fillable = array('ret_id', 'return_number');
	protected $guarded = array();

	public function getRetId()
	{
		return $this->ret_id;
	}

	public function getReturnNumber()
	{
		return $this->return_number;
	}

	public function setRetId($value)
	{
		$this->ret_id = $value;
		return $this;
	}

	public function setReturnNumber($value)
	{
		$this->return_number = $value;
		return $this;
	}
}

?>
