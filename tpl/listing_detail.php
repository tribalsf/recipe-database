    
    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th>name</th>
        <th>value</th>
      </tr>

      <?foreach ($recipe_detail as $dr):?>
      <tr <?=(isset($newone) && $newone == $dr->value ? 'class="newrow"' : '')?>>
        <td><?=$dr->name?></td>
        <td>
          <div 
            class="delete_button" 
            data-type="recipe_detail" 
            data-value='<?=json_encode(array('recipe_id' => $dr->recipe_id, 'type' => $dr->type, 'value' => $dr->value), JSON_HEX_APOS)?>' >
            <div class="sprite sprite_delete"></div></div>
        <?=$dr->value?></td>
      </tr>
      <?endforeach?>

    </table>

