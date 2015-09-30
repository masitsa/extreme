
		<div class="row">
        
          <section class="panel">

                <header class="panel-heading">
                	<div class="row">
	                	<div class="col-md-6">
		                    <h2 class="panel-title"><?php echo $title;?></h2>
		                </div>
		                <div class="col-md-6">
                        	<a href="<?php echo site_url();?>accounts/salary-data" class="btn btn-sm btn-info pull-right">Back to personnel</a>
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
                            <div class="row">
                                <div class="col-md-6">
                                </div>
                                <div class="col-md-6">
                                    <a href="<?php echo site_url();?>accounts/payroll/view-payslip/<?php echo $personnel_id;?>" class="btn btn-sm btn-warning pull-right" target="_blank">View payslip</a>
                                </div>
                            </div>
                        	
                            <div class="row">
                                <div class="col-md-4">
                                    <section class="panel panel-featured panel-featured-info">
                                        <header class="panel-heading">						
                                            <h2 class="panel-title">Payments</h2>
                                        </header>
                                        <div class="panel-body">
                                        	<form action="<?php echo site_url("accounts/payroll/edit-personnel-payments/".$personnel_id);?>" method="post">
                                            <table class='table table-striped table-hover table-condensed'>
                                                
                                            <?php
                                            
											$total_payments = 0;
											
                                            if($payments->num_rows() > 0)
                                            {
                                                foreach ($payments->result() as $row2)
                                                {
                                                    $payment_id = $row2->payment_id;
                                                    $payment_name = $row2->payment_name;
                                                    
                                                    if($personel_payments->num_rows() > 0){
                                                        foreach($personel_payments->result() as $allow){
                                                            $id = $allow->id;
                                                            
                                                            if($id == $payment_id){
                                                                $amount = $allow->amount;
                                                                break;
                                                            }
                                                    
                                                            else{
                                                                $amount = 0;
                                                            }
                                                        }
                                                    }
                                                    
                                                    else{
                                                        $amount = 0;
                                                    }
                                                    $total_payments += $amount;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $payment_name;?></td>
                                                        <td><input type='text' name='personnel_payment_amount<?php echo $payment_id;?>' value='<?php echo $amount;?>' class="form-control"></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                                <tr>
                                                    <th>TOTAL</th>
                                                    <th><?php echo number_format($total_payments, 2);?></th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><button class='btn btn-success' type='submit'><i class='fa fa-pencil'></i> Edit payments</button></td>
                                                </tr>
                                            </table>
                                            </form>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                                
                                <div class="col-md-4">
                                    <section class="panel panel-featured panel-featured-info">
                                        <header class="panel-heading">						
                                            <h2 class="panel-title">Non cash benefits</h2>
                                        </header>
                                        <div class="panel-body">
                                        	<form action="<?php echo site_url("accounts/payroll/edit-personnel-benefits/".$personnel_id);?>" method="post">
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            
											$total_benefits = 0;
											
                                            if($benefits->num_rows() > 0)
                                            {
                                                foreach ($benefits->result() as $row2)
                                                {
                                                   	$benefit_id = $row2->benefit_id;
													$benefit_name = $row2->benefit_name;
													$benefit_taxable = $row2->benefit_taxable;
                                                    
                                                    if($personnel_benefits->num_rows() > 0)
													{
                                                        foreach($personnel_benefits->result() as $allow)
														{
                                                            $id = $allow->id;
															
                                                            if($id == $benefit_id){
                                                                $amount = $allow->amount;
                                                                break;
                                                            }
                                                    
                                                            else{
                                                                $amount = 0;
                                                            }
                                                        }
                                                    }
                                                    
                                                    else{
                                                        $amount = 0;
                                                    }
													
													$class = '';
													if($benefit_taxable == 2)
													{
														$class = ' class="danger"';
													}
                                                    $total_benefits += $amount;
                                                    ?>
                                                    <tr <?php echo $class;?>>
                                                        <td><?php echo $benefit_name;?></td>
                                                        <td><input type='text' name='personnel_benefit_amount<?php echo $benefit_id;?>' value='<?php echo $amount;?>' class="form-control"></td>
                                                        
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                                <tr>
                                                    <th>TOTAL</th>
                                                    <th><?php echo number_format($total_benefits, 2);?></th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><button class='btn btn-success' type='submit'><i class='fa fa-pencil'></i> Edit non cash benefits</button></td>
                                                </tr>
                                            </table>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                                
                                <div class="col-md-4">
                                    <section class="panel panel-featured panel-featured-info">
                                        <header class="panel-heading">						
                                            <h2 class="panel-title">Cash benefits</h2>
                                        </header>
                                        <div class="panel-body">
                                        	<form action="<?php echo site_url("accounts/payroll/edit-personnel-allowances/".$personnel_id);?>" method="post">
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            
											$total_allowances = 0;
											
                                            if($allowances->num_rows() > 0)
                                            {
                                                foreach ($allowances->result() as $row2)
                                                {
                                                   	$allowance_id = $row2->allowance_id;
													$allowance_name = $row2->allowance_name;
													$allowance_taxable = $row2->allowance_taxable;
                                                    
                                                    if($personnel_allowances->num_rows() > 0){
                                                        foreach($personnel_allowances->result() as $allow){
                                                            $id = $allow->id;
                                                            
                                                            if($id == $allowance_id){
                                                                $amount = $allow->amount;
                                                                break;
                                                            }
                                                    
                                                            else{
                                                                $amount = 0;
                                                            }
                                                        }
                                                    }
                                                    
                                                    else{
                                                        $amount = 0;
                                                    }
													
													$class = '';
													if($allowance_taxable == 2)
													{
														$class = ' class="danger"';
													}
                                                    $total_allowances += $amount;
                                                    ?>
                                                    <tr <?php echo $class;?>>
                                                        <td><?php echo $allowance_name;?></td>
                                                        <td><input type='text' name='personnel_allowance_amount<?php echo $allowance_id;?>' value='<?php echo $amount;?>' class="form-control"></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                                <tr>
                                                    <th>TOTAL</th>
                                                    <th><?php echo number_format($total_allowances, 2);?></th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><button class='btn btn-success' type='submit'><i class='fa fa-pencil'></i> Edit cash benefits</button></td>
                                                </tr>
                                            </table>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                                
                            </div>
                            
                            <!-- Deductions -->
                            <div class="row">
                            	<div class="col-md-4">
                                    <section class="panel panel-featured panel-featured-info">
                                        <header class="panel-heading">						
                                            <h2 class="panel-title">Deductions</h2>
                                        </header>
                                        <div class="panel-body">
                                        	<form action="<?php echo site_url("accounts/payroll/edit-personnel-deductions/".$personnel_id);?>" method="post">
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            
											$total_deductions = 0;
											
                                            if($deductions->num_rows() > 0)
                                            {
                                                foreach ($deductions->result() as $row2)
                                                {
                                                   	$deduction_id = $row2->deduction_id;
													$deduction_name = $row2->deduction_name;
													$deduction_taxable = $row2->deduction_taxable;
                                                    
                                                    if($personnel_deductions->num_rows() > 0){
                                                        foreach($personnel_deductions->result() as $allow){
                                                            $id = $allow->id;
                                                            
                                                            if($id == $deduction_id){
                                                                $amount = $allow->amount;
                                                                break;
                                                            }
                                                    
                                                            else{
                                                                $amount = 0;
                                                            }
                                                        }
                                                    }
                                                    
                                                    else{
                                                        $amount = 0;
                                                    }
                                                    $total_deductions += $amount;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $deduction_name;?></td>
                                                        <td><input type='text' name='personnel_deduction_amount<?php echo $deduction_id;?>' value='<?php echo $amount;?>' class="form-control"></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                                <tr>
                                                    <th>TOTAL</th>
                                                    <th><?php echo number_format($total_deductions, 2);?></th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><button class='btn btn-success' type='submit'><i class='fa fa-pencil'></i> Edit deductions</button></td>
                                                </tr>
                                            </table>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-md-4">
                                    <section class="panel panel-featured panel-featured-info">
                                        <header class="panel-heading">						
                                            <h2 class="panel-title">Other deductions</h2>
                                        </header>
                                        <div class="panel-body">
                                        	<form action="<?php echo site_url("accounts/payroll/edit-personnel-other-deductions/".$personnel_id);?>" method="post">
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            
											$total_other_deductions = 0;
											
                                            if($other_deductions->num_rows() > 0)
                                            {
                                                foreach ($other_deductions->result() as $row2)
                                                {
                                                   	$other_deduction_id = $row2->other_deduction_id;
													$other_deduction_name = $row2->other_deduction_name;
													$other_deduction_taxable = $row2->other_deduction_taxable;
                                                    
                                                    if($personnel_other_deductions->num_rows() > 0){
                                                        foreach($personnel_other_deductions->result() as $allow){
                                                            $id = $allow->id;
                                                            
                                                            if($id == $other_deduction_id){
                                                                $amount = $allow->amount;
                                                                break;
                                                            }
                                                    
                                                            else{
                                                                $amount = 0;
                                                            }
                                                        }
                                                    }
                                                    
                                                    else{
                                                        $amount = 0;
                                                    }
                                                    $total_other_deductions += $amount;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $other_deduction_name;?></td>
                                                        <td><input type='text' name='personnel_other_deduction_amount<?php echo $other_deduction_id;?>' value='<?php echo $amount;?>' class="form-control"></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                                <tr>
                                                    <th>TOTAL</th>
                                                    <th><?php echo number_format($total_other_deductions, 2);?></th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><button class='btn btn-success' type='submit'><i class='fa fa-pencil'></i> Edit other deductions</button></td>
                                                </tr>
                                            </table>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                                
                                <div class="col-md-4">
                                    <section class="panel panel-featured panel-featured-info">
                                        <header class="panel-heading">						
                                            <h2 class="panel-title">Relief</h2>
                                        </header>
                                        <div class="panel-body">
                                        	<form action="<?php echo site_url("accounts/payroll/edit-personnel-relief/".$personnel_id);?>" method="post">
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            
											$total_relief = 0;
											
                                            if($relief->num_rows() > 0)
                                            {
                                                foreach ($relief->result() as $row2)
                                                {
													$relief_id = $row2->relief_id;
													$relief_name = $row2->relief_name;
													$relief_amount = $row2->relief_amount;
													$relief_type = $row2->relief_type;
													$relief_abbr = $row2->relief_abbr;
                                                    
                                                    if($personnel_relief->num_rows() > 0)
													{
                                                        foreach($personnel_relief->result() as $allow)
														{
                                                            $id = $allow->id;
                                                            
                                                            if($id == $relief_id){
                                                                $amount = $allow->amount;
                                                                break;
                                                            }
                                                    
                                                            else{
                                                                $amount = 0;
                                                            }
                                                        }
                                                    }
                                                    
                                                    else{
                                                        $amount = 0;
                                                    }
													
													if($relief_type == 1)
													{
                                                    	$total_relief += $relief_amount;
														?>
														<tr>
															<td><?php echo $relief_name;?></td>
															<td><input type='text' value='<?php echo $relief_amount;?>' class="form-control" readonly="readonly"></td>
														</tr>
														<?php
													}
													
													else
													{
                                                    	$total_relief += $amount;
														?>
                                                        <tr>
                                                            <td><?php echo $relief_abbr;?> amount</td>
                                                            <td><input type='text' name='personnel_relief_amount<?php echo $relief_id;?>' value='<?php echo $amount;?>' class="form-control"></td>
                                                        </tr>
                                                        <?php
													}
                                                }
                                            }
                                            ?>
                                                <tr>
                                                    <th>TOTAL</th>
                                                    <th><?php echo number_format($total_relief, 2);?></th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><button class='btn btn-success' type='submit'><i class='fa fa-pencil'></i> Edit relief</button></td>
                                                </tr>
                                            </table>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                            </div>
                            <!-- End deductions -->
                            
                            <!-- Loans & savings  -->
                            <div class="row">
                            	<div class="col-md-6">
                                    <section class="panel panel-featured panel-featured-info">
                                        <header class="panel-heading">						
                                            <h2 class="panel-title">Loan schemes</h2>
                                        </header>
                                        <div class="panel-body">
                                        	<form action="<?php echo site_url("accounts/payroll/edit-personnel-loan-schemes/".$personnel_id);?>" method="post">
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            
											$total_loan_schemes = 0;
											
                                            if($loan_schemes->num_rows() > 0)
                                            {
												$amount = 0;
												$monthly = 0;
												$interest = 0;
												$interest2 = 0;
												$sdate = '';
												$edate = '';
												$today = date("y-m-d");
												$prev_payments = "";
												$prev_interest = "";
												
                                                foreach ($loan_schemes->result() as $row2)
                                                {
                                                   	$loan_scheme_id = $row2->loan_scheme_id;
													$loan_scheme_name = $row2->loan_scheme_name;
                                                    
                                                    if($personnel_loan_schemes->num_rows() > 0)
													{
                                                        foreach($personnel_loan_schemes->result() as $open)
														{
                                                            $id = $open->id;
                                                            
                                                            if($id == $loan_scheme_id)
															{
                                                                $amount = $open->amount;
																$monthly = $open->monthly;
																$interest = $open->interest;
																$interest2 = $open->interest2;
																$sdate = $open->sdate;
																$edate = $open->edate;
																$prev_payments = $monthly * $this->payroll_model->dateDiff($sdate.' 00:00', $today.' 00:00', 'month');
																$prev_interest = $interest * $this->payroll_model->dateDiff($sdate.' 00:00', $today.' 00:00', 'month');
                                                                break;
                                                            }
                                                        }
                                                    }
                                                    $total_loan_schemes += $amount;
													
													echo"
													<tr>
														<td>".$loan_scheme_name." - Borrowings</td>
														<td><input type='text' name='borrowings".$loan_scheme_id."' value='".$amount."'  class='form-control'></td>
													</tr>
													<tr>
														<td>".$loan_scheme_name." - Interest</td>
					
														<td><input type='text' name='interest2".$loan_scheme_id."' value='".$interest2."'  class='form-control'></td>
													</tr>
													<tr>
														<td>Repayment Start Date (yyyy-mm-dd)</td>
														<td>
															<div class='input-group'>
																<span class='input-group-addon'>
																	<i class='fa fa-calendar'></i>
																</span>
																<input type='text' class='form-control' data-plugin-datepicker='' name='start_date".$loan_scheme_id."' value='".$sdate."' placeholder='Start date'>
															</div>
														</td>
													</tr>
													<tr>
														<td>Repayment End Date(yyyy-mm-dd)</td>
														<td>
															<div class='input-group'>
																<span class='input-group-addon'>
																	<i class='fa fa-calendar'></i>
																</span>
																<input type='text' class='form-control' data-plugin-datepicker='' name='end_date".$loan_scheme_id."' value='".$edate."' placeholder='Finish date'>
															</div>
														</td>
													</tr>
													<tr>
														<td>".$loan_scheme_name." - Monthly Payments</td>
														<td><input type='text' name='payments".$loan_scheme_id."' value='".$monthly."'  class='form-control'></td>
													</tr>
													<tr>
														<td>".$loan_scheme_name." - Monthly Interest</td>
														<td><input type='text' name='interest".$loan_scheme_id."' value='".$interest."'  class='form-control'></td>
													</tr>
													<tr>
														<td>".$loan_scheme_name." - Balance</td>
														<td><input type='text' name='balance".$loan_scheme_id."' value='".number_format(($amount-$prev_payments), 2)."' readonly class='form-control'></td>
													</tr>
													<tr>
														<td>".$loan_scheme_name." - Interest bal</td>
														<td><input type='text' value='".number_format(($interest2-$prev_interest), 2)."' readonly='readonly' class='form-control'></td>
													</tr>
											";
                                                }
                                            }
                                            ?>
                                                <tr>
                                                    <th>TOTAL</th>
                                                    <th><?php echo number_format($total_loan_schemes, 2);?></th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><button class='btn btn-success' type='submit'><i class='fa fa-pencil'></i> Edit loan schemes</button></td>
                                                </tr>
                                            </table>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                                <div class="col-md-6">
                                    <section class="panel panel-featured panel-featured-info">
                                        <header class="panel-heading">						
                                            <h2 class="panel-title">Savings</h2>
                                        </header>
                                        <div class="panel-body">
                                        	<form action="<?php echo site_url("accounts/payroll/edit-personnel-savings/".$personnel_id);?>" method="post">
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            
											$total_savings = 0;
											
                                            if($savings->num_rows() > 0)
                                            {
                                                foreach ($savings->result() as $row2)
                                                {
                                                   	$saving_id = $row2->savings_id;
													$saving_name = $row2->savings_name;
                                                    
                                                    if($personnel_savings->num_rows() > 0){
                                                        foreach($personnel_savings->result() as $allow){
                                                            $id = $allow->id;
                                                            
                                                            if($id == $saving_id){
                                                                $amount = $allow->amount;
																$opening = $allow->personnel_savings_opening;
                                                                break;
                                                            }
                                                    
                                                            else{
                                                                $amount = 0;
                                                                $opening = 0;
                                                            }
                                                        }
                                                    }
                                                    
                                                    else{
                                                        $amount = 0;
                                                        $opening = 0;
                                                    }
                                                    $total_savings += ($amount + $opening);
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $saving_name;?></td>
                                                        <td><input type='text' name='personnel_savings_amount<?php echo $saving_id;?>' value='<?php echo $amount;?>' class="form-control"></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td><?php echo $saving_name;?> Opening total</td>
                                                        <td><input type='text' name='personnel_savings_opening<?php echo $saving_id;?>' value='<?php echo $opening;?>' class="form-control"></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                                <tr>
                                                    <th>TOTAL</th>
                                                    <th><?php echo number_format($total_savings, 2);?></th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><button class='btn btn-success' type='submit'><i class='fa fa-pencil'></i> Edit savings</button></td>
                                                </tr>
                                            </table>
                                            </form>
                                        </div>
                                    </section>
                                </div>
                            </div>
                            <!-- End loans & savings -->
                            <div class="row">
                            	<div class="col-md-12">
                                    <section class="panel panel-featured panel-featured-info">
                                        <header class="panel-heading">						
                                            <h2 class="panel-title">Payment details</h2>
                                        </header>
                                        <div class="panel-body">
                                        	<form action="<?php echo site_url("accounts/payroll/edit-payment-details/".$personnel_id);?>" method="post">
                                            	<div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-lg-5 control-label">Account number: </label>
                                                            
                                                            <div class="col-lg-7">
                                                                <input type="text" class="form-control" name="personnel_account_number" placeholder="Account number" value="<?php echo $personnel_account_number;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-lg-5 control-label">NSSF number: </label>
                                                            
                                                            <div class="col-lg-7">
                                                                <input type="text" class="form-control" name="personnel_nssf_number" placeholder="NSSF number" value="<?php echo $personnel_nssf_number;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-lg-5 control-label">NHIF number: </label>
                                                            
                                                            <div class="col-lg-7">
                                                                <input type="text" class="form-control" name="personnel_nhif_number" placeholder="NHIF number" value="<?php echo $personnel_nhif_number;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="col-lg-5 control-label">KRA number: </label>
                                                            
                                                            <div class="col-lg-7">
                                                                <input type="text" class="form-control" name="personnel_kra_pin" placeholder="KRA number" value="<?php echo $personnel_kra_pin;?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-12 center-align">
                                                    	<button class='btn btn-success' type='submit'><i class='fa fa-pencil'></i> Edit payment details</button>
                                                    </div>
                                            	</div>
                                            </form>
                                        </div>
                                    </section>
                            	</div>
                            </div>
                            <!-- End payment details -->
                        </div>
                    </div>
                </div>
            </section>
        </div>