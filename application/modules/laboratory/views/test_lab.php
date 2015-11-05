<?php

if ($this->session->userdata('nurse_lab') <> NULL){
	$nurse_lab = $this->session->userdata('nurse_lab');
}

// $rs2 = $this->lab_model->get_lab_visit2($visit_id);
//  $num_rows2 = count($rs2);
// if($num_rows2 > 0){
// 	foreach ($rs2 as $key):
// 		 $lab_visit = $key->lab_visit;
// 	endforeach;
// }
if(!empty($service_charge_id))
{
	//get lab test id from service charge
	 $lab_test_id_array = $this->lab_model->get_lab_test_id($service_charge_id);

	foreach ($lab_test_id_array as $key_array):
		# code...
		$lab_test_id = $key_array->lab_test_id;
	endforeach;
	
	//get lab test formats
	$get_lab_visit_rs = $this->lab_model->get_visits_lab_result($visit_id, $lab_test_id);
	$num_rows = count($get_lab_visit_rs);
	
	if ($num_rows == 0 ){//if no formats
		$rs = $this->lab_model->get_lab_visit($visit_id, $service_charge_id);
		$num_visit = count($rs);
		
		//save lab test into the lab billing table(may be multiple)
		$visit_lab_test_id = $this->lab_model->save_lab_visit_trail($visit_id, $service_charge_id);
		if($num_visit > 0){//if visit charge has been saved
			/*$save= new doctor();
			$save->update_lab_visit($visit_id,$service_charge_id);*/
		}
		else{

			//$this->lab_model->save_lab_visit_trail($visit_id, $service_charge_id);
			// $this->lab_model->save_lab_visit($visit_id, $service_charge_id);
		}
	}
	
	else{//if there are formats

		$rs = $this->lab_model->get_lab_visit($visit_id, $service_charge_id);
		$num_visit = count($rs);
		
		//save lab test into the lab billing table(may be multiple)
		$visit_lab_test_id = $this->lab_model->save_lab_visit_trail($visit_id, $service_charge_id);
		//echo $num_visit;
		if($num_visit > 0){//if visit charge has been saved
			/*$save= new doctor();
			$save->update_lab_visit($visit_id,$service_charge_id);*/
		}
		else{
			
			//$this->lab_model->save_lab_visit_trail($visit_id, $service_charge_id);
		}
		
		if($visit_lab_test_id != FALSE)
		{
			foreach ($get_lab_visit_rs as $key2 ): 		
				$lab_format_id = $key2->lab_test_format_id;
			
				$this->lab_model->save_lab_visit_format($visit_id, $service_charge_id, $lab_format_id, $visit_lab_test_id);
			endforeach;
		}
	}
}
$rs = $this->lab_model->get_lab_visit2($visit_id);
$num_rows = count($rs);

echo "

<table class='table table-striped table-hover table-condensed'>
	<thead>
		<th>No.</th>
    	<th>Test</th>
		<th>Cost</th>
	</thead>
	<tbody>
";

$total = 0;
$s=0;
foreach ($rs as $key6):
	
	$visit_lab_test_id = $key6->visit_lab_test_id;
	$test = $key6->service_charge_name;
	$price = $key6->service_charge_amount;
	$service_charge_id = $key6->service_charge_id;
	$total = $total + $price;
	$s++;
	echo "
		<tr>
        	<td>".($s)."</td>
			<td>".$test."</td>
			<td>".number_format($price, 2)."</td>
			<td>
				<div class='btn-toolbar'>
					<div class='btn-group'>
						<a class='btn btn-danger btn-sm' href='#' onclick='delete_cost(".$visit_lab_test_id.", ".$visit_id.")'><i class='fa fa-trash'></i></a>
					</div>
				</div>
			</td>
		</tr>
	";

endforeach;
$lab_visit = 0;
echo "
	<tr bgcolor='#D9EDF7'>
		<th colspan=2>Total</th>
		<th>".number_format($total, 2)."</th>";
if(!empty($nurse_lab)){
	echo"	<td><input type='button' class='btn btn-primary' onclick='update_doctor(".$visit_id.")' value='Send to Lab'/></td>
	</tr>
";
}
else if($lab_visit == 5){
	echo"	<td><input type='button' class='btn btn-primary' onclick='send_to_lab3(".$visit_id.")' value='Done'/></td>
	</tr>
";
}
else{
	echo"	<td><input type='button' class='btn btn-primary' onclick='send_to_lab2(".$visit_id.")' value='Done'/></td>
	</tr>
";
}

echo "
</tbody>
</table>

";

?>