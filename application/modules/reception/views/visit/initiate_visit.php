		<style type="text/css">
			#insured_company{display:none;}
		</style>
		<section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title">Initiate Visit</h2>
            </header>
		        <!-- Widget content -->
		        <div class="panel-body">
                	<a href="<?php echo site_url();?>reception/patients-list" class="btn btn-primary btn-sm pull-right">  <i class="fa fa-angle-left"></i> Patients list</a>
                	
                	<div class="well well-sm info" style="margin-top:30px;">
                        <h5 style="margin:0;">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <strong>First name:</strong>
                                        </div>
                                        <div class="col-md-4">
                                            <?php echo $patient_surname;?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-5">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Other names:</strong>
                                        </div>
                                        <div class="col-md-6">
                                            <?php echo $patient_othernames;?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Balance:</strong>
                                        </div>
                                        <div class="col-md-8">
                                            Kes <?php echo number_format($account_balance, 2);?>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-1">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <a href="<?php echo site_url();?>administration/individual_statement/<?php echo $patient_id;?>/2" class="btn btn-sm btn-success pull-right" target="_blank" style="margin-top: 5px;">Statement</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </h5>
                    </div>
                    
					<?php 
                        $validation_error = validation_errors();
                        
                        if(!empty($validation_error))
                        {
                            echo '<div class="alert alert-danger center-align">'.$validation_error.'</div>';
                        }
						
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
                    ?>
                    
					<div class="tabs">
                        <ul class="nav nav-tabs nav-justified">
                            <li class="active">
                                <a class="text-center" data-toggle="tab" href="#outpatient">Outpatient</a>
                            </li>
                            <li>
                                <a class="text-center" data-toggle="tab" href="#inpatient">Inpatient</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="outpatient">
                                <h4 class="center-align" style="margin-bottom:10px;">Initiate outpatient visit</h4>
                                <?php $this->load->view('visit/initiate_outpatient');?>
                            </div>
                            <div class="tab-pane" id="inpatient">
                                <h4 class="center-align" style="margin-bottom:10px;">Initiate inpatient visit</h4>
                                <?php $this->load->view('visit/initiate_inpatient');?>
                            </div>
                        </div>
                    </div>
                    
                </div>
        	</section>