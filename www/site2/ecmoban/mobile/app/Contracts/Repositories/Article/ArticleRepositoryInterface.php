<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Contracts\Repositories\Article;

interface ArticleRepositoryInterface
{
	public function all($cat_id, $columns, $page, $offset);

	public function detail($id);

	public function create($data);

	public function update($data);

	public function delete($id);
}


?>
