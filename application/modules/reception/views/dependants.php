<div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i>All Dependants</h4>
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
				
		
				$patient_row = $patient_query->row();
				$visit_type_id = $patient_row->visit_type_id;
				
				//if patient is staff
				if($visit_type_id == 2)
				{
					$dependants_query = $this->reception_model->get_all_staff_dependants($patient_id);
				}
				
                if($dependants_query->num_rows() > 0)
				{
					$count = 0;
					echo '
          		<table class="table table-hover table-bordered ">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Surname</th>
					  <th>Other Names</th>
					  <th colspan="4">Actions</th>
					</tr>
				  </thead>
				  <tbody>';
				  
					$dependants = $dependants_query->result();
					
					//if patient is staff
					if($visit_type_id == 2)
					{
						foreach($dependants as $dep)
						{
							$count++;
							$patient_surname = $dep->names;
							$patient_othernames = $dep->other_names;
							$staff_patient_query = $this->reception_model->get_staff_dependant_patient($dep->staff_dependants_id, $dep->Staff_Number);
							if($staff_patient_query->num_rows() > 0)
							{
								$staff_patient_row = $staff_patient_query->row();
								
								$patient_id = $staff_patient_row->patient_id;
								
								echo 
								'
								<tr>
									<td>'.$count.'</td>
									<td>'.$patient_surname.'</td>
									<td>'.$patient_othernames.'</td>
									<td><a href="'.site_url().'/reception/set_visit/'.$patient_id.'" class="btn btn-sm btn-success">Visit</a></td>
									<td><a href="'.site_url().'/reception/lab_visit/'.$patient_id.'" class="btn btn-sm btn-info">Lab</a></td>
									<td><a href="'.site_url().'/reception/initiate_pharmacy/'.$patient_id.'" class="btn btn-sm btn-warning">Pharmacy</a></td>
									<td><a href="'.site_url().'/reception/dependants/'.$patient_id.'" class="btn btn-sm btn-primary">Dependants</a></td>
								</tr>
								';
							}
						}
					}
					
					else
					{
						foreach($dependants as $dep)
						{
							$count++;
							$patient_surname = $dep->patient_surname;
							$patient_othernames = $dep->patient_othernames;
							$patient_id = $dep->patient_id;
							
							echo 
							'
							<tr>
								<td>'.$count.'</td>
								<td>'.$patient_surname.'</td>
								<td>'.$patient_othernames.'</td>
								<td><a href="'.site_url().'/reception/set_visit/'.$patient_id.'" class="btn btn-sm btn-success">Visit</a></td>
								<td><a href="'.site_url().'/reception/lab_visit/'.$patient_id.'" class="btn btn-sm btn-info">Lab</a></td>
								<td><a href="'.site_url().'/reception/initiate_pharmacy/'.$patient_id.'" class="btn btn-sm btn-warning">Pharmacy</a></td>
								<td><a href="'.site_url().'/reception/dependants/'.$patient_id.'" class="btn btn-sm btn-primary">Dependants</a></td>
							</tr>
							';
						}
					}
					
					echo '
                </tbody>
                </table>';
				}
				
				else
				{
					echo 'This patient has no dependants';
				}
                ?>
          </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>
  
  <div class="row">
    <div class="col-md-12">

      <!-- Widget -->
      <div class="widget boxed">
        <!-- Widget head -->
        <div class="widget-head">
          <h4 class="pull-left"><i class="icon-reorder"></i>Add Dependant</h4>
          <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
          </div>
          <div class="clearfix"></div>
        </div>             

        <!-- Widget content -->
        <div class="widget-content">
          <div class="padd">
          <div class="center-align">
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
                echo $this->load->view("patients/dependants", '', TRUE);
                ?>
          </div>
        </div>
        <!-- Widget ends -->

      </div>
    </div>
  </div>

