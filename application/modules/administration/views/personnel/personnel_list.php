<!-- search -->
<?php echo $this->load->view('personnel/search_personnel', '', TRUE);?>
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
		if(!empty($search))
		{
			echo '<a href="'.site_url().'/administration/personnel/close_personnel_search" class="btn btn-warning">Close Search</a>';
		}
		echo '<a href="'.site_url().'/administration/personnel/add_personnel" class="btn btn-success">Add Personnel</a>';
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
						  <th>Other Names</th>
						  <th>First Name</th>
						  <th>Username</th>
						  <th>Phone</th>
						  <th>Roles</th>
						  <th colspan="3">Actions</th>
						</tr>
					  </thead>
					  <tbody>
				';
			
			foreach ($query->result() as $row)
			{
				
				$personnel_id = $row->personnel_id;
				$personnel_fname = $row->personnel_fname;
				$personnel_onames = $row->personnel_onames;
				$personnel_username = $row->personnel_username;
				$personnel_phone = $row->personnel_phone;
				$personnel_email = $row->personnel_email;
				$authorise = $row->authorise;
				$personnel_departments = $this->personnel_model->get_personnel_departments($personnel_id);
				
				$departments = '';
				if($personnel_departments->num_rows() > 0)
				{
					foreach($personnel_departments->result() as $dept)
					{
						$personnel_department_id = $dept->personnel_department_id;
						$departments_name = $dept->departments_name;
						
						$departments .= '<a href="'.site_url().'/administration/personnel/delete_personnel_department/'.$personnel_department_id.'" class="btn btn-sm btn-primary" onclick="return confirm(\'Delete '.$departments_name.' role from '.$personnel_fname.'?\');"><i class="icon-remove"></i></a> '.$departments_name.'<br/>';
					}
				}

				if($authorise == 1)
				{
					$buttons = '<a href="'.site_url().'/administration/personnel/activate_personnel/'.$personnel_id.'" class="btn btn-sm btn-success"onclick="return confirm(\'Do you want to activate '.$personnel_fname.' account?\');">Activate account</a>';
				}
				else if($authorise == 0)
				{
					$buttons = '<a href="'.site_url().'/administration/personnel/deactivate_personnel/'.$personnel_id.'" class="btn btn-sm btn-danger"onclick="return confirm(\'Do you want to deactivate '.$personnel_fname.' account?\');">Deactivate account</a>';
				}
				else
				{
					$buttons = '';
				}
				
				$count++;
				$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$personnel_onames.'</td>
							<td>'.$personnel_fname.'</td>
							<td>'.$personnel_username.'</td>
							<td>'.$personnel_phone.'</td>
							<td>'.$departments.'</td>
							<td><a href="'.site_url().'/administration/personnel/reset_password/'.$personnel_id.'" class="btn btn-sm btn-success" onclick="return confirm(\'Reset password for '.$personnel_fname.'?\');">Reset Password</a></td>
							<td><a href="'.site_url().'/administration/personnel/edit_personnel/'.$personnel_id.'" class="btn btn-sm btn-info">Edit</a></td>
							<td>'.$buttons.'</td>
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
			$result .= "There are no personnel";
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