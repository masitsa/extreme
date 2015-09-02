<?php

//  check if visit exists

$rs = $this->nurse_model->check_visit_type($visit_id);

if(count($rs) >0){
	foreach ($rs as $rs1):
		# code...
		$visit_t = $rs1->visit_type;

		// get the visit charge

	endforeach;

	$rs2 = $this->nurse_model->visit_charge($visit_id);

	


}



echo "
	<div class='navbar-inner'><p style='text-align:center; color:#0e0efe;'><input type='button' class='btn btn-primary' value='Add Vaccine' onclick='myPopup4(".$visit_id.")'/></p></div>
<table align='center' class='table table-striped table-hover table-condensed'>
	<tr>
		
		<th>Vaccine</th>
		<th>Units</th>
		<th>Unit Cost</th>
		<th>Total</th>
	<th></th>
	</tr>		
";                     
		$total= 0;  
		if(count($rs2) >0){
			foreach ($rs2 as $rs3):
				# code...
				$v_vaccine_id = $rs3->visit_charge_id;
				$vaccine_id = $rs3->service_charge_id;
				$visit_charge_amount = $rs3->visit_charge_amount;
				$units = $rs3->visit_charge_units;

				//  get the service charge

				$rs4 = $this->nurse_model->get_service_charge($vaccine_id);
				if(count($rs4) >0){
					foreach ($rs4 as $rs5):
		
						$procedure_name = $rs5->service_charge_name;
						$service_id = $rs5->service_id;
						if($service_id==15){
							$total= $total +($units * $visit_charge_amount);
							echo"
									<tr> 
							 			<td align='center'>".$procedure_name."</td>
										<td align='center'><input type='text' id='units".$v_vaccine_id."' value='".$units."' size='3' onkeyup='calculatevaccinetotal(".$visit_charge_amount.",".$v_vaccine_id.", ".$vaccine_id.",".$visit_id.")'/></td>
										<td align='center'>".$visit_charge_amount."</td>
										<td align='center'><input type='text' readonly='readonly' size='5' value='".$units * $visit_charge_amount."' id='total".$v_vaccine_id."'></div></td>
										<td>
											<div class='btn-toolbar'>
												<div class='btn-group'>
													<a class='btn' href='#' onclick='delete_vaccine(".$v_vaccine_id.", ".$visit_id.")'><i class='icon-remove'></i></a>
												</div>
											</div>
										</td>
									</tr>	
							";					
						} 
						endforeach;
				}
				endforeach;

		}
echo"
<tr bgcolor='#59B3D5'>
<td></td>
<td></td>
<td></td>
<td>Grand Total: </td>
<td align='center'><div id='grand_total'>".$total."</div></td>
</tr>
 </table>
";
?>