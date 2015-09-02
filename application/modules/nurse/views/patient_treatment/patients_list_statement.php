<!-- search -->
<?php echo $this->load->view('search/search_patient', '', TRUE);?>
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
				
		$search = $this->session->userdata('patient_treatment_statement_search');
		
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/nurse/close_patient_treatment_search/'.$module.'" class="btn btn-warning">Close Search sd</a>';
		}
		
		
		$result = '';
		
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			if($delete == 0)
			{
				$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Patient Type</th>
						  <th>Surname</th>
						  <th>Other Names</th>
						  <th>Date Created</th>
						  <th>Last Visit</th>
						  <th colspan="5">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			}
			
			//deleted patients
			else
			{
				$result .= 
				'
					<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Student/Staff Number</th>
						  <th>Patient Type</th>
						  <th>Surname</th>
						  <th>Other Names</th>
						  <th>Action</th>
						</tr>
					  </thead>
					  <tbody>
				';
			}
			
			$personnel_query = $this->personnel_model->get_all_personnel();
			
			foreach ($query->result() as $row)
			{
				$patient_id = $row->patient_id;
				$dependant_id = $row->dependant_id;
				$strath_no = $row->strath_no;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$deleted_by = $row->deleted_by;
				$visit_type_id = $row->visit_type_id;
				$created = $row->patient_date;
				$last_modified = $row->last_modified;
				$last_visit = $row->last_visit;
				if($last_visit != NULL)
				{
					$last_visit = date('jS M Y',strtotime($last_visit));
				}
				
				else
				{
					$last_visit = '';
				}
				
				$patient = $this->reception_model->patient_names2($patient_id);
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
				if(empty($strath_no))
				{
					if(empty($dependant_id))
					{
						$strath_no = '-';
					}
					else
					{
						$strath_no = $dependant_id;
					}

				}
				else
				{
					if(empty($dependant_id))
					{
						$strath_no = $strath_no;
					}
					else
					{
						$strath_no = $dependant_id;
					}

				}
				$count++;
				
				if($delete == 1)
				{
					$deleted = $row->date_deleted;
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$strath_no.'</td>
							<td>'.$patient_type.'</td>
							<td>'.$patient_surname.'</td>
							<td>'.$patient_othernames.'</td>
							<td><a href="'.site_url().'/nurse/treatment_statement/'.$patient_id.'/'.$module.'" class="btn btn-sm btn-success">Treatment Statement</a></td>
						</tr> 
					';
				}
				
				else
				{
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$patient_type.'</td>
							<td>'.$patient_surname.'</td>
							<td>'.$patient_othernames.'</td>
							<td><a href="'.site_url().'/nurse/treatment_statement/'.$patient_id.'/'.$module.'" class="btn btn-sm btn-success">Treatment Statement</a></td>
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