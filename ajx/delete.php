<?

require_once 'ajax.php';

$error = false;

$datas = json_decode($_REQUEST['datas'], true);

$grouped = array();

foreach ($datas as $data) {

  switch ($data['type']) {

    case 'detail_recipe' :
      $grouped['detail_recipe'][] = $data['value'];
      break;

    case 'recipe' :
      $grouped['recipe'][$data['value']['recipe_id']] = true;
      break;

    case 'instruction' :
      $grouped['instruction'][] = $data['value'];
      break;

  }
}

if (isset($grouped['recipe'])) {

  $recipes = recipe::gets('WHERE id IN ('.implode(',', array_keys($grouped['recipe'])).')');

  foreach ($recipes as $recipe) {
    $recipe->deleted = !$recipe->deleted;
  }

  $recipes->save();

}

if (isset($grouped['detail_recipe'])) {
  foreach ($grouped['detail_recipe'] as $dr) {
    kdb::i()->q('DELETE FROM detail_recipe WHERE recipe_id = %n AND `type` = %s AND `value` = %s', $dr);
  }
}


if (isset($grouped['instruction'])) {
  foreach ($grouped['instruction'] as $instruction) {
    kdb::i()->q('DELETE FROM instruction WHERE recipe_id = %n AND `title` = %s', $instruction);
  }
}

echo json_encode(array('error' => $error));

