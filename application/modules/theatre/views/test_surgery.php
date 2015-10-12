<?php

if(!empty($service_charge_id))
{
	$rs = $this->theatre_model->get_surgery_visit($visit_id, $service_charge_id);
	$num_visit = count($rs);
	
	if($num_visit > 0)
	{
		//if visit charge has been saved dont save it again
	}
	else
	{
		$this->theatre_model->save_surgery_visit($visit_id, $service_charge_id);
	}
}
$rs = $this->theatre_model->get_surgery_visit2($visit_id);
$num_rows = count($rs);

echo "

<table class='table table-striped table-hover table-condensed'>
	<tr>
		<th>No.</th>
    	<th>Surgery</th>
		<th>Cost</th>
		<th></th>
	</tr>
	<tbody>
";

$total = 0;
$s=0;
foreach ($rs as $key6):
	
	$visit_charge_id = $key6->visit_charge_id;
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
				<a class='btn btn-danger btn-sm' href='#' onclick='delete_surgery_cost(".$visit_charge_id.", ".$visit_id.");'><i class='fa fa-trash'></i></a>
			</td>
		</tr>
	";

endforeach;
$surgery_visit = 0;
echo "
	<tr bgcolor='#D9EDF7'>
		<th colspan='2'>Total</th>
		<th>".number_format($total, 2)."</th>";
if($surgery_visit == 5){
	echo"	<td><input type='button' class='btn btn-primary' onclick='send_to_surgery3(".$visit_id.")' value='Done'/></td>
	</tr>
";
}
else{
	echo"	<td><input type='button' class='btn btn-primary' onclick='send_to_surgery2(".$visit_id.")' value='Done'/></td>
	</tr>
";
}

echo "
</tbody>
</table>

";

?>