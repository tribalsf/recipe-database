<?

require_once 'config.php';
require_once 'tpl/header.php';

$site = 'hiddenvalley';

if (isset($_REQUEST['site']) && in_array($_REQUEST['site'], array('hiddenvalley','grilling')) ) {
  $site = $_REQUEST['site'];
}

if (isset($_REQUEST['details'])) {

  require_once 'details.php';

} elseif (isset($_REQUEST['recipe']) && is_numeric($_REQUEST['recipe'])) {

  require_once 'modify.php';

} else {

  require_once 'listing.php';

}

require_once 'tpl/footer.php';

?>
