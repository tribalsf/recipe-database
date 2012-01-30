    
    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th>type</th>
        <th>value</th>
      </tr>

      <?foreach ($detail_recipe as $dr):?>
      <tr <?=(isset($newone) && $newone == $dr->value ? 'class="newrow"' : '')?>>
        <td><?=$dr->type?></td>
        <td><?=$dr->value?></td>
      </tr>
      <?endforeach?>

    </table>

