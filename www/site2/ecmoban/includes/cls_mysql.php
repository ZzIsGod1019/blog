<?php
/*QQ:2172298892  瑾梦网络  禁止倒卖 一经发现停止任何服务https://www.dscmall.cn*/
class cls_mysql
{
	public $link_id;
	public $settings = array();
	public $queryCount = 0;
	public $queryTime = '';
	public $queryLog = array();
	public $max_cache_time = 300;
	public $cache_data_dir = 'temp/query_caches/';
	public $root_path = '';
	public $error_message = array();
	public $platform = '';
	public $version = '';
	public $dbhash = '';
	public $starttime = 0;
	public $timeline = 0;
	public $timezone = 0;
	public $mysql_config_cache_file_time = 0;
	public $mysql_disable_cache_tables = array();
	public $dbname;

	public function __construct($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'gbk', $pconnect = 0, $quiet = 0)
	{
		$this->cls_mysql($dbhost, $dbuser, $dbpw, $dbname, $charset, $pconnect, $quiet);
		$this->dbname = $dbname;
	}

	public function cls_mysql($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'gbk', $pconnect = 0, $quiet = 0)
	{
		if (defined('EC_CHARSET')) {
			$charset = strtolower(str_replace('-', '', EC_CHARSET));
		}

		if (defined('ROOT_PATH') && !$this->root_path) {
			$this->root_path = ROOT_PATH;
		}

		if ($quiet) {
			$this->connect($dbhost, $dbuser, $dbpw, $dbname, $charset, $pconnect, $quiet);
		}
		else {
			$this->settings = array('dbhost' => $dbhost, 'dbuser' => $dbuser, 'dbpw' => $dbpw, 'dbname' => $dbname, 'charset' => $charset, 'pconnect' => $pconnect);
		}
	}

	public function connect($dbhost, $dbuser, $dbpw, $dbname = '', $charset = 'utf8', $pconnect = 0, $quiet = 0)
	{
		if ($pconnect) {
			if (!($this->link_id = @mysql_pconnect($dbhost, $dbuser, $dbpw))) {
				if (!$quiet) {
					$this->ErrorMsg('Can\'t pConnect MySQL Server(' . $dbhost . ')!');
				}

				return false;
			}
		}
		else {
			if ('4.2' <= PHP_VERSION) {
				$this->link_id = @mysql_connect($dbhost, $dbuser, $dbpw, true);
			}
			else {
				$this->link_id = @mysql_connect($dbhost, $dbuser, $dbpw);
				mt_srand((double) microtime() * 1000000);
			}

			if (!$this->link_id) {
				if (!$quiet) {
					$this->ErrorMsg('Can\'t Connect MySQL Server(' . $dbhost . ')!');
				}

				return false;
			}
		}

		$this->dbhash = md5($this->root_path . $dbhost . $dbuser . $dbpw . $dbname);
		$this->version = mysql_get_server_info($this->link_id);

		if ('4.1' < $this->version) {
			if ($charset != 'latin1') {
				mysql_query('SET character_set_connection=' . $charset . ', character_set_results=' . $charset . ', character_set_client=binary', $this->link_id);
			}

			if ('5.0.1' < $this->version) {
				mysql_query('SET sql_mode=\'\'', $this->link_id);
			}
		}

		$sqlcache_config_file = $this->root_path . $this->cache_data_dir . 'sqlcache_config_file_' . $this->dbhash . '.php';
		@include $sqlcache_config_file;
		$this->starttime = time();
		if ($this->max_cache_time && $this->mysql_config_cache_file_time + $this->max_cache_time < $this->starttime) {
			if ($dbhost != '.') {
				$result = mysql_query('SHOW VARIABLES LIKE \'basedir\'', $this->link_id);
				$row = mysql_fetch_assoc($result);
				if (!empty($row['Value'][1]) && $row['Value'][1] == ':' && !empty($row['Value'][2]) && $row['Value'][2] == '\\') {
					$this->platform = 'WINDOWS';
				}
				else {
					$this->platform = 'OTHER';
				}
			}
			else {
				$this->platform = 'WINDOWS';
			}

			if ($this->platform == 'OTHER' && ($dbhost != '.' && strtolower($dbhost) != 'localhost:3306' && $dbhost != '127.0.0.1:3306') || '5.1' <= PHP_VERSION && date_default_timezone_get() == 'UTC') {
				$result = mysql_query('SELECT UNIX_TIMESTAMP() AS timeline, UNIX_TIMESTAMP(\'' . date('Y-m-d H:i:s', $this->starttime) . '\') AS timezone', $this->link_id);
				$row = mysql_fetch_assoc($result);
				if ($dbhost != '.' && strtolower($dbhost) != 'localhost:3306' && $dbhost != '127.0.0.1:3306') {
					$this->timeline = $this->starttime - $row['timeline'];
				}

				if ('5.1' <= PHP_VERSION && date_default_timezone_get() == 'UTC') {
					$this->timezone = $this->starttime - $row['timezone'];
				}
			}

			$content = '<' . "?php\r\n" . '$this->mysql_config_cache_file_time = ' . $this->starttime . ";\r\n" . '$this->timeline = ' . $this->timeline . ";\r\n" . '$this->timezone = ' . $this->timezone . ";\r\n" . '$this->platform = ' . '\'' . $this->platform . "';\r\n?" . '>';
			@file_put_contents($sqlcache_config_file, $content);
		}

		if ($dbname) {
			if (mysql_select_db($dbname, $this->link_id) === false) {
				if (!$quiet) {
					$this->ErrorMsg('Can\'t select MySQL database(' . $dbname . ')!');
				}

				return false;
			}
			else {
				return true;
			}
		}
		else {
			return true;
		}
	}

