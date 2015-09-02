<?php

$patient_id = $this->nurse_model->get_patient_id($visit_id);

$get_medical_rs = $this->nurse_model->get_medicals($patient_id);
$num_rows = count($get_medical_rs);
//echo $num_rows;
echo "";
if($num_rows > 0){
	foreach ($get_medical_rs as $key):
	$food_allergies = $key->food_allergies;
	$medicine_allergies = $key->medicine_allergies;
	$regular_treatment = $key->regular_treatment;
	$recent_medication = $key->medication_name;
	endforeach;
	echo "
	 <div class='row'>
            <div class='col-md-6'>
            	<div class='form-group'>
		            <label class='col-lg-4 control-label'>Food Allergies: </label>
		            
		            <div class='col-lg-8'>
		            	<textarea id='food_allergies' class='form-control'>".$food_allergies."</textarea>
		            </div>
		        </div>
		        <div class='form-group'>
		            <label class='col-lg-4 control-label'>Medicine Allergies: </label>
		            
		            <div class='col-lg-8'>
		            	<textarea id='medicine_allergies' class='form-control'>".$medicine_allergies."</textarea>
		            </div>
		        </div>
            </div>
            <div class='col-md-6'>
            	<div class='form-group'>
		            <label class='col-lg-4 control-label'>Regular Treatment: </label>
		            
		            <div class='col-lg-8'>
		            	<td><textarea id='regular_treatment' class='form-control'>".$regular_treatment."</textarea>
		            </div>
		        </div>
		        <div class='form-group'>
		            <label class='col-lg-4 control-label'>Recent Medication: </label>
		            
		            <div class='col-lg-8'>
		            	<textarea id='medication_description' class='form-control'>".$recent_medication."</textarea>
		            </div>
		        </div>
            </div>
      </div>
	
	<div class='align-center'>
		<input type='button' class='btn btn-large' value='Update' onclick='save_medication(".$visit_id.")' />
		
    </div>
";
}

else{
echo
"
	<div class='row'>
            <div class='col-md-6'>
            	<div class='form-group'>
		            <label class='col-lg-4 control-label'>Food Allergies: </label>
		            
		            <div class='col-lg-8'>
		            	<textarea id='food_allergies' class='form-control'></textarea>
		            </div>
		        </div>
		        <div class='form-group'>
		            <label class='col-lg-4 control-label'>Medicine Allergies: </label>
		            
		            <div class='col-lg-8'>
		            	<textarea id='medicine_allergies' class='form-control'></textarea>
		            </div>
		        </div>
            </div>
            <div class='col-md-6'>
            <div class='col-md-12'>
            	<div class='form-group'>
		            <label class='col-lg-4 control-label'>Regular Treatment: </label>
		            
		            <div class='col-lg-8'>
		            	<textarea id='regular_treatment' class='form-control'></textarea>
		            </div>
		        </div>
		        <div class='form-group'>
		            <label class='col-lg-4 control-label'>Recent Medication: </label>
		            
		            <div class='col-lg-8'>
		            	<textarea id='medication_description' class='form-control'></textarea>
		            </div>
		        </div>
            </div>
            </div>
      </div>
	
	<div class='align-center'>
		<input type='button' class='btn btn-large' value='Save' onclick='save_medication(".$visit_id.")' />
		
    </div>
";
}
	
?>