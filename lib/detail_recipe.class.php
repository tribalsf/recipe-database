<?

class detail_recipe extends ktbl {

  public function __construct($recipe_id,$type,$value) {
    parent::__construct(array('recipe_id' => $recipe_id, 'type' => $type, 'value' => $value));
  }

}
