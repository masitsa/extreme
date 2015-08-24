<?php

$segment = 3;
$where = 'visit.patient_id = '.$patient_id;

$table = 'visit';
//pagination
$this->load->library('pagination');
$config['base_url'] = site_url().'/administration/patient_statement';
$config['total_rows'] = $this->reception_model->count_items($table, $where);
$config['uri_segment'] = $segment;
$config['per_page'] = 20;
$config['num_links'] = 5;


$config['full_tag_open'] = '<ul class="pagination pull-right">';
$config['full_tag_close'] = '</ul>';

$config['first_tag_open'] = '<li>';
$config['first_tag_close'] = '</li>';

$config['last_tag_open'] = '<li>';
$config['last_tag_close'] = '</li>';

$config['next_tag_open'] = '<li>';
$config['next_link'] = 'Next';
$config['next_tag_close'] = '</span>';

$config['prev_tag_open'] = '<li>';
$config['prev_link'] = 'Prev';
$config['prev_tag_close'] = '</li>';

$config['cur_tag_open'] = '<li class="active"><a href="#">';
$config['cur_tag_close'] = '</a></li>';

$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$this->pagination->initialize($config);

$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
$v_data["links"] = $this->pagination->create_links();
$query = $this->administration_model->get_all_patient_visit($table, $where, $config["per_page"], $page);


?>

