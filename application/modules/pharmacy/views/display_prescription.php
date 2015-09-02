<?php 

$rs = $this->pharmacy_model->select_prescription($visit_id);
$num_rows =count($rs);

echo"
<input type='button' class='btn btn-primary' value='Load Prescription' onclick='window.location.reload()'/></div>
	<table class='table table-striped table-hover table-condensed'>
		 <tr>
		 	<th>No.</th>
			<th>Medicine:</th>
			<th>Dose</th>
			<th>Dose Unit</th>
			<th>Method</th>
			<th>Quantity</th>
			<th>Times</th>
			<th>Duration</th>
			<th>Start Date</th>
			<th>Finish Date</th>
			<th>Allow Substitution</th>
		</tr>";
$s=0;
foreach($rs as $key):
	$service_charge_id = $key->drugs_id;
	$frequncy = $key->drug_times_name;
	$id = $key->prescription_id;
	$date1 = $key->prescription_startdate;
	$date2 = $key->prescription_finishdate;
	$sub = $key->prescription_substitution;
	$duration = $key->drug_duration_name;
	$consumption = $key->drug_consumption_name;
	$quantity = $key->prescription_quantity;
	$medicine = $key->drugs_name;
	$s++;
	$rs2 = $this->pharmacy_model->get_drug($service_charge_id);
	
	foreach ($rs2 as $key2):
		$drug_type_id = $key2->drug_type_id;
		$admin_route_id = $key2->drug_administration_route_id;
		$dose = $key2->drugs_dose;
		$dose_unit_id = $key2->drug_dose_unit_id;
	endforeach;

	if(!empty($drug_type_id)){

		$rs3 = $this->pharmacy_model->get_drug_type_name($drug_type_id);
		$num_rows3 = count($rs3);
		if($num_rows3 > 0){
			foreach ($rs3 as $key3):
				$drug_type_name = $key3->drug_type_name;
			endforeach;
		}
	}
	
	else
	{
		$drug_type_name = '';
	}
	
	if(!empty($dose_unit_id)){

		$rs3 = $this->pharmacy_model->get_dose_unit2($dose_unit_id);
		$num_rows3 = count($rs3);
		if($num_rows3 > 0){
			foreach ($rs3 as $key3):
				$doseunit = $key3->drug_dose_unit_name;
			endforeach;
		}
	}
	
	else
	{
		$doseunit = '';
	}
	
	if(!empty($admin_route_id)){
		$rs3 = $this->pharmacy_model->get_admin_route2($admin_route_id);
		$num_rows3 = count($rs3);
		if($num_rows3 > 0){
			foreach ($rs3 as $key3):
				$admin_route = $key3->drug_administration_route_name;
			endforeach;
		}
	}
	
	else
	{
		$admin_route = '';
	}
	echo"
		<tr>
			<td>".($s)."</td>
			<td>".$medicine."</td>
			<td>".$dose."</td>
			<td>".$doseunit."</td>
			<td>".$consumption."</td>
			<td>".$quantity."</td>
			<td>".$frequncy."</td>
			<td>".$duration."</td>
			<td>".$date1."</td>
			<td>".$date2."</td>
			<td>".$sub."</td>
		</tr>";
endforeach;
echo "</table>";
?>