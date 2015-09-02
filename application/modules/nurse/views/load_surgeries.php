<?php
$patient_id = $this->nurse_model->get_patient_id($visit_id);

$surgery_rs = $this->nurse_model->get_surgeries($patient_id);
$num_surgeries = count($surgery_rs);

echo
"
			<table align='center' class='table table-striped table-hover table-condensed'>
		<tr>
			<th>Year</th>
			<th>Month</th>
			<th>Description</th>
			<th></th>
		</tr>
		";
		if($num_surgeries > 0){
			foreach ($surgery_rs as $key):
			$date = $key->surgery_year;
			$month = $key->month_name;
			$description = $key->surgery_description;
			$id = $key->surgery_id;
			echo
			"
					<tr>
						<td>".$date."</td>
						<td>".$month."</td>
						<td>".$description."</td>
						<td>
						<div class='btn-toolbar'>
							<div class='btn-group'>
								<a class='btn' href='#'  onclick='delete_surgery(".$id.", ".$visit_id.")'><i class='icon-remove'></i></a>
							</div>
						</div>
						</td>
					</tr>
			";
			endforeach;
		}
echo
"
	<tr>
		<td valign='top'><input type='text' id='datepicker' autocomplete='off' size='5' class='form-control'/>
  
		</td>
		<td valign='top'>
			<select id='month' class='form-control'>
				<option value='1'>January</option>
				<option value='2'>February</option>
				<option value='3'>March</option>
				<option value='4'>April</option>
				<option value='5'>May</option>
				<option value='6'>June</option>
				<option value='7'>July</option>
				<option value='8'>August</option>
				<option value='9'>September</option>
				<option value='10'>October</option>
				<option value='11'>November</option>
				<option value='12'>December</option>
			</select>
		</td>
        <td><textarea id='surgery_description' class='form-control'></textarea></td>
        <td><input type='button' class='btn' value='Save' onclick='save_surgery(".$visit_id.")' /></td>
    </tr>
 </table>
";
?>