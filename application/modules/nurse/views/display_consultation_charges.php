<?php

$rs2 = $this->nurse_model->get_visit_consultant_charges($visit_id);
//$consultant = $this->nurse_model->get_visit_consultant($visit_id);

$consultants = $this->nurse_model->get_consultants();
$doctors = '<option value="">-- Select doctor --</option>';
if($consultants->num_rows() > 0)
{
	foreach($consultants->result() as $res)
	{
		$personnel_id = $res->personnel_id;
		$personnel = 'Dr. '.$res->personnel_onames.' '.$res->personnel_fname;
		$doctors .= '<option value="'.$personnel_id.'">'.$personnel.'</option>';
	}
}

$charges_rs = $this->nurse_model->get_doctor_review_charges($visit_id);
$charges = '<option value="">-- Select charge --</option>';
if($charges_rs->num_rows() > 0)
{
	foreach($charges_rs->result() as $res)
	{
		$service_charge_id = $res->service_charge_id;
		$service_charge_name = $res->service_charge_name;
		$charges .= '<option value="'.$service_charge_id.'">'.$service_charge_name.'</option>';
	}
}

echo form_open('nurse/add_consultant/'.$visit_id);
	echo '
	<div class="row" style="margin-bottom:10px;">
		<div class="col-md-4">
			<div class="form-group">
				<label class="col-md-4 control-label">Doctor: </label>
				
				<div class="col-md-8">
					<select class="form-control" name="personnel_id">
						'.$doctors.'
					</select>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="form-group">
				<label class="col-md-4 control-label">Charge: </label>
				
				<div class="col-md-8">
					<select class="form-control" name="service_charge_id">
						'.$charges.'
					</select>
				</div>
			</div>
		</div>
		
		<div class="col-md-4">
			<div class="center-align">
				<button type="submit" class="btn btn-primary">Add doctor</a>
			</div>
		</div>
	</div>
	';
echo form_close();

echo "
<table align='center' class='table table-striped table-hover table-condensed'>
	<tr>
		
		<th>Consultant</th>
		<th>Units</th>
		<th>Unit Cost</th>
		<th>Total</th>
		<th></th>
	</tr>		
";                     
		$total= 0;  
		if(count($rs2) >0){
			foreach ($rs2 as $key1):
				$visit_charge_id = $key1->visit_charge_id;
				$service_charge_id = $key1->service_charge_id;
				$visit_charge_amount = $key1->visit_charge_amount;
				$units = $key1->visit_charge_units;
				$service_charge_name = $key1->service_charge_name;
				$service_id = $key1->service_id;
				$personnel = 'Dr. '.$key1->personnel_onames.' '.$key1->personnel_fname;
			
				$total= $total +($units * $visit_charge_amount);
				
				echo"
						<tr> 
							<td>".$service_charge_name." - ".$personnel."</td>
							<td align='center'>
								<input class='form-control' type='text' id='consultation_charge_units".$visit_charge_id."' value='".$units."' size='3'/>
							</td>
							<td align='center'>".number_format($visit_charge_amount)."</td>
							<td align='center'><input type='text' class='form-control' readonly='readonly' size='5' value='".$units * $visit_charge_amount."' id='total".$visit_charge_id."'></div></td>
							<td>
							<a class='btn btn-sm btn-primary' href='#' onclick='calculateconsultationtotal(".$visit_charge_amount.",".$visit_charge_id.", ".$service_charge_id.",".$visit_id.")'><i class='fa fa-pencil'></i></a>
							</td>
						</tr>	
				";
				endforeach;

		}
echo"
<tr bgcolor='#D9EDF7'>
<td></td>
<td></td>
<th>Grand Total: </th>
<th colspan='3'><div id='grand_total'>".number_format($total)."</div></th>
</tr>
 </table>
";
?>