<?php

class kdb {

	public static $connection = array();
	private static $_instance = null;
  private static $_host = null;

  private static $_queryLimit = 10; 
  private static $_rowLimit = 10; 

  const types = '/(?<!\\\)%(n|s|t|f|l)/';
	const manip = '/select|insert|update|delete|replace/i';
	
	public static $debug;
	private static $signature;

	public function __construct($params=array()) {

		self::$_instance = $this;

	}

	public static function i($params=array()) {

    self::$_host = isset($params['host']) ? $params['host'] : DB_HOST;

		if (self::$_instance === null) {
			self::$_instance = new kdb($params);
 	  }

		if (!isset(self::$connection[self::$_host])) {
			self::$_instance->connect($params);
		} 

  	return self::$_instance;

	}

	public function connect($params=array()) {

    $params = array_merge(array('host' => DB_HOST, 'user' => DB_USER, 'pass' => DB_PASSWORD, 'db' => DB_DATABASE), $params);
    self::$connection[self::$_host] = new mysqli($params['host'], $params['user'], $params['pass'], $params['db']);
	
		if ($this->debug()) {
			self::$signature[self::$_host] = $params['user'].'@'.$params['host'].' ('.$params['db'].')';
			self::$debug[self::$signature[self::$_host]] = 
				array('stats' => self::$connection[self::$_host]->stat(), 'duplicates' => 0, 'total_runtime' => 0, 'query_count' => 0);
		}

	}

	public function q($query) {

		if (count(func_get_args()) > 1) {

			$args = func_get_args();

			for ($i = 1; isset($args[$i]); $i++) {
				if (!is_array($args[$i])) {
					$params[] = $args[$i];
				} else {
					if (!isset($params)) {
						$params = array_values($args[$i]);
					} else {
						$params = array_merge($params, array_values($args[$i]));
					}
				}
			}

		}

		if (preg_match_all(self::types, $query, $types)) {

			if (count($types[0]) != count($params)) {
				$error = 'kdb::query() parameter mismatch: '.count($types[0]).' found and '.count($params).' given';
				$this->throwexec($error);
				return false;
			}

			foreach ($params as $key=>$param) {

				switch ($types[0][$key]) {
					case '%n' :
						if (!is_numeric($param)) {
							$this->throwexec("'$param' is not numeric ($query) (".implode(',', $params).")");
						}
						$param = '"'.$this->esc($param).'"';
						break;

					case '%t' :
						if (in_array($param,array('now()', 'now'))) {
							$param = 'now()';
							break;
						}
						$param = '"'.$this->esc($param).'"';
						break;

					case '%l' :
							$param = '"[[PERCENT]]'.$this->esc($param).'[[PERCENT]]"';
							break;

					case '%f' :
							$param = '`'.$this->esc($param).'`';
							break;

					default :
						$param = '"'.$this->esc($param).'"';
						break;
				}


				$query = preg_replace('/(?<!\\\)'.$types[0][$key].'/', str_replace('\\','\\\\', $param), $query, 1);

			}


		}

		$query = preg_replace('/(?<!\\\)%t/', '"'.date("Y-m-d H:i:s").'"',$query);
    $query = str_replace('[[PERCENT]]', '%', $query);

		if ($this->debug()) {

			$before = microtime(true);

      if (isset(self::$debug[self::$signature[self::$_host]]['queries'][$query])) {

        self::$debug[self::$signature[self::$_host]]['duplicates']++;
        self::$debug[self::$signature[self::$_host]]['queries'][$query] = 
          array('rows' => 0, 'data' => array(), 'duplicate' => true);

      } elseif (++self::$debug[self::$signature[self::$_host]]['query_count'] <= self::$_queryLimit) {
        self::$debug[self::$signature[self::$_host]]['queries'][$query] = array('rows' => 0, 'data' => array());
      }

      if (!$this->result = self::$connection[self::$_host]->query($query)) {
        $error = self::$connection[self::$_host]->error ."<br />($query)";
        $this->throwexec($error);
        exit;
      }

      $runtime = microtime(true) - $before;
      self::$debug[self::$signature[self::$_host]]['total_runtime'] += $runtime;

      if (isset(self::$debug[self::$signature[self::$_host]]['queries'][$query])) {

        self::$debug[self::$signature[self::$_host]]['queries'][$query]['runtime'] = $runtime;

        if (preg_match('/^select|^show|^describe/i', ltrim($query))) {
          self::$debug[self::$signature[self::$_host]]['queries'][$query]['rows'] = $this->result->num_rows;
        }

        self::$debug[self::$signature[self::$_host]]['queries'][$query]['affected'] = $this->affected_rows;

      }


      $this->query =  $query;

		} else {
			$this->result = self::$connection[self::$_host]->query($query);
		}

		return $this;

	}

	public function fetch($key=null) {

		if ($this->debug()) {
			if ($row = $this->result->fetch_assoc()) {
				if (
          isset(self::$debug[self::$signature[self::$_host]]['queries'][$this->query]['data']) && 
				  count(self::$debug[self::$signature[self::$_host]]['queries'][$this->query]['data']) <= self::$_rowLimit) {
					self::$debug[self::$signature[self::$_host]]['queries'][$this->query]['data'][] = $row;
				}
				if ($key !== null) {
					return $row[$key];
				}
				return $row;
			}

			return false;
		}

		return $this->result->fetch_assoc();
	}

	public function fetch_all($index=null) {

		$return = array();
		while ($row = $this->fetch()) {
			if ($index !== null) {
				$return[$row[$index]] = $row;
			} else {
				$return[] = $row;
			}
		}

		return $return;

	}

	public function __call($name,$args) {

		switch ($name) {

			case 'esc' :
				$name = 'real_escape_string';
        break;

		}

		return call_user_func_array(array(self::$connection[self::$_host],$name),$args);
	}

	public function __get($name) {

		switch ($name) {

			case 'affected_rows' :
				return self::$connection[self::$_host]->affected_rows;
				break;
			case 'insert_id' :
				return self::$connection[self::$_host]->insert_id;
				break;

		}

		return $this->$name;

	}

	private function debug() {
		return defined('KDEBUG_SQL') && KDEBUG_SQL == true;
	}

	private function throwexec($error) {

		if ($this->debug()) {

			foreach (debug_backtrace() as $trace) {
				if (isset($trace['file'])) {
					$file = file($trace['file']);
					for ($i = $trace['line']; $i != $trace['line']-10 && $i > 0; $i--) {
						if (isset($file[$i]) && preg_match(self::manip, $file[$i])) {
							kdebug::handler(420, $error, $trace['file'], $i);
							exit;
						}
					}
				}
			}

			$backtrace = array_pop(debug_backtrace());
			kdebug::handler(420, $error, $backtrace['file'], $backtrace['line']);
			exit;
		}

	}

}
