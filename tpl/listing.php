
<div class="container">

<table class="listing" border="0" cellspacing="0" cellpadding="0">

  <tr>
    <th>id</th>
    <th>title</th>
    <th>created</th>
    <th>updated</th>
    <th>servings</th>
    <th>prep time</th>
    <th>cook time</th>
  </tr>

  <?foreach ($recipes as $recipe): ?>
  <tr class="listing_row" data-recipe="<?=$recipe->id?>">
    <td><?=$recipe->id?></td>
    <td><?=$recipe->title?></td>
    <td><?=$recipe->created_format?></td>
    <td><?=$recipe->updated_format?></td>
    <td><?=$recipe->servings?></td>
    <td><?=$recipe->prep_time?></td>
    <td><?=$recipe->cook_time?></td>
  </tr>
  <?endforeach?>

</table>

<ul class="pages">
  <?for ($i = 1; $i != $pages; $i++):?><li class="page <?=($i == 1 ? 'page_first' : '').' '.($i == $page ? 'page_selected' : '')?>"><?=$i?></li><?endfor?>
</ul>

<div class="clear"></div>

</div>
