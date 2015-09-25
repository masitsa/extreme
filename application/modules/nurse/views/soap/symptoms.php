<?php


if ($status==0){
	$this->nurse_model->update_visit_sypmtom($symptoms_id,$visit_id,$description);
}else{

	$this->nurse_model->save_visit_sypmtom($symptoms_id,$visit_id,$status);
}

$rs = $this->nurse_model->get_symptoms($visit_id);
$num_rows = count($rs);

$rs2 = $this->nurse_model->get_visit_symptoms($visit_id);
$num_rows2 = count($rs2);

echo "
	<div class='row'>
		<div class='col-md-12 center-align'>
			<input type='button' class='btn btn-primary' value='Add Symptoms' onclick='open_symptoms(".$visit_id.")'/>
		</div>
	</div>
	<div class='row' style='margin-top:10px;'>";

if($num_rows2 > 0)
{
	echo
	"
		<div class='col-md-6'>
			<textarea class='form-control' class='form-control' rows='8' id='visit_symptoms1' disabled='disabled'>"; 
			$z=0;
			foreach ($rs2 as $key):	
				$count=$z+1;
				$symptoms_name = $key->symptoms_name;
				$status_name = $key->status_name;
				$visit_symptoms_id = $key->visit_symptoms_id;
				$description= $key->description;
				
				echo $symptoms_name.", ".$status_name."->".$description."\n" ;
			endforeach; echo "
			</textarea>
		</div>
	";
}

else
{
	echo
	"
		<div class='col-md-6'>
			<textarea class='form-control' class='form-control' rows='8' id='visit_symptoms1' disabled='disabled'></textarea>
		</div>
	";
}
	
if($num_rows > 0){
	foreach ($rs as $key2):
		
	$visit_symptoms = $key2->visit_symptoms;
	endforeach;
	echo
	"
		<div class='col-md-6'>
			<textarea rows='8' class='form-control' id='visit_symptoms' onKeyUp='save_symptoms(".$visit_id.")'>".$visit_symptoms."</textarea>
		</div>
	";
}

else{
	echo
	"
		<div class='col-md-6'>
			<textarea rows='8' class='form-control' id='visit_symptoms' onKeyUp='save_symptoms(".$visit_id.")'></textarea>
		</div>
	";
}

echo '</div>';
	echo "
	
	<div class='row' style='margin-top:10px;'>
			<div class='center-align '>
				<a class='btn btn-info btn-sm' type='submit' onclick='save_symptoms(".$visit_id.")'>Update Symptoms</a>
			</div>
	</div>

		";

?>
