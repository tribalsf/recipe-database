<?


if (is_numeric($_REQUEST['recipe'])) {
  $recipe = new recipe($_REQUEST['recipe']);
  $image = new recipe_image($_REQUEST['recipe']);
  $ingredients = ingredient::gets('WHERE recipe_id = %n ORDER BY `SET`', $recipe->id);
  $instructions = instruction::gets('WHERE recipe_id = %n ORDER BY `SET`', $recipe->id);
  $recipe_detail = recipe_detail::gets('WHERE recipe_id = %n', $recipe->id);
} else {
  $recipe_detail = array();
  $ingredients = array();
  $instructions = array();
}

$details = array();
foreach (detail::gets() as $detail) {
  $details[$detail->name][] = $detail->data();
}

require_once 'tpl/modify.php';

