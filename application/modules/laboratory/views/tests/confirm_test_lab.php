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
if(!empty($service_charge_id)){
	
	 $lab_test_id_array = $this->lab_model->get_lab_test_id($service_charge_id);

	foreach ($lab_test_id_array as $key_array):
		# code...
		$lab_test_id = $key_array->lab_test_id;
	endforeach;

}

if(!empty($service_charge_id)){
	
	$get_lab_visit_rs = $this->lab_model->get_visits_lab_result($visit_id, $lab_test_id);
	$num_rows = count($get_lab_visit_rs);
	
	if ($num_rows == 0 ){//if no formats


		$rs = $this->lab_model->get_lab_visit($visit_id, $service_charge_id);
		$num_visit = count($rs);
		
		if($num_visit > 0){//if visit charge has been saved
			/*$save= new doctor();
			$save->update_lab_visit($visit_id,$service_charge_id);*/
			$this->lab_model->save_lab_charge($visit_id, $service_charge_id);
		}
		else{

			$this->lab_model->save_lab_visit_trail($visit_id, $service_charge_id);
			// $this->lab_model->save_lab_visit($visit_id, $service_charge_id);
		}
	}
	
	else{//if there are formats

		$rs = $this->lab_model->get_lab_visit($visit_id, $service_charge_id);
		$num_visit = count($rs);
		//echo $num_visit;
		if($num_visit > 0){//if visit charge has been saved
			/*$save= new doctor();
			$save->update_lab_visit($visit_id,$service_charge_id);*/
			$this->lab_model->save_lab_charge($visit_id, $service_charge_id);
		}
		else{
			
			$this->lab_model->save_lab_visit_trail($visit_id, $service_charge_id);
		}
		
		foreach ($get_lab_visit_rs as $key2 ): 		
			$lab_format_id = $key2->lab_test_format_id;
		
			$this->lab_model->save_lab_visit_format($visit_id, $service_charge_id, $lab_format_id);
		endforeach;
	}
}
$rs = $this->lab_model->get_lab_visit2($visit_id);
$num_rows = count($rs);

echo "
<div class='alert alert-info'>Please ensure you have clicked <a class='btn btn-success btn-sm'>Charge for lab test</a></div>
<table class='table table-striped table-hover table-condensed'>
	<thead>
		<th>No.</th>
    	<th>Test</th>
		<th>Cost</th>
		<th colspan=2></th>
	</thead>
	<tbody>
";

$send = "<a class='btn btn-warning btn-sm' href='".site_url()."laboratory/send_to_accounts/".$visit_id."' onclick='return confirm(\'Do you want to send the patient to the accounts ?\');'>Send to accounts</a>

<a class='btn btn-info btn-sm' href='".site_url()."laboratory/send_to_doctor/".$visit_id."' onclick='return confirm(\'Do you want to send the patient to the doctor ?\');'>Send to doctor</a>
";

$total = 0;
$s=0;
foreach ($rs as $key6):
	
	$visit_lab_test_id = $key6->visit_lab_test_id;
	$test = $key6->service_charge_name;
	$price = $key6->service_charge_amount;
	$total = $total + $price;
	$service_charge_id = $key6->service_charge_id;

	// check if lab test exisit in the visit charge table
	$visit_charge_id = $this->lab_model->check_visit_charge_lab_test($visit_lab_test_id);
	//echo $visit_lab_test_id."<br/>";

	if($visit_charge_id > 0)
	{
		$button = "<a class='btn btn-danger btn-sm' href='".site_url()."laboratory/remove_cost/".$visit_charge_id."/".$visit_id."' onclick='return confirm(\'Do you want to remove ".$test." from the invoice ?\');'>Remove lab test charge</a>";
		
		$status = '';
	}
	else
	{
		$button = "<a class='btn btn-success btn-sm' onclick='update_lab_test_charge(".$visit_lab_test_id.",".$visit_id.")' >Charge for lab test</a>";
		$status ="<a class='btn btn-info btn-sm' href='".site_url()."laboratory/remove_lab_test/".$visit_lab_test_id."/".$visit_id."' onclick='return confirm(\"Are you sure you want to remove this test?\");'>Remove from list</a>";
	}

	$s++;
	echo "
		<tr>
        	<td>".($s)."</td>
			<td>".$test."</td>
			<td><input type='text' value='".$price."' id='lab_test_price".$visit_lab_test_id."'/> </td>
			<td>
				<div class='btn-toolbar'>
					<div class='btn-group'>
						".$button."
					</div>
				</div>
			</td>
			<td>
				<div class='btn-toolbar'>
					<div class='btn-group'>
						".$status."
					</div>
				</div>
			</td>
		</tr>
	";

endforeach;

$lab_visit = 0;
echo "
</tbody>
</table>
";
echo '<div class="center-align">'.$send.'</div>';

?>