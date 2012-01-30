<?

$details = array();

foreach (detail::gets() as $detail) {
  $details[$detail->type][] = $detail->data();
}

require_once 'tpl/details.php';

