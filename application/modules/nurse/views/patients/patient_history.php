<!-- search -->
<?php //echo $this->load->view('patients/search_visit', '', TRUE);?>
<!-- end search -->
 
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd">
          
<?php
		$search = $this->session->userdata('visit_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/nurse/close_queue_search" class="btn btn-warning">Close Search</a>';
		}
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Visit Date</th>
						  <th>Patient</th>
						  <th>Patient Type</th>
						  <th>Visit Type</th>
						  <th>Time In</th>
						  <th>Doctor</th>
						  <th colspan="4">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			$personnel_query = $this->personnel_model->get_all_personnel();
			
			foreach ($query->result() as $row)
			{
				$visit_date = date('jS M Y',strtotime($row->visit_date));
				$visit_time = date('H:i a',strtotime($row->visit_time));
				if($row->visit_time_out != '0000-00-00 00:00:00')
				{
					$visit_time_out = date('H:i a',strtotime($row->visit_time_out));
				}
				else
				{
					$visit_time_out = '-';
				}
				$visit_id = $row->visit_id;
				$patient_id = $row->patient_id;
				$personnel_id = $row->personnel_id;
				$dependant_id = $row->dependant_id;
				$strath_no = $row->strath_no;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$visit_type_id = $row->visit_type_id;
				$visit_type = $row->visit_type;
				$created = $row->patient_date;
				$last_modified = $row->last_modified;
				$last_visit = $row->last_visit;
				
				//staff & dependant
				if($visit_type == 2)
				{
					//dependant
					if($dependant_id > 0)
					{
						$patient_type = $this->reception_model->get_patient_type($visit_type_id, $dependant_id);
						$visit_type = 'Dependant';
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
							$patient_othernames = '<span class="label label-important">Dependant not found</span>';
							$patient_surname = '';
							$patient_date_of_birth = '';
							$relationship = '';
							$gender = '';
						}
					}
					
					//staff
					else
					{
						$patient_type = $this->reception_model->get_patient_type($visit_type_id, $dependant_id);
						$staff_query = $this->reception_model->get_staff($strath_no);
						$visit_type = 'Staff';
						
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
							$patient_othernames = '<span class="label label-important">Staff not found</span>';
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
					$patient_type = $this->reception_model->get_patient_type($visit_type_id);
					$visit_type = 'Student';
					
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
						$patient_othernames = '<span class="label label-important">Student not found</span>';
						$patient_surname = '';
						$patient_date_of_birth = '';
						$relationship = '';
						$gender = '';
					}
				}
				
				//other patient
				else
				{
					$patient_type = $this->reception_model->get_patient_type($visit_type_id);
					
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
					
					$patient_othernames = $row->patient_othernames;
					$patient_surname = $row->patient_surname;
					$title_id = $row->title_id;
					$patient_date_of_birth = $row->patient_date_of_birth;
					$civil_status_id = $row->civil_status_id;
					$patient_address = $row->patient_address;
					$patient_post_code = $row->patient_postalcode;
					$patient_town = $row->patient_town;
					$patient_phone1 = $row->patient_phone1;
					$patient_phone2 = $row->patient_phone2;
					$patient_email = $row->patient_email;
					$patient_national_id = $row->patient_national_id;
					$religion_id = $row->religion_id;
					$gender_id = $row->gender_id;
					$patient_kin_othernames = $row->patient_kin_othernames;
					$patient_kin_sname = $row->patient_kin_sname;
					$relationship_id = $row->relationship_id;
				}
				
				//creators and editors
				if($personnel_query->num_rows() > 0)
				{
					$personnel_result = $personnel_query->result();
					
					foreach($personnel_result as $adm)
					{
						$personnel_id2 = $adm->personnel_id;
						
						if($personnel_id == $personnel_id2)
						{
							$doctor = $adm->personnel_fname;
							break;
						}
						
						else
						{
							$doctor = '-';
						}
					}
				}
				
				else
				{
					$doctor = '-';
				}
				
				$count++;
				
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$visit_date.'</td>
							<td>'.$patient_surname.' '.$patient_othernames.'</td>
							<td>'.$patient_type.'</td>
							<td>'.$visit_type.'</td>
							<td>'.$visit_time.'</td>
							<td>'.$doctor.'</td>
							<td><a href="'.site_url().'nurse/patient_card/'.$visit_id.'" class="btn btn-sm btn-info">Patient Card</a></td>
							<td><a href="'.site_url().'delete-brand/1" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you really want to delete ?\');">To Doctor</a></td>
							<td><a href="'.site_url().'delete-brand/1" class="btn btn-sm btn-success" onclick="return confirm(\'Do you really want to delete ?\');">To Lab</a></td>
							<td><a href="'.site_url().'delete-brand/1" class="btn btn-sm btn-primary" onclick="return confirm(\'Do you really want to delete ?\');">To Pharmacy</a></td>
						</tr> 
					';
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "This patient has no previous visits";
		}
		
		echo $result;
?>
          </div>
          
          <div class="widget-foot">
                                
				<?php if(isset($links)){echo $links;}?>
            
                <div class="clearfix"></div> 
            
            </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>