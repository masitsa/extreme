<?php 
$patient_id = $this->nurse_model->get_patient_id($visit_id);

$get_medical_rs = $this->nurse_model->get_medicals($patient_id);
$num_rows = count($get_medical_rs);
//echo $num_rows;
echo "";
if($num_rows > 0){
	$allergies = '';
	foreach ($get_medical_rs as $key):
	$food_allergies = $key->food_allergies;
	$medicine_allergies = $key->medicine_allergies;
	$regular_treatment = $key->regular_treatment;
	$recent_medication = $key->medication_name;

	if(!empty($food_allergies))
	{
		$allergies .= ' Food Allergies: '.$food_allergies.'.';
	}
	else{
		$allergies .= ' Food Allergies: None.';
	}
	if(!empty($medicine_allergies) )
	{
		$allergies .= ' Medicine Allergies: '.$medicine_allergies.'.';
	}
	else
	{
		$allergies .= ' Medicine Allergies: None.';	
	}

	if(!empty($regular_treatment))
	{

		$allergies .= ' Regular Treatment: '.$regular_treatment.'.';
	}

	else
	{
		$allergies .= ' Regular Treatment: None.';	
	}

	if(!empty($recent_medication))
	{
		$allergies .= ' Recent Medication: '.$recent_medication.'.';
	}

	else 
	{
		$allergies .= ' Recent Medication: None.';
	}
	endforeach;


?>
<div class="center-align">
     <div class="alert alert-danger center-align">Allergies: <?php echo $allergies;?></div>
</div>
<br>
<?php
}
else
{

}
?>