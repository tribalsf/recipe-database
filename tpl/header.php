<!DOCTYPE html>
<html
<head>
<script src="<?=G_URL?>jst/libs.js" type="text/javascript"></script>
<script src="<?=G_URL?>jst/script.js" type="text/javascript"></script>

<style type="text/css">
  @import "<?=G_URL?>css/style.css?r=<?=rand(1,10000)?>";
</style>

</head>
<body>

<div class="header">

  <div class="site_buttons">
    <div class="site_button <?=(isset($_REQUEST['site']) && $_REQUEST['site'] == 'grilling') ? 'site_button_active' : ''?>" data-value="grilling">grilling.com</div>
    <div class="site_button <?=(!isset($_REQUEST['site']) || $_REQUEST['site'] == 'hiddenvalley') ? 'site_button_active' : ''?>" data-value="hiddenvalley">hiddenvalley.com</div>
  </div>
  <div class="site_buttons">
    <div class="site_button <?=(isset($_REQUEST['details']) ? 'site_button_active' : '')?>" data-value="details">Details</div>
  </div>
  <div class="site_buttons">
    <div class="site_button" data-value="add_recipe">Add a Recipe</div>
  </div>

  <a href="<?=G_URL?>"><div class="logo"></div></a>
  <div class="header_title">Recipe Administration</div>

  <div class="clear"></div>

</div>

