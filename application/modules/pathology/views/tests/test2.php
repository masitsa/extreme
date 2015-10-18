<?php

$patient_id = $this->nurse_model->get_patient_id($visit_id);
$coming_from = $this->reception_model->coming_from($visit_id);

if(!empty($coming_from)){

	$get_test_rs = $this->lab_model->get_lab_visit_test($visit_id);
	$num_rows = count($get_test_rs);
	
	$rs3 = $this->lab_model->get_comment($visit_id);
	$num_rows3 = count($rs3);
	
	if($num_rows3 > 0){
		foreach ($rs3 as $key3):
			$comment = $key3->lab_visit_comment;
		endforeach;
	}
	
	if ($num_rows >0 ){
		foreach ($get_test_rs as $key_rs):
			$lab_test = $key_rs->department_id;
		endforeach;
		//echo $lab_test;
		
		//coming from reception
		if(($lab_test == 1)){
			echo "
			<table align='center'>
			<tr><td>
			<input name='test' type='button' value='Check Test' onclick='open_window_laboratory(".$visit_id.",552)' />
			
			</td></tr>
			
			</table>
			
			
			";
			
		}else {}
		
	}
	$lab_rs = $this->lab_model->get_lab_visit_item($visit_id);
	$num_lab_visit = count($lab_rs);
		echo"
<div class='row'>
	<div class='col-md-12'>
		<section class='panel panel-featured panel-featured-info'>
        <header class='panel-heading'>
            <h2 class='panel-title'>Laboratory test results</h2>
        </header>

        <div class='panel-body'>";
	
	if($num_lab_visit > 0){
		
		echo"
			<table class='table table-striped table-hover table-condensed'>
				<tr>
					<th>Test</th>
					<th>Class</th>
					<th>Format</th>
					<th>Result</th>
					<th>Units</th>
					<th>Level</th>
					<th>Male Range</th>
					<th>Female Range</th>
					<th></th>
					<th></th>
				<tr>
		";
		foreach ($lab_rs as $key):
			
			$visit_charge_id = $key->visit_lab_test_id;
		
			$format_rs = $this->lab_model->get_lab_visit_result($visit_charge_id);
			$num_format = count($format_rs);
			
			if($num_format > 0){
				$rs = $this->lab_model->get_test($visit_charge_id);
				$num_lab = count($rs);
			}
			
			else{
				$rs = $this->lab_model->get_m_test($visit_charge_id);//die();
				$num_lab = count($rs);
			}
			
			//echo "num lab = ".$num_lab;
			$r = 0;
			foreach ($rs as $key2):
			
			$lab_test_name =$key2->lab_test_name;
			$lab_test_class_name =$key2->lab_test_class_name;
			$lab_test_units =$key2->lab_test_units;
			$lab_test_upper_limit =$key2->lab_test_malelowerlimit;
			$lab_test_lower_limit =$key2->lab_test_malelupperlimit;
			$lab_test_upper_limit1 =$key2->lab_test_femalelowerlimit;
			$lab_test_lower_limit1 =$key2->lab_test_femaleupperlimit;
			$lab_visit_result =$key2->lab_visit_result;
			$visit_charge_id =$key2->lab_visit_id;
			
			//echo $_SESSION['test'];
			//echo "hlkjasda".$this->session->userdata('test');
			if($this->session->userdata('test') ==0){
				$test_format =$key2->lab_test_formatname;
				$lab_test_format_id =$key2->lab_test_format_id;
				$lab_test_format_units =$key2->lab_test_format_units;
				$lab_test_format_malelowerlimit =$key2->lab_test_format_malelowerlimit;
				$lab_test_format_maleupperlimit =$key2->lab_test_format_maleupperlimit;
				$lab_test_format_femalelowerlimit =$key2->lab_test_format_femalelowerlimit;
				$lab_test_format_femaleupperlimit =$key2->lab_test_format_femaleupperlimit;
				$lab_visit_result =$key2->lab_visit_results_result;
			}
			else{
				$test_format ="-";
			}
						
			if(!empty($lab_visit_result)){
				$class = "class='success'";
			}
			else{
				$class = "class=''";
			}
	
			if((($num_format > 0) && ($r == 0)) || ($num_format <= 0)){
				echo "
				<tr ".$class.">
					<td>".$lab_test_name."</td>
					<td>".$lab_test_class_name."</td>";
			}
			else{
				echo "
				<tr ".$class.">
					<td></td>
					<td></td>";
			}
			
			echo"
				<td>".$test_format."</td>";
					
				if($this->session->userdata('test') ==0){
					echo"<td><input type='text' class='form-control' id='laboratory_result2".$lab_test_format_id."' size='10' onkeyup='save_result_format(".$visit_charge_id.",".$lab_test_format_id.", ".$visit_id.")' value='".$lab_visit_result."' readonly/></td>";
					
					echo"
						<td>".$lab_test_format_units."</td>
						<td></td>
						<td>".$lab_test_format_malelowerlimit." - ".$lab_test_format_maleupperlimit ."</td>
						<td>".$lab_test_format_femalelowerlimit." - ".$lab_test_format_femaleupperlimit ."</td>
						<td id='result_space".$lab_test_format_id."'></td>";
				}
				else {
					echo"<td><input type='text' class='form-control'  id='laboratory_result".$visit_charge_id."' size='10' onkeyup='save_result(".$visit_charge_id.", ".$visit_id.")' value='".$lab_visit_result."' readonly/></td>";
									
					echo "
						<td>".$lab_test_units."</td>
						<td></td>
						<td>".$lab_test_upper_limit." - ".$lab_test_lower_limit."</td>
						<td>".$lab_test_upper_limit1." - ".$lab_test_lower_limit1."</td>
						<td id='result_space".$visit_charge_id."'></td>";
				}
				
				if($this->session->userdata('test') ==0){
					echo"<td><div class='ui-widget' id='value2".$lab_test_format_id."'></div></td>";
				}
				else {
					echo"<td><div class='ui-widget' id='value".$visit_charge_id."'></div></td>";
				} 
					
				echo "</tr>";
				$r++;
			endforeach;
			
				if((($num_format > 0) && ($r == 0)) || ($num_format <= 0)){
	
				}
				
				else{
						$rsy2 = $this->lab_model->get_test_comment($visit_charge_id);
						$num_rowsy2 = count($rsy2);
						$comment4 = '';
	
						if($num_rowsy2 > 0){
	
							foreach ($rsy2 as $keyy):
								$comment4= $keyy->lab_visit_format_comments;
							endforeach;
						}
					echo "
						<tr>
							<td colspan='8'><textarea rows='5' class='form-control' id='laboratory_comment".$visit_charge_id."'  onkeyup='save_lab_comment(".$visit_charge_id.", ".$visit_id.")' placeholder='".$lab_test_name." Comments' readonly>".$comment4."</textarea> </td>
						</tr>";
					}
		endforeach;
		
		echo //MM.$lab_test.
		"
		</table>
		
		<div class='row' style='margin-bottom: 20px;'>
			<div class='col-md-12'>
				<div class='center-align'><h3>General Comments</h3></div>
				<textarea rows='5' id='test_comment' onkeyup='save_comment(".$visit_charge_id.")' class='form-control' readonly='readonly'>".$comment."</textarea>
			</div>
		</div>
		";
		if ($lab_test == 12){
			echo"
			<div class='center-align'>
				<input type='button' value='Print' name='std' class='btn btn-sm btn-info' onclick='print_previous_test(".$visit_id.",".$patient_id.")'/>
				<input type='button' value='Send to Doctor' name='std' class='btn btn-sm btn-warning' onClick='send_to_doc(".$visit_id.")'/>
				<input type='button' class='btn btn-sm btn-success' value='Done' onclick='finish_lab_test(".$visit_id.")'/>
			</div>
		";
		}
		
		elseif ($lab_test == 22){
			echo"
			<div class='center-align'>
				<input type='button' value='Print' name='std' class='btn btn-sm btn-info' onclick='print_previous_test(".$visit_id.",".$patient_id.")'/>
				<input type='button' value='Send to Doctor' name='std' class='btn btn-sm btn-warning' onClick='send_to_doc(".$visit_id.")'/>
				<input type='button' class='btn btn-sm btn-success' value='Done' onclick='finish_lab_test(".$visit_id.")'/>
			</div>
		";
		}
		
		/*else if($coming_from == ''){
		echo"
			<div class='center-align'>
				<input type='button' value='Print' name='std' class='btn btn-sm btn-info' onclick='print_previous_test(".$visit_id.",".$patient_id.")'/>
				<input type='button' value='Send to Doctor' name='std' class='btn btn-sm btn-warning' onClick='send_to_doc(".$visit_id.")'/>
				<input type='button' class='btn btn-sm btn-success' value='Done' onclick='finish_lab_test(".$visit_id.")'/>
			</div>
		";
		}*/
		
		else if(($coming_from == 'Lab') || ($coming_from == 'Nurse') || ($coming_from == 'Reception')){
		echo"
			<div class='center-align'>
				<input type='button' value='Print' name='std' class='btn btn-sm btn-info' onclick='print_previous_test(".$visit_id.",".$patient_id.")'/>
			</div>
		";
		}
		
		else{
		// 	echo"
		// 	<div class='center-align'>
		// 		<input type='button' value='Print' name='std' class='btn btn-sm btn-info' onclick='print_previous_test(".$visit_id.",".$patient_id.")'/>
		// 		<input type='button' value='Send to Doctor' name='std' class='btn btn-sm btn-warning' onClick='send_to_doc(".$visit_id.")'/>
		// 		<input type='button' class='btn btn-sm btn-success' value='Done' onclick='finish_lab_test(".$visit_id.")'/>
		// 	</div>
		// ";
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