	public function select_database($dbname)
	{
		return mysql_select_db($dbname, $this->link_id);
	}

	public function set_mysql_charset($charset)
	{
		if ('4.1' < $this->version) {
			if (in_array(strtolower($charset), array('gbk', 'big5', 'utf-8', 'utf8'))) {
				$charset = str_replace('-', '', $charset);
			}

			if ($charset != 'latin1') {
				mysql_query('SET character_set_connection=' . $charset . ', character_set_results=' . $charset . ', character_set_client=binary', $this->link_id);
			}
		}
	}

	public function fetch_array($query, $result_type = MYSQL_ASSOC)
	{
		return mysql_fetch_array($query, $result_type);
	}

	public function query($sql, $type = '')
	{
		if ($this->link_id === NULL) {
			$this->connect($this->settings['dbhost'], $this->settings['dbuser'], $this->settings['dbpw'], $this->settings['dbname'], $this->settings['charset'], $this->settings['pconnect']);
			$this->settings = array();
		}

		if ($this->queryCount++ <= 99) {
			$this->queryLog[] = $sql;
		}

		if ($this->queryTime == '') {
			if ('5.0.0' <= PHP_VERSION) {
				$this->queryTime = microtime(true);
			}
			else {
				$this->queryTime = microtime();
			}
		}

		if ('4.3' <= PHP_VERSION && $this->starttime + 1 < time()) {
			mysql_ping($this->link_id);
		}

		if (!($query = mysql_query($sql, $this->link_id)) && $type != 'SILENT') {
			$this->error_message[]['message'] = 'MySQL Query Error';
			$this->error_message[]['sql'] = $sql;
			$this->error_message[]['error'] = mysql_error($this->link_id);
			$this->error_message[]['errno'] = mysql_errno($this->link_id);
			$this->ErrorMsg();
			return false;
		}

		if (defined('DEBUG_MODE') && (DEBUG_MODE & 8) == 8) {
			$logfilename = $this->root_path . DATA_DIR . '/mysql_query_' . $this->dbhash . '_' . date('Y_m_d') . '.log';
			$str = $sql . "\n\n";

			if ('5.0' <= PHP_VERSION) {
				file_put_contents($logfilename, $str, FILE_APPEND);
			}
			else {
				$fp = @fopen($logfilename, 'ab+');

				if ($fp) {
					fwrite($fp, $str);
					fclose($fp);
				}
			}
		}

		return $query;
	}

	public function affected_rows()
	{
		return mysql_affected_rows($this->link_id);
	}

	public function error()
	{
		return mysql_error($this->link_id);
	}

	public function errno()
	{
		return mysql_errno($this->link_id);
	}

	public function result($query, $row)
	{
		return @mysql_result($query, $row);
	}

	public function num_rows($query)
	{
		return mysql_num_rows($query);
	}

	public function num_fields($query)
	{
		return mysql_num_fields($query);
	}

	public function free_result($query)
	{
		return mysql_free_result($query);
	}

	public function insert_id()
	{
		return mysql_insert_id($this->link_id);
	}

	public function fetchRow($query)
	{
		return mysql_fetch_assoc($query);
	}

	public function fetch_fields($query)
	{
		return mysql_fetch_field($query);
	}

	public function version()
	{
		return $this->version;
	}

