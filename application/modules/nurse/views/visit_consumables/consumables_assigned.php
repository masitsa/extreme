<?php

// get all the procedures

$visit__rs1 = $this->nurse_model->get_visit_consumables_charges($visit_id);

echo "
	<table align='center' class='table table-striped table-hover table-condensed'>
	<tr>
		<th>#</th>
		<th>Consumable Name</th>
		<th>Units</th>
		<th>Unit Cost</th>
		<th>Total</th>
		<th></th>
		<th></th>
	</tr>
	";
$total = 0;
$count = 0;
foreach ($visit__rs1 as $key1):
    $v_vaccine_id        = $key1->visit_charge_id;
    $vaccine_id          = $key1->service_charge_id;
    $visit_charge_amount = $key1->visit_charge_amount;
    $units               = $key1->visit_charge_units;
    $consumable        = $key1->service_charge_name;
    $service_id          = $key1->service_id;
    $total = $total + ($units * $visit_charge_amount);
    $count++;
    
    echo "
					<tr> 
						<td>" . $count . "</td>
			 			<td align='center'>" . $consumable . "</td>
						<td align='center'><input type='text' id='units" . $v_vaccine_id . "' value='" . $units . "' size='3' onkeyup='calculatevaccinetotal(" . $visit_charge_amount . "," . $v_vaccine_id . ", " . $vaccine_id . "," . $visit_id . ")'/></td>
						<td align='center'>" . $visit_charge_amount . "</td>
						<td align='center'><input type='text' readonly='readonly' size='5' value='" . $units * $visit_charge_amount . "' id='total" . $v_vaccine_id . "'></div></td>
						<td>
							<a class='btn btn-sm btn-primary' href='#' onclick='calculateconsumabletotal(" . $visit_charge_amount . "," . $v_vaccine_id . ", " . $vaccine_id . "," . $visit_id . ")'><i class='fa fa-pencil'></i></a>
						</td>
						<td>
							<a class='btn btn-sm btn-danger' href='#' onclick='delete_consumable(" . $v_vaccine_id . ", " . $visit_id . ")'><i class='fa fa-trash'></i></a>
						</td>
					</tr>	
			";
endforeach;
echo "
<tr bgcolor='#D9EDF7'>
	<th colspan='4'>Grand Total: </th>
	<th colspan='3'><div id='grand_total'>" . number_format($total, 2) . "</div></th>
</tr>
 </table>
";
?>