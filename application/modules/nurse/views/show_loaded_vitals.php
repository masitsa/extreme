<?php

	
	//visit_vital
	$visit_vitals_rs = $this->nurse_model->get_visit_vitals($visit_id, $vitals_id);
	//if users exist display them
	if (count($visit_vitals_rs) > 0)
		{
	
		//visit_range
		$visit_range_rs = $this->nurse_model->vitals_range($vitals_id);
		
		foreach ($visit_vitals_rs as $rs1):
			$visit_vital_value = $rs1->visit_vital_value;
		endforeach;

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
			
			if(($lower_limit == NULL) || ($upper_limit == NULL)){
					echo"<div class='alert alert-info' style='margin-top:5px'>".$visit_vital_value."</div>";
			}
			
			else{
				if(($visit_vital_value < $lower_limit) || ($visit_vital_value > $upper_limit)){
					echo"<div class='alert alert-danger' style='margin-top:5px'>".$visit_vital_value."</div>";
				}
			
				else{
					echo"<div class='alert alert-info' style='margin-top:5px'>".$visit_vital_value."</div>";
				}
			}
	}
?>