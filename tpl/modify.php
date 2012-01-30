
<div class="container">

  <!-- START: main form -->
  <div class="form">
  <form>

    <label for="title">Recipe Title</label>
    <div class="input"><input type="text" name="title" id="title" value="<?=$recipe->title?>" /></div>

    <div class="clear"></div>

    <label for="serving">Servings</label>

    <div class="input">
      <select name="serving" id="serving">
        <?for ($i = 1; $i != 100; $i++): ?>
        <option value="<?=$i?>" <?=($i == $recipe->servings ? 'selected' : '')?>><?=$i?></option>
        <?endfor?>
      </select>
    </div>

    <div class="clear"></div>

    <label for="prep_time">Prep Time</label>

    <div class="input">
      <select name="prep_time_hrs" id="prep_time_hrs">
        <?for ($i = 1; $i != 100; $i++): ?>
        <option value="<?=$i?>" <?=($i == $recipe->prep_time_hrs ? 'selected' : '')?>><?=$i?></option>
        <?endfor?>
      </select>

      <div class="sidelabel">hours</div>

      <select name="prep_time_mins" id="prep_time_mins">
        <?for ($i = 1; $i != 60; $i++): ?>
        <option value="<?=$i?>" <?=($i == $recipe->prep_time_mins ? 'selected' : '')?>><?=$i?></option>
        <?endfor?>
      </select>

      <div class="sidelabel">minutes</div>

    </div>

    <div class="clear"></div>

    <label for="cook_time">Cook Time</label>

    <div class="input">
      <select name="cook_time_hrs" id="cook_time_hrs">
        <?for ($i = 1; $i != 100; $i++): ?>
        <option value="<?=$i?>" <?=($i == $recipe->cook_time_hrs ? 'selected' : '')?>><?=$i?></option>
        <?endfor?>
      </select>

      <div class="sidelabel">hours</div>

      <select name="cook_time_mins" id="cook_time_mins">
        <?for ($i = 1; $i != 60; $i++): ?>
        <option value="<?=$i?>" <?=($i == $recipe->cook_time_mins ? 'selected' : '')?>><?=$i?></option>
        <?endfor?>
      </select>

      <div class="sidelabel">minutes</div>

    </div>

  </div>

  <!-- END: main form -->
  <div class="form formright">

    <label for="instruction">Instructions</label>
    <div class="button" data-action="add_instruction">Add</div>
    <div class="input">
      <select name="step" id="step">
        <?for ($i = 1; $i != 60; $i++):?>
        <option value="<?=$i?>" <?=($i == count($instructions)+1 ? 'selected' : '')?> ><?=$i?></option>
        <?endfor?>
      </select>
      <input type="text" name="instruction" id="instruction" value="" data-cmd="modify.add('instruction')" />
    </div>
    <div class="clear"></div>

    <div class="instruction_listing">
    <?require_once 'tpl/listing_instruction.php';?>
    </div>

  </div>

  <div class="form formleft">

    <label for="ingredient">Ingredients</label>
    <div class="button" data-action="add_ingredient">Add</div>
    <div class="input">
      <input type="text" name="ingredient" id="ingredient" value="" data-cmd="modify.add('ingredient')" />
    </div>
    <div class="clear"></div>

    <div class="ingredient_listing">
    <?require_once 'tpl/listing_ingredient.php'?>
    </div>

  </div>

  <div class="clear"></div>

  <div class="form formleft">

    <label for="details">Details</label>
    <div class="button" data-action="add_detail_recipe">Add</div>

    <div class="input" id="details">

      <select name="detail_type" class="detail_type" id="detail_type">
        <?foreach ($details[$site] as $key=>$value):?>
        <option value="<?=$key?>"><?=$key?></option>
        <?endforeach?>
      </select>

      <?foreach ($details[$site] as $key=>$value):?>
      <select name="detail_value" id="detail_value" class="detail_value detail_value_<?=$site?>_<?=str_replace(' ', '_', $key)?>">
        <?foreach ($value as $val):?>
        <option value="<?=$val?>"><?=$val?></option>
        <?endforeach?>
      </select>
      <?endforeach?>

    </div>

    <div class="clear"></div>

    <div class="detail_listing">
    <?require_once 'tpl/listing_detail.php'; ?>

    </div>
  </div>

  <div class="clear"></div>

  <div class="button button_big" data-action="save_recipe">Save</div>
  <div class="button button_big" data-action="back">Back</div>

  <div class="clear"></div>
  </form>

</div>

