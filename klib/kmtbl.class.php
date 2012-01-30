<?

class kmtbl extends ktbl implements Countable {

	protected $_class = null;
	protected $_column = null;

  protected $_count = 0;

  protected $_found_rows = 0;

	public function __construct($class) {
		$this->_class = $class;
		$this->_column = $this->columns($class);
	}

  public function count() {
    return $this->_count;
  }

  public function foundrows() {
    return $this->_found_rows;
  }

	public function save() {

		$changes = array();

		foreach ($this as $id=>$obj) {
			if ($obj instanceof $this->_class) {
				foreach ($obj->_changed as $field) {
					$changes[$field][$obj->_data[$field]][] = $obj->primary;
				}
			}
		}

		foreach ($changes as $field=>$values) {
			foreach ($values as $value=>$ids) {

				$query = 'UPDATE %f SET %f = '.$this->type($field).' WHERE ';
        $allids = $primarysQuery = array();

        foreach ($ids as $primarys) {
          $this->primary = $primarys;
          $primarysQuery[] = $this->primarysQuery();
          $allids = array_merge($allids, array_values($primarys));
        }

				self::i()->q($query.implode(' OR ' , $primarysQuery), array_merge(array($this->_class, $field, $value),$allids));
			}
		}

	}

}
