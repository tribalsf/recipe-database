    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th>title</th>
      </tr>
      <?foreach ($ingredients as $ingredient):?>
      <tr>
        <td <?=(isset($newone) && $newone == $ingredient->title ? 'class="newrow"' : '')?>>
          <div 
            class="delete_button" 
            data-type="ingredient" 
            data-value='<?=json_encode(array('recipe_id' => $ingredient->recipe_id, 'title' => $ingredient->title))?>'>
            <div class="sprite sprite_delete"></div></div>
          <?=$ingredient->title?>
        </td>
      </tr>
      <?endforeach?>
    </table>
