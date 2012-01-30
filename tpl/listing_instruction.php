    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">

      <tr>
        <th style="width: 20px;">step</th>
        <th>title</th>
      </tr>

      <?foreach ($instructions as $instruction): ?>
      <tr <?=(isset($newone) && $newone == $instruction->title ? 'class="newrow"' : '')?>>
        <td><?=$instruction->step?></td>
        <td><?=$instruction->title?></td>
      </tr>
      <?endforeach?>

    </table>

