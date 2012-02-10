<?

require_once 'config.php';

/* truncate the tables we're importing to
kdb::i()->q('TRUNCATE TABLE recipe'); 
kdb::i()->q('TRUNCATE TABLE ingredient'); 
kdb::i()->q('TRUNCATE TABLE instruction'); 
kdb::i()->q('TRUNCATE TABLE detail'); 

// import the recipe table (active)
kdb::i()->q('
  INSERT INTO tribal.recipe 
    (id, title, created, updated, servings, prep_time, cook_time, deleted) 
  SELECT 
    recipe_id, title, acquired_date, acquired_date, num_servings, prep_time_minutes, cook_time_minutes, 0
  FROM oldrecipes.recipe 
  WHERE status = 3
  ORDER BY acquired_date
');

// import the recipe table (deleted) 
kdb::i()->q('
  INSERT INTO tribal.recipe 
    (id, title, created, updated, servings, prep_time, cook_time, deleted) 
  SELECT 
    recipe_id, title, acquired_date, acquired_date, num_servings, prep_time_minutes, cook_time_minutes, 1
  FROM oldrecipes.recipe 
  WHERE status = 4
  ORDER BY acquired_date
');

// import ingredients from recipe_ingredient and ingredient_set
kdb::i()->q('
  INSERT IGNORE INTO tribal.ingredient
    (recipe_id, `set`, title)
  SELECT ins.recipe_id,ins.name,ri.ingredient 
  FROM (oldrecipes.recipe_ingredient ri) 
  LEFT JOIN (oldrecipes.ingredient_set ins) ON ins.ingredient_set_id = ri.ingredient_set_id 
');
*/

// import recipe_ingredient instructions 
/*
$rows = kdb::i()->q('
  SELECT recipe_id, name, instructions
  FROM oldrecipes.ingredient_set
')->fetch_all();

$instructions = array();
foreach ($rows as $row) {
  $instr = explode('<br/>', $row['instructions']);
  foreach ($instr as $instruction) {
    $instruction = trim($instruction);
    if ($instruction != '') {
      $instructions[$row['recipe_id']][$row['name']][] = trim($instruction);
    }
  }
}


foreach ($instructions as $recipe_id=>$set) {
  foreach ($set as $setname=>$instrs) {
    foreach ($instrs as $step=>$instr) {
      $instri = new instruction($recipe_id, $step+1, $setname);
      $instri->title = $instr;
      $instri->set = $setname;
      $instri->save();
    }
  }
}

/*

// details - Cooking Method

$rows = kdb::i()->q("
  INSERT INTO detail
    (name, type, value)
  SELECT 'Cooking Method', 'choice', name
  FROM oldrecipes.cooking_method
");


$rows = kdb::i()->q("
  INSERT INTO recipe_detail (recipe_id, name, type, value)
  SELECT r.recipe_id, 'Cooking Method', 'choice', cm.name
  FROM oldrecipes.recipe r, oldrecipes.cooking_method cm
  WHERE cm.cooking_method_id = r.cooking_method_id
");

*/

/*

// details - cuisine 

$rows = kdb::i()->q("
  insert into detail
    (name, type, value)
  select 'cuisine', 'choice', name
  from oldrecipes.cuisine
");


$rows = kdb::i()->q("
  insert into recipe_detail (recipe_id, name, type, value)
  select r.recipe_id, 'cuisine', 'choice', cm.name
  from oldrecipes.recipe r, oldrecipes.cuisine cm
  where cm.cuisine_id = r.cuisine_id
");

*/

// details - Dietetic

/*

$rows = kdb::i()->q("
  INSERT INTO detail
    (name, type, value)
  SELECT 'Dietetic', 'choice', name
  FROM oldrecipes.dietetic
");

$rows = kdb::i()->q("
  INSERT INTO recipe_detail (recipe_id, name, type, value)
  SELECT rd.recipe_id, 'Dietetic', 'choice', d.name
  FROM oldrecipes.recipe_dietetic rd, oldrecipes.dietetic d
  WHERE d.dietetic_id = rd.dietetic_id
");

*/

// details - dish type 

/*

$rows = kdb::i()->q("
  insert into detail
    (name, type, value)
  select 'Dish Type', 'choice', name
  from oldrecipes.dish_type
");

$rows = kdb::i()->q("
  insert into recipe_detail (recipe_id, name, type, value)
  select r.recipe_id, 'Dish Type', 'choice', dt.name
  from oldrecipes.recipe r, oldrecipes.dish_type dt
  where dt.dish_type_id = r.dish_type_id
");

*/

// details - main ingredient

/*

$rows = kdb::i()->q("
  INSERT INTO detail
    (name, type, value)
  VALUES
    ('Main Ingredient', 'string', '');
");

$rows = kdb::i()->q("
  INSERT INTO recipe_detail (recipe_id, name, type, value)
  SELECT rd.recipe_id, 'Main Ingredient', 'string', d.name
  FROM oldrecipes.recipe_main_ingredient rd, oldrecipes.main_ingredient d
  WHERE d.main_ingredient_id = rd.main_ingredient_id
");

*/


// details - meal type 

/*

$rows = kdb::i()->q("
  INSERT INTO detail
    (name, type, value)
  SELECT 'Meal Type', 'choice', name
  FROM oldrecipes.meal_type
");

$rows = kdb::i()->q("
  INSERT INTO recipe_detail (recipe_id, name, type, value)
  SELECT rd.recipe_id, 'Meal Type', 'choice', d.name
  FROM oldrecipes.recipe_meal_type rd, oldrecipes.meal_type d
  WHERE d.meal_type_id = rd.meal_type_id
");

*/


// details - occasion

/*

$rows = kdb::i()->q("
  INSERT INTO detail
    (name, type, value)
  SELECT 'Occasion', 'choice', name
  FROM oldrecipes.occasion
");

$rows = kdb::i()->q("
  INSERT INTO recipe_detail (recipe_id, name, type, value)
  SELECT rd.recipe_id, 'Occasion', 'choice', d.name
  FROM oldrecipes.recipe_occasion rd, oldrecipes.occasion d
  WHERE d.occasion_id = rd.occasion_id
");

*/







?>


