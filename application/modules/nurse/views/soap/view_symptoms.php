<?php


$rs = $this->nurse_model->get_symptoms($visit_id);
$num_rows = count($rs);

$rs2 = $this->nurse_model->get_visit_symptoms($visit_id);
$num_rows2 = count($rs2);

echo "
	<div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'><input type='button' class='btn btn-primary' value='Add Symptoms' onclick='open_symptoms(".$visit_id.")'/></p></div>";


	
if($num_rows > 0){
	foreach ($rs as $key1):
	$visit_symptoms = $key1->visit_symptoms;
	endforeach;
	echo
	"
	<div class='row'>
		<div class='col-md-6'>
			<textarea class='form-control' class='form-control' rows='8' id='visit_symptoms1' disabled='disabled'>"; 
			$z=0;
			foreach ($rs2 as $key):	
				$count=$z+1;
				$symptoms_name = $key->symptoms_name;
				$status_name = $key->status_name;
				$visit_symptoms_id = $key->visit_symptoms_id;
				$description= $key->description;
				
				echo $symptoms_name." ->".$description."\n" ;
			endforeach;
			echo $visit_symptoms; echo "
			</textarea>
		</div>
		
		<div class='col-md-6'>
			<textarea class='form-control' rows='8' placeholder='Type Additional Symptoms Here' id='visit_symptoms' >".$visit_symptoms."</textarea>
		</div>
	</div>
	";
	echo "
	<br>
	<div class='row'>
			<div class='center-align '>
				<a class='btn btn-info btn-sm' type='submit' onclick='save_symptoms(".$visit_id.")'>Update Symptoms</a>
			</div>
	</div>

		";
}

else{
	echo
	"
	<div class='row'>
		<div class='col-md-6'>
			<textarea class='form-control' rows='8' id='visit_symptoms1' disabled='disabled'>"; 
			$z=0;
			foreach ($rs2 as $key):	
				$count=$z+1;
				$symptoms_name = $key->symptoms_name;
				$status_name = $key->status_name;
				$visit_symptoms_id = $key->visit_symptoms_id;
				$description= $key->description;
					
					echo $symptoms_name." ->".$description."\n" ;
			endforeach;
			 echo $visit_symptoms; echo "
			 </textarea>
		</div>
		
		<div class='col-md-6'>
			<textarea class='form-control' rows='8' placeholder='Type Additional Symptoms Here' id='visit_symptoms'></textarea>
		</div>
	</div>
	";
	echo "
	<br>
	<div class='row'>
			<div class='center-align '>
				<a class='btn btn-info btn-sm' type='submit' onclick='save_symptoms(".$visit_id.")'>Save Symptoms</a>
			</div>
	</div>

		";
}

?>
