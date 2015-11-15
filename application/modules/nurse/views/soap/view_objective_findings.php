<?php


$rs = $this->nurse_model->get_objective_findings($visit_id);
$num_rows = count($rs);

$rs2 = $this->nurse_model->get_visit_objective_findings($visit_id);
$num_rows2 = count($rs2);

echo "
	<div class='row' style='margin-bottom:10px;'>
		<div class='col-md-12 center-align'>
			<input type='button' class='btn btn-primary' value='Add Objective Findings' onclick='open_objective_findings(".$visit_id.")'/>
		</div>
	</div>";

if($num_rows > 0){
	foreach ($rs as $key1):
		$visit_objective_findings = $key1->visit_objective_findings;
	endforeach;
	echo
	"
	<div class='row'>
		<div class='col-md-12' id='visit_objective_findings1'>"; 
			
	echo"<table class='table table-condensed table-striped table-bordered'>"; 
		echo"<tr>"; 
			echo"<th>";
				echo"#"; 
			echo"</th>"; 
			echo"<th>";
				echo"Group"; 
			echo"</th>"; 
			echo"<th>";
				echo"Name"; 
			echo"</th>"; 
			echo"<th>";
				echo"Description"; 
			echo"</th>"; 
		echo"</tr>"; 
		$count=0;
		
			foreach ($rs2 as $key):
				$count++;
				$objective_findings_name = $key->objective_findings_name;
				$visit_objective_findings_id = $key->visit_objective_findings_id;
				$objective_findings_class_name = $key->objective_findings_class_name;
				$description= $key->description;
				
				echo"<tr>"; 
					echo"<td>";
						echo $count; 
					echo"</td>"; 
					echo"<td>";
						echo $objective_findings_class_name; 
					echo"</td>"; 
					echo"<td>";
						echo $objective_findings_name; 
					echo"</td>"; 
					echo"<td>";
						echo $description; 
					echo"</td>"; 
				echo"<tr>"; 
			endforeach; 
			echo "
			</table>
		</div>
		
		<div class='col-md-12'>
			<textarea class='cleditor' rows='10' id='visit_objective_findings' onkeyup='save_objective_findings(".$visit_id.")'>".$visit_objective_findings."</textarea>
		</div>
	</div>
	";
	echo "
	
	<div class='row' style='margin-top:60px;'>
			<div class='center-align '>
				<a class='btn btn-info btn-sm' type='submit' onclick='save_objective_findings(".$visit_id.")'>Update Objective Findings</a>
			</div>
	</div>

		";
}

else{
	echo
	"
	<div class='row'>
		<div class='col-md-12' id='visit_objective_findings1'>"; 
			
	echo"<table class='table table-condensed table-striped table-bordered'>"; 
		echo"<tr>"; 
			echo"<th>";
				echo"#"; 
			echo"</th>"; 
			echo"<th>";
				echo"Group"; 
			echo"</th>"; 
			echo"<th>";
				echo"Name"; 
			echo"</th>"; 
			echo"<th>";
				echo"Description"; 
			echo"</th>"; 
		echo"</tr>"; 
		$count=0;
		
			foreach ($rs2 as $key):
				$count++;
				$objective_findings_name = $key->objective_findings_name;
				$visit_objective_findings_id = $key->visit_objective_findings_id;
				$objective_findings_class_name = $key->objective_findings_class_name;
				$description= $key->description;
				
				echo"<tr>"; 
					echo"<td>";
						echo $count; 
					echo"</td>"; 
					echo"<td>";
						echo $objective_findings_class_name; 
					echo"</td>"; 
					echo"<td>";
						echo $objective_findings_name; 
					echo"</td>"; 
					echo"<td>";
						echo $description; 
					echo"</td>"; 
				echo"<tr>"; 
			endforeach; 
			echo "
			</table>
		</div>
		
		<div class='col-md-12' style='height:400px;'>
			<textarea rows='10' class='cleditor' id='visit_objective_findings' onkeyup='save_objective_findings(".$visit_id.")'>".$visit_objective_findings."</textarea>
		</div>
	</div>
	";
	echo "
	<div class='row' style='margin-top:60px;'>
		<div class='center-align '>
			<a class='btn btn-info btn-sm' type='submit' onclick='save_objective_findings(".$visit_id.")'>Save Objective Findings</a>
		</div>
	</div>

		";
}


?>