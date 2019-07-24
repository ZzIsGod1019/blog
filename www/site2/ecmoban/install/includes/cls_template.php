<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
class template
{
	/**
    * 用来存储变量的空间
    *
    * @access  private
    * @var     array      $vars
    */
	public $vars = array();
	/**
    * 模板存放的目录路径
    *
    * @access  private
    * @var     string      $path
    */
	public $path = '';

	public function __construct($path)
	{
		$this->template($path);
	}

	public function template($path)
	{
		$this->path = $path;
	}

	public function assign($name, $value)
	{
		$this->vars[$name] = $value;
	}

	public function fetch($file)
	{
		extract($this->vars);
		ob_start();
		include $this->path . $file;
		$contents = ob_get_contents();
		ob_end_clean();
		return $contents;
	}

	public function display($file)
	{
		echo $this->fetch($file);
	}
}

if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

?>
