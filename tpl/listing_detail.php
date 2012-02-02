    
    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th>type</th>
        <th>value</th>
      </tr>

      <?foreach ($detail_recipe as $dr):?>
      <tr <?=(isset($newone) && $newone == $dr->value ? 'class="newrow"' : '')?>>
        <td><?=$dr->type?></td>
        <td>
          <div 
            class="delete_button" 
            data-type="detail_recipe" 
            data-value='<?=json_encode(array('recipe_id' => $dr->recipe_id, 'type' => $dr->type, 'value' => $dr->value))?>' >
            <div class="sprite sprite_delete"></div></div>
        <?=$dr->value?></td>
      </tr>
      <?endforeach?>

    </table>

