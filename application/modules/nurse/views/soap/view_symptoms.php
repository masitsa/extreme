<?php


$rs = $this->nurse_model->get_symptoms($visit_id);
$num_rows = count($rs);

$rs2 = $this->nurse_model->get_visit_symptoms($visit_id);
$num_rows2 = count($rs2);

echo "
	<div class='row'>
		<div class='col-md-12 center-align'>
			<input type='button' class='btn btn-primary' value='Add Symptoms' onclick='open_symptoms(".$visit_id.")'/>
		</div>
	</div>";
	
if($num_rows > 0){
	foreach ($rs as $key1):
		$visit_symptoms = $key1->visit_symptoms;
	endforeach;
	echo
	"
	<div class='row'>
		<div class='col-md-12' id='visit_symptoms1'>"; 
	echo"<table class='table table-striped table-condensed table-bordered'>"; 
		echo"<tr>"; 
			echo"<th>";
				echo"#"; 
			echo"</th>"; 
			echo"<th>";
				echo"Symptom"; 
			echo"</th>"; 
			echo"<th>";
				echo"Yes/ No"; 
			echo"</th>"; 
			echo"<th>";
				echo"Description"; 
			echo"</th>"; 
		echo"</tr>"; 
			$count=0;
			foreach ($rs2 as $key):	
				$count++;
				$symptoms_name = $key->symptoms_name;
				$status_name = $key->status_name;
				$visit_symptoms_id = $key->visit_symptoms_id;
				$description= $key->description;
				
				echo"<tr>"; 
					echo"<td>";
						echo $count; 
					echo"</td>"; 
					echo"<td>";
						echo $symptoms_name; 
					echo"</td>"; 
					echo"<td>";
						echo $status_name; 
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
			<textarea class='cleditor' placeholder='Type Additional Symptoms Here' id='visit_symptoms' >".$visit_symptoms."</textarea>
		</div>
	</div>
	";
	echo "
	<div class='row' style='margin-top:60px;'>
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
		<div class='col-md-12'>"; 
			
	echo"<table class='table table-condensed table-striped table-bordered'>"; 
		echo"<tr>"; 
			echo"<th>";
				echo"#"; 
			echo"</th>"; 
			echo"<th>";
				echo"Symptom"; 
			echo"</th>"; 
			echo"<th>";
				echo"Yes/ No"; 
			echo"</th>"; 
			echo"<th>";
				echo"Description"; 
			echo"</th>"; 
		echo"</tr>"; 
			$count=0;
			foreach ($rs2 as $key):	
				$count++;
				$symptoms_name = $key->symptoms_name;
				$status_name = $key->status_name;
				$visit_symptoms_id = $key->visit_symptoms_id;
				$description= $key->description;
				
				echo"<tr>"; 
					echo"<td>";
						echo $count; 
					echo"</td>"; 
					echo"<td>";
						echo $symptoms_name; 
					echo"</td>"; 
					echo"<td>";
						echo $status_name; 
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
			<textarea class='cleditor' placeholder='Type Additional Symptoms Here' id='visit_symptoms'>".$visit_symptoms."</textarea>
		</div>
	</div>
	";
	echo "
	
	<div class='row' style='margin-top:60px;'>
			<div class='center-align '>
				<a class='btn btn-info btn-sm' type='submit' onclick='save_symptoms(".$visit_id.")'>Save Symptoms</a>
			</div>
	</div>

		";
}

?>