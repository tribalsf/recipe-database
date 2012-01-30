<?

class instruction extends ktbl {

  public function __construct($recipe_id,$step) {
    parent::__construct(array('recipe_id' => $recipe_id, 'step' => $step));
  }

}
