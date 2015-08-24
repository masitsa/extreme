<!-- search -->
<?php echo $this->load->view('patients/search_visit', '', TRUE);?>
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
            	$error = $this->session->userdata('error_message');
            	$validation_error = validation_errors();
				$success = $this->session->userdata('success_message');
				
				if(!empty($error))
				{
					echo '<div class="alert alert-danger">'.$error.'</div>';
					$this->session->unset_userdata('error_message');
				}
				
				if(!empty($validation_error))
				{
					echo '<div class="alert alert-danger">'.$validation_error.'</div>';
				}
				
				if(!empty($success))
				{
					echo '<div class="alert alert-success">'.$success.'</div>';
					$this->session->unset_userdata('success_message');
				}
			?>
<?php
		$search = $this->session->userdata('visit_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/reception/close_visit_search/'.$visit.'/'.$page_name.'" class="btn btn-warning">Close Search</a>';
		}
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			//deleted patients
			if($delete == 1)
			{
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
						  <th>Date Deleted</th>
						  <th>Deleted By</th>
						</tr>
					  </thead>
					  <tbody>
				';
			}
			
			else
			{
				if(($visit == 0) || ($visit == 3))
				{
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
							  <th colspan="5">Actions</th>
							</tr>
						  </thead>
						  <tbody>
					';
				}
				
				else

				{
					if($page == 'nurse' || $page == 'doctor')
					{
						$result .= 
					'
						<table class="table table-hover table-bordered ">
						  <thead>
							<tr>
							  <th>#</th>
							  <th>Visit Date</th>
							  <th>Patient</th>
							  <th>Patient Type</th>
							  <th>Time In</th>
							  <th>Time Out</th>
							  <th>Doctor</th>
							  <th>Diagnosis</th>
							</tr>
						  </thead>
						  <tbody>
					';
					}
					else
					{
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
								  <th>Time Out</th>
								  <th>Doctor</th>
								  <th>Actions</th>
								</tr>
							  </thead>
							  <tbody>
						';
					}
				}
			}
			
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
				$visit_type_id = $row->visit_type_id;
				$visit_type = $row->visit_type;
				$visit_table_visit_type = $visit_type;
				$patient_table_visit_type = $visit_type_id;
				$patient_national_id = $row->patient_national_id;
				
				$patient = $this->reception_model->patient_names2($patient_id, $visit_id);
				$visit_type = $patient['visit_type'];
				$patient_type = $patient['patient_type'];
				$patient_othernames = $patient['patient_othernames'];
				$patient_surname = $patient['patient_surname'];
				$patient_date_of_birth = $patient['patient_date_of_birth'];
				$gender = $patient['gender'];
				
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

				// start of diagnosis
				$diagnosis_rs = $this->nurse_model->get_diagnosis($visit_id);
				$diagnosis_num_rows = count($diagnosis_rs);
				//echo $num_rows;
				$patient_diagnosis = "";
				if($diagnosis_num_rows > 0){
					foreach ($diagnosis_rs as $diagnosis_key):
						$diagnosis_id = $diagnosis_key->diagnosis_id;
						$diagnosis_name = $diagnosis_key->diseases_name;
						$diagnosis_code = $diagnosis_key->diseases_code;
						$patient_diagnosis .="".$diagnosis_code."-".$diagnosis_name."<br>";
						
					endforeach;
				}
				else
				{
					$patient_diagnosis .= "-";
				}
				// end of diagnosis
				
				$count++;
				
				if($delete == 0)
				{
					if(($visit == 0) || ($visit == 3))
					{
						if($page_name == 'doctor')
						{
							$button = '
							<td><a href="'.site_url().'/nurse/patient_card/'.$visit_id.'/a/1" class="btn btn-sm btn-info">Patient Card</a></td>
							<td><a href="'.site_url().'/nurse/dental_visit/'.$visit_id.'/a/1" class="btn btn-sm btn-danger">Dental Vitals</a></td>
							<td><a href="'.site_url().'/nurse/send_to_labs/'.$visit_id.'" class="btn btn-sm btn-success" onclick="return confirm(\'Send to lab?\');">To Lab</a></td>
							<td><a href="'.site_url().'/nurse/send_to_pharmacy/'.$visit_id.'" class="btn btn-sm btn-primary" onclick="return confirm(\'Send to pharmacy?\');">To Pharmacy</a></td>
							';
						}
						
						else if($page_name == 'dental')
						{
							$button = '
							<td><a href="'.site_url().'/dental/patient_card/'.$visit_id.'" class="btn btn-sm btn-success">Patient Card</a></td>
							<td><a href="'.site_url().'/dental/send_to_account/'.$visit_id.'" class="btn btn-sm btn-primary" onclick="return confirm(\'Send to accounts?\');">To Account</a></td>
							';
						}
						else if($page_name == 'physiotherapy')
						{
							$button = '
							<td><a href="'.site_url().'/physiotherapy/patient_card/'.$visit_id.'" class="btn btn-sm btn-success">Patient Card</a></td>
							<td><a href="'.site_url().'/physiotherapy/send_to_account/'.$visit_id.'" class="btn btn-sm btn-primary" onclick="return confirm(\'Send to accounts?\');">To Account</a></td>
							';
						}
						
						else if($page_name == 'ultra_sound')
						{
							$button = '
							<td><a href="'.site_url().'/ultra_sound/patient_card/'.$visit_id.'" class="btn btn-sm btn-success">Patient Card</a></td>
							<td><a href="'.site_url().'/ultra_sound/send_to_account/'.$visit_id.'" class="btn btn-sm btn-primary" onclick="return confirm(\'Send to accounts?\');">To Account</a></td>
							';
						}
						
						else if($page_name == 'nurse')
						{
							$button = '
							<td><a href="'.site_url().'/nurse/patient_card/'.$visit_id.'/a/0" class="btn btn-sm btn-info">Patient Card</a></td>
							<td><a href="'.site_url().'/nurse/dental_visit/'.$visit_id.'/a/0" class="btn btn-sm btn-danger">Dental Vitals</a></td>

							<td><a href="'.site_url().'/nurse/send_to_doctor/'.$visit_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Send to doctor?\');">To Doctor</a></td>
							<td><a href="'.site_url().'/nurse/send_to_labs/'.$visit_id.'" class="btn btn-sm btn-success" onclick="return confirm(\'Send to lab?\');">To Lab</a></td>
							<td><a href="'.site_url().'/nurse/send_to_pharmacy/'.$visit_id.'" class="btn btn-sm btn-primary" onclick="return confirm(\'Send to pharmacy?\');">To Pharmacy</a></td>
							';
						}
						
						
						else
						{
							$button = '<td><a href="'.site_url().'/reception/end_visit/'.$visit_id.'/'.$visit.'" class="btn btn-sm btn-info" onclick="return confirm(\'Do you really want to end this visit ?\');">End Visit</a></td>
								<td><a href="'.site_url().'/reception/delete_visit/'.$visit_id.'/'.$visit.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete this visit?\');">Delete</a></td>';
								
							//if staff was registered as other
							if(($visit_table_visit_type == 2) && ($patient_table_visit_type != $visit_table_visit_type))
							{
								$button .= '<td><a href="'.site_url().'/reception/change_patient_type/'.$patient_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you really want to change this patient type?\');">Change Patient Type</a></td>';
							}
							//if student was registered as other
							else if(($visit_table_visit_type == 1) && ($patient_table_visit_type != $visit_table_visit_type))
							{
								$button .= '<td><a href="'.site_url().'/reception/change_patient_type/'.$patient_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you really want to change this patient type?\');">Change Patient Type</a></td>';
							}
							
							else
							{
								$button .= '<td></td>';
							}
						}
						
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
								'.$button.'
							</tr> 
						';
					}
					
					else
					{

						if($page == 'nurse' || $page == 'doctor')
						{
							$result .= 
							'
								<tr>
									<td>'.$count.'</td>
									<td>'.$visit_date.'</td>
									<td>'.$patient_surname.' '.$patient_othernames.'</td>
									<td>'.$patient_type.'</td>
									<td>'.$visit_time.'</td>
									<td>'.$visit_time_out.'</td>
									<td>'.$doctor.'</td>
									<td>'.$patient_diagnosis.'</td>
								</tr> 
							';
						}
						else
						{
							$result .= 
							'
								<tr>
									<td>'.$count.'</td>
									<td>'.$visit_date.'</td>
									<td>'.$patient_surname.' '.$patient_othernames.'</td>
									<td>'.$patient_type.'</td>
									<td>'.$visit_type.'</td>
									<td>'.$visit_time.'</td>
									<td>'.$visit_time_out.'</td>
									<td>'.$doctor.'</td>
									<td><a href="'.site_url().'/reception/delete_visit/'.$visit_id.'/'.$visit.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete this visit?\');">Delete</a></td>
								</tr> 
							';
						}
						
					}
				}
				
				else
				{
					//deleted by
					$deleted_by = $row->deleted_by;
					
					if($personnel_query->num_rows() > 0)
					{
						$personnel_result = $personnel_query->result();
						
						foreach($personnel_result as $adm)
						{	
							$personnel_id2 = $adm->personnel_id;
							if($deleted_by == $personnel_id2)
							{
								$deleted_by = $adm->personnel_fname;
								break;
							}
						}
					}
					
					$deleted = $row->date_deleted;
					
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
							<td>'.date('jS M Y H:i a',strtotime($deleted)).'</td>
							<td>'.$deleted_by.'</td>
						</tr> 
					';
				}
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