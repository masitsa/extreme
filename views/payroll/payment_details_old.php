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
                                <div class="row" style="margin-bottom:20px;">
                                    <div class="col-lg-12">
                                        <a href="<?php echo site_url();?>payroll/configuration" class="btn btn-info pull-right">Payroll configuration</a>
                                    </div>
                                </div>
								<form action="<?php echo site_url('accounts/save-payment-details/'.$personnel_id)?>" method="post">
                                    <div class="row">
                                        <div class="col-sm-4 col-md-4 sub_section_border">
                                            <div class='sub_section_title'>Basic Pay</div>
                                            <input type="text" class="form-control" name="basic_pay" value="<?php echo $basic_pay?>">
                                        </div><!-- End Basic Pay -->
                                        
                                        <div class="col-sm-4 col-md-4 sub_section_border">
                                            <div class='sub_section_title'>Allowances</div>
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            $total_allowance = 0;
                                            if($allowances->num_rows() > 0){
                            
                                                foreach ($allowances->result() as $row2)
                                                {
                                                    $allowance_id = $row2->allowance_id;
                                                    $allowance_name = $row2->allowance_name;
                                                    
                                                    if($personel_allowances->num_rows() > 0){
                                                        foreach($personel_allowances->result() as $allow){
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
                                                    $total_allowance += $amount;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $allowance_name;?></td>
                                                        <td><input type='text' class="form-control" name='allowance<?php echo $allowance_id;?>' value='<?php echo $amount;?>'></td>
                                                </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                                <tr>
                                                    <th align="right">Total</th>
                                                    <td><?php echo number_format($total_allowance, 2);?></td>
                                                </tr>
                                            </table>
                                        </div><!-- End Allowances -->
                                        
                                        <div class="col-sm-4 col-md-4 sub_section_border">
                                            <div class='sub_section_title'>Deductions</div>
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            $total_deduction = 0;
                                            $tax_deduction = 0;
                                            
                                            if($deductions->num_rows() > 0)
											{
                                                foreach ($deductions->result() as $row2)
                                                {
                                                    $deduction_id = $row2->deduction_id;
                                                    $deduction_name = $row2->deduction_name;
                                                    
                                                    if($personel_deductions->num_rows() > 0){
                                                        foreach($personel_deductions->result() as $deduction){
                                                            $id = $deduction->id;
                                                            
                                                            if($id == $deduction_id){
                                                                $amount = $deduction->amount;
                                                                if(($deduction_name == "NSSF") ||
                                                                    ($deduction_name == "NHIF") || 
                                                                    ($deduction_name == "Pension") || 
                                                                    ($deduction_name == "Insurance")){
                                            
                                                                    $tax_deduction += $amount;
                                                                }
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
                                                    $total_deduction += $amount;
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $deduction_name;?></td>
                                                        <td><input type='text' class="form-control" name='deduction<?php echo $deduction_id;?>' value='<?php echo $amount;?>'></td>
                                                    </tr>
                                                    <?php
                                                }
                                            }
                                
                                            if($basic_pay == 0){
                                                $tax = 0;
                                            }
                                            else{
                                                $gross_pay = $basic_pay + $total_allowance;
                                                $paye = $gross_pay - $tax_deduction;
                                                //echo $tax_deduction;
                                                $count = 1;
                                                $tax = 0;
                                                
                                                //echo $paye;
                                                while (($count < 6) && ($paye > 0)){ 
                            
                                                    if($count == 1){
                                                        $rate = 10164;
                                                        $tax = ($rate *0.10);
                                                        //echo "<br/>1 = ".$tax;
                                                        $paye = $paye - $rate;
                                                        $count ++;
                                                    }
                            
                                                    else if($count == 2){
                                                        $rate = 9576;
                                                        $tax = $tax + ($rate *0.15);
                                                        //echo "<br/>2 = ".$tax;
                                
                                                        $paye = $paye - $rate;
                                                        $count ++;
                                                    }
                            
                                                    else if($count == 3){
                                                        $rate = 9576;
                                                        $tax = $tax + ($rate *0.20);
                                                        //echo "<br/>3 = ".$tax;
                                
                                                        $paye = $paye - $rate;
                                                        $count ++;
                                                    }
                            
                                                    else if($count == 4){
                                                        $rate = 9576;
                                                        $tax = $tax + ($rate *0.25);
                                                        //echo "<br/>4 = ".$tax;
                                
                                                        $paye = $paye - $rate;
                                                        $count ++;
                                                    }
                            
                                                    else if($count == 5){
                                                        $tax = $tax + ($paye *0.30);
                                                        //echo "<br/>5 = ".$tax."<br/>";
                                
                                                        $count ++;
                                                    }
                                                }
                                                //echo $tax - 1162;
                        
                                                if($paye == 0){
                                                    $tax = 0;
                                                }
                                                else{
                                                    $tax = $tax - 1162;
                                                }
                                            }
                                            ?>
                                                <tr>
                                                    <td>PAYE</td>
                                                    <td><?php echo number_format($tax, 2);?></td>
                                                </tr>
                                                <tr>
                                                    <th>Total</th>
                                                    <td><?php echo number_format(($total_deduction + $tax), 2);?></td>
                                                </tr>
                                            </table>
                                        </div><!-- End Deductions -->
                                    </div><!-- End Row -->
                                    
                                    <div class="row">
                                        <div class="col-sm-2 col-md-2 col-md-offset-5">
                                                <input type="submit" name="submit" id="submit" value="Update" class="btn btn-primary" />
                                        </div>
                                    </div><!-- End Row -->
                            </form>
                            
                                    <div class="row">
                                        <form action="<?php echo site_url('accounts/update-savings/'.$personnel_id)?>" method="post">
                                        <div class="col-sm-6 col-md-6 sub_section_border">
                                            <div class='sub_section_title'>Savings</div>
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            if($savings->num_rows() > 0){
                                                
                                                foreach ($savings->result() as $row2)
                                                {
                                                    $savings_id = $row2->savings_id;
                                                    $savings_name = $row2->savings_name;
                                                    
                                                    if($savings_opening->num_rows() > 0){
                                                        
                                                        foreach($savings_opening->result() as $open){
                                                            $id = $open->savings_id;
                                                            
                                                            if($savings_id == $id){
                                                                $opening_amount = $open->savings_opening_amount;
                                                                break;
                                                            }
                                                            else{
                                                                $opening_amount = 0;
                                                            }
                                                        }
                                                    }
                                                    else{
                                                        $opening_amount = 0;
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $savings_name;?> Opening Total</td>
                                                        <td><input type='text' name='savings_opening<?php echo $savings_id;?>' value='<?php echo $opening_amount;?>' class="form-control"></td>
                                                    </tr>
                                                    <?php
                                                    
                                                    if($personel_savings->num_rows() > 0){
                                                        
                                                        foreach($personel_savings->result() as $open){
                                                            $id = $open->savings_id;
                                                            
                                                            if($savings_id == $id){
                                                                $amount = $open->amount;
                                                                break;
                                                            }
                                                            else{
                                                                $amount = 0;
                                                            }
                                                        }
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $savings_name;?></td>
                                                                <td><input type='text' name='savings<?php echo $savings_id;?>' value='<?php echo $amount;?>' class="form-control"></td>
                                                            </tr>
                                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                            </table>
                                    
                                    <div class="row">
                                        <div class="col-sm-2 col-md-2 col-md-offset-5">
                                                <input type="submit" name="submit" id="submit" value="Update" class="btn btn-primary" />
                                        </div>
                                    </div><!-- End Row -->
                                        </div> <!-- End Savings -->
                                    </form>
                                    <form action="<?php echo site_url('accounts/update-loan-schemes/'.$personnel_id)?>" method="post">
                                        <div class="col-sm-6 col-md-6 sub_section_border">
                                            <div class='sub_section_title'>Loan Schemes</div>
                                            <table class='table table-striped table-hover table-condensed'>
                                            <?php
                                            if($loan_schemes->num_rows() > 0){
                            
                                                foreach ($loan_schemes->result() as $row2)
                                                {
                                                    $loan_scheme_id = $row2->loan_scheme_id;
                                                    $loan_scheme_name = $row2->loan_scheme_name;
                                                    
                                                    if($personel_schemes->num_rows() > 0){
                                                        
                                                        foreach($personel_schemes->result() as $open){
                                                            $id = $open->id;
                                                            
                                                            if($loan_scheme_id == $id){
                                                                $amount = $open->amount;
                                                                $monthly = $open->monthly;
                                                                $interest = $open->interest;
                                                                $interest2 = $open->interest2;
                                                                $sdate = $open->sdate;
                                                                $edate = $open->edate;
                                                                
                                                                $today = date("y-m-d");
                
                                                                $prev = new Database2;
                                                                $prev_payments = $monthly * $prev->dateDiff($sdate.' 00:00', $today.' 00:00', 'month');
                                                                $prev = new Database2;
                                                                $prev_interest = $interest * $prev->dateDiff($sdate.' 00:00', $today.' 00:00', 'month');
                                                                break;
                                                            }
                                                            else{
                                                                $amount = 0;
                                                                $monthly = 0;
                                                                $interest = 0;
                                                                $interest2 = 0;
                                                                $sdate = "";
                                                                $edate = "";
                                                                $today = date("y-m-d");
                                                                $prev_payments = "";
                                                                $prev_interest = "";
                                                            }
                                                        }
                                                    }
                                                    
                                                    else{
                                                        $amount = 0;
                                                        $monthly = 0;
                                                        $interest = 0;
                                                        $interest2 = 0;
                                                        $sdate = "";
                                                        $edate = "";
                                                        $today = date("y-m-d");
                                                        $prev_payments = "";
                                                        $prev_interest = "";
                                                    }
                                                                
                                        
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
                                                    <td><input type='text' id='datepicker' name='datepicker".$loan_scheme_id."' size='15' autocomplete='off' placeholder='Start Date' class='form-control' value='".$sdate."' /></td>
                                                </tr>
                                                <tr>
                                                    <td>Repayment End Date(yyyy-mm-dd)</td>
                                                    <td><input type='text'' id='datepicker2' name='2datepicker".$loan_scheme_id."' size='15' autocomplete='off' placeholder='Finish Date' class='form-control'  value='".$edate."'/></td>
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
                                                    <td><input type='text' name='balance".$loan_scheme_id."' value='".($amount-$prev_payments)."' readonly class='form-control'></td>
                                                </tr>
                                                <tr>
                                                    <td>".$loan_scheme_name." - Interest bal</td>
                                                    <td><input type='text' value='".($interest2-$prev_interest)."' readonly='readonly' class='form-control'></td>
                                                </tr>
                                        ";
                                                }
                                            }
                                            ?>
                                            </table>
                                    
                                    <div class="row">
                                        <div class="col-sm-2 col-md-2 col-md-offset-5">
                                                <input type="submit" name="submit" id="submit" value="Update" class="btn btn-primary" />
                                        </div>
                                    </div><!-- End Row -->
                                        </div> <!-- End Loan Schemes -->
                                    </div>
                            	</form>
							</div>
                            <div class="panel-footer">
                            	
                            </div>
						</section>