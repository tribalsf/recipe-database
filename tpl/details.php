
<div class="container">

  <div class="form">

    <label for="site">Add a Detail</label>

    <div class="input">
      <select name="site" id="site">
        <option value="grilling">grilling.com</option>
        <option value="hiddenvalley">hiddenvalley.com</option>
      </select>
      <input type="text" name="type" id="type" value="" />
      <input type="text" name="value" id="value" value="" />
      <div class="button" data-action="add_detail">Add</div>
    </div>
  </div>

  <?$i=0?>
  <?foreach ($details as $type=>$detail):?>
  <?$i++?>

  <div class="form form<?=($i%2 ? 'left' : 'right')?>">
    <label class="detail_label"><?=$type?></label>

    <div class="clear"></div>

    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th>site</th>
        <th>value</th>
      </tr>
      <?foreach ($detail as $d): ?>
      <tr>
        <td><?=$d['site']?></td>
        <td><?=$d['value']?></td>
      </tr>
      <?endforeach?>
    </table>

  </div>

  <?endforeach?>

  <div class="clear"></div>


</div>
