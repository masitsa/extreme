<?php 

if($update_id==1){
$update_objz= $this->nurse_model->update_objective_finding($objective_finding_id, $visit_id, $description);

 }else{
$get_namez= $this->nurse_model->save_objective_finding($objective_finding_id, $visit_id);
 }
$rs = $this->nurse_model->get_objective_findings($visit_id);
$num_rows = count($rs);

$rs2 = $this->nurse_model->get_visit_objective_findings($visit_id);
$num_rows2 = count($rs2);

echo "
	<div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'>Objective Findings <br/><input type='button' class='btn btn-primary' value='Add Objective Findings' onclick='open_objective_findings(".$visit_id.")'/></p></div>";


if($num_rows > 0){
	foreach ($rs as $key2):
		$visit_objective_findings = $key2->visit_objective_findings;
	endforeach;
	echo
	"	<table align='left'>
			<tr>
				<td>
					<textarea rows='10' cols='80' class='form-control col-md-6'  id='visit_symptoms1' disabled='disabled'>";
					$z = 0;

						foreach ($rs2 as $key):
							
							$count=$z+1;
							$objective_findings_name = $key->objective_findings_name;
							$visit_objective_findings_id = $key->visit_objective_findings_id;
							$objective_findings_class_name = $key->objective_findings_class_name;
							$description= $key->description;
							
							
							echo $objective_findings_class_name.":".$objective_findings_name." ->".$description."\n" ;
						endforeach;
					echo $visit_objective_findings; echo "
					</textarea>
				</td>
			</tr>
		</table>
		<table align='center'>
			<tr>
				<td>
					<textarea rows='10' cols='80' class='form-control col-md-6'  id='objective_findings' onKeyUp='save_symptoms(".$visit_id.")'>".$visit_objective_findings."</textarea>
				</td>
			</tr>
		</table>
	";

}
else{
	echo
	"		<table align='left'>
			<tr>
				<td>
					<textarea rows='10' cols='80' class='form-control col-md-6'  id='visit_symptoms' disabled='disabled'>"; 
						$z = 0;

						foreach ($rs2 as $key):
							
							$count=$z+1;
							$objective_findings_name = $key->objective_findings_name;
							$visit_objective_findings_id = $key->visit_objective_findings_id;
							$objective_findings_class_name = $key->objective_findings_class_name;
						
						echo $objective_findings_name.":".$objective_findings_class_name." ->".$description."\n" ;
				endforeach;
				 echo $visit_objective_findings; echo "
				</textarea>
				</td>
			</tr>
		<table align='center'>
			<tr>
				<td>
					<textarea rows='10' cols='80' class='form-control col-md-6'  id='objective_findings' onKeyUp='save_symptoms(".$visit_id.")'></textarea>
				</td>
			</tr>
		</table>
	";
}
?>