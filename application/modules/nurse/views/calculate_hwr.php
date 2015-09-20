<?php


$rs = $this->nurse_model->get_vitals($visit_id);


if(count($rs) > 0){
	
	foreach ($rs as $rs1):
		$vital_id = $rs1->vital_id;
		
		if($vital_id == 3){
			$waist = $rs1->visit_vital_value;
		}
		if($vital_id == 4){
			$hip = $rs1->visit_vital_value;
		}
	endforeach;
	
	if(empty($waist))
	{
		$waist = 0;
	}
	
	if(empty($hip))
	{
		$hip = 0;
	}
	
	if(($waist != NULL) && ($hip != NULL))
	{
		$hwr = $hip / $waist;
		
		echo "<div class='alert alert-info'><strong>H | W:</strong> ".number_format($hwr, 2)."</div>";
	}
}
?>