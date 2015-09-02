<?php
	$departments_display = '';
	
	if($personnel_departments->num_rows() > 0)
	{
		$departments = $personnel_departments->result();
		
		foreach($departments as $dept)
		{
			$department_id = $dept->department_id;
			$department_name = $dept->departments_name;
			$department_image = $dept->departments_image;
			$department_url = $dept->departments_url;
			
			$departments_display .= '
			<div class="col-sm-4 col-md-2">
				<div class="thumbnail">
					<a href="'.site_url().'/'.$department_url.'">
						<img src="'.base_url().'images/icons/'.$department_image.'" alt="'.$department_name.'." class="control_panel_img">
						<div class="caption">
							<p><h3>'.$department_name.'</h3></p>
						</div>
					</a>
				</div>
			</div>
			';
		}
	}
	
	else
	{
		$departments_display = '<h3>You have not been assigned to any department. Please see an administrator</h3>';
	}
?>
<div class="control_panel_form">
        <div class="container">
        	
            <div class="center-align">
            	<div class="row">
            			<?php
			             $error = $this->session->userdata('user_error_message');
        				 $success = $this->session->userdata('user_success_message');
        				
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
            		
            	</div>
            	<div class="row">
                    <h3>Hi <?php echo $this->session->userdata('personnel_fname');?>. Please select a department</h3>
                </div>
                <div class="row">
                	<?php
                    	echo $departments_display;
					?>
                </div>
            </div>
        </div> 
    </div>