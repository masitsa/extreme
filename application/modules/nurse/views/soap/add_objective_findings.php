<?php 

$rs = $this->nurse_model->get_objective_findings($visit_id);
$num_rows = count($rs);

$rs2 = $this->nurse_model->get_visit_objective_findings($visit_id);
$num_rows2 = count($rs2);

echo 
"
	<div class='row'>
		<div class='col-md-12 center-align' style='padding-bottom:10px;'>
			<input type='button' class='btn btn-primary' value='Add Objective Findings' onclick='open_objective_findings(".$visit_id.")'/>
		</div>
	</div>
";


if($num_rows > 0){
	foreach ($rs as $key2):
		$visit_objective_findings = $key2->visit_objective_findings;
	endforeach;
	echo
	"	
		<div class='row'>
			<div class='col-md-6'>
				<textarea rows='10'' class='form-control col-md-6'  id='visit_objective_findings1' disabled='disabled'>";
				$z = 0;

					foreach ($rs2 as $key):
						
						$count=$z+1;
						$objective_findings_name = $key->objective_findings_name;
						$visit_objective_findings_id = $key->visit_objective_findings_id;
						$objective_findings_class_name = $key->objective_findings_class_name;
						$description= $key->description;
						
						echo $objective_findings_class_name.":".$objective_findings_name." ->".$description."\n" ;
					endforeach;
				echo $visit_objective_findings;
				echo "
				</textarea>
			</div>
			
			<div class='col-md-6'>
					<textarea rows='10' class='form-control col-md-6'  id='visit_objective_findings' onKeyUp='save_objective_findings(".$visit_id.")'>".$visit_objective_findings."</textarea>
			</div>
		</div>
	";

}
else{
	echo
	"
		<div class='row'>
			<div class='col-md-6'>
					<textarea rows='10' class='form-control'  id='visit_objective_findings1' disabled='disabled'>"; 
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
			</div>
			
			<div class='col-md-6'>
					<textarea rows='10' class='form-control' id='visit_objective_findings' onKeyUp='save_objective_findings(".$visit_id.")'></textarea>
			</div>
		</div>
	";
}
	echo "
	<div class='row'>
			<div class='center-align ' style='margin-top:10pxl'>
				<a class='btn btn-info btn-sm' type='submit' onclick='save_objective_findings(".$visit_id.")'>Save Objective Findings</a>
			</div>
	</div>";
?>