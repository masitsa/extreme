
<?php
//$personnel_id = $this->session->userdata('personnel_id');
$created_by = $this->session->userdata('first_name');

?>

<!DOCTYPE html>
<html lang="en">
	<style type="text/css">
		.receipt_spacing{letter-spacing:0px; font-size: 14px;}
		.center-align{margin:0 auto; text-align:center;}
		
		.receipt_bottom_border{border-bottom: #888888 medium solid;}
		.row .col-md-12 table {
			border:solid #000 !important;
			border-width:0px 0 0 1px !important;
			font-size:12px;
		}
		.row .col-md-12 th, .row .col-md-12 td {
			border:solid #000 !important;
			border-width:0 1px 1px 0 !important;
		}
		.row .col-md-12 .title-item{float:left;width: 130px; font-weight:bold; text-align:right; padding-right: 20px;}
		.title-img{float:left; padding-left:30px;}
		img.logo{max-height:70px; margin:0 auto;}
		.col-xs-6{text-align:right;}
		.col-xs-6#align-left{text-align:left !important; padding-left: 0px !important;}
		.row {
    margin-left: 5px !important;
    margin-right: 5px !important;
}
		
	</style>
    <head>
        <title>Payslip for <?php echo $personnel_name;?></title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css">
    </head>
    <body class="receipt_spacing">
        <div class="row">
			<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
				<div class="row">
					<strong>
						INSIGHT MANAGEMENT CONSULTANTS LTD<br/>
					</strong>
					<h4><?php echo strtoupper($personnel_name) ?></br></h4>
					<h5>STAFF NUMBER. <?php echo  $personnel_number ?></br>
					NSSF NO.<?php  echo $nssf_number ?></br>
					NHIF NO. <?php echo $nhif_number?></br>
					KRA PIN: <?php echo  $kra_pin?></br></h5>
				</div>
                <div class="row">
                        <strong>EARNINGS</strong>
                </div>
                <div class="row">
                    <?php 
                    /*********** Payments ***********/
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
                                    
                                    if($id == $payment_id)
                                    {
                                        $amount = $allow->amount;
										if($amount != 0)
										{
											?>
											<div class="col-xs-6" id="align-left">
												<?php echo  strtoupper($payment_name);?>
											</div>
											
											<div class="col-xs-6">
												<?php echo number_format($amount, 2);?>
											</div>
											<?php
											break;
										}
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
                        }
                    }
        
                    ?>
                </div>
                
                <!-- Allowances header -->
                <div class="row">
                        <strong>NON CASH BENEFITS</strong>
                </div>
                <!-- End allowances header -->
                
                <!-- Allowances -->
                <div class="row">
                    <?php
                    
                    /*********** Non cash benefits ***********/
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
										if($amount > 0)
										{
											?>
											<div class="col-xs-6" id="align-left">
												<?php echo strtoupper($benefit_name);?>
											</div>
											
											<div class="col-xs-6">
												<?php echo number_format($amount, 2);?>
											</div>
											<?php
											break;
										}
                                    }
                            
                                    else{
                                        $amount = 0;
                                    }
                                }
                            }
                            
                            else{
                                $amount = 0;
                            }
							
							if($benefit_taxable == 1)
							{
                            	$total_benefits += $amount;
							}
                        }
                    }
                    ?>
                </div>
                
                <!-- Allowances header -->
                <div class="row">
                        <strong>ALLOWANCES</strong>
                </div>
                <!-- End allowances header -->
                
                <!-- Allowances -->
                <div class="row">
                    <?php
                    
                    /*********** Allowances ***********/
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
										if($amount > 0)
										{
											?>
											<div class="col-xs-6" id="align-left">
												<?php echo strtoupper($allowance_name);?>
											</div>
											
											<div class="col-xs-6">
												<?php echo number_format($amount, 2);?>
											</div>
											<?php
											break;
										}
                                    }
                            
                                    else{
                                        $amount = 0;
                                    }
                                }
                            }
                            
                            else{
                                $amount = 0;
                            }
							
							if($allowance_taxable == 1)
							{
                            	$total_allowances += $amount;
							}
                        }
                    }
					
                    /*********** Taxable ***********/
					$gross = ($total_payments + $total_allowances);
					$gross_taxable = $gross + $total_benefits;
					$nssf = 0;
					$taxable = 0;
					$paye = 0;
					$paye_less_relief = 0;
					$monthly_relief = 0;
					$insurance_relief = 0;
					$insurance_amount = 0;
						
					/*********** NSSF ***********/
					$nssf_query = $this->payroll_model->get_nssf();
					
					if($nssf_query->num_rows() > 0)
					{
						foreach ($nssf_query->result() as $row2)
						{
							$nssf_id = $row2->nssf_id;
							$nssf = $row2->amount;
							$nssf_percentage = $row2->percentage;
					
							if($nssf_percentage == 1)
							{
								$nssf_deduction_amount = $gross_taxable;
								
								if($nssf_deduction_amount > 18000)
								{
									$nssf_deduction_amount = 18000;
								}
								$nssf = $nssf_deduction_amount * ($nssf/100);
							}
						}
					}
					$taxable = $gross_taxable - $nssf;
					
					if($taxable > 10164)
					{
						$monthly_relief = $this->payroll_model->get_monthly_relief();
						$insurance_res = $this->payroll_model->get_insurance_relief($personnel_id);
						$insurance_relief = $insurance_res['relief'];
						$insurance_amount = $insurance_res['amount'];
						
						/*********** PAYE ***********/
						$paye = $this->payroll_model->calculate_paye($taxable);
						$paye_less_relief = $paye - $monthly_relief - $insurance_relief;
						
						if($paye_less_relief < 0)
						{
							$paye_less_relief = 0;
						}
					}
                    ?>
                </div>
                
                <!-- Taxable -->
                <div class="row">
                	<div class="col-xs-6" id="align-left">
                        <strong>TOTAL EARNINGS</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($gross_taxable), 2);?>
                    </div>
                </div>
				
				<div class="row">
                	 <strong>P.A.Y.E </strong>
                </div>
				
                <!-- End taxable -->
                <div class="row">
                	<div class="col-xs-6" id="align-left">
                       LIABLE PAY
                    </div>
                    
                    <div class="col-xs-6">
                       <?php echo number_format(($gross_taxable), 2);?>
                    </div>
                </div>
                <!-- NSSF -->
                <div class="row">
                	<div class="col-xs-6" id="align-left">
                        LESS PENSIONS/NSSF
                    </div>
                    
                    <div class="col-xs-6">
                       (<?php echo number_format(($nssf), 2);?>)
                    </div>
                </div>
				
                <!-- End NSSF -->
                <div class="row">
                	<div class="col-xs-6" id="align-left">
                        CHARGEABLE AMT KSHS
                    </div>
                    
                    <div class="col-xs-6">
                       <?php echo number_format(($taxable), 2);?>
                    </div>
                </div>
                
                 <div class="row">
                	<div class="col-xs-6" id="align-left">
                        TAX CHARGED
                    </div>
                    
                    <div class="col-xs-6">
                       <?php echo number_format(($paye), 2);?>
                    </div>
                </div>
				 <div class="row">
                	<div class="col-xs-6" id="align-left">
                        PERSONAL RELIEF
                    </div>
                    
                    <div class="col-xs-6">
                       <?php echo number_format(($monthly_relief), 2);?>
                    </div>
                </div>
                <!-- End allowances-->
                <!-- Insurance relief -->
                <?php if($insurance_relief > 0){?>
                <div class="row">
                	<div class="col-xs-6" id="align-left">
                        INSURANCE RELIEF
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($insurance_relief), 2);?>
                    </div>
                </div>
                <?php }?>
                <!-- End insurance relief -->
                <div class="row">
                	 <strong>DEDUCTIONS </strong>
                </div>
                <!-- PAYE -->
                <div class="row">
                	<div class="col-xs-6" id="align-left">
                       PAYE
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($paye_less_relief), 2);?>
                    </div>
                </div>
                <!-- End PAYE -->
                
                <!-- Life insurance -->
                <?php if($insurance_amount > 0){?>
                <div class="row">
                	<div class="col-xs-6" id="align-left">
                        LIFE INSURANCE
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($insurance_amount), 2);?>
                    </div>
                </div>
                <?php }?>
                <!-- End life insurance -->
                
                <!-- Deductions -->
                <div class="row">
                    <div class="col-xs-6" id="align-left">
                        NSSF
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format($nssf, 2);?>
                    </div>
                    <?php
                    
                    /*********** NHIF ***********/
					$nhif_query = $this->payroll_model->calculate_nhif($gross);
					$nhif = 0;
					
					if($nhif_query->num_rows() > 0)
					{
						foreach ($nhif_query->result() as $row2)
						{
							$nhif = $row2->amount;
						}
					}
					?>
                    <div class="col-xs-6" id="align-left">
                        NHIF
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format($nhif, 2);?>
                    </div>
                    <?php
					
                    /*********** Deductions ***********/
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
										if($amount > 0)
										{
											?>
											<div class="col-xs-6" id="align-left">
												<?php echo strtoupper($deduction_name);?>
											</div>
											
											<div class="col-xs-6">
												<?php echo number_format($amount, 2);?>
											</div>
											<?php
											break;
										}
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
						}
					}
					
                    /*********** Other deductions ***********/
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
										if($amount > 0)
										{
											?>
											<div class="col-xs-6" id="align-left">
												<?php echo strtoupper($other_deduction_name);?>
											</div>
											
											<div class="col-xs-6">
												<?php echo number_format($amount, 2);?>
											</div>
											<?php
											break;
										}
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
						}
					} ?>
                </div>
                <!-- End deductions -->
                
                <!-- Savings header -->
                <div class="row">
                	<strong>SAVINGS </strong>
                </div>
                <!-- End savings header -->
                
                <!-- Savings -->
                <div class="row">
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
										?>
                                        <div class="col-xs-6" id="align-left">
                                        	<?php echo $saving_name;?>
                                        </div>
                                        
                                        <div class="col-xs-6">
                                            <?php echo number_format($amount, 2);?>
                                        </div>
                                        <?php
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
							$total_savings += ($amount);
						}
					}
                    ?>
                </div>
                <!-- End savings -->
                
                <!-- Loans header -->
                <div class="row">
                    <strong> LOANS </strong>
                </div>
                <!-- End loans header -->
                
                <!-- Loans -->
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
									
									if($amount > 0)
									{
										$monthly = $open->monthly;
										$interest = $open->interest;
										$interest2 = $open->interest2;
										$sdate = $open->sdate;
										$edate = $open->edate;
										$prev_payments = $monthly * $this->payroll_model->dateDiff($sdate.' 00:00', $today.' 00:00', 'month');
										$prev_interest = $interest * $this->payroll_model->dateDiff($sdate.' 00:00', $today.' 00:00', 'month');
										$loan_balance = ($amount-$prev_payments);
										$interest_balance = ($interest2-$prev_interest);
										?>
										<div class="row">
											<div class="col-xs-6" id="align-left">
												<?php echo $loan_scheme_name;?>
											</div>
											
											<div class="col-xs-6" style="text-align:left">
												<?php echo number_format($amount, 2);?>
											</div>
										</div>
										
										<div class="row">
											<div class="col-xs-6" id="align-left">
												<?php echo $loan_scheme_name;?> Int
											</div>
											
											<div class="col-xs-6" style="text-align:left">
												<?php echo number_format($interest2, 2);?>
											</div>
										</div>
										
										<div class="row">
											<div class="col-xs-6" id="align-left">
												<?php echo $loan_scheme_name;?> PM
											</div>
											
											<div class="col-xs-6">
												<?php echo number_format($monthly, 2);?>
											</div>
										</div>
										
										<div class="row">
											<div class="col-xs-6" id="align-left">
												<?php echo $loan_scheme_name;?> Int PM
											</div>
											
											<div class="col-xs-6" style="text-align:left">
												<?php echo number_format($interest, 2);?>
											</div>
										</div>
										
										<div class="row">
											<div class="col-xs-6" id="align-left">
												<?php echo $loan_scheme_name;?> Bal
											</div>
											
											<div class="col-xs-6" style="text-align:left">
												<?php echo number_format($loan_balance, 2);?>
											</div>
										</div>
										
										<div class="row">
											<div class="col-xs-6" id="align-left">
												<?php echo $loan_scheme_name;?> Int Bal
											</div>
											
											<div class="col-xs-6" style="text-align:left">
												<?php echo number_format($interest_balance, 2);?>
											</div>
										</div>
										<?php
										break;
									}
                                }
                            }
                        }
                        $total_loan_schemes += $monthly;
                    }
                }
				
				//$all_deductions = $paye_less_relief + $nssf + $nhif + $total_deductions + $total_other_deductions + $total_loan_schemes + $total_savings + $insurance_amount;
				$all_deductions = $paye_less_relief + $nssf + $nhif + $total_deductions + $total_other_deductions + $total_loan_schemes + $total_savings;
				
				$net_pay = $gross - $all_deductions;
                ?>
                <!-- End loans -->
               
                <!-- End Gross -->
                
                <!-- Total deductions -->
                <div class="row">
                	<div class="col-xs-6" id="align-left">
                        <strong>TOTAL DEDUCTIONS</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($all_deductions), 2);?>
                    </div>
                </div>
                <!-- End Total deductions -->
								
                <!-- Net pay -->		
                <div class="row">
                    <div class="col-xs-6" id="align-left">
                        <strong>NET PAY</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php 
						if($net_pay < 0)
						{	
							$net_pay = 0;
						}echo number_format($net_pay, 2);?>
                    </div>
                </div>
                <!-- End Net -->
               
            </div>
        </div>
        
    </body>
</html>
