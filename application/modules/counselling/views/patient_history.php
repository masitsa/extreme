<!-- search -->
<?php 

		
		 $patient_id = $this->nurse_model->get_patient_id($visit_id);
		// this is it

		$where = 'visit.`close_card` = 1 AND visit.patient_id = patients.patient_id AND visit.`patient_id`='.$patient_id;
		
		
		$table = 'visit,patients';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/nurse/patient_card/'.$visit_id;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = 4;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->nurse_model->get_all_patient_history($table, $where, $config["per_page"], $page);
		
	
		
?>
<!-- end search -->
 
<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i>Patient History</h4>
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
						  <th>Time In</th>
						  <th>Waiting time</th>
						  <th>Doctor</th>
						  <th colspan="4">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			$personnel_query = $this->personnel_model->get_all_personnel();
			$count = 0;
			foreach ($query->result() as $row)
			{
				$visit_date = date('jS M Y',strtotime($row->visit_date));
				$visit_time = date('H:i a',strtotime($row->visit_time));


				

				$visit_id1 = $row->visit_id;
				$waiting_time = $this->nurse_model->waiting_time($visit_id1);
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
							<td>'.$visit_time.'</td>
							<td align=center>'.$waiting_time.'</td>
							<td>'.$doctor.'</td>
							<td><a onclick="patient_history_popup('.$visit_id1.',1)" class="btn btn-sm btn-info">Patient Card</a></td>
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
			$result .= "There are no patients";
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

  <script>
	  function patient_history_popup(visit_id,mike) {
	    var config_url = $('#config_url').val();
	    window.open( config_url+"/nurse/patient_card/"+visit_id+"/"+mike, "myWindow", "status = 1, height = auto, width = 100%, resizable = 0" )
		}

  </script>