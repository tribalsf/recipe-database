
<div class="container">

  <div class="form">

    <label for="name">Add a Detail</label>

    <div class="input">
      <input type="text" name="name" id="name" value="" />
      <select name="type" id="type">
        <option value="choice">choice</option>
        <option value="string">string</option>
        <option value="none">none</option>
      </select>
      <input type="text" name="value" id="value" value="" data-cmd="details.add();" />
      <div class="button" data-action="add_detail">Add</div>
    </div>
  </div>

  <?$i=0?>
  <?foreach ($details as $name=>$detail):?>
  <?$i++?>

  <div class="form form<?=($i%2 ? 'left' : 'right')?>">
    <label class="detail_label"><?=$name?> (<?=$detail[0]['type']?>)</label>

    <div class="clear"></div>

    <?if ($detail[0]['type'] == 'choice'):?>
    <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <th>value</th>
      </tr>
      <?foreach ($detail as $d): ?>
      <tr>
        <td>
          <div 
            class="delete_button" 
            data-type="detail" 
            data-value='<?=json_encode(array('name' => $name, 'value' => $d['value']), JSON_HEX_APOS)?>'>
            <div class="sprite sprite_delete"></div></div>

          <?=$d['value']?></td>
      </tr>
      <?endforeach?>
    </table>
    <?endif?>

  </div>

  <?endforeach?>

  <div class="clear"></div>

  <div class="button button_big button_disabled button_delete_selected" data-action="delete_selected">Delete Selected</div>

</div>
