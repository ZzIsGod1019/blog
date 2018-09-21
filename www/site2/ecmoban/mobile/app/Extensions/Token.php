<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace App\Extensions;

class Token
{
	private $key;
	private $iv;

	public function __construct($key)
	{
		$this->key = hash('MD5', $key, true);
		$this->iv = chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0) . chr(0);
	}

	public function encrypt($value)
	{
		$str = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $this->key, $value, MCRYPT_MODE_CBC, $this->iv);
		return base64_encode($str);
	}

	public function decrypt($value)
	{
		$str = base64_decode($value);
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $this->key, $str, MCRYPT_MODE_CBC, $this->iv);
	}
}


?>
