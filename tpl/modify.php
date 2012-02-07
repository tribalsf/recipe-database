
<div class="container">

  <!-- START: main form -->
  <div class="form">
  <form>

    <input type="hidden" name="recipe_id" id="recipe_id" value="<?=$_REQUEST['recipe']?>" />

    <label for="title">Recipe Title</label>
    <div class="input"><input type="text" name="title" id="title" value="<?=isset($recipe) ? $recipe->title : ''?>" /></div>

    <div class="clear"></div>

    <label for="serving">Servings</label>

    <div class="input">
      <input type="text" name="servings" id="servings" value="<?=isset($recipe) ? $recipe->servings : ''?>" />
    </div>

    <div class="clear"></div>

    <label for="prep_time">Prep Time</label>

    <div class="input">
      <input type="text" name="prep_time" id="prep_time" value="<?=isset($recipe) ? $recipe->prep_time : ''?>" />
    </div>

    <div class="clear"></div>

    <label for="cook_time">Cook Time</label>

    <div class="input">
      <input type="text" name="cook_time" id="cook_time" value="<?=isset($recipe) ? $recipe->cook_time : ''?>" />
    </div>

  </div>
  <div class="clear"></div>

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

  <div class="form">
    <label for="set">Set Label</label>
    <div class="button" data-action="create_set">Create a Set</div>
    <div class="input">
      <input type="text" name="set" id="set" value="" data-cmd="modify.set()" />
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
  <div class="button button_big button_disabled button_delete_selected" data-action="delete_selected">Delete Selected</div>

  <div class="clear"></div>
  </form>

</div>

