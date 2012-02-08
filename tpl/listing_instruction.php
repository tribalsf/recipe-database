    
    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th style="width: 20px;">step</th>
        <th>title</th>
      </tr>

      <?$set = false?>
      <?foreach ($instructions as $instruction): ?>

      <?if ($instruction->set != '' && $instruction->set != $set):?>
      <?$set = $instruction->set?>
      <tr>
        <th colspan="2" style="text-align: right;"><b><?=$set?></b></th>
      </tr>

      <?endif?>

      <tr <?=(isset($newone) && $newone == $instruction->title ? 'class="newrow"' : '')?>>
        <td><?=$instruction->step?></td>
        <td>
          <div 
            class="delete_button" 
            data-type="instruction" 
            data-value='<?=json_encode(array('recipe_id' => $instruction->recipe_id, 'title' => $instruction->title), JSON_HEX_APOS)?>' >
            <div class="sprite sprite_delete"></div></div>
          <?=$instruction->title?>
        </td>
      </tr>
      <?endforeach?>

    </table>

