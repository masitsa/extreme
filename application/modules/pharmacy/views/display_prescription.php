<?php 

$rs = $this->pharmacy_model->select_prescription($visit_id);
$num_rows =count($rs);

echo"
	<div class='center-align' style='margin-bottom:10px;'><input type='button' class='btn btn-primary' value='Load Prescription' onclick='window.location.reload()'/></div>
	<table class='table table-striped table-hover table-condensed'>
		 <tr>
		 	<th>No.</th>
			<th>Medicine:</th>
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
	$service_charge_id = $key->product_id;
	$frequncy = $key->drug_times_name;
	$id = $key->prescription_id;
	$date1 = $key->prescription_startdate;
	$date2 = $key->prescription_finishdate;
	$sub = $key->prescription_substitution;
	$duration = $key->drug_duration_name;
	$consumption = $key->drug_consumption_name;
	$quantity = $key->prescription_quantity;
	$medicine = $key->product_name;
	// $dose = $key->unit_of_measure;
	// $drug_type_name = $key->drug_type_name;


	$s++;
	$rs2 = $this->pharmacy_model->get_drug($service_charge_id);
	$dose = '';
	foreach ($rs2 as $key2) {
		$dose = $key2->unit_of_measure;
	   // $drug_type_name = $key2->drug_type_name;
	}
	

	echo"
		<tr>
			<td>".($s)."</td>
			<td>".$medicine."</td>
			<td>".$dose."</td>
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