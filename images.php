<?

highlight_file('images.php');

require_once 'config.php';

$images = recipe_image::gets('WHERE recipe_id = 721');

foreach ($images as $image) {
  hpr($image->recipe_id.'  '.$image->name.', '.number_format(strlen($image->data)));
  ?>
  <img src="data:image/<?=$image->type?>;base64,<?=base64_encode($image->data)?>" />
  <?
}

