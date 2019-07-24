<?php


/* 访问控制 */
define('BIND_MODULE', 'Respond');
define('BIND_CONTROLLER', 'Index');
define('BIND_ACTION', 'notify');


if (!defined('_PHP_FILE_')) {
	define('_PHP_FILE_', rtrim($_SERVER['SCRIPT_NAME'], '/'));
}

if (!defined('__ROOT__')) {
	$_root = rtrim(dirname(_PHP_FILE_), '/');
	$_root = rtrim(str_replace('public/notify', '', $_root), '/');
	define('__ROOT__', ($_root == '/') || ($_root == '\\') ? '' : $_root);
}




$_GET['code'] = basename(__FILE__, '.php');
require __DIR__ . '/../../index.php';