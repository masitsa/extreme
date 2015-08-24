<?php
$count_disease = 0;
$width = 30;
$height = 5;
$box_height = 7;
$current_x = 1;
$checkbox_number = 0;

$this->fpdf->SetFont('Times','',11);

if($family_disease_query->num_rows() > 0)
{
	$family_diseases = $family_disease_query->result();
	
	foreach($family_diseases as $dis)
	{	
		$fd_id = $dis->family_disease_id;
		$disease = $dis->family_disease_name;
		
		if($family_query->num_rows() > 0)
		{
			$count_family = 0;
			$family = $family_query->result();
	
			foreach($family as $fam)
			{
				$family = $fam->family_relationship;
				$family_id = $fam->family_id;
				
				if($count_family == 0)
				{	
					if($count_disease == 0)
					{
						$this->fpdf->Cell($width, $height, '', 'B', 0, 'C');
					}
					
					else
					{
						$this->fpdf->Cell(0, $box_height, $disease, 0, 1, 'L');
					}
				}
				
				else if($count_disease == 0)
				{
					if(($count_family + 1) == $family_query->num_rows())
					{
						$this->fpdf->Cell($width, $height, $family, 'B', 1, 'C');
					}
					
					else
					{
						$this->fpdf->Cell($width, $height, $family, 'B', 0, 'C');
					}
				}
				
				else
				{	
					$rs_history = $this->nurse_model->get_family_history($family_id, $patient_id, $fd_id);
					$num_history = $rs_history->num_rows();
					$current_y = $this->fpdf->GetY();
					$checkbox_number++;
					
					if($checkbox_number == 6)
					{
						$current_x = 1;
						$current_y = $current_y + 7;
						$checkbox_number = 1;
					}
					
					if($current_x == 1)
					{
						$current_x = $current_x + 50;
					}
					
					else
					{
						$current_x = $current_x + 30;
					}
					$current_y = $current_y - 5;
					//$this->fpdf->Cell($width, $height, $current_x.', '.$current_y, 0, 0, 'C');
					
					if($num_history == 0)
					{
						$this->fpdf->Image(base_url().'images/unchecked_checkbox.jpg', $current_x, $current_y, 3, 3);
					}
					
					else
					{
						$this->fpdf->Image(base_url().'images/checked_checkbox.jpg', $current_x, $current_y, 3, 3);
					}
				}
				$count_family++;
			}
		}
		
		else
		{
			$this->fpdf->Cell(0, $height, 'Family not found', 'B', 0, 'C');
		}
		
		$count_disease++;
	}
}
?>