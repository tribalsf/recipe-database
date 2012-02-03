
<div class="footer">&nbsp;</div>

<div class="status status_check">
  <div class="sprite_check status_icon"></div>
  <span class="status_body">This is a status message</span>
</div>

<div class="notify"></div>

<script>

g.G_URL = '<?=G_URL?>';
g.site = '<?=$site?>';

admin.init();

<?if (isset($_REQUEST['recipe'])):?>
modify.recipe_id = '<?=$_REQUEST['recipe']?>';
modify.init();
<?else:?>
listing.init();
<?endif?>


</script>


</body>
</html>

