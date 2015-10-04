<?php



//  enter the visit procedure 

 $this->dental_model->submitvisitbilling($procedure_id,$visit_id,$suck);

//  end of this function to enter


// get the visit type 

$visit_type_rs = $this->nurse_model->get_visit_type($visit_id);

if(count($visit_type_rs) >0){
	foreach ($visit_type_rs as $rs1 ) :
		# code...
		$visit_t = $rs1->visit_type;

	endforeach;
}
//  end of getting the visit type


// insert into visit charge function

$this->nurse_model->visit_charge_insert($visit_id,$procedure_id,$suck);

// end of visit charge function to insert



// get all the procedures

$visit__rs1 = $this->nurse_model->get_visit_procedure_charges($visit_id);




echo "
	<table align='center' class='table table-striped table-hover table-condensed'>
	<tr>
		<th></th>
		<th>Procedure</th>
		<th>Units</th>
		<th>Unit Cost</th>
		<th>Total</th>
	
	</tr>
";                       $total= 0;  
						
						foreach ($visit__rs1 as $key1) :
							$v_procedure_id = $key1->visit_charge_id;
							$procedure_id = $key1->service_charge_id;
							$visit_charge_amount = $key1->visit_charge_amount;
							$units = $key1->visit_charge_units;
	 
							//  get service charge information

								$service_charge_rs = $this->nurse_model->get_service_charge($procedure_id);
								foreach($service_charge_rs as $key2):
									$procedure_name = $key2->service_charge_name;
									$service_id = $key2->service_id;
								endforeach;	
							// end of service charge information
						
							$total= $total +($units * $visit_charge_amount);
							
							if($service_id==3){
								echo"
										<tr> 
											<td></td>
								 			<td align='center'>".$procedure_name."</td>
											<td align='center'><input type='text' id='units".$v_procedure_id."' value='".$units."' size='3' onkeyup='calculatetotal(".$visit_charge_amount.",".$v_procedure_id.", ".$procedure_id.",".$visit_id.")'/></td>
											<td align='center'>".$visit_charge_amount."</td>
											<td align='center'><input type='text' readonly='readonly' size='5' value='".$units * $visit_charge_amount."' id='total".$v_procedure_id."'></div></td>
											<td>
												<div class='btn-toolbar'>
													<div class='btn-group'>
														<a class='btn' href='#' onclick='delete_procedure(".$v_procedure_id.", ".$visit_id.")'><i class='icon-remove'></i></a>
													</div>
												</div>
											</td>
										</tr>	
								";	
							}
								
			endforeach;
?>