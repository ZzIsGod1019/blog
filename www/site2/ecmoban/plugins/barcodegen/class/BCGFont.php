<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
interface BCGFont
{
	public function getText();

	public function setText($text);

	public function getRotationAngle();

	public function setRotationAngle($rotationDegree);

	public function getBackgroundColor();

	public function setBackgroundColor($backgroundColor);

	public function getForegroundColor();

	public function setForegroundColor($foregroundColor);

	public function getDimension();

	public function draw($im, $x, $y);
}


?>