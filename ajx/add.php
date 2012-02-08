<?

require_once 'ajax.php';

$error = false;
$html = false;

if (isset($_REQUEST['type']) && !empty($_REQUEST['type'])) {

  switch ($_REQUEST['type']) {

    case 'ingredient' :

      $data = json_decode($_REQUEST['data'], true);

      if ($data['title'] == '') {
        $error = 'You must specify an ingredient';
      } else {

        $ingredient = new ingredient($data['recipe_id'], $data['title']);
        $ingredient->save();
        $ingredients = ingredient::gets('WHERE recipe_id = %n ORDER BY `set`', $data['recipe_id']);
        $newone = $data['title'];

        ob_start();
        require_once '../tpl/listing_ingredient.php';
        $html = ob_get_contents();
        ob_end_clean();
      }

      break;

    case 'instruction' :

      $data = json_decode($_REQUEST['data'], true);

      if ($data['title'] == '') {
        $error = 'You must specify an instruction';
      }  else {

        $instruction = new instruction($data['recipe_id'], $data['step']);
        $instruction->title = $data['title'];
        $instruction->save();
        $instructions = instruction::gets('WHERE recipe_id = %n ORDER BY `set`', $data['recipe_id']);
        $newone = $data['title'];

        ob_start();
        require_once '../tpl/listing_instruction.php';
        $html = ob_get_contents();
        ob_end_clean();

      }

      break;

    case 'detail' :

      $data = json_decode($_REQUEST['data'], true);

      $dr = new detail_recipe($data['recipe_id'], $data['name'], $data['value']);
      $dr->type = $data['type'];
      $dr->save();
      $detail_recipe = detail_recipe::gets('WHERE recipe_id = %n', $data['recipe_id']);
      $newone = $data['value'];

      ob_start();
      require_once '../tpl/listing_detail.php';
      $html = ob_get_contents();
      ob_end_clean();
      break;

    }

  }

echo json_encode(array('error' => $error, 'html' => $html));

