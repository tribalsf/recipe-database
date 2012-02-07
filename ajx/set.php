<?

require_once 'ajax.php';

$data = json_decode($_REQUEST['data'], true);

$ingredients = ingredient::gets("WHERE recipe_id = %n AND `set` = ''", array($data['recipe_id']));
foreach ($ingredients as $ingredient) {
  $ingredient->set = $data['set'];
}
$ingredients->save();

$instructions = instruction::gets("WHERE recipe_id = %n AND `set` = ''", array($data['recipe_id']));
foreach ($instructions as $instruction) {
  $instruction->set = $data['set'];
}
$instructions->save();

hpr($data);


