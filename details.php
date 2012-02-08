<?

$details = array();

foreach (detail::gets() as $detail) {
  $details[$detail->name][] = $detail->data();
}

require_once 'tpl/details.php';

