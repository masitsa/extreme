<?php
//personnel data
$row = $personnel->row();

// var_dump($row) or die();
$personnel_onames = $row->personnel_onames;
$personnel_fname = $row->personnel_fname;
$personnel_dob = $row->personnel_dob;
$personnel_email = $row->personnel_email;
$personnel_phone = $row->personnel_phone;
$personnel_address = $row->personnel_address;
$civil_status_id = $row->civilstatus_id;
$personnel_locality = $row->personnel_locality;
$title_id = $row->title_id;
$gender_id = $row->gender_id;
$personnel_username = $row->personnel_username;
$personnel_kin_fname = $row->personnel_kin_fname;
$personnel_kin_onames = $row->personnel_kin_onames;
$personnel_kin_contact = $row->personnel_kin_contact;
$personnel_kin_address = $row->personnel_kin_address;
$kin_relationship_id = $row->kin_relationship_id;
$job_title_idd = $row->job_title_id;
$staff_id = $row->personnel_staff_id;

//echo $gender_id;
//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$personnel_onames =set_value('personnel_onames');
	$personnel_fname =set_value('personnel_fname');
	$personnel_dob =set_value('personnel_dob');
	$personnel_email =set_value('personnel_email');
	$personnel_phone =set_value('personnel_phone');
	$personnel_address =set_value('personnel_address');
	$civil_status_id =set_value('civil_status_id');
	$personnel_locality =set_value('personnel_locality');
	$title_id =set_value('title_id');
	$gender_id =set_value('gender_id');
	$personnel_username =set_value('personnel_username');
	$personnel_kin_fname =set_value('personnel_kin_fname');
	$personnel_kin_onames =set_value('personnel_kin_onames');
	$personnel_kin_contact =set_value('personnel_kin_contact');
	$personnel_kin_address =set_value('personnel_kin_address');
	$kin_relationship_id =set_value('kin_relationship_id');
	$job_title_id =set_value('job_title_id');
	$staff_id =set_value('staff_id');
}
?>
		<div class="row">
        	<!-- <div class="col-md-2">
            	<img src="<?php echo base_url().'assets/img/avatar.jpg';?>" class="img-responsive img-thumbnail" />
            </div> -->
            
            <div class="col-md-9">
            	<!-- <h2><?php echo $personnel_fname.' '.$personnel_onames;?></h2>
                <p>
                    <i class="fa fa-phone"/></i>
                    <span id="mobile_phone"><?php echo $personnel_phone;?></span>
                    <i class="fa fa-envelope"/></i>
                    <span id="work_email"><?php echo $personnel_email;?></span>
                </p> -->
            </div>
            
        	<div class="col-md-3">
            
            </div>
        </div>
      	<div class="row">
        
          <section class="panel">

                <header class="panel-heading">
                	<div class="row">
	                	<div class="col-md-6">
		                    <h2 class="panel-title"><?php echo $title;?> <?php echo $personnel_fname.' '.$personnel_onames;?> Details</h2>
		                    <i class="fa fa-phone"/></i>
		                    <span id="mobile_phone"><?php echo $personnel_phone;?></span>
		                    <i class="fa fa-envelope"/></i>
		                    <span id="work_email"><?php echo $personnel_email;?></span>
		                </div>
		                <div class="col-md-6">
		                		<a href="<?php echo site_url();?>human-resource/personnel" class="btn btn-sm btn-info pull-right">Back to personnel</a>
		                </div>
	                </div>
                </header>
                <div class="panel-body">
                    
                    <div class="row">
                    	<div class="col-md-12">
                        	<?php
                            	$success = $this->session->userdata('success_message');
                            	$error = $this->session->userdata('error_message');
								
								if(!empty($success))
								{
									echo '
										<div class="alert alert-success">'.$success.'</div>
									';
									
									$this->session->unset_userdata('success_message');
								}
								
								if(!empty($error))
								{
									echo '
										<div class="alert alert-danger">'.$error.'</div>
									';
									
									$this->session->unset_userdata('error_message');
								}
								
							?>
                        	<div class="tabs">
								<ul class="nav nav-tabs nav-justified">
									<li class="active">
										<a class="text-center" data-toggle="tab" href="#general"><i class="fa fa-user"></i> General details</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#account"><i class="fa fa-lock"></i> Account details</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#emergency">Emergency contacts</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#dependants">Dependants</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#job">Job</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#leave"><i class="fa fa-calendar-check-o"></i> Leave</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="general">
										<?php echo $this->load->view('my_account/about', '', TRUE);?>
									</div>
									<div class="tab-pane" id="account">
										<?php echo $this->load->view('my_account/account', '', TRUE);?>
									</div>
									<div class="tab-pane" id="emergency">
										<?php echo $this->load->view('my_account/emergency', '', TRUE);?>
									</div>
									<div class="tab-pane" id="dependants">
										<?php echo $this->load->view('my_account/dependants', '', TRUE);?>
									</div>
									<div class="tab-pane" id="job">
										<?php echo $this->load->view('my_account/jobs', '', TRUE);?>
									</div>
									<div class="tab-pane" id="leave">
										<?php echo $this->load->view('my_account/leave', '', TRUE);?>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>