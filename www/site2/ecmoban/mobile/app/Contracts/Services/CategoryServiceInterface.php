<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Contracts\Services;

interface CategoryServiceInterface
{
	public function create();

	public function get();

	public function update();

	public function delete();

	public function search();

	public function category();
}


?>
