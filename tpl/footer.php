
<div class="footer">&nbsp;</div>

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

<div class="notify"></div>

</body>
</html>

