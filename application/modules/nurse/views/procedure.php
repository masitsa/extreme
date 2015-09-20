<?php



//  enter the visit procedure 

 $this->nurse_model->submitvisitprocedure($procedure_id,$visit_id,$suck);

//  end of this function to enter


//  end of getting the visit type


// insert into visit charge function

$this->nurse_model->visit_charge_insert($visit_id,$procedure_id,$suck);

// end of visit charge function to insert



// get all the procedures

$visit__rs1 = $this->nurse_model->get_visit_procedure_charges($visit_id, $procedure_id);
echo "

	<table align='center' class='table table-striped table-hover table-condensed'>
	<tr>
		<th></th>
		<th>Procedure</th>
		<th>Units</th>
		<th>Unit Cost</th>
		<th>Total</th>
		<th></th>
		<th></th>
	</tr>
";                       $total= 0;  
						
						foreach ($visit__rs1 as $key1) :
							$v_procedure_id = $key1->visit_charge_id;
							$procedure_id = $key1->service_charge_id;
							$visit_charge_amount = $key1->visit_charge_amount;
							$units = $key1->visit_charge_units;
							$procedure_name = $key1->service_charge_name;
							$service_id = $key1->service_id;
							
							$total= $total +($units * $visit_charge_amount);
							
								echo"
										<tr> 
											<td></td>
								 			<td align='center'>".$procedure_name."</td>
											<td align='center'><input type='text' id='units".$v_procedure_id."' value='".$units."' size='3' onkeyup='calculatetotal(".$visit_charge_amount.",".$v_procedure_id.", ".$procedure_id.",".$visit_id.")'/></td>
											<td align='center'>".$visit_charge_amount."</td>
											<td align='center'><input type='text' readonly='readonly' size='5' value='".$units * $visit_charge_amount."' id='total".$v_procedure_id."'></div></td>
											<td>
												<a class='btn btn-sm btn-primary' href='#' onclick='calculatetotal(".$visit_charge_amount.",".$v_procedure_id.", ".$procedure_id.",".$visit_id.")'><i class='fa fa-pencil'></i></a>
											</td>
											<td>
												<a class='btn btn-sm btn-danger' href='#' onclick='delete_procedure(".$v_procedure_id.", ".$visit_id.")'><i class='fa fa-trash'></i></a>
											</td>
										</tr>	
								";	
								
			endforeach;
?>