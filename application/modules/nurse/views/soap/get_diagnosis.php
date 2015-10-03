<?php 

$rs = $this->nurse_model->get_diagnosis($visit_id);
$num_rows = count($rs);
//echo $num_rows;
		
if($num_rows > 0){

	echo
	"
	<section class='panel panel-featured panel-featured-info'>
		<header class='panel-heading'>
			<h2 class='panel-title'>Diagnosis</h2>
		</header>

		<div class='panel-body'>
			<table class='table table-striped table-hover table-condensed'>
				<tr>
					<th></th>
					<th>Code</th>
					<th>Disease</th>
				</tr>";
	
	foreach ($rs as $key):
		$diagnosis_id = $key->diagnosis_id;
		$name = $key->diseases_name;
		$code = $key->diseases_code;
		
		echo "<tr>
			<td>
				<div class='btn-toolbar'>
					<div class='btn-group'>
						<a class='btn btn-danger btn-sm delete_diagnosis' href='".$diagnosis_id."' id='".$visit_id."'><i class='fa fa-trash'></i></a>
					</div>
				</div>
			</td>
				<td>".$code."</td>
				<td>".$name."</td></tr>";
	endforeach;
 echo"</table>
 </div>
 </section>
 ";
}
?>