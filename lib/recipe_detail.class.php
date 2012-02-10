<?

class recipe_detail extends ktbl {

  public function __construct($recipe_id,$name,$value) {
    parent::__construct(array('recipe_id' => $recipe_id, 'name' => $name, 'value' => $value));
  }

}
