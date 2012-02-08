<?

require_once 'ajax.php';

$error = false;

if (isset($_REQUEST['action'])) {

  switch ($_REQUEST['action']) {

    case 'add' :

      $data = json_decode($_REQUEST['data'], true);

      if (empty($data['name'])) {
        $error = 'You must specify a name';
        break;
      }

      $detail = new detail($data['name'], $data['type'], $data['value']);
      $detail->save();

      break;

  }

}

echo json_encode(array('error' => $error));
