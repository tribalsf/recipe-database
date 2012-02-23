<?

require_once 'ajax.php';

$file = file_get_contents('php://input');

if (isset($_REQUEST['recipe_id']) && is_numeric($_REQUEST['recipe_id'])) {
  $img = new recipeImage($_REQUEST['recipe_id']);
  $img->type = $_REQUEST['imagetype'];
  $img->data = $file;
  $img->save();
}

echo json_encode(array('success' => true, 'size' => strlen($file)));

