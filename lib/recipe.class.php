<?

class recipe extends ktbl {

  public function __construct($id=null) {
    parent::__construct(array('id' => $id));
  }

  public function __get($name) {

    switch ($name) {

      case 'created_format' :
      case 'updated_format' :

      $date = parent::__get(str_replace('_format','', $name));

      return date("n/d/y", strtotime($date));
      break;

      case 'prep_time_hrs' :
      case 'cook_time_hrs' :
       return floor(parent::__get(str_replace('_hrs', '', $name)) / 60);
       break;

      case 'cook_time_mins' :
      case 'prep_time_mins' :
        $type = str_replace('_mins', '', $name);
        return parent::__get($type) - $this->{$type.'_hrs'} * 60;
       break;

    }

    return parent::__get($name);

  }

}
