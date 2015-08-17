<?php
//individual data
$row = $individual->row();

$individual_onames = $row->individual_onames;
$individual_fname = $row->individual_fname;
$individual_email = $row->individual_email;
$individual_phone = $row->individual_phone;
?>
      	<div class="row">
        
          <section class="panel">

                <header class="panel-heading">
                	<div class="row">
	                	<div class="col-md-6">
		                    <h2 class="panel-title">Edit <?php echo $individual_fname.' '.$individual_onames;?></h2>
		                    <i class="fa fa-phone"/></i>
		                    <span id="mobile_phone"><?php echo $individual_phone;?></span>
		                    <i class="fa fa-envelope"/></i>
		                    <span id="work_email"><?php echo $individual_email;?></span>
		                </div>
		                <div class="col-md-6">
		                		<a href="<?php echo site_url();?>microfinance/individual" class="btn btn-sm btn-info pull-right">Back to individuals</a>
		                </div>
	                </div>
                </header>
                <div class="panel-body">
                
                	<?php
					$validation_errors = validation_errors();
					if(!empty($validation_errors))
					{
						echo '<div class="alert alert-danger"> '.$validation_errors.' </div>';
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
						echo '<div class="alert alert-danger"> '.$error.' </div>';
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
										<a class="text-center" data-toggle="tab" href="#emergency">Next of kin</a>
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