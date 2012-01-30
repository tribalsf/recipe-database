<?

require_once 'ajax.php';

$error = false;

if (isset($_REQUEST['action'])) {

  switch ($_REQUEST['action']) {

    case 'add' :

      $data = json_decode($_REQUEST['data'], true);

      if (empty($data['type'])) {
        $error = 'You must specify a type';
        break;
      }

      if (empty($data['value'])) {
        $error = 'You must specify a value';
        break;
      }

      $detail = new detail($data['site'], $data['type'], $data['value']);
      $detail->save();

      break;

  }

}

echo json_encode(array('error' => $error));
