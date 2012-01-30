<?

class kch {

  const space = 360;
  static public $angles = array();
  static private $_host = null;

  static function chash($id) {
    $distances = array();

    foreach(self::$angles as $angle=>$server) {
      $distances[$angle] = ($angle - ($id % self::space) + self::space) % self::space;
    }

    asort($distances);
    return $distances;
  }

  static function angle($id) {
    $angles = self::chash($id);
    return key($angles);
  }

  static function whoami() {
    foreach (self::$angles as $angle=>$server) {
      if ($server['host'] == self::host()) {
        return $angle;
      }
    }

    return false;
  }

  static function host() {

    if (self::$_host != null) {
      return self::$_host;
    }

    if (isset($_SERVER['SERVER_ADDR'])) {
      self::$_host = $_SERVER['SERVER_ADDR'];
    } else {
      self::$_host = trim(`ifconfig eth0 | sed -n '/dr:/{;s/.*dr://;s/ .*//;p;}'`);
    }

    return self::$_host;
  }

  static function migrate($run=false) {

    $before = microtime(true);
    $migrate = $classes = $fields = array();

    foreach (explode(',', LIB_PATHS) as $libdir) {
      if (is_dir($libdir)) {
        $files = scandir($libdir);
        foreach ($files as $file) {
          if (strpos($file, 'class.php') !== false) {
            $class = str_replace('.class.php', '', $file);
            if (isset($class::$angled)) {
              $classes[] = $class;
            }
          }
        }
      }
    }

    foreach ($classes as $class) {

      $migrate[$class] = array();

      foreach($class::gets() as $obj) {

        if (kch::whoami() !== kch::angle($obj->{$class::$angled})) {

          if (!isset($fields[$class])) {
            $fields[$class] = array_keys($obj->data());
          }

          $migrate[$class][kch::angle($obj->{$class::$angled})][$obj->{$class::$angled}] = $obj->data();

        }

      }

    }

    $insert = $delete = false;
    echo '['.self::host().':'.self::whoami()."]\t\t";

    foreach ($migrate as $class=>$adata) {

      foreach ($adata as $angle=>$data) {
        echo 'rows: '.count($data).' angle: '.$angle.' table: '.$class.', ';

        $insert = 'INSERT INTO `'.$class.'` (`'.implode('`,`', $fields[$class]).'`) VALUES ';
        $values = array();

        foreach ($data as $id=>$value) {
          $values[] = '("'.implode('","', $value).'")';
        }

        $insert .= implode(',', ($values));
        $delete = 'DELETE FROM `'.$class.'` WHERE `'.$class::$angled.'` IN ("'.implode('", "', array_keys($data)).'")';

        if ($run && kdb::i(kch::$angles[$angle])->q($insert)) {
          kdb::i()->q($delete);
        }

      }
    }

    echo 'rt: '.round(microtime(true) - $before, 2)."s\r\n";

  }

}
