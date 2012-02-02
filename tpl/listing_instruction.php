    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">

      <tr>
        <th style="width: 20px;">step</th>
        <th>title</th>
      </tr>

      <?foreach ($instructions as $instruction): ?>
      <tr <?=(isset($newone) && $newone == $instruction->title ? 'class="newrow"' : '')?>>
        <td><?=$instruction->step?></td>
        <td>
          <div 
            class="delete_button" 
            data-type="instruction" 
            data-value='<?=json_encode(array('recipe_id' => $instruction->recipe_id, 'title' => $instruction->title))?>' >
            <div class="sprite sprite_delete"></div></div>
          <?=$instruction->title?>
        </td>
      </tr>
      <?endforeach?>

    </table>

