<?php
// get all exercises
 $exercise_rs = $this->nurse_model->get_exercices_values();
 // end of all exercises

 // exercise duration
  $exerciseduration_rs = $this->nurse_model->get_exercices_duration_values();
 // end of exercixe duration

  // sleep rs duration
  $sleep_rs = $this->nurse_model->get_sleep_values();
 // end of rs duration

 // sleep rs duration
  $meal_rs = $this->nurse_model->get_values('meals','meals_id');
 // end of rs duration 


   // education rs duration
  $education_rs = $this->nurse_model->get_values('education','education_id');
 // end of rs duration 

   // sleep rs duration
  $housing_rs = $this->nurse_model->get_values('housing','housing_id');
 // end of rs duration 

   // sleep rs duration
  $coffee_rs = $this->nurse_model->get_values('coffee','coffee_id');
 // end of rs duration 


  
 
?>
<?php echo form_open("nurse/save_lifestyle/".$visit_id, array("class"=>"form-horizontal"));?>
<div class="row">
	<div class="col-md-6">
        <div class="form-group row">
            <label class="col-lg-4 control-label">Exercise: </label>
            
            <div class="col-lg-8">
	            <select class="form-control" name='excercise'>
	            	<option value="0">Select Exercise</option>
	            	<?php 
						if(count($exercise_rs) > 0){
						 	foreach ($exercise_rs as $key ): 
						 		# code...
						 		 $exercise_id = $key->excersise_id;
						 		 $exercise_name = $key->excersise_name;

						 		echo "<option value='$exercise_id'>$exercise_name</option>";

						 	endforeach;
						 }

	            	?>
            	</select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-4 control-label">Exercise Duration: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name='excercise_duration'>
	            	<option value="0">Select Exercise Duration</option>
	            	<?php 
						if(count($exerciseduration_rs) > 0){
						 	foreach ($exerciseduration_rs as $key2 ): 
						 		# code...
						 		 $exercise_duration_id = $key2->excersise_duration_id;
						 		 $exercise_duration_name = $key2->excersise_duration_name;

						 		echo "<option value='$exercise_duration_id'>$exercise_duration_name</option>";

						 	endforeach;
						 }

	            	?>
            	</select>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-lg-4 control-label">Sleep: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name='sleep'>
	            	<option value="0">Select Sleep</option>
	            	<?php 
						if(count($sleep_rs) > 0){
						 	foreach ($sleep_rs as $key3 ): 
						 		# code...
						 		 $sleep_id = $key3->sleep_id;
						 		 $sleep_name = $key3->sleep_name;

						 		echo "<option value='$sleep_id'>$sleep_name</option>";

						 	endforeach;
						 }

	            	?>
            	</select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 control-label">Meals: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name='meals'>
	            	<option value="0">Select Meals</option>
	            	<?php 
						if(count($meal_rs) > 0){
						 	foreach ($meal_rs as $key4 ): 
						 		# code...
						 		 $meals_id = $key4->meals_id;
						 		 $meals_name = $key4->meals_name;

						 		echo "<option value='$meals_id'>$meals_name</option>";

						 	endforeach;
						 }

	            	?>
            	</select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 control-label">Coffee: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name='coffee'>
	            	<option value="0">Select Coffee</option>
	            	<?php 
						if(count($coffee_rs) > 0){
						 	foreach ($coffee_rs as $key5 ): 
						 		# code...
						 		 $coffee_id = $key5->coffee_id;
						 		 $coffee_name = $key5->coffee_name;

						 		echo "<option value='$coffee_id'>$coffee_name</option>";

						 	endforeach;
						 }

	            	?>
            	</select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 control-label">Housing: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name='housing'>
	            	<option value="0">Select Housing</option>
	            	<?php 
						if(count($housing_rs) > 0){
						 	foreach ($housing_rs as $key6 ): 
						 		# code...
						 		 $housing_id = $key6->housing_id;
						 		 $housing_name = $key6->housing_name;

						 		echo "<option value='$housing_id'>$housing_name</option>";

						 	endforeach;
						 }

	            	?>
            	</select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 control-label">Education: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name='education'>
	            	<option value="0">Select Education</option>
	            	<?php 
						if(count($education_rs) > 0){
						 	foreach ($education_rs as $key7 ): 
						 		# code...
						 		 $education_id = $key7->education_id;
						 		 $education_name = $key7->education_name;

						 		echo "<option value='$education_id'>$education_name</option>";

						 	endforeach;
						 }

	            	?>
            	</select>
            </div>
        </div>
        
	</div>
    
    <div class="col-md-6">
        
        <div class="form-group row">
            <label class="col-lg-4 control-label">Diet: </label>
            
            <div class="col-lg-8">
            	<textarea class="form-control" name="diet" placeholder="diet"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 control-label">Drugs: </label>
            
            <div class="col-lg-8">
            	<textarea class="form-control" name="drugs" placeholder="drugs"></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 control-label">Alcohol %: </label>
            
            <div class="col-lg-8">
            	<input type="text" class="form-control" name="alcohol_percentage" placeholder="Address">
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-lg-4 control-label">Alcohol Qty: </label>
            
            <div class="col-lg-8">

            	<input type="text" class="form-control" name="alcohol_qty" placeholder="Address">
            </div>
        </div>
       
        
    </div>
</div>
</br>
<div class="row">
	<div class="center-align ">
		<button class="btn btn-info btn-lg" type="submit" name="save_lifestyle">Save Patient Lifestyle</button>
	</div>
</div>
<?php echo form_close();?>
