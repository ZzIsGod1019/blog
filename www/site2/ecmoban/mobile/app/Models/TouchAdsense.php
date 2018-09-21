<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class TouchAdsense extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'touch_adsense';
	public $timestamps = false;
	protected $fillable = array('from_ad', 'referer', 'clicks');
	protected $guarded = array();

	public function getFromAd()
	{
		return $this->from_ad;
	}

	public function getReferer()
	{
		return $this->referer;
	}

	public function getClicks()
	{
		return $this->clicks;
	}

	public function setFromAd($value)
	{
		$this->from_ad = $value;
		return $this;
	}

	public function setReferer($value)
	{
		$this->referer = $value;
		return $this;
	}

	public function setClicks($value)
	{
		$this->clicks = $value;
		return $this;
	}
}

?>