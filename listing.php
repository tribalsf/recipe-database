<?

$page = 1;
$perpage = 20;
$limit = '0, '.$perpage;

if (isset($_REQUEST['page']) && is_numeric($_REQUEST['page'])) {
  $page = $_REQUEST['page'];
  $limit = ($page - 1) * $perpage.', '.$perpage;
}

if (isset($_REQUEST['deleted'])) {
  $recipes = recipe::gets('WHERE deleted <> 0 ORDER BY CREATED DESC LIMIT '.$limit, array(), array('found_rows' => 1));
} else {
  $recipes = recipe::gets('WHERE deleted <> 1 ORDER BY CREATED DESC LIMIT '.$limit, array(), array('found_rows' => 1));
}
$rows = $recipes->foundrows();
$pages = ceil($rows / $perpage);

require_once 'tpl/listing.php';

