    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th>title</th>
      </tr>
      <?foreach ($ingredients as $ingredient):?>
      <tr>
        <td <?=(isset($newone) && $newone == $ingredient->title ? 'class="newrow"' : '')?>><?=$ingredient->title?></td>
      </tr>
      <?endforeach?>
    </table>
