   
        <!-- Jasny -->
        <link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">		
        <script type="text/javascript" src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script> 
          <div class="padd">
            <?php
				$error2 = validation_errors(); 
				if(!empty($error2)){?>
					<div class="row">
						<div class="col-md-6 col-md-offset-2">
							<div class="alert alert-danger">
								<strong>Error!</strong> <?php echo validation_errors(); ?>
							</div>
						</div>
					</div>
				<?php }
				$attributes = array('role' => 'form', 'class' => 'form-horizontal');
		
				echo form_open($this->uri->uri_string(), $attributes);
				?>
                	
                <div class="row">
                	<div class="col-md-12">
                		<h2>Loan requirements</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="requirements"><?php echo $requirements;?></textarea>
                            </div>
                        </div>
                        
                		<h2>Loan conditions</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="conditions"><?php echo $conditions;?></textarea>
                            </div>
                        </div>
                        
                		<h2>Loan approval</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="approval"><?php echo $approval;?></textarea>
                            </div>
                        </div>
                        
                		<h2>Loan insurance fee</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="insurance_fee"><?php echo $insurance_fee;?></textarea>
                            </div>
                        </div>
                        
                		<h2>Loan application fee</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="application_fee"><?php echo $application_fee;?></textarea>
                            </div>
                        </div>
                        
                		<h2>Loan disbursement</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="disbursement"><?php echo $disbursement;?></textarea>
                            </div>
                        </div>
                        
                		<h2>Loan perfection</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="perfection"><?php echo $perfection;?></textarea>
                            </div>
                        </div>
                        
                		<h2>Loan negotiation</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="negotiation"><?php echo $negotiation;?></textarea>
                            </div>
                        </div>
                        
                		<h2>Acceptable collateral securities</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="acceptable_securities"><?php echo $acceptable_securities;?></textarea>
                            </div>
                        </div>
                        
                		<h2>Asset finance</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="asset_finance"><?php echo $asset_finance;?></textarea>
                            </div>
                        </div>
                        
                		<h2>Loan rescheduling</h2>
                        <div class="form-group">
                            <div class="col-md-12">
                                <textarea class="cleditor" name="rescheduling"><?php echo $rescheduling;?></textarea>
                            </div>
                        </div>
                        
					</div>
                </div>
				
				<div class="form-group center-align">
					<input type="submit" value="Edit loans" class="login_btn btn btn-success btn-lg">
				</div>
				<?php
					form_close();
				?>
		</div>