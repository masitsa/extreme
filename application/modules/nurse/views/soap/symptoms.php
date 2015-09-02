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
	<div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'>Symptoms <br/><input type='button' class='btn btn-primary' value='Add Symptoms' onclick='open_symptoms(".$visit_id.")'/></p></div>";

if($num_rows2 > 0){
	echo"
		<table align='center' class='table table-striped table-hover table-condensed'>
		<tr>
			<th></th>
			<th>Symptom</th>
			<th>Description</th>
			<th></th>
		</tr>
	";	
	$z=0;
		foreach ($rs2 as $key):
		
		$count=$z+1;
		$symptoms_name = $key->symptoms_name;
		$status_name = $key->status_name;
		$visit_symptoms_id = $key->visit_symptoms_id;
		echo"
		<tr> 
			<td>".$count."</td>
 			<td align='center'>".$symptoms_name."</td>
 			<td align='center'>".$status_name."</td>
			<td>
				<div class='btn-toolbar'>
					<div class='btn-group'>
						<a class='btn' href='#' onclick='delete_symptom(".$visit_symptoms_id.", ".$visit_id.")'><i class='icon-remove'></i></a>
					</div>
				</div>
			</td>
		</tr>	
		";
	endforeach;
echo"
 </table>
";
}
	
if($num_rows > 0){
	foreach ($rs as $key2):
		
	$visit_symptoms = $key2->visit_symptoms;
	endforeach;
	echo
	"
		<table align='center'>
			<tr>
				<td>
					<textarea rows='5' cols='100' id='visit_symptoms' onKeyUp='save_symptoms(".$visit_id.")'>".$visit_symptoms."</textarea>
				</td>
			</tr>
		</table>
	";
}

else{
	echo
	"
		<table align='center'>
			<tr>
				<td>
					<textarea rows='5' cols='100' id='visit_symptoms' onKeyUp='save_symptoms(".$visit_id.")'></textarea>
				</td>
			</tr>
		</table>


	";
}

?>
