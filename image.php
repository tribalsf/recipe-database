<?

require_once 'config.php';
$img = new recipe_image($_REQUEST['recipe_id']);

?>

<img src="data:image/<?=$img->type?>;base64,<?=base64_encode($img->data)?>" />
