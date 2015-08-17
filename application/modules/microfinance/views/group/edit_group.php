<?php
//group data
$row = $group->row();

$group_name = $row->group_name;
$group_onames = $row->group_contact_onames;
$group_fname = $row->group_contact_fname;
$group_email = $row->group_email;
$group_phone = $row->group_phone;
//echo $gender_id;
//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$group_onames =set_value('group_onames');
	$group_fname =set_value('group_fname');
	$group_dob =set_value('group_dob');
	$group_email =set_value('group_email');
	$group_phone =set_value('group_phone');
	$group_address =set_value('group_address');
	$civil_status_id =set_value('civil_status_id');
	$group_locality =set_value('group_locality');
	$title_id =set_value('title_id');
	$gender_id =set_value('gender_id');
	$group_username =set_value('group_username');
	$group_kin_fname =set_value('group_kin_fname');
	$group_kin_onames =set_value('group_kin_onames');
	$group_kin_contact =set_value('group_kin_contact');
	$group_kin_address =set_value('group_kin_address');
	$kin_relationship_id =set_value('kin_relationship_id');
	$job_title_id =set_value('job_title_id');
	$staff_id =set_value('staff_id');
}
?>
      	<div class="row">
        
          <section class="panel">

                <header class="panel-heading">
                	<div class="row">
	                	<div class="col-md-6">
		                    <h2 class="panel-title">Edit <?php echo $group_name;?> Details</h2>
		                    <i class="fa fa-phone"/></i>
		                    <span id="mobile_phone"><?php echo $group_phone;?></span>
		                    <i class="fa fa-envelope"/></i>
		                    <span id="work_email"><?php echo $group_email;?></span>
		                </div>
		                <div class="col-md-6">
		                		<a href="<?php echo site_url();?>microfinance/group" class="btn btn-sm btn-info pull-right">Back to groups</a>
		                </div>
	                </div>
                </header>
                <div class="panel-body">
                
                	<?php
					$validation_errors = validation_errors();
					if(!empty($validation_errors))
					{
						echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
					}
					
					$success = $this->session->userdata('success_message');
		
					if(!empty($success))
					{
						echo '<div class="alert alert-success"> <strong>Success!</strong> '.$success.' </div>';
						$this->session->unset_userdata('success_message');
					}
					
					$error = $this->session->userdata('error_message');
					
					if(!empty($error))
					{
						echo '<div class="alert alert-danger"> <strong>Oh snap!</strong> '.$error.' </div>';
						$this->session->unset_userdata('error_message');
					}
					?>
                    
                    <div class="row">
                    	<div class="col-md-12">
                        	<div class="tabs">
								<ul class="nav nav-tabs nav-justified">
									<li class="active">
										<a class="text-center" data-toggle="tab" href="#general"><i class="fa fa-user"></i> General details</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#members"><i class="fa fa-users"></i> Members</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#add_member"><i class="fa fa-plus"></i> Add member</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#history"><i class="fa fa-hourglass-end"></i> History</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="general">
										<?php echo $this->load->view('edit/about', '', TRUE);?>
									</div>
									<div class="tab-pane" id="members">
										<?php echo $this->load->view('edit/members', '', TRUE);?>
									</div>
									<div class="tab-pane" id="add_member">
										<?php echo $this->load->view('edit/add_member', '', TRUE);?>
									</div>
									<div class="tab-pane" id="history">
										<?php echo $this->load->view('edit/history', '', TRUE);?>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </section>