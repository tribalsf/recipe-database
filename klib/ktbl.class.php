<?

abstract class ktbl extends kdb {

	protected $primary = null;

	protected $_data = array();
	protected $_changed = array();

	protected $_column = array();
	protected static $_columns = false;

	private $_exists = null;

  private $_nullkey = false;

	const column_expire = 3600;

	public function __construct($ids) {

    $this->primary = $ids;
		$this->_column = $this->columns();

    if ($ids[key($ids)] == null) {
      $this->_nullkey = true;
      $this->_exists = false;
    }

	}

  public function gets($query=null,$params=array(),$options=array('found_rows' => 0)) {

		$class = get_called_class();

		foreach (self::columns($class) as $field=>$column) {
			if (isset($column['Key']) && $column['Key'] == 'PRI') {
				$primarys[$field] = null;
			}
		}

		$return = new kmtbl($class);

    $querytext = 'SELECT * FROM %f ';
    if ($options['found_rows'] == 1) {
      $querytext = 'SELECT SQL_CALC_FOUND_ROWS * FROM %f ';
    } 

	  $query = self::i()->q($querytext.$query, $class, $params);
    $rows = $query->fetch_all(((count($primarys) > 1) ? null : key($primarys)));

    if ($options['found_rows'] == 1) {
      $return->_found_rows = self::i()->q('select FOUND_ROWS()')->fetch('FOUND_ROWS()');
    }

		foreach ($rows as $key=>$value) {
      $refObj = new ReflectionClass($class);
      $return->$key = $refObj->newInstanceArgs(array_merge(array_intersect_key($value, $primarys)));
			$return->$key->get($value);
      $return->_count++;
		}

		return $return;

	}

	public function __get($name) {


		switch ($name) {

			case '_table' :
				return get_class($this);
				break;

			case 'primary' :
        return $this->primary;
				break;

		}

		if (!isset($this->_data[$name])) {
			$this->exists();
		}

		if (!isset($this->_data[$name])) {
      return null;
    }

		return $this->_data[$name];
	
	}

	public function __set($name, $value) {

		if (isset($this->_class) && $value instanceof $this->_class) {
			$this->$name = $value;
			return;
		}


		if (!isset($this->_data[$name])) {
			$this->exists();
		}

		if (!isset($this->_data[$name]) || $this->_data[$name] != $value) {
			$this->_changed[$name] = $name;
		}

		$this->_data[$name] = $value;
		
	}

	public function get($result=null) {

		$query = 'SELECT * FROM `'.$this->_table.'` WHERE ';

		if ($result != null || $result = self::i()->q($query.$this->primarysQuery(), array_values($this->primary))->fetch()) {
			$this->_data = array_merge($result,$this->_data);
			$this->_exists = true;
			return true;
		}

		$this->_exists = false;
		return false;

	}

	public function exists() {

		if ($this->_exists === null) {
		  $this->get();
		}

		return $this->_exists;

	}

  public function data() {

    $return = array();
    foreach ($this->_data as $key=>$value) {
      $return[$key] = $this->$key;
    }

    if (isset($this->__gets)) {
      foreach ($this->__gets as $get) {
        if (is_object($this->$get)) {
          $return[$get] = $this->$get->data();
        } else {
          $return[$get] = $this->$get;
        }
      }
    }

    return $return;
  }

	public function save() {

		if ($this->exists()) {

			$values = $updates = array();
			$query = 'UPDATE `'.$this->_table.'` SET';

			foreach ($this->_changed as $key=>$value) {
				if (in_array($key, array_keys($this->_column))) {
					$values[] = $this->_data[$key];
					$updates[] = ' `'.$key.'` = '.$this->type($key);
				}
			}

			if (count($updates) > 0) {

				$query .= implode(',', $updates).' WHERE '.$this->primarysQuery();
				$values = array_merge($values, array_values($this->primary));

				return self::i()->q($query, $values);

			}

			return false;

		}

		$_data = array_intersect_key($this->_data, $this->_column);

    if (!$this->_nullkey) {
      $_data = array_merge($this->primary, $_data);
    }
    
    foreach ($_data as $key=>$value) {
      $types[] = $this->type($key);
    }

		$query = '
			INSERT INTO `'.$this->_table.'`
			(`'.implode('`,`', array_keys($_data)).'`)
			VALUES
			('.implode(',', $types).')
		';

    $result = self::i()->q($query, $_data);

    if ($this->_nullkey) {

      $this->primary[key($this->primary)] = $result->insert_id;
      $this->{key($this->primary)} = $result->insert_id;
    }

    return $result;

	}

  public function delete() {

    hpr('deleteing');

    $query = 'DELETE FROM `'.$this->_table.'` WHERE '.$this->primarysQuery();
    $values = array_values($this->primary);
    return self::i()->q($query, array_values($this->primary));

  }

	protected function primarysQuery() {

		$first = null;
    $first = $query = null;

		foreach ($this->primary as $key=>$value) {

			$query .= (($first === null) ? ' (' : ' AND ').'`'.$key.'` = '.$this->type($key);
			$first = false;

		}

		return $query.') ';

	}

	protected function columns($table=false) {

		if (!$table) {
			$table = $this->_table;
		}

		if (isset(self::$_columns[$table])) {
			return self::$_columns[$table];
		}

		$file = G_PATH.'che/'.$table.'.columns.js';

		if (is_file($file)) {

			$columns = json_decode(file_get_contents($file), true);
			$diff = (time() - key($columns));

			if (self::column_expire > 0 && (time() - key($columns)) > self::column_expire) {
				self::$_columns[$table] = self::i()->q('DESCRIBE `'.$table.'`')->fetch_all('Field');
				file_put_contents($file, json_encode(array(time() => self::$_columns[$table])));
				chmod($file, 0777);
			} else {
				self::$_columns[$table] = current($columns);
			}

		} else {

			self::$_columns[$table] = self::i()->q('DESCRIBE `'.$table.'`')->fetch_all('Field');
			file_put_contents($file, json_encode(array(time() => self::$_columns[$table])));
  		chmod($file, 0777);
		}

		return self::$_columns[$table];

	}

	protected function type($column) {

		$field = preg_replace("/\([0-9]+\)/", "", $this->_column[$column]['Type']);

		switch ($field) {

			case 'bigint' :
			case 'mediumint' :
			case 'int' :
				return '%n';

			case 'timestamp' :
				return '%t';

			case 'blob' :
			case 'longblob' :
			case 'tinyblob' :
			case 'mediumblob' :
				return '%d';

			default :
				return '%s';
				break;

		}

	}
	
}
