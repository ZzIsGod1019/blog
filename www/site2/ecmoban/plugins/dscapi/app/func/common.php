<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
namespace app\func;

class common
{
	static private $format = 'json';
	static private $page_size = 10;
	static private $page = 1;
	static private $charset = 'utf-8';
	static private $result;
	static private $msg;
	static private $error;
	static private $allowOutputType = array('xml' => 'application/xml', 'json' => 'application/json', 'html' => 'text/html');

	public function __construct($data = array())
	{
		self::common($data);
	}

	static public function common($data = array())
	{
		self::$format = isset($data['format']) ? $data['format'] : 'josn';
		self::$page_size = isset($data['page_size']) ? $data['page_size'] : 10;
		self::$page = isset($data['page']) ? $data['page'] : 1;
		self::$msg = isset($data['msg']) ? $data['msg'] : '';
		self::$result = isset($data['result']) ? $data['result'] : 'success';
		self::$error = isset($data['error']) ? $data['error'] : 0;
	}

	static public function data_back($info = array(), $arr_type = 0)
	{
		if ($arr_type == 1) {
			$list = self::page_array(self::$page_size, self::$page, $info);
			$info = $list;
		}

		$data_arr = array('result' => self::$result, 'error' => self::$error, 'msg' => self::$msg);

		if ($info) {
			$data_arr['info'] = $info;
		}

		$data_arr = self::to_utf8_iconv($data_arr);

		if (self::$format == 'xml') {
			if (isset(self::$allowOutputType[self::$format])) {
				header('Content-Type: ' . self::$allowOutputType[self::$format] . '; charset=' . self::$charset);
			}

			return self::xml_encode($data_arr);
		}
		else {
			if (isset(self::$allowOutputType[self::$format])) {
				header('Content-Type: ' . self::$allowOutputType[self::$format] . '; charset=' . self::$charset);
			}

			return json_encode($data_arr);
		}
	}

	static public function xml_encode($data, $root = 'dsc', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
	{
		if (is_array($attr)) {
			$_attr = array();

			foreach ($attr as $key => $value) {
				$_attr[] = $key . '="' . $value . '"';
			}

			$attr = implode(' ', $_attr);
		}

		$attr = trim($attr);
		$attr = empty($attr) ? '' : ' ' . $attr;
		$xml = '<?xml version="1.0" encoding="' . $encoding . '"?>';
		$xml .= '<' . $root . $attr . '>';
		$xml .= self::data_to_xml($data, $item, $id);
		$xml .= '</' . $root . '>';
		return $xml;
	}

	static public function data_to_xml($data, $item = 'item', $id = 'id')
	{
		$xml = $attr = '';

		foreach ($data as $key => $val) {
			if (is_numeric($key)) {
				$id && ($attr = ' ' . $id . '="' . $key . '"');
				$key = $item;
			}

			$xml .= '<' . $key . $attr . '>';
			$xml .= is_array($val) || is_object($val) ? self::data_to_xml($val, $item, $id) : $val;
			$xml .= '</' . $key . '>';
		}

		return $xml;
	}

	static public function to_utf8_iconv($str)
	{
		if (EC_CHARSET != 'utf-8') {
			if (is_string($str)) {
				return ecs_iconv(EC_CHARSET, 'utf-8', $str);
			}
			else if (is_array($str)) {
				foreach ($str as $key => $value) {
					$str[$key] = to_utf8_iconv($value);
				}

				return $str;
			}
			else if (is_object($str)) {
				foreach ($str as $key => $value) {
					$str->$key = to_utf8_iconv($value);
				}

				return $str;
			}
			else {
				return $str;
			}
		}

		return $str;
	}

	static public function page_array($page_size = 1, $page = 1, $array = array(), $order = 0)
	{
		$arr = array();
		$pagedata = array();

		if ($array) {
			global $countpage;
			$start = ($page - 1) * $page_size;

			if ($order == 1) {
				$array = array_reverse($array);
			}

			if (isset($array['record_count'])) {
				$totals = $array['record_count'];
				$countpage = ceil($totals / $page_size);
				$pagedata = $array['list'];
			}
			else {
				$totals = count($array);
				$countpage = ceil($totals / $page_size);
				$pagedata = array_slice($array, $start, $page_size);
			}

			$filter = array('page' => $page, 'page_size' => $page_size, 'record_count' => $totals, 'page_count' => $countpage);
			$arr = array('list' => $pagedata, 'filter' => $filter, 'page_count' => $countpage, 'record_count' => $totals);
		}

		return $arr;
	}

	static public function get_reference_only($table, $where = 1, $select = '', $type = 0)
	{
		if (!empty($select) && is_array($select)) {
			$select = implode(',', $select);
		}
		else {
			$select = '*';
		}

		$sql = 'SELECT ' . $select . ' FROM ' . $GLOBALS['ecs']->table($table) . (' WHERE ' . $where);

		if ($type == 1) {
			return $GLOBALS['db']->getRow($sql);
		}
		else {
			return $GLOBALS['db']->getOne($sql);
		}
	}

	public static function __callStatic($method, $arguments)
	{
		return call_user_func_array(array(self, $method), $arguments);
	}
}


?>
