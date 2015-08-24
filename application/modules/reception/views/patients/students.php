<!-- search -->
<?php echo $this->load->view('patients/search/students', '', TRUE);?>
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
		$success = $this->session->userdata('success_message');
		
		if(!empty($error))
		{
			echo '<div class="alert alert-danger">'.$error.'</div>';
			$this->session->unset_userdata('error_message');
		}
		
		if(!empty($success))
		{
			echo '<div class="alert alert-success">'.$success.'</div>';
			$this->session->unset_userdata('success_message');
		}
		
		if(!empty($patient_search))
		{
			echo '<a href="'.site_url().'/reception/close_patient_search/3" class="btn btn-warning">Close Search</a>';
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
					  <th>Student Number</th>
					  <th>Surname</th>
					  <th>Other Names</th> 
					  <th>Phone number</th>
					  <th>Last Visit</th>
					  <th>Balance</th>
					  <th colspan="4">Actions</th>
					</tr>
				  </thead>
				  <tbody>';
			
			$personnel_query = $this->personnel_model->get_all_personnel();
			
			foreach ($query->result() as $row)
			{
				$patient_id = $row->patient_id;
				$strath_no = $row->strath_no;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$deleted_by = $row->deleted_by;
				$visit_type_id = $row->visit_type_id;
				$created = $row->patient_date;
				$last_modified = $row->last_modified;
				$last_visit = $row->last_visit;
				$patient_phone1 = $row->patient_phone1;

				$patient = $this->reception_model->patient_names2($patient_id);
				$patient_type = $patient['patient_type'];
				$patient_othernames = $patient['patient_othernames'];
				$patient_surname = $patient['patient_surname'];
				$patient_type_id = $patient['visit_type_id'];
				$account_balance = $patient['account_balance'];
				if(!empty($patient_search))
				{
					$student_number = $row->student_Number;
					$patient_othernames = $row->Other_names;
					$patient_surname = $row->Surname;
					$patient_date_of_birth = $row->DOB;
					$gender = $row->gender;
				}
				
				else
				{
					$patient = $this->reception_model->get_student_data($strath_no);
					$student_number = $patient['student_number'];
					$patient_othernames = $patient['patient_othernames'];
					$patient_surname = $patient['patient_surname'];
					$patient_date_of_birth = $patient['patient_date_of_birth'];
					$gender = $patient['gender'];
				}
				
				if($last_visit != NULL)
				{
					$last_visit = date('jS M Y',strtotime($last_visit));
				}
				
				else
				{
					$last_visit = '';
				}
				
				//creators and editors
				if($personnel_query->num_rows() > 0)
				{
					$personnel_result = $personnel_query->result();
					
					foreach($personnel_result as $adm)
					{
						$personnel_id = $adm->personnel_id;
						
						if($personnel_id == $created_by)
						{
							$created_by = $adm->personnel_fname;
						}
						
						if($personnel_id == $modified_by)
						{
							$modified_by = $adm->personnel_fname;
						}
						
						if($personnel_id == $modified_by)
						{
							$modified_by = $adm->personnel_fname;
						}
						
						if($personnel_id == $deleted_by)
						{
							$deleted_by = $adm->personnel_fname;
						}
					}
				}
				
				else
				{
					$created_by = '-';
					$modified_by = '-';
					$deleted_by = '-';
				}
				
				$count++;
				
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$student_number.'</td>
						<td>'.$patient_surname.'</td>
						<td>'.$patient_othernames.'</td>
						<td>'.$patient_phone1.'</td>
						<td>'.$last_visit.'</td>
						<td>  '.number_format($account_balance,0).'</td>
						<td><a href="'.site_url().'/reception/set_visit/'.$patient_id.'" class="btn btn-sm btn-success">Visit</a></td>
						<td><a href="'.site_url().'/reception/edit_student/'.$patient_id.'" class="btn btn-sm btn-warning">Edit </a></td>
						<td><a href="'.site_url().'/administration/individual_statement/'.$patient_id.'/2" class="btn btn-sm btn-danger" target="_blank">Patient Statement</a></td>
						<td><a href="'.site_url().'/reception/to-others/'.$patient_id.'/1" class="btn btn-sm btn-primary">Change to others</a></td>

						<!--<td><a href="'.site_url().'/reception/dependants/'.$patient_id.'" class="btn btn-sm btn-primary">Dependants</a></td>-->
						<!--<td><a href="'.site_url().'edit-patient/'.$patient_id.'" class="btn btn-sm btn-default">Edit</a></td>-->
						<!--<td><a href="'.site_url().'/reception/delete_patient/'.$patient_id.'/4" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete ?\');">Delete</a></td>-->
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