	public function ping()
	{
		if ('4.3' <= PHP_VERSION) {
			return mysql_ping($this->link_id);
		}
		else {
			return false;
		}
	}

	static public function escape_string($unescaped_string)
	{
		if ('4.3' <= PHP_VERSION) {
			return mysql_real_escape_string($unescaped_string);
		}
		else {
			return mysql_escape_string($unescaped_string);
		}
	}

	public function close()
	{
		return mysql_close($this->link_id);
	}

	public function ErrorMsg($message = '', $sql = '')
	{
		if ($message) {
			echo '<b>DSC info</b>: ' . $message . "\n\n<br /><br />";
		}
		else {
			echo '<b>MySQL server error report:';
			print_r($this->error_message);
		}

		exit();
	}

	public function selectLimit($sql, $num, $start = 0)
	{
		if ($start == 0) {
			$sql .= ' LIMIT ' . $num;
		}
		else {
			$sql .= ' LIMIT ' . $start . ', ' . $num;
		}

		return $this->query($sql);
	}

	public function getOne($sql, $limited = false)
	{
		if ($limited == true) {
			$sql = trim($sql . ' LIMIT 1');
		}

		$res = $this->query($sql);

		if ($res !== false) {
			$row = mysql_fetch_row($res);

			if ($row !== false) {
				return $row[0];
			}
			else {
				return '';
			}
		}
		else {
			return false;
		}
	}

	public function getOneCached($sql, $cached = 'FILEFIRST')
	{
		$sql = trim($sql . ' LIMIT 1');
		$cachefirst = ($cached == 'FILEFIRST' || $cached == 'MYSQLFIRST' && $this->platform != 'WINDOWS') && $this->max_cache_time;

		if (!$cachefirst) {
			return $this->getOne($sql, true);
		}
		else {
			$result = $this->getSqlCacheData($sql, $cached);

			if (empty($result['storecache']) == true) {
				return $result['data'];
			}
		}

		$arr = $this->getOne($sql, true);
		if ($arr !== false && $cachefirst) {
			$this->setSqlCacheData($result, $arr);
		}

		return $arr;
	}

	public function getAll($sql)
	{
		$res = $this->query($sql);

		if ($res !== false) {
			$arr = array();

			while ($row = mysql_fetch_assoc($res)) {
				$arr[] = $row;
			}

			return $arr;
		}
		else {
			return false;
		}
	}

	public function getAllCached($sql, $cached = 'FILEFIRST')
	{
		$cachefirst = ($cached == 'FILEFIRST' || $cached == 'MYSQLFIRST' && $this->platform != 'WINDOWS') && $this->max_cache_time;

		if (!$cachefirst) {
			return $this->getAll($sql);
		}
		else {
			$result = $this->getSqlCacheData($sql, $cached);

			if (empty($result['storecache']) == true) {
				return $result['data'];
			}
		}

		$arr = $this->getAll($sql);
		if ($arr !== false && $cachefirst) {
			$this->setSqlCacheData($result, $arr);
		}

		return $arr;
	}

	public function getRow($sql, $limited = false)
	{
		if ($limited == true) {
			$sql = trim($sql . ' LIMIT 1');
		}

		$res = $this->query($sql);

		if ($res !== false) {
			return mysql_fetch_assoc($res);
		}
		else {
			return false;
		}
	}

	public function getRowCached($sql, $cached = 'FILEFIRST')
	{
		$sql = trim($sql . ' LIMIT 1');
		$cachefirst = ($cached == 'FILEFIRST' || $cached == 'MYSQLFIRST' && $this->platform != 'WINDOWS') && $this->max_cache_time;

		if (!$cachefirst) {
			return $this->getRow($sql, true);
		}
		else {
			$result = $this->getSqlCacheData($sql, $cached);

			if (empty($result['storecache']) == true) {
				return $result['data'];
			}
		}

		$arr = $this->getRow($sql, true);
		if ($arr !== false && $cachefirst) {
			$this->setSqlCacheData($result, $arr);
		}

		return $arr;
	}

	public function getCol($sql)
	{
		$res = $this->query($sql);

		if ($res !== false) {
			$arr = array();

			while ($row = mysql_fetch_row($res)) {
				$arr[] = $row[0];
			}

			return $arr;
		}
		else {
			return false;
		}
	}

