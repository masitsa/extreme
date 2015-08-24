<?php

$rs= $this->reception_model->doctors_schedule($personnel_id,$date);

if (count($rs) == 0)
{
$rs1= $this->reception_model->doctors_names($personnel_id);
	if (count($rs1) > 0)
	{
		foreach ($rs1 as $key2) {
			$name= $key2->personnel_fname;
			$name2= $key2->personnel_onames;
		}
	}
echo '<h4 style="font-family:Palatino Linotype;">Appointment List from '.$date.' Onwards for Doctor '.$name.'	'.$name2.' </h4>';

echo 'No appointments for'.$name.'		'.$name2.' on '.$date;
}
else{
$rs1= $this->reception_model->doctors_names($personnel_id);
if (count($rs1) > 0)
{
	foreach ($rs1 as $key2) {
		$name= $key2->personnel_fname;
		$name2= $key2->personnel_onames;
	}
}
echo '<h4 style="font-family:Palatino Linotype;">Appointment List from '.$date.' Onwards for Doctor '.$name.'	'.$name2.' </h4>';
echo '<table> <tr> <th>Visit Date</th> <th>Start Time</th> <th> End Time</th>';
foreach ($rs as $key) {
	# code...

	$time_end=$key->time_end;
	$time_start=$key->time_start;
	$visit_date=$key->visit_date;
echo '<tr> <td>'.$visit_date.' </td><td>'.$time_start.' <td>'.$time_end.'</td> </tr>';

}

	
}

?>