
      	<div class="row">
        
          <section class="panel">

                <header class="panel-heading">
                	<div class="row">
	                	<div class="col-md-6">
		                    <h2 class="panel-title"><?php echo $title;?></h2>
		                </div>
		                <div class="col-md-6">
		                		<a href="<?php echo site_url();?>accounts/payroll" class="btn btn-sm btn-info pull-right">Back to payroll</a>
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
                    
								$validation_errors = validation_errors();
								
								if(!empty($validation_errors))
								{
									echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
								}
								
							?>
                        	<div class="tabs">
								<ul class="nav nav-tabs nav-justified">
									<li class="active">
										<a class="text-center" data-toggle="tab" href="#general">General</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#allowances">Allowances</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#deductions">Deductions</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="general">
										<?php echo $this->load->view('payroll/configuration/general', '', TRUE);?>
									</div>
									<div class="tab-pane" id="allowances">
										<?php echo $this->load->view('payroll/configuration/allowances', '', TRUE);?>
									</div>
									<div class="tab-pane" id="deductions">
										<?php echo $this->load->view('payroll/configuration/deductions', '', TRUE);?>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </section>
        </div>