<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i>Previous Vitals</h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd">
		<?php
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			
			$result .= 
			'
				<table class="table table-hover table-bordered ">
				  
				  <tbody>
			';
			
			
			$personnel_query = $this->personnel_model->get_all_personnel();
	
			foreach ($query->result() as $row)
			{
				$visit_id12 = $row->visit_id;
				$visit_date = $row->visit_date;
				$visit_date = date('jS M Y',strtotime($visit_date));
				$rs = $this->nurse_model->get_previous_vitals($visit_id12);
				 //print_r($rs);
				$distinct_rs = $this->nurse_model->get_distict_vitals($visit_id12);

				$counter = count($distinct_rs);
				if(count($distinct_rs) > 0){

					echo '
						<td >'.$visit_date.'</td>
						<td>
						<table class="table table-striped table-hover table-condensed ">
							<tr>
								<th></th>
								<th>Systolic</th>
								<th>Diastolic</th>
								<th>Weight</th>
								<th>Height</th>
								<th>BMI</th>
								<th>Hip</th>
								<th>Waist</th>
								<th>H / W</th>
								<th>Temperature</th>
								<th>Pulse</th>
								<th>Respiration</th>
								<th>Oxygen Saturation</th>
								<th>Pain</th>
								<th>Created by</th>
							</tr>
					';
					
					
					
					foreach ($distinct_rs as $rs2):
						$visit_counter = $rs2->visit_counter;
						$visit_time = $rs2->visit_vitals_time;
						$created_by = $rs2->created_by;
						$height = 0;
						$weight = 0;
						$waist = 0;
						$hip = 0;

						$height2 = 0;
						$weight2 = 0;
						$waist2 = 0;
						$hip2 = 0;
						$temperature =0;
						$respiration =0;
						$systolic = 0;
						$diastolic =0;
						$oxygen = 0;
						$pulse =0;
						$pain = 0;

						foreach ($rs as $rs1):
							$vital_id = $rs1->vital_id;
							$visit_date = $rs1->visit_date;

							$value = $rs1->visit_vital_value;
							$visit_counter2 = $rs1->visit_counter;
							
							

							$visit_range_rs = $this->nurse_model->vitals_range($vital_id);

							if($visit_counter2 == $visit_counter)
							{
								if (count($visit_range_rs) > 0)
								{

									foreach ($visit_range_rs as $rs2):
										$vitals_range_range = $rs2->vitals_range_range;
										$vitals_range_name = $rs2->vitals_range_name;

										if($vitals_range_name == "Upper Limit"){
										
											if(!empty($vitals_range_range)){
												$upper_limit = $vitals_range_range;
											}
										}
								
										else if($vitals_range_name == "Lower Limit"){
									
											if(!empty($vitals_range_range)){
												$lower_limit = $vitals_range_range;
											}
										}
									endforeach;	
									
								}
								else{
									$upper_limit = NULL;
									$lower_limit = NULL;
								}



								if($vital_id == 1){
									
										$temperature = $rs1->visit_vital_value;

										if(($lower_limit == NULL) || ($upper_limit == NULL)){
											$temperature = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$temperature."</div>";
										}
										
										else{
											if(($temperature < $lower_limit) || ($temperature > $upper_limit)){
												$temperature = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$temperature."</div>";
											}
										
											else{
												$temperature = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$temperature."</div>";
											}
										}
								}
								
								else if($vital_id == 2){
									$respiration = $rs1->visit_vital_value;
									if(($lower_limit == NULL) || ($upper_limit == NULL)){
										$respiration = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$respiration."</div>";
									}
									
									else{
										if(($respiration < $lower_limit) || ($respiration > $upper_limit)){
											$respiration = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$respiration."</div>";
										}
									
										else{
											$respiration = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$respiration."</div>";
										}
									}
								}
								
								else if($vital_id == 3){
									 $waist = $rs1->visit_vital_value;
									 $waist2 = $rs1->visit_vital_value;
									 if(($lower_limit == NULL) || ($upper_limit == NULL)){
										$waist = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$waist."</div>";
									}
									
									else{
										if(($waist < $lower_limit) || ($waist > $upper_limit)){
											$waist = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$waist."</div>";
										}
									
										else{
											$waist = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$waist."</div>";
										}
									}
								}
								
								else if($vital_id == 4){
									 $hip = $rs1->visit_vital_value;
									 $hip2 = $rs1->visit_vital_value;
									if(($lower_limit == NULL) || ($upper_limit == NULL)){
										$hip = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$hip."</div>";
									}

									else{
									if(($hip < $lower_limit) || ($hip > $upper_limit)){
										$hip = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$hip."</div>";
									}

									else{
										$hip = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$hip."</div>";
									}
									}
								}
								
								else if($vital_id == 5){
									$systolic = $rs1->visit_vital_value;
									if(($lower_limit == NULL) || ($upper_limit == NULL)){
										$systolic = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$systolic."</div>";
									}

									else{
									if(($systolic < $lower_limit) || ($systolic > $upper_limit)){
										$systolic = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$systolic."</div>";
									}

									else{
										$systolic = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$systolic."</div>";
									}
									}
								}
								
								else if($vital_id == 6){
									$diastolic = $rs1->visit_vital_value;
									if(($lower_limit == NULL) || ($upper_limit == NULL)){
										$diastolic = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$diastolic."</div>";
									}

									else{
									if(($diastolic < $lower_limit) || ($diastolic > $upper_limit)){
										$diastolic = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$diastolic."</div>";
									}

									else{
										$diastolic = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$diastolic."</div>";
									}
									}
								}
								
								else if($vital_id == 7){
									$pulse = $rs1->visit_vital_value;
									if(($lower_limit == NULL) || ($upper_limit == NULL)){
										$pulse = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$pulse."</div>";
									}

									else{
									if(($pulse < $lower_limit) || ($pulse > $upper_limit)){
										$pulse = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$pulse."</div>";
									}

									else{
										$pulse = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$pulse."</div>";
									}
									}
								}
								
								else if($vital_id == 8){
									$weight = $rs1->visit_vital_value;
									$weight2 = $rs1->visit_vital_value;
									if(($lower_limit == NULL) || ($upper_limit == NULL)){
										$weight = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$weight."</div>";
									}

									else{
									if(($weight < $lower_limit) || ($weight > $upper_limit)){
										$weight = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$weight."</div>";
									}

									else{
										$weight = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$weight."</div>";
									}
									}
								}
								
								else if($vital_id == 9){
									$height = $rs1->visit_vital_value;
									$height2 = $rs1->visit_vital_value;
									if(($lower_limit == NULL) || ($upper_limit == NULL)){
										$height = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$height."</div>";
									}

									else{
									if(($height < $lower_limit) || ($height > $upper_limit)){
										$height = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$height."</div>";
									}

									else{
										$height = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$height."</div>";
									}
									}
								}
								
								else if($vital_id == 10){
									$pain = $rs1->visit_vital_value;
									if(($lower_limit == NULL) || ($upper_limit == NULL)){
										$pain = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$pain."</div>";
									}

									else{
									if(($pain < $lower_limit) || ($pain > $upper_limit)){
										$pain = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$pain."</div>";
									}

									else{
										$pain = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$pain."</div>";
									}
									}
								}
								
								else if($vital_id == 11){
									$oxygen = $rs1->visit_vital_value;
									if(($lower_limit == NULL) || ($upper_limit == NULL)){
										$oxygen = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$oxygen."</div>";
									}

									else{
									if(($oxygen < $lower_limit) || ($oxygen > $upper_limit)){
										$oxygen = "<div class='alert alert-danger ' style='padding: 0px;text-align: center;' >".$oxygen."</div>";
									}

									else{
										$oxygen = "<div class='alert alert-info' style='padding: 0px;text-align: center;'>".$oxygen."</div>";
									}
									}
								}
								if($height2 > 0)
								{
									$bmi = $weight2 / ($height2 * $height2);
								}
								else
								{
									$bmi = 0;
								}
								
								if($waist2 > 0)
								{
									$hwr = $hip2 / $waist2;
								}
								else
								{
									$hwr = 0;
								}
						}
							
						endforeach;
								
						//creators and editors
							if($personnel_query->num_rows() > 0)
							{
								$personnel_result = $personnel_query->result();
								
								foreach($personnel_result as $adm)
								{
									$personnel_id = $adm->personnel_id;
									
									if($personnel_id == $created_by)
									{
										$created_by = $adm->personnel_fname;
									}
									
									
								}
							}
							
							else
							{
								$created_by = '-';
							}
				
								echo '
								<tr>
									<td>'.$visit_time.'</td>
									<td>'.$systolic.'</td>
									<td>'.$diastolic.'</td>
									<td>'.$weight.'</td>
									<td>'.$height.'</td>
									<td>'.number_format($bmi,2).'</td>
									<td>'.$hip.'</td>
									<td>'.$waist.'</td>
									<td>'.number_format($hwr,2).'</td>
									<td>'.$temperature.'</td>
									<td>'.$pulse.'</td>
									<td>'.$respiration.'</td>
									<td>'.$oxygen.'</td>
									<td>'.$pain.'</td>
									<td>'.$created_by.'</td>
								</tr>

							';
						
					endforeach;
						
						
					
					
					
					echo "</table></td>";
				}
				
			}
				
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no items";
		}
		
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>