<?

require_once 'ajax.php';

$d = json_decode($_REQUEST['data'], true);

$error = false;
$message = false;
$recipe_id = false;

if (isset($d['recipe_id']) && is_numeric($d['recipe_id'])) {

  $recipe_id = $d['recipe_id'];

  $recipe = new recipe($d['recipe_id']);
  $recipe->updated = 'now()';
  $recipe->title = $d['title'];
  $recipe->servings = $d['servings'];
  $recipe->prep_time = $d['prep_time'];
  $recipe->cook_time = $d['cook_time'];
  $recipe->save();

  $message = 'Recipe Updated';

} else {

  $recipe = new recipe();
  $recipe->created = 'now()';
  $recipe->updated = 'now()';
  $recipe->title = $d['title'];
  $recipe->servings = $d['servings'];
  $recipe->prep_time = $d['prep_time'];
  $recipe->cook_time = $d['cook_time'];
  $recipe->save();
  $recipe_id = $recipe->id;

  $message = 'Recipe Added';

}

echo json_encode(array('error' => false, 'message' => $message, 'recipe_id' => $recipe_id));