	public function getColCached($sql, $cached = 'FILEFIRST')
	{
		$cachefirst = ($cached == 'FILEFIRST' || $cached == 'MYSQLFIRST' && $this->platform != 'WINDOWS') && $this->max_cache_time;

		if (!$cachefirst) {
			return $this->getCol($sql);
		}
		else {
			$result = $this->getSqlCacheData($sql, $cached);

			if (empty($result['storecache']) == true) {
				return $result['data'];
			}
		}

		$arr = $this->getCol($sql);
		if ($arr !== false && $cachefirst) {
			$this->setSqlCacheData($result, $arr);
		}

		return $arr;
	}

	public function autoExecute($table, $field_values, $mode = 'INSERT', $where = '', $querymode = '')
	{
		$field_names = $this->getCol('DESC ' . $table);
		$sql = '';

		if ($mode == 'INSERT') {
			$fields = $values = array();

			foreach ($field_names as $value) {
				if (array_key_exists($value, $field_values) == true) {
					$fields[] = $value;
					$values[] = '\'' . $field_values[$value] . '\'';
				}
			}

			if (!empty($fields)) {
				foreach ($values as $key => $row) {
					if ($row) {
						$values[$key] = '\'' . str_replace('\'', '', $row) . '\'';
					}
					else {
						$values[$key] = $row;
					}
				}

				$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
			}
		}
		else {
			$sets = array();

			foreach ($field_names as $value) {
				if (array_key_exists($value, $field_values) == true) {
					$sets[] = $value . ' = \'' . $field_values[$value] . '\'';
				}
			}

			if (!empty($sets)) {
				$sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets) . ' WHERE ' . $where;
			}
		}

