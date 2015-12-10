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
			
			$visit_lab_test_id = $key->visit_lab_test_id;

			// get invoiced charge for this test
				// parameters
			$service_charge_id = $key->service_charge_id;
				// check if test is in visit charge
			$actual_visit_charge = $this->lab_model->check_visit_charge_lab_test($visit_lab_test_id,$visit_id);
			// end of geting the actual charge id

			$actual_visit_charge_id = 0;

			if(count($actual_visit_charge) > 0)
			{
				$row_v = $actual_visit_charge[0];
			// /var_dump($row_v); die();
				$actual_visit_charge_id = $row_v->visit_charge_id;
			}
			
			$test_formats = $this->lab_model->get_lab_formats($service_charge_id);
			$num_formats = $test_formats->num_rows();
			
			if($num_formats > 0)
			{
				$r = 0;
				//get test
				$test = $this->lab_model->get_format_test_details($service_charge_id);
				$row = $test->row();
				
				$lab_test_name =$row->lab_test_name;
				$lab_test_class_name =$row->lab_test_class_name;
				
				//$format_rs = $this->lab_model->get_lab_visit_result($visit_lab_test_id);
				foreach ($test_formats->result() as $key2)
				{
					$test_format =$key2->lab_test_formatname;
					$lab_test_format_id =$key2->lab_test_format_id;
					$lab_test_format_units =$key2->lab_test_format_units;
					$lab_test_format_malelowerlimit =$key2->lab_test_format_malelowerlimit;
					$lab_test_format_maleupperlimit =$key2->lab_test_format_maleupperlimit;
					$lab_test_format_femalelowerlimit =$key2->lab_test_format_femalelowerlimit;
					$lab_test_format_femaleupperlimit =$key2->lab_test_format_femaleupperlimit;
					
					$lab_visit_result = '';
					$rs = $this->lab_model->get_format_test_results($lab_test_format_id, $visit_id);
					
					if($rs->num_rows() > 0)
					{
						$row2 = $rs->row();
						$lab_visit_result = $row2->lab_visit_results_result;
					}
					
					if(!empty($lab_visit_result)){
						$class = "class='success'";
					}
					else{
						$class = "class=''";
					}
									
					if($r > 0)
					{
						$lab_test_name = '';
						$lab_test_class_name = '';
					}
					
					echo "
						<tr ".$class.">
							<td>".$lab_test_name."</td>
							<td>".$lab_test_class_name."</td>
							<td>".$test_format."</td>
							<td><input type='text' class='form-control' id='laboratory_result2".$lab_test_format_id."' size='10' readonly='readonly' value='".$lab_visit_result."'/></td>
							<td>".$lab_test_format_units."</td>
							<td></td>
							<td>".$lab_test_format_malelowerlimit." - ".$lab_test_format_maleupperlimit ."</td>
							<td>".$lab_test_format_femalelowerlimit." - ".$lab_test_format_femaleupperlimit ."</td>
							<td id='result_space".$lab_test_format_id."'></td>
							<td><div class='ui-widget' id='value2".$lab_test_format_id."'></div></td>
						</tr>";
					$r++;
				}
			}
			
			//no formats
			else
			{
				//get test
				$test = $this->lab_model->get_test_details($service_charge_id);
				if($test->num_rows() > 0)
				{
					$row = $test->row();
					
					$lab_test_name =$row->lab_test_name;
					
					$rs = $this->lab_model->get_m_test($visit_lab_test_id);//die();
					foreach ($rs as $key2)
					{
						$lab_test_class_name =$key2->lab_test_class_name;
						$lab_test_units =$key2->lab_test_units;
						$lab_test_upper_limit =$key2->lab_test_malelowerlimit;
						$lab_test_lower_limit =$key2->lab_test_malelupperlimit;
						$lab_test_upper_limit1 =$key2->lab_test_femalelowerlimit;
						$lab_test_lower_limit1 =$key2->lab_test_femaleupperlimit;
						$lab_visit_result =$key2->lab_visit_result;
						$visit_charge_id =$key2->lab_visit_id;
						
						if(!empty($lab_visit_result)){
							$class = "class='success'";
						}
						else{
							$class = "class=''";
						}
						echo "
						<tr ".$class.">
							<td>".$lab_test_name."</td>
							<td>".$lab_test_class_name."</td>
							<td></td>
							<td><input type='text' class='form-control'  id='laboratory_result".$visit_charge_id."' size='10' readonly='readonly' value='".$lab_visit_result."'/></td>
							<td>".$lab_test_units."</td>
							<td></td>
							<td>".$lab_test_upper_limit." - ".$lab_test_lower_limit."</td>
							<td>".$lab_test_upper_limit1." - ".$lab_test_lower_limit1."</td>
							<td id='result_space".$visit_charge_id."'></td>
							<td><div class='ui-widget' id='value".$visit_charge_id."'></div></td>
						</tr>";
					}
					
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
							<td colspan='8'><textarea readonly='readonly' rows='5' class='form-control' id='laboratory_comment".$visit_charge_id."'  placeholder='".$lab_test_name." Comments'>".$comment4."</textarea> </td>
						</tr>";
				}
			}
		endforeach;
		echo //MM.$lab_test.
		"
		</table>
		
		<div class='row' style='margin-bottom: 20px;'>
			<div class='col-md-12'>
				<div class='center-align'><h3>General Comments</h3></div>
				<textarea readonly='readonly' rows='5' id='test_comment' onkeyup='save_comment(".$actual_visit_charge_id.")' class='form-control' readonly='readonly'>".$comment."</textarea>
			</div>
		</div>
		
		<div class='center-align'>
			<a href='".site_url()."laboratory/print_test/".$visit_id."' class='btn btn-sm btn-success' target='_blank'>Print</a>
		</div>
		";
	}
}
echo '
			</div>
		</section>
	</div>
</div>
';
?>