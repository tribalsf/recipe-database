
-- oldrecipe to recipe
insert into recipe 
  (title, created, updated, servings, prep_time, cook_time) 
select 
  title, acquired_date, acquired_date, num_servings, prep_time_minutes, cook_time_minutes 
FROM oldrecipe order by acquired_date
