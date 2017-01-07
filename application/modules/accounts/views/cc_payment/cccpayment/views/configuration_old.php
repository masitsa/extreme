<?php
//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$job_title_id = set_value('job_title_id');
	$job_commencement_date = set_value('job_commencement_date');
}

else
{
	$job_title_id = '';
	$job_commencement_date = '';
}
?>
						<section class="panel">
							<header class="panel-heading">						
								<h2 class="panel-title"><?php echo $title;?></h2>
							</header>
							<div class="panel-body">
                            	<?php
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
								if(!empty($validation_errors))
								{
									echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
								}
								
								?>
                                
								<div class="row">
                        
        				<div class="col-sm-6 col-md-6 sub_section_border">
							<h3>Allowances</h3>
                            <table class='table table-striped table-hover table-condensed'>
                            	<form action="<?php echo site_url("accounts/payroll/new_allowance");?>" method="post">
                                <tr>
                    				<td><input type='text' name='allowance' class="form-control"></td>
                    				<td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add Allowance</button></td>
                    			</tr>
                                </form>
                            <?php
							
                			if($allowances->num_rows() > 0)
							{
								foreach ($allowances->result() as $row2)
								{
									$allowance_id = $row2->allowance_id;
									$allowance_name = $row2->allowance_name;
									?>
                            		<form action="<?php echo site_url("accounts/payroll/edit_allowance/".$allowance_id);?>" method="post">
                                    <tr>
                    					<td><input type='text' name='allowance<?php echo $allowance_id;?>' value='<?php echo $allowance_name;?>' class="form-control"></td>
                    					<td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                    					<td><a href="<?php echo site_url("accounts/payroll/delete_allowance/".$allowance_id);?>" onclick="return confirm('Do you want to delete <?php echo $allowance_name;?>?');" title="Delete <?php echo $allowance_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
                    				</tr>
                                    </form>
                                    <?php
								}
							}
							?>
                            </table>
                        </div><!-- End Allowances -->
                        
        				<div class="col-sm-6 col-md-6 sub_section_border">
							<h3>Deductions</h3>
                            <table class='table table-striped table-hover table-condensed'>
                            	<form action="<?php echo site_url("accounts/payroll/new_deduction");?>" method="post">
                                <tr>
                    				<td><input type='text' name='deduction' class="form-control"></td>
                    				<td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add Deduction</button></td>
                    			</tr>
                                </form>
                            <?php
							
                			if($deductions->num_rows() > 0)
							{

								foreach ($deductions->result() as $row2)
								{
									$deduction_id = $row2->deduction_id;
									$deduction_name = $row2->deduction_name;
									?>
                            		<form action="<?php echo site_url("accounts/payroll/edit_deduction/".$deduction_id);?>" method="post">
                                    <tr>
                    					<td><input type='text' name='deduction<?php echo $deduction_id;?>' value='<?php echo $deduction_name;?>' class="form-control"></td>
                    					<td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                    					<td><a href="<?php echo site_url("accounts/payroll/delete_deduction/".$deduction_id);?>" onclick="return confirm('Do you want to delete <?php echo $deduction_name;?>?');" title="Delete <?php echo $deduction_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
                    				</tr>
                                    </form>
                                    <?php
								}
							}
							?>
                            </table>
                        </div><!-- End Deductions -->
                        
                    </div><!-- End Row -->
            
        			<div class="row">
        				<div class="col-sm-6 col-md-6 sub_section_border">
							<h3>Savings</h3>
                            <table class='table table-striped table-hover table-condensed'>
                            	<form action="<?php echo site_url("accounts/payroll/new_saving");?>" method="post">
                                <tr>
                    				<td><input type='text' name='saving' class="form-control"></td>
                    				<td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add Saving</button></td>
                    			</tr>
                                </form>
                            <?php
							
                			if($savings->num_rows() > 0)
							{
								foreach ($savings->result() as $row2)
								{
									$saving_id = $row2->savings_id;
									$saving_name = $row2->savings_name;
									?>
                            		<form action="<?php echo site_url("accounts/payroll/edit_saving/".$saving_id);?>" method="post">
                                    <tr>
                    					<td><input type='text' name='saving<?php echo $saving_id;?>' value='<?php echo $saving_name;?>' class="form-control"></td>
                    					<td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                    					<td><a href="<?php echo site_url("accounts/payroll/delete_saving/".$saving_id);?>" onclick="return confirm('Do you want to delete <?php echo $saving_name;?>?');" title="Delete <?php echo $saving_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
                    				</tr>
                                    </form>
                                    <?php
								}
							}
							?>
                            </table>
                        </div> <!-- End Savings -->
                        
        				<div class="col-sm-6 col-md-6 sub_section_border">
							<h3>Loan Schemes</h3>
                            <table class='table table-striped table-hover table-condensed'>
                            	<form action="<?php echo site_url("accounts/payroll/new_loan_scheme");?>" method="post">
                                <tr>
                    				<td><input type='text' name='loan_scheme' class="form-control"></td>
                    				<td colspan="2"><button class='btn btn-info btn-sm' type='submit'>Add Loan Scheme</button></td>
                    			</tr>
                                </form>
                            <?php
							
                			if($loan_schemes->num_rows() > 0)
							{
								foreach ($loan_schemes->result() as $row2)
								{
									$loan_scheme_id = $row2->loan_scheme_id;
									$loan_scheme_name = $row2->loan_scheme_name;
									?>
                            		<form action="<?php echo site_url("accounts/payroll/edit_loan_scheme/".$loan_scheme_id);?>" method="post">
                                    <tr>
                    					<td><input type='text' name='loan_scheme<?php echo $loan_scheme_id;?>' value='<?php echo $loan_scheme_name;?>' class="form-control"></td>
                    					<td><button class='btn btn-success btn-xs' type='submit'><i class='fa fa-pencil'></i> Edit</button></td>
                    					<td><a href="<?php echo site_url("accounts/payroll/delete_loan_scheme/".$loan_scheme_id);?>" onclick="return confirm('Do you want to delete <?php echo $loan_scheme_name;?>?');" title="Delete <?php echo $loan_scheme_name;?>"><button class='btn btn-danger btn-xs' type='button'><i class='fa fa-trash'></i> Delete</button></a></td>
                    				</tr>
                                    </form>
                                    <?php
								}
							}
							?>
                            </table>
                        </div> <!-- End Loan Schemes -->
                   	</div>
            </form>
        <!-- End Monthly Details -->
        
    </div>
							</div>
                            <div class="panel-footer">
                            	
                            </div>
						</section>