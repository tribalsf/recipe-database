
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


      <select name="site" id="site">
        <option value="both">both</option>
        <option value="grilling">grilling.com</option>
        <option value="hiddenvalley">hiddenvalley.com</option>
      </select>

      <input type="text" name="value" id="value" value="" data-cmd="details.add();" />

      <div class="button" data-action="add_detail">Add</div>
    </div>
  </div>

</div>

  <div class="formmason">
  <?foreach ($details as $name=>$detail):?>

    <div class="form formsmall">
      <label class="detail_label"><?=$name?> (<?=$detail[0]['type']?>)</label>

      <div class="clear"></div>

      <?if ($detail[0]['type'] == 'choice'):?>
      <table class="listing sublisting" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <th>value</th>
          <th>site</th>
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

          <td>
            <div class="site_buttons site_buttons_detailsite">
              <div 
                class="site_button <?=($d['site'] == 'both') ? 'site_button_active' : ''?>" 
                data-data='<?=json_encode($d)?>'
                data-value="both" 
                data-detail="1"
              >b</div>
              <div 
                class="site_button <?=($d['site'] == 'grilling') ? 'site_button_active' : ''?>" 
                data-data='<?=json_encode($d)?>'
                data-value="grilling" 
                data-detail="1"
              >g</div>
              <div 
                class="site_button <?=($d['site'] == 'hiddenvalley') ? 'site_button_active' : ''?>" 
                data-data='<?=json_encode($d)?>'
                data-value="hiddenvalley" 
                data-detail="1"
              >hv</div>
            </div>
          </td>
        </tr>
        <?endforeach?>
      </table>
      <?endif?>

    </div>

  <?endforeach?>

  </div>
  
  <div class="container">

  <div class="clear"></div>

  <div class="button button_big button_disabled button_delete_selected" data-action="delete_selected">Delete Selected</div>

  <div class="clear"></div>

</div>
