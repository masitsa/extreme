<?php
class Doctor_model extends CI_Model 
{
	public function get_family_history($visit_id)
	{
		$v_data['patient_id'] = $this->reception_model->get_patient_id_from_visit($visit_id);
		$v_data['patient'] = $this->reception_model->patient_names2(NULL, $visit_id);
		$v_data['family_disease_query'] = $this->nurse_model->get_family_disease();
		$v_data['family_query'] = $this->nurse_model->get_family();
		
		echo $this->load->view('doctor/family_history', $v_data, TRUE);
	}
	
	public function print_checkup($visit_id)
	{
		$title = 'Medical Checkup Form';
		$heading = 'Checkup';
		$number = 'SUMC/MED/00'.$visit_id;
		
		$personnel_id = $this->session->userdata('personnel_id');
		/*
			-----------------------------------------------------------------------------------------
			Retrieve the details of the patient
			-----------------------------------------------------------------------------------------
		*/
		$patient = $this->reception_model->get_patient_data_from_visit($visit_id);
		$strath_no = $patient->strath_no;
		$visit_type = $patient->visit_type;
		$doctor_id = $patient->personnel_id;
		$patient_number = $patient->patient_number;
		$patient_insurance_id = $patient->patient_insurance_id;
		$visit_date = date('jS M Y H:i a',strtotime($patient->visit_time));
		$dependant_id = $patient->dependant_id;
		
		//patient names
		if($visit_type == 2)
		{
			//dependant
			if($dependant_id > 0)
			{
				$patient_type = $this->reception_model->get_patient_type($visit_type, $dependant_id);
				
				$dependant_query = $this->reception_model->get_dependant($strath_no);
				
				if($dependant_query->num_rows() > 0)
				{
					$dependants_result = $dependant_query->row();
					
					$patient_othernames = $dependants_result->other_names;
					$patient_surname = $dependants_result->names;
					$patient_date_of_birth = $dependants_result->DOB;
					$relationship = $dependants_result->relation;
					$gender = $dependants_result->Gender;
				}
				
				else
				{
					$patient_othernames = 'Dependant not found';
					$patient_surname = '';
					$patient_date_of_birth = '';
					$relationship = '';
					$gender = '';
				}
			}
			
			//staff
			else
			{
				$patient_type = $this->reception_model->get_patient_type($visit_type, $dependant_id);
				$staff_query = $this->reception_model->get_staff($strath_no);
				
				if($staff_query->num_rows() > 0)
				{
					$staff_result = $staff_query->row();
					
					$patient_surname = $staff_result->Surname;
					$patient_othernames = $staff_result->Other_names;
					$patient_date_of_birth = $staff_result->DOB;
					$patient_phone1 = $staff_result->contact;
					$gender = $staff_result->gender;
				}
				
				else
				{
					$patient_othernames = 'Staff not found';
					$patient_surname = '';
					$patient_date_of_birth = '';
					$relationship = '';
					$gender = '';
					$patient_type = '';
				}
			}
		}
		
		//student
		else if($visit_type == 1)
		{
			$student_query = $this->reception_model->get_student($strath_no);
			$patient_type = $this->reception_model->get_patient_type($visit_type);
			
			if($student_query->num_rows() > 0)
			{
				$student_result = $student_query->row();
				
				$patient_surname = $student_result->Surname;
				$patient_othernames = $student_result->Other_names;
				$patient_date_of_birth = $student_result->DOB;
				$patient_phone1 = $student_result->contact;
				$gender = $student_result->gender;
			}
			
			else
			{
				$patient_othernames = 'Student not found';
				$patient_surname = '';
				$patient_date_of_birth = '';
				$relationship = '';
				$gender = '';
			}
		}
		
		//other patient
		else
		{
			$patient_type = $this->reception_model->get_patient_type($visit_type);
			
			if($visit_type == 3)
			{
				$visit_type = 'Other';
			}
			else if($visit_type == 4)
			{
				$visit_type = 'Insurance';
			}
			else
			{
				$visit_type = 'General';
			}
			
			$patient_othernames = $patient->patient_othernames;
			$patient_surname = $patient->patient_surname;
			$patient_date_of_birth = $patient->patient_date_of_birth;
			$gender_id = $patient->gender_id;
			
			if($gender_id == 1)
			{
				$gender = 'Male';
			}
			else
			{
				$gender = 'Female';
			}
		}
		
		/*
			-----------------------------------------------------------------------------------------
			Get personnel data of the person who is printing the receipt
			-----------------------------------------------------------------------------------------
		*/
		$personnel = $this->personnel_model->get_single_personnel($personnel_id);
		$personnel_surname = $personnel->personnel_onames;
		$personnel_fname = $personnel->personnel_fname;
		
		//doctor
		$doctor_data = $this->personnel_model->get_single_personnel($doctor_id);
		$doctor_surname = $doctor_data->personnel_onames;
		$doctor_fname = $doctor_data->personnel_fname;
		$doctor = $doctor_surname." ".$doctor_fname;
		
		$totalxx = 0;
			
		/*
			-----------------------------------------------------------------------------------------
			Measurements of the page cells
			-----------------------------------------------------------------------------------------
		*/
		$pageH = 5;//height of an output cell
		$pageW = 0;//width of the output cell. Takes the entire width of the page
		$lineBreak = 20;//height between cells
		
		/*
			-----------------------------------------------------------------------------------------
			Begin creating the PDF in A4
			-----------------------------------------------------------------------------------------
		*/
		$this->load->library('fpdf');
		$this->fpdf->AliasNbPages();
		$this->fpdf->AddPage();
		
		/*
			-----------------------------------------------------------------------------------------
			Colors of frames, background and Text
			-----------------------------------------------------------------------------------------
		*/
		$this->fpdf->SetDrawColor(092, 123, 29);//color of borders
		$this->fpdf->SetFillColor(0, 232, 12);//color of shading
		//$this->fpdf->SetTextColor(092, 123, 29);//color of text
		$this->fpdf->SetFont('Times', 'B', 12);
		
		/*
			-----------------------------------------------------------------------------------------
			Title of the document.
			-----------------------------------------------------------------------------------------
		*/
		$lineBreak = 20;
		//Colors of frames, background and Text
		$this->fpdf->SetDrawColor(092, 123, 29);
		$this->fpdf->SetFillColor(0, 232, 12);
		$this->fpdf->SetTextColor(092, 123, 29);
		
		//thickness of frame (mm)
		//$this->SetLineWidth(1);
		//Logo
		$this->fpdf->Image(base_url().'images/strathmore.gif',10,8,45,15);
		//font
		$this->fpdf->SetFont('Arial', 'B', 12);
		//title
		$this->fpdf->Cell(0, 5, 'Strathmore University Medical Center', 0, 1, 'C');
		$this->fpdf->Cell(0, 5, 'P.O. Box 59857 00200, Nairobi, Kenya', 0, 1, 'C');
		$this->fpdf->Cell(0, 5, 'info@strathmore.edu', 0, 1, 'C');
		$this->fpdf->Cell(0, 5, 'Madaraka Estate', 'B', 1, 'C');
		$this->fpdf->SetFont('Arial', 'B', 10);
		
		$this->fpdf->Cell(0, 5, $title, 'B', 1, 'C');
		
		$this->fpdf->Ln(3);
		$this->fpdf->Cell(100,5,'Patient Name:	'.$patient_surname.' '.$patient_othernames, 0, 0, 'L');
		$this->fpdf->Cell(0,5,$heading.' Number:	'.$number, 0, 1, 'L');
		$this->fpdf->Cell(100,5,'Patient Number:	'.$patient_number, 0, 0, 'L');
		$this->fpdf->Cell(0,5,'Att. Doctor:	'.$doctor, 0, 1, 'L');
		$this->fpdf->Cell(0,5,$heading.' Date:	'.$visit_date, 'B', 1, 'L');
		$this->fpdf->Ln(3);
		
		$this->fpdf->SetTextColor(0, 0, 0); //226, 225, 225
		$this->fpdf->SetDrawColor(0, 0, 0); //226, 225, 225
		$total = 0;
		$pageH = 6;
		$width = 22.5;
		$mulit_height = 5;
		
		$exam_categories = $this->nurse_model->medical_exam_categories();

		if($exam_categories->num_rows() > 0)
		{
			$exam_results = $exam_categories->result();
			
			foreach ($exam_results as $exam_res)
			{
				$mec_name = $exam_res->mec_name;
				$mec_id = $exam_res->mec_id;
				
				$illnesses = $this->nurse_model->get_illness($visit_id, $mec_id);
				
				if($illnesses->num_rows() > 0)
				{
					$illnesses_row = $illnesses->row();
					$mec_result= $illnesses_row->infor;
				}
				
				else
				{
					$mec_result= '';
				}
				
				if($mec_name=="Family History")
				{
					$this->fpdf->SetFont('Times','B',11);
					$this->fpdf->Cell(0, $pageH, $mec_name, 'B', 1, 'C');
					$this->get_family_history($visit_id);
				}
				
				else if(($mec_name=="Present Illness")||($mec_name=="Past Illness")) 
				{
					$this->fpdf->SetFont('Times','B',11);
					$this->fpdf->Cell(0, $pageH, $mec_name, 0, 1, 'C');
					$this->fpdf->ln(2);
					
					$this->fpdf->SetFont('Times','',11);
					$this->fpdf->MultiCell(0, $mulit_height, $mec_result, 1, 'L');
					$this->fpdf->ln(2);
				}
				
				else if(($mec_name=="Physiological History")||($mec_name=="General Physical Examination")||($mec_name=="Head Physical Examination")||($mec_name=="Neck Physical Examination")||($mec_name=="Cardiovascular System Physical Examination")||($mec_name=="Respiratory System Physical Examination")||($mec_name=="Abdomen Physical Examination")||($mec_name=="Nervous System Physical Examination")) 
				{	
					$this->fpdf->SetFont('Times','B',11);
					$this->fpdf->Cell($width, $pageH, $mec_name, 'B', 1, 'C');
					
					$this->fpdf->SetFont('Times','',11);
					$this->fpdf->MultiCell(0, $mulit_height, $mec_result, 1, 'L');
					
					$category_items = $this->nurse_model->mec_med($mec_id);
					
					if($category_items->num_rows() > 0)
					{
						$ab=0;
						$category_items_result = $category_items->result();
						
						foreach($category_items_result as $cat_res)
						{
							$item_format_id = $cat_res->item_format_id;
							$ab++;
							
							$cat_items = $this->nurse_model->cat_items($item_format_id, $mec_id);
							
							if($cat_items->num_rows() > 0)
							{
								$cat_items_result = $cat_items->result();
								
								foreach($cat_items_result as $items_res)
								{
									$cat_item_name = $items_res->cat_item_name;
									$cat_items_id1 = $items_res->cat_items_id;
									
									$this->fpdf->SetFont('Times','B',11);
									$this->fpdf->Cell(0, $pageH, $cat_item_name, 'B', 1, 'C');
									
									$items_cat = $this->nurse_model->get_cat_items($item_format_id, $mec_id);
									
									if($items_cat->num_rows() > 0)
									{
										$items_result = $items_cat->result();
										
										foreach($items_result as $res)
										{
											$cat_item_name = $res->cat_item_name;
											$cat_items_id = $res->cat_items_id;
											$item_format_id1 = $res->item_format_id;
											$format_name = $res->format_name;
											$format_id = $res->format_id;
											
											if($cat_items_id == $cat_items_id1)
											{
												if($item_format_id1 == $item_format_id)
												{
													$results = $this->nurse_model->cat_items2($cat_items_id, $format_id,$visit_id);
													if($results->num_rows() > 0)
													{
														$this->fpdf->Image(base_url().'images/checked_checkbox.jpg', null, null, 3, 3);
													} 
												
													else 
													{ 
														$this->fpdf->Image(base_url().'images/unchecked_checkbox.jpg', null, null, 3, 3);
													}
												}
											}	
										}
									}
									
									else
									{
										$this->fpdf->Cell(0, $pageH, 'There are no items', 'B', 1, 'C');
									}
								}
							}
							
							else
							{
								$this->fpdf->Cell(0, $pageH, 'There are no category item results', 'B', 1, 'C');
							}
						}
					}
					
					else
					{
						$this->fpdf->Cell(0, $pageH, 'There are no category items', 'B', 1, 'C');
					}
				} 
			}
		}
		$this->fpdf->SetFont('Times','B',11);
		$this->fpdf->Cell($width, $pageH, $mec_name, 'B', 1, 'C');
		
		$this->fpdf->SetFont('Times','',11);
		$this->fpdf->MultiCell(0, $mulit_height, $mec_result, 1, 'L');

		$this->fpdf->Output();
	}
}
?>