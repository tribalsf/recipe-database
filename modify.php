<?

$recipe = new recipe($_REQUEST['recipe']);

$ingredients = ingredient::gets('WHERE recipe_id = %n', $recipe->id);
$instructions = instruction::gets('WHERE recipe_id = %n', $recipe->id);
$detail_recipe = detail_recipe::gets('WHERE recipe_id = %n', $recipe->id);

$details = array();
foreach (detail::gets() as $detail) {
  $details[$detail->site][$detail->type][] = $detail->value;
}

require_once 'tpl/modify.php';

