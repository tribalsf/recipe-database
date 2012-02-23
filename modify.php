<?


if (is_numeric($_REQUEST['recipe'])) {
  $recipe = new recipe($_REQUEST['recipe']);
  $image = new recipeImage($_REQUEST['recipe']);
  $ingredients = ingredient::gets('WHERE recipe_id = %n ORDER BY `SET`', $recipe->id);
  $instructions = instruction::gets('WHERE recipe_id = %n ORDER BY `SET`', $recipe->id);
  $recipeDetail = recipeDetail::gets('WHERE recipe_id = %n', $recipe->id);
} else {
  $recipeDetail = array();
  $ingredients = array();
  $instructions = array();
}

$details = array();
foreach (detail::gets() as $detail) {
  $details[$detail->name][] = $detail->data();
}

require_once 'tpl/modify.php';

