<?php

$rs = $this->nurse_model->get_plan($visit_id);
$num_rows = count($rs);
	
echo "
	<div class='row' style='margin-bottom:10px;'>
		<div class='col-md-4 center-align'>
			<input type='button' class='btn btn-warning btn-sm' value='Laboratory Test' onclick='open_window_lab(0, ".$visit_id.")'/>
		</div>
		<div class='col-md-4 center-align'>
			<input type='button' class='btn btn-info btn-sm' value='Diagnose' onclick='open_window(6, ".$visit_id.")'/>
		</div>
		<div class='col-md-4 center-align'>
			<input type='button' class='btn btn-success btn-sm' value='Prescribe' onclick='open_window(1, ".$visit_id.")'/>
			<!-- 
			<a href='".site_url()."pharmacy/prescription/".$visit_id."' target='_blank' class='btn btn-success btn-sm'>Prescribe</a>
			-->
		</div>
	</div>";

if($num_rows > 0){
	foreach ($rs as $key):
		$visit_plan = $key->visit_plan;
	endforeach;
	echo
	"
	<div class='row'>
		<div class='col-md-12'>
			<textarea class='form-control' id='visit_plan' rows='8' >".$visit_plan."</textarea>
		</div>
	</div>
	";
	echo "
	<br>
	<div class='row'>
			<div class='center-align '>
				<a class='btn btn-info btn-sm' type='submit' onclick='save_plan(".$visit_id.")'>Update Plan</a>
			</div>
	</div>

		";
}

else{
	echo
	"
	<div class='row'>
		<div class='col-md-12'>
			<textarea class='form-control' id='visit_plan' rows='8' ></textarea>
		</div>
	</div>
	";
	echo "
	<br>
	<div class='row'>
			<div class='center-align '>
				<a class='btn btn-info btn-sm' type='submit' onclick='save_plan(".$visit_id.")'>Save Plan</a>
			</div>
	</div>

		";
}



echo "

<div id='test_results'></div>";


?>
<script type="text/javascript">
	
	
</script>