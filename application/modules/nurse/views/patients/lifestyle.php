<?php
$patient_lifestyle = $this->nurse_model->get_patient_lifestyle2($visit_id);

$excersise_id = '';
$excersise_duration_id = '';
$sleep_id = '';
$meals_id = '';
$coffee_id = '';
$housing_id = '';
$education_id = '';
$lifestyle_diet = '';
$lifestyle_drugs = '';
$lifestyle_alcohol_percentage = '';
$lifestyle_alcohol_quantity = '';

if($patient_lifestyle->num_rows() > 0)
{
	$row = $patient_lifestyle->row();
	
	$excersise_id = $row->excersise_id;
	$excersise_duration_id = $row->excersise_duration_id;
	$sleep_id = $row->sleep_id;
	$meals_id = $row->meals_id;
	$coffee_id = $row->coffee_id;
	$housing_id = $row->housing_id;
	$education_id = $row->education_id;
	$lifestyle_diet = $row->lifestyle_diet;
	$lifestyle_drugs = $row->lifestyle_drugs;
	$lifestyle_alcohol_percentage = $row->lifestyle_alcohol_percentage;
	$lifestyle_alcohol_quantity = $row->lifestyle_alcohol_quantity;
}
 
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
								 $exercise_id2 = $key->excersise_id;
								 $exercise_name = $key->excersise_name;
								 
								 if($excersise_id == $exercise_id2)
								 {
									echo "<option value='$exercise_id2' selected='selected'>$exercise_name</option>";
								 }
								
								else
								{
						 			echo "<option value='$exercise_id2'>$exercise_name</option>";
								}

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
						 		 $exercise_duration_id2 = $key2->excersise_duration_id;
						 		 $exercise_duration_name = $key2->excersise_duration_name;
								 
								 if($excersise_duration_id == $exercise_duration_id2)
								 {
									echo "<option value='$exercise_duration_id2' selected='selected'>$exercise_duration_name</option>";
								 }
								
								else
								{
						 			echo "<option value='$exercise_duration_id2'>$exercise_duration_name</option>";
								}

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
						 		 $sleep_id2 = $key3->sleep_id;
						 		 $sleep_name = $key3->sleep_name;
								 
								 if($sleep_id2 == $sleep_id)
								 {
									echo "<option value='$sleep_id2' selected='selected'>$sleep_name</option>";
								 }
								
								else
								{
						 			echo "<option value='$sleep_id2'>$sleep_name</option>";
								}

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
						 		 $meals_id2 = $key4->meals_id;
						 		 $meals_name = $key4->meals_name;
								 
								 if($meals_id == $meals_id2)
								 {
									echo "<option value='$meals_id2' selected='selected'>$meals_name</option>";
								 }
								
								else
								{
						 			echo "<option value='$meals_id2'>$meals_name</option>";
								}

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
						 		 $coffee_id2 = $key5->coffee_id;
						 		 $coffee_name = $key5->coffee_name;
								 
								 if($coffee_id == $coffee_id2)
								 {
									echo "<option value='$coffee_id2' selected='selected'>$coffee_name</option>";
								 }
								
								else
								{
						 			echo "<option value='$coffee_id2'>$coffee_name</option>";
								}

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
						 		 $housing_id2 = $key6->housing_id;
						 		 $housing_name = $key6->housing_name;
								 
								 if($housing_id == $housing_id2)
								 {
									echo "<option value='$housing_id2' selected='selected'>$housing_name</option>";
								 }
								
								else
								{
						 			echo "<option value='$housing_id2'>$housing_name</option>";
								}

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
						 		 $education_id2 = $key7->education_id;
						 		 $education_name = $key7->education_name;
								 
								 if($education_id2 == $education_id)
								 {
									echo "<option value='$education_id2' selected='selected'>$education_name</option>";
								 }
								
								else
								{
						 			echo "<option value='$education_id2'>$education_name</option>";
								}

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
            	<textarea class="form-control" name="diet" placeholder="diet"><?php echo $lifestyle_diet;?></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 control-label">Drugs: </label>
            
            <div class="col-lg-8">
            	<textarea class="form-control" name="drugs" placeholder="drugs"><?php echo $lifestyle_drugs;?></textarea>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-lg-4 control-label">Alcohol %: </label>
            
            <div class="col-lg-8">
            	<input type="text" class="form-control" name="alcohol_percentage" placeholder="Alcohol %" value="<?php echo $lifestyle_alcohol_percentage;?>">
            </div>
        </div>
        
        <div class="form-group row">
            <label class="col-lg-4 control-label">Alcohol Qty: </label>
            
            <div class="col-lg-8">

            	<input type="text" class="form-control" name="alcohol_qty" placeholder="Alcohol Qty" value="<?php echo $lifestyle_alcohol_quantity;?>">
            </div>
        </div>
       
        
    </div>
</div>
</br>
<div class="row">
	<div class="center-align ">
		<button class="btn btn-info btn-sm" type="submit" name="save_lifestyle">Save Patient Lifestyle</button>
	</div>
</div>
<?php echo form_close();?>


<div class="row">
    <div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Allergies</h2>
			</header>
			<div class="panel-body">
				<!-- vitals from java script -->
				<div id="medication"></div>
				<!-- end of vitals data -->
			</div>
		</section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Surgeries</h2>
			</header>
			<div class="panel-body">
				<!-- vitals from java script -->
				<div id="surgeries"></div>
				<!-- end of vitals data -->
			</div>
		</section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Patient vaccine history</h2>
			</header>
			<div class="panel-body">
				<!-- vitals from java script -->
				<div id="patient_vaccine"></div>
				<!-- end of vitals data -->
			</div>
		</section>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
			<header class="panel-heading">
				<h2 class="panel-title">Family history</h2>
			</header>
			<div class="panel-body">
				<?php
					$v_data['patient_id'] = $this->reception_model->get_patient_id_from_visit($visit_id);
					$v_data['patient'] = $this->reception_model->patient_names2(NULL, $visit_id);
					$v_data['family_disease_query'] = $this->nurse_model->get_family_disease();
					$v_data['family_query'] = $this->nurse_model->get_family();
				?>
				<!-- vitals from java script -->
				<?php echo $this->load->view("patients/family_history", $v_data, TRUE); ?>
				<!-- end of vitals data -->
			</div>
		</section>
    </div>
</div>
