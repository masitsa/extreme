<?php

$patient_id = $this->nurse_model->get_patient_id($visit_id);
$coming_from = $this->reception_model->coming_from($visit_id);
$xray_test = 0;

if(!empty($coming_from)){

	$get_test_rs = $this->xray_model->get_xray_visit_test($visit_id);
	$num_rows = count($get_test_rs);
	
	if ($num_rows >0 ){
		foreach ($get_test_rs as $key_rs):
			$xray_test = $key_rs->department_id;
		endforeach;
		//echo $xray_test;
		
		//coming from reception
		if(($xray_test == 1)){
			echo "
			<table align='center'>
			<tr><td>
			<input name='test' type='button' value='Check Test' onclick='open_window_xray(".$visit_id.",552)' />
			
			</td></tr>
			
			</table>
			
			
			";
			
		}else {}
		
	}
	$xray_rs = $this->xray_model->get_xray_visit_item($visit_id);
	$num_xray_visit = count($xray_rs);
		echo"
<div class='row'>
	<div class='col-md-12'>
		<section class='panel panel-featured panel-featured-info'>
			<header class='panel-heading'>
				<h2 class='panel-title'>X Ray results</h2>
			</header>
	
			<div class='panel-body'>";
	
	if($num_xray_visit > 0){
		
		echo"
			<table class='table table-striped table-hover table-condensed'>
				<tr>
					<th>#</th>
					<th>X Ray</th>
					<th>Comment</th>
				<tr>
		";
		$r = 1;
		foreach ($xray_rs as $key2):
			
			$service_charge_name =$key2->service_charge_name;
			$visit_charge_comment =$key2->visit_charge_comment;
			
			$class = "class=''";
	
			echo "
				<tr ".$class.">
					<td>".$r."</td>
					<td>".$service_charge_name."</td>
					<td>".$visit_charge_comment."</td>";
			echo "</tr>";
			$r++;
			
		endforeach;
		
		echo "</table>";
		
		if ($xray_test == 12){
			echo"
			<div class='center-align'>
				<input type='button' value='Print' name='std' class='btn btn-sm btn-info' onclick='print_previous_test(".$visit_id.",".$patient_id.")'/>
				<input type='button' value='Send to Doctor' name='std' class='btn btn-sm btn-warning' onClick='send_to_doc(".$visit_id.")'/>
				<input type='button' class='btn btn-sm btn-success' value='Done' onclick='finish_xray_test(".$visit_id.")'/>
			</div>
		";
		}
		
		elseif ($xray_test == 22){
			echo"
			<div class='center-align'>
				<input type='button' value='Print' name='std' class='btn btn-sm btn-info' onclick='print_previous_test(".$visit_id.",".$patient_id.")'/>
				<input type='button' value='Send to Doctor' name='std' class='btn btn-sm btn-warning' onClick='send_to_doc(".$visit_id.")'/>
				<input type='button' class='btn btn-sm btn-success' value='Done' onclick='finish_xray_test(".$visit_id.")'/>
			</div>
		";
		}
		
		else if(($coming_from == 'Lab') || ($coming_from == 'Nurse') || ($coming_from == 'Reception')){
		echo"
			<!--<div class='center-align'>
				<input type='button' value='Print' name='std' class='btn btn-sm btn-info' onclick='print_previous_test(".$visit_id.",".$patient_id.")'/>
			</div>-->
		";
		}
		
	}
}
echo '
			</div>
		</section>
	</div>
</div>
';
?>