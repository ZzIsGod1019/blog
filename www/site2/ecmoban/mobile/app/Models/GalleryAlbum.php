<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Models;

class GalleryAlbum extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'gallery_album';
	protected $primaryKey = 'album_id';
	public $timestamps = false;
	protected $fillable = array('parent_album_id', 'ru_id', 'album_mame', 'album_cover', 'album_desc', 'sort_order', 'add_time');
	protected $guarded = array();

	public function getParentAlbumId()
	{
		return $this->parent_album_id;
	}

	public function getRuId()
	{
		return $this->ru_id;
	}

	public function getAlbumMame()
	{
		return $this->album_mame;
	}

	public function getAlbumCover()
	{
		return $this->album_cover;
	}

	public function getAlbumDesc()
	{
		return $this->album_desc;
	}

	public function getSortOrder()
	{
		return $this->sort_order;
	}

	public function getAddTime()
	{
		return $this->add_time;
	}

	public function setParentAlbumId($value)
	{
		$this->parent_album_id = $value;
		return $this;
	}

	public function setRuId($value)
	{
		$this->ru_id = $value;
		return $this;
	}

	public function setAlbumMame($value)
	{
		$this->album_mame = $value;
		return $this;
	}

	public function setAlbumCover($value)
	{
		$this->album_cover = $value;
		return $this;
	}

	public function setAlbumDesc($value)
	{
		$this->album_desc = $value;
		return $this;
	}

	public function setSortOrder($value)
	{
		$this->sort_order = $value;
		return $this;
	}

	public function setAddTime($value)
	{
		$this->add_time = $value;
		return $this;
	}
}

?>
