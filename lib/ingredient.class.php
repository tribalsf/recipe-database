<?

class ingredient extends ktbl {

  public function __construct($recipe_id,$title) {
    parent::__construct(array('recipe_id' => $recipe_id, 'title' => $title));
  }

}