		if ($sql) {
			return $this->query($sql, $querymode);
		}
		else {
			return false;
		}
	}

	public function autoReplace($table, $field_values, $update_values, $where = '', $querymode = '')
	{
		$field_descs = $this->getAll('DESC ' . $table);
		$primary_keys = array();

		foreach ($field_descs as $value) {
			$field_names[] = $value['Field'];

			if ($value['Key'] == 'PRI') {
				$primary_keys[] = $value['Field'];
			}
		}

		$fields = $values = array();

		foreach ($field_names as $value) {
			if (array_key_exists($value, $field_values) == true) {
				$fields[] = $value;
				$values[] = '\'' . $field_values[$value] . '\'';
			}
		}

		$sets = array();

		foreach ($update_values as $key => $value) {
			if (array_key_exists($key, $field_values) == true) {
				if (is_int($value) || is_float($value)) {
					$sets[] = $key . ' = ' . $key . ' + ' . $value;
				}
				else {
					$sets[] = $key . ' = \'' . $value . '\'';
				}
			}
		}

		$sql = '';

		if (empty($primary_keys)) {
			if (!empty($fields)) {
				$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
			}
		}
		else if ('4.1' <= $this->version()) {
			if (!empty($fields)) {
				$sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';

				if (!empty($sets)) {
					$sql .= 'ON DUPLICATE KEY UPDATE ' . implode(', ', $sets);
				}
			}
		}
		else {
			if (empty($where)) {
				$where = array();

				foreach ($primary_keys as $value) {
					if (is_numeric($value)) {
						$where[] = $value . ' = ' . $field_values[$value];
					}
					else {
						$where[] = $value . ' = \'' . $field_values[$value] . '\'';
					}
				}

				$where = implode(' AND ', $where);
			}

			if ($where && (!empty($sets) || !empty($fields))) {
				if (0 < intval($this->getOne('SELECT COUNT(*) FROM ' . $table . ' WHERE ' . $where))) {
					if (!empty($sets)) {
						$sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets) . ' WHERE ' . $where;
					}
				}
				else if (!empty($fields)) {
					$sql = 'REPLACE INTO ' . $table . ' (' . implode(', ', $fields) . ') VALUES (' . implode(', ', $values) . ')';
				}
			}
		}

		if ($sql) {
			return $this->query($sql, $querymode);
		}
		else {
			return false;
		}
	}

	public function setMaxCacheTime($second)
	{
		$this->max_cache_time = $second;
	}

	public function getMaxCacheTime()
	{
		return $this->max_cache_time;
	}

	public function getSqlCacheData($sql, $cached = '')
	{
		$sql = trim($sql);
		$result = array();
		$result['filename'] = $this->root_path . $this->cache_data_dir . 'sqlcache_' . abs(crc32($this->dbhash . $sql)) . '_' . md5($this->dbhash . $sql) . '.php';

		if ($GLOBALS['_CFG']['open_memcached'] == 1) {
			$result['filename'] = str_replace(array(':', '/', '\\', '.'), '_', $result['filename']);
			$data = $GLOBALS['cache']->get('sqlcache_' . $result['filename']);
		}
		else {
			$data = @file_get_contents($result['filename']);
		}

		if (isset($data[23])) {
			$filetime = substr($data, 13, 10);
			$data = substr($data, 23);
			if ($cached == 'FILEFIRST' && $filetime + $this->max_cache_time < time() || $cached == 'MYSQLFIRST' && $filetime < $this->table_lastupdate($this->get_table_name($sql))) {
				$result['storecache'] = true;
			}
			else {
				$result['data'] = @unserialize($data);

				if ($result['data'] === false) {
					$result['storecache'] = true;
				}
				else {
					$result['storecache'] = false;
				}
			}
		}
		else {
			$result['storecache'] = true;
		}

		return $result;
	}

	public function setSqlCacheData($result, $data)
	{
		if ($result['storecache'] === true && $result['filename']) {
			if ($GLOBALS['_CFG']['open_memcached'] == 1) {
				$result['filename'] = str_replace(array(':', '/', '\\', '.'), '_', $result['filename']);
				$data = $GLOBALS['cache']->set('sqlcache_' . $result['filename'], '<?php exit;?>' . time() . serialize($data));
			}
			else {
				@file_put_contents($result['filename'], '<?php exit;?>' . time() . serialize($data));
			}

			clearstatcache();
		}
	}

	public function table_lastupdate($tables)
	{
		if ($this->link_id === NULL) {
			$this->connect($this->settings['dbhost'], $this->settings['dbuser'], $this->settings['dbpw'], $this->settings['dbname'], $this->settings['charset'], $this->settings['pconnect']);
			$this->settings = array();
		}

		$lastupdatetime = '0000-00-00 00:00:00';
		$tables = str_replace('`', '', $tables);
		$this->mysql_disable_cache_tables = str_replace('`', '', $this->mysql_disable_cache_tables);

		foreach ($tables as $table) {
			if (in_array($table, $this->mysql_disable_cache_tables) == true) {
				$lastupdatetime = '2037-12-31 23:59:59';
				break;
			}

			if (strstr($table, '.') != NULL) {
				$tmp = explode('.', $table);
				$sql = 'SHOW TABLE STATUS FROM `' . trim($tmp[0]) . '` LIKE \'' . trim($tmp[1]) . '\'';
			}
			else {
				$sql = 'SHOW TABLE STATUS LIKE \'' . trim($table) . '\'';
			}

			$result = mysql_query($sql, $this->link_id);
			$row = mysql_fetch_assoc($result);

			if ($lastupdatetime < $row['Update_time']) {
				$lastupdatetime = $row['Update_time'];
			}
		}

		$lastupdatetime = strtotime($lastupdatetime) - $this->timezone + $this->timeline;
		return $lastupdatetime;
	}

	public function get_table_name($query_item)
	{
		$query_item = trim($query_item);
		$table_names = array();

		if (stristr($query_item, ' JOIN ') == '') {
			if (preg_match('/^SELECT.*?FROM\\s*((?:`?\\w+`?\\s*\\.\\s*)?`?\\w+`?(?:(?:\\s*AS)?\\s*`?\\w+`?)?(?:\\s*,\\s*(?:`?\\w+`?\\s*\\.\\s*)?`?\\w+`?(?:(?:\\s*AS)?\\s*`?\\w+`?)?)*)/is', $query_item, $table_names)) {
				$table_names = preg_replace('/((?:`?\\w+`?\\s*\\.\\s*)?`?\\w+`?)[^,]*/', '\\1', $table_names[1]);
				return preg_split('/\\s*,\\s*/', $table_names);
			}
		}
		else if (preg_match('/^SELECT.*?FROM\\s*((?:`?\\w+`?\\s*\\.\\s*)?`?\\w+`?)(?:(?:\\s*AS)?\\s*`?\\w+`?)?.*?JOIN.*$/is', $query_item, $table_names)) {
			$other_table_names = array();
			preg_match_all('/JOIN\\s*((?:`?\\w+`?\\s*\\.\\s*)?`?\\w+`?)\\s*/i', $query_item, $other_table_names);
			return array_merge(array($table_names[1]), $other_table_names[1]);
		}

		return $table_names;
	}

	public function set_disable_cache_tables($tables)
	{
		if (!is_array($tables)) {
			$tables = explode(',', $tables);
		}

		foreach ($tables as $table) {
			$this->mysql_disable_cache_tables[] = $table;
		}

		array_unique($this->mysql_disable_cache_tables);
	}
}

if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

?>
