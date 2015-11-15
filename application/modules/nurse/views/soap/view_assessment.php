<?php


$rs = $this->nurse_model->get_assessment($visit_id);
$num_rows = count($rs);
	
echo "
	<div class='navbar-inner'></div>";

if($num_rows > 0){
	foreach ($rs as $key):
	$visit_assessment = $key->visit_assessment;
	endforeach;
	echo
	"
	<div class='row'>
		<div class='col-md-12' >
			<textarea class='cleditor' id='visit_assessment' >".$visit_assessment."</textarea>
		</div>
	</div>
	";
	echo "
	<div class='row' style='margin-top:60px;'>
		<div class='center-align '>
			<a class='btn btn-info btn-sm' type='submit' onclick='save_assessment(".$visit_id.")'>Update Assesment</a>
		</div>
	</div>

		";
}

else{
	echo
	"
	<div class='row'>
		<div class='col-md-12' style='height:400px;'>
			<textarea class='cleditor' id='visit_assessment' ></textarea>
		</div>
	</div>
	";
	echo "
	<div class='row' style='margin-top:60px;'>
		<div class='center-align '>
			<a class='btn btn-info btn-sm' type='submit' onclick='save_assessment(".$visit_id.")'>Save Assesment</a>
		</div>
	</div>

		";
}

?>
