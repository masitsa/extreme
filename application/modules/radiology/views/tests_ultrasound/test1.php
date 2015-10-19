<?php
$patient_id = $this->nurse_model->get_patient_id($visit_id);
$coming_from = $this->reception_model->coming_from($visit_id);
$ultrasound_test = 0;

if(!empty($coming_from)){

	$get_test_rs = $this->ultrasound_model->get_ultrasound_visit_test($visit_id);
	$num_rows = count($get_test_rs);
	
	if ($num_rows >0 ){
		foreach ($get_test_rs as $key_rs):
			$ultrasound_test = $key_rs->department_id;
		endforeach;
		//echo $ultrasound_test;
		
		//coming from reception
		if(($ultrasound_test == 1)){
			echo "
			<table align='center'>
			<tr><td>
			<input name='test' type='button' value='Check Test' onclick='open_window_ultrasound(".$visit_id.",552)' />
			
			</td></tr>
			
			</table>
			
			
			";
			
		}else {}
		
	}
	$ultrasound_rs = $this->ultrasound_model->get_ultrasound_visit_item($visit_id);
	$num_ultrasound_visit = count($ultrasound_rs);
	
	if($num_ultrasound_visit > 0){
		
		echo"
<div class='row'>
	<div class='col-md-12'>
		<section class='panel panel-featured panel-featured-info'>
        <header class='panel-heading'>
            <h2 class='panel-title'>Ultrasounds</h2>
        </header>

        <div class='panel-body'>
			<table class='table table-striped table-hover table-condensed'>
				<tr>
					<th>#</th>
					<th>Ultrasound</th>
					<th>Comment</th>
					<th></th>
				<tr>
		";
		$r = 1;
		foreach ($ultrasound_rs as $key2):
			
			$visit_charge_id =$key2->visit_charge_id;
			$service_charge_name =$key2->service_charge_name;
			$visit_charge_comment =$key2->visit_charge_comment;
			
			$class = "class=''";
	
			echo "
				<tr ".$class.">
					<td>".$r."</td>
					<td>".$service_charge_name."</td>
					<td style='height: 500px;'><textarea class='cleditor' id='ultrasound_result".$visit_charge_id."'>".$visit_charge_comment."</textarea></td>
					<td><a href='#' onclick='save_result(".$visit_charge_id.", ".$visit_id.")' class='btn btn-success'>Save</a></td>";
			echo "</tr>";
			$r++;
			
		endforeach;
		
		echo"
			<div class='center-align'>
				<a href='".site_url()."radiology/ultrasound/print_ultrasound/".$visit_id."' class='btn btn-sm btn-info' target='_blank'> Print</a>
				<a href='".site_url()."radiology/ultrasound/send_to_accounts/".$visit_id."' class='btn btn-sm btn-success' onclick='return confirm(\'Send to accounts?\');'> Send to accounts</a>
				<a href='".site_url()."radiology/ultrasound/send_to_doctor/".$visit_id."' class='btn btn-sm btn-warning' onclick='return confirm(\'Send to doctor?\');'> Send to doctor</a>
			</div>
		";
		
	}
}
echo '
	</div>
</section>';
?>