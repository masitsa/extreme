<?php

$rs = $this->nurse_model->get_diagnosis($visit_id);
$num_rows = count($rs);
//echo $num_rows;
		
if($num_rows > 0){

	echo
	"
	<div class='navbar-inner2'>
		<p style='text-align:center; color:#0e0efe;'>Diagnosis</p>
	</div>
			<table class='table table-striped table-hover table-condensed'>
				<tr>
					<th>No.</th>
					<th>Code</th>
					<th>Disease</th>
				</tr>";
	$t=0;
	foreach ($rs as $key):
		$diagnosis_id =$key->diagnosis_id;
		$name =$key->diseases_name;
		$code =$key->diseases_code;
		$t++;
		echo "<tr>
				<td>".($t)."</td>
				<td>".$code."</td>
				<td>".$name."</td>
				</tr>";
	endforeach;
}
	echo "
	<table>
	";