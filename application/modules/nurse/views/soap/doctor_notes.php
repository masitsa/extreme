<?php

$patient_id = $this->nurse_model->get_patient_id($visit_id);


$get_medical_rs = $this->nurse_model->get_doctor_notes($patient_id);
$num_rows = count($get_medical_rs);
//echo $num_rows;
echo '<div class="row">
		    <div class="col-md-12">
		       <div class="alert alert-danger">Nurse notes entery has been changed. Please find a button named <a hred="#" class="btn btn-sm btn-success" >Save Doctor Notes</a> or <a hred="#" class="btn btn-sm btn-primary" >Update Doctor Notes</a> to save the keyed in notes.. ~ development team </div>
		    </div>
		</div>';
if($num_rows > 0){
	foreach ($get_medical_rs as $key2) :
		$doctor_notes = $key2->doctor_notes;
	endforeach;
	
echo
'
	<div class="row">
		<div class="col-md-12">
			 <table align="center">
			 	<tr>
					<td><textarea id="doctor_notes_item" rows="10" cols="100" class="form-control col-md-6" >'.$doctor_notes.'</textarea></td>
		         </tr>
			</table>
		</div>
	</div>
	<br>
	<div class="row">
	    <div class="col-md-12">
	        <div class="form-group">
	            <div class="col-lg-12">
	                <div class="center-align">
	                      <a hred="#" class="btn btn-sm btn-primary" onclick="save_doctor_notes('.$visit_id.')">Update Doctor Notes</a>
	                  </div>
	            </div>
	        </div>
	    </div>
	</div>
';
}

else{
echo

'
	<div class="row">
		<div class="col-md-12">
			 <table align="center">
			 	<tr>
					<td><textarea id="doctor_notes_item" rows="10" cols="100" class="form-control col-md-6" ></textarea></td>
		         </tr>
			</table>
		</div>
	</div>
	<br>
	<div class="row">
	    <div class="col-md-12">
	        <div class="form-group">
	            <div class="col-lg-12">
	                <div class="center-align">
	                      <a hred="#" class="btn btn-sm btn-success" onclick="save_doctor_notes('.$visit_id.')">Save Doctor Notes</a>
	                  </div>
	            </div>
	        </div>
	    </div>
	</div>
';
}
	
?>
