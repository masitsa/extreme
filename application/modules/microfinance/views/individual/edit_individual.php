<?php
//individual data
$row = $individual->row();

$individual_onames = $row->individual_onames;
$individual_fname = $row->individual_fname;
$individual_email = $row->individual_email;
$individual_phone = $row->individual_phone;
//echo $gender_id;
//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$individual_onames =set_value('individual_onames');
	$individual_fname =set_value('individual_fname');
	$individual_dob =set_value('individual_dob');
	$individual_email =set_value('individual_email');
	$individual_phone =set_value('individual_phone');
	$individual_address =set_value('individual_address');
	$civil_status_id =set_value('civil_status_id');
	$individual_locality =set_value('individual_locality');
	$title_id =set_value('title_id');
	$gender_id =set_value('gender_id');
	$individual_username =set_value('individual_username');
	$individual_kin_fname =set_value('individual_kin_fname');
	$individual_kin_onames =set_value('individual_kin_onames');
	$individual_kin_contact =set_value('individual_kin_contact');
	$individual_kin_address =set_value('individual_kin_address');
	$kin_relationship_id =set_value('kin_relationship_id');
	$job_title_id =set_value('job_title_id');
	$staff_id =set_value('staff_id');
}
?>
		<div class="row">
        	<div class="col-md-2">
            	<img src="<?php echo base_url().'assets/img/avatar.jpg';?>" class="img-responsive img-thumbnail" />
            </div>
            
            <div class="col-md-7">
            	<h2><?php echo $individual_fname.' '.$individual_onames;?></h2>
                <p>
                    <i class="fa fa-phone"/></i>
                    <span id="mobile_phone"><?php echo $individual_phone;?></span>
                    <i class="fa fa-envelope"/></i>
                    <span id="work_email"><?php echo $individual_email;?></span>
                </p>
            </div>
            
        	<div class="col-md-3">
            	<a href="<?php echo site_url();?>microfinance/individual" class="btn btn-info pull-right">Back to individual</a>
            </div>
        </div>
        
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"><?php echo $title;?></h2>
                </header>
                <div class="panel-body">
                
                	<?php
					$validation_error = validation_errors();
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
										<a class="text-center" data-toggle="tab" href="#account"><i class="fa fa-money"></i> Savings</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#emergency">Next of kin contacts</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#dependants">Dependants</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#job">Employer</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#history">History</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="general">
										<?php echo $this->load->view('edit/about', '', TRUE);?>
									</div>
									<div class="tab-pane" id="account">
										<?php echo $this->load->view('edit/account', '', TRUE);?>
									</div>
									<div class="tab-pane" id="emergency">
										<?php echo $this->load->view('edit/emergency', '', TRUE);?>
									</div>
									<div class="tab-pane" id="dependants">
										<?php echo $this->load->view('edit/dependants', '', TRUE);?>
									</div>
									<div class="tab-pane" id="job">
										<?php echo $this->load->view('edit/jobs', '', TRUE);?>
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