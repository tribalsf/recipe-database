    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th>title</th>
      </tr>
      <?$set = false?>
      <?foreach ($ingredients as $ingredient):?>

      <?if ($ingredient->set != '' && $ingredient->set != $set):?>
      <?$set = $ingredient->set?>
      <tr>
        <th style="text-align: right;"><?=$set?></th>
      </tr>
      <?endif?>
      <tr>
        <td <?=(isset($newone) && $newone == $ingredient->title ? 'class="newrow"' : '')?>>
          <div 
            class="delete_button" 
            data-type="ingredient" 
            data-value='<?=json_encode(array('recipe_id' => $ingredient->recipe_id, 'title' => $ingredient->title), JSON_HEX_APOS)?>'>
            <div class="sprite sprite_delete"></div></div>
          <?=$ingredient->title?>
        </td>
      </tr>
      <?endforeach?>
    </table>
