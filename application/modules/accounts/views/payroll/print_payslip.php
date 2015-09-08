
<?php
//$personnel_id = $this->session->userdata('personnel_id');
$created_by = $this->session->userdata('first_name');

?>

<!DOCTYPE html>
<html lang="en">
	<style type="text/css">
		.receipt_spacing{letter-spacing:0px; font-size: 12px;}
		.center-align{margin:0 auto; text-align:center;}
		
		.receipt_bottom_border{border-bottom: #888888 medium solid;}
		.row .col-md-12 table {
			border:solid #000 !important;
			border-width:1px 0 0 1px !important;
			font-size:10px;
		}
		.row .col-md-12 th, .row .col-md-12 td {
			border:solid #000 !important;
			border-width:0 1px 1px 0 !important;
		}
		
		.row .col-md-12 .title-item{float:left;width: 130px; font-weight:bold; text-align:right; padding-right: 20px;}
		.title-img{float:left; padding-left:30px;}
		
		.col-xs-6{text-align:right;}
		img.logo{max-height:70px; margin:0 auto;}
	</style>
    <head>
        <title>Payslip for <?php echo $personnel_name;?></title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" media="all"/>
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css" media="all"/>
    </head>
    <body class="receipt_spacing">
    	<div class="row">
        	<div class="col-xs-12 center-align">
            	<img src="<?php echo base_url().'assets/logo/'.$contacts['logo'];?>" alt="<?php echo $contacts['company_name'];?>" class="img-responsive logo"/>
            </div>
        </div>
        
        <div class="row">
        	<?php if(isset($download)){?>
        	<div class="col-xs-12">
            <?php } else{?>
        	<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
            <?php }?>
                <div class="row">
                    <div class="col-xs-12">
                    	<div class="center-align">
                        	<h5>Payslip for <?php echo $personnel_name;?> <?php echo $personnel_number;?> <?php echo $date;?></h5>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <?php 
                    /*********** Payments ***********/
					$total_payments = 0; 
					$table = $this->payroll_model->get_table_id("payment");  
					                   
                    if($payments->num_rows() > 0)
                    {
                        foreach ($payments->result() as $row2)
                        {
                            $payment_id = $row2->payment_id;
                            $payment_name = $row2->payment_name;
                            
							$table_id = $payment_id;
							
							$amount = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
							
                           ?>
                            <div class="col-xs-6">
                                <strong><?php echo $payment_name;?></strong>
                            </div>
                            
                            <div class="col-xs-6">
                                <?php echo number_format($amount, 2);?>
                            </div>
                            <?php
                            $total_payments += $amount;
                        }
                    }
        
                    ?>
                </div>
                
                <!-- Allowances header -->
                <div class="row">
                	<div class="col-xs-12">
                        Non cash benefits
                    </div>
                </div>
                <!-- End allowances header -->
                
                <!-- Allowances -->
                <div class="row">
                    <?php
                    
                    /*********** Non cash benefits ***********/
                    $total_benefits = 0;  
					$table = $this->payroll_model->get_table_id("benefit");
							                
                    if($benefits->num_rows() > 0)
                    {
                        foreach ($benefits->result() as $row2)
                        {
                            $benefit_id = $row2->benefit_id;
                            $benefit_name = $row2->benefit_name;
                            $benefit_taxable = $row2->benefit_taxable;
                            
							$table_id = $benefit_id;
							
							$amount = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
							
                            ?>
                            <div class="col-xs-6">
                                <strong><?php echo $benefit_name;?></strong>
                            </div>
                            
                            <div class="col-xs-6">
                                <?php echo number_format($amount, 2);?>
                            </div>
                            <?php
							
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
                	<div class="col-xs-12">
                        Allowances
                    </div>
                </div>
                <!-- End allowances header -->
                
                <!-- Allowances -->
                <div class="row">
                    <?php
                    
                    /*********** Allowances ***********/
                    $total_allowances = 0;
					$table = $this->payroll_model->get_table_id("allowance");
                                                                
                    if($allowances->num_rows() > 0)
                    {
                        foreach ($allowances->result() as $row2)
                        {
                            $allowance_id = $row2->allowance_id;
                            $allowance_name = $row2->allowance_name;
                            $allowance_taxable = $row2->allowance_taxable;
                            
							$table_id = $allowance_id;
							
							$amount = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
							
                             ?>
                            <div class="col-xs-6">
                                <strong><?php echo $allowance_name;?></strong>
                            </div>
                            
                            <div class="col-xs-6">
                                <?php echo number_format($amount, 2);?>
                            </div>
                            <?php
							
							if($allowance_taxable == 1)
							{
                            	$total_allowances += $amount;
							}
                        }
                    }
		
                    /*********** NSSF ***********/
					$table = $this->payroll_model->get_table_id("nssf");
					$nssf = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
					
                    /*********** NHIF ***********/
					$table = $this->payroll_model->get_table_id("nhif");
					$nhif = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
					
                    /*********** PAYE ***********/
					$table = $this->payroll_model->get_table_id("paye");
					$paye =$this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
					
					//relief
					$table = $this->payroll_model->get_table_id("relief");
					$monthly_relief = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
					
					//insurance_relief
					$table = $this->payroll_model->get_table_id("insurance_relief");
					$insurance_relief = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
					
					//insurance_amount
					$table = $this->payroll_model->get_table_id("insurance_amount");
					$insurance_amount = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
					
                    /*********** Taxable ***********/
					$gross = ($total_payments + $total_allowances);
        			$gross_taxable = $gross + $total_benefits;
					$taxable = $gross_taxable - $nssf;
					
					$paye_less_relief = $paye - $monthly_relief - $insurance_relief;
                    ?>
                </div>
                
                <!-- Taxable -->
                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
                	<div class="col-xs-6">
                        <strong>Gross taxable amount</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($gross_taxable), 2);?>
                    </div>
                </div>
                <!-- End taxable -->
                
                <!-- NSSF -->
                <div class="row">
                	<div class="col-xs-6">
                        <strong>NSSF</strong>
                    </div>
                    
                    <div class="col-xs-6">
                       (<?php echo number_format(($nssf), 2);?>)
                    </div>
                </div>
                <!-- End NSSF -->
                
                <!-- Taxable -->
                <div class="row">
                	<div class="col-xs-6">
                        <strong>Taxable amount</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($taxable), 2);?>
                    </div>
                </div>
                <!-- End taxable -->
                
                <!-- End allowances-->
                
                <!-- Tax calculation header -->
                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
                	<div class="col-xs-12">
                        Tax calculation
                    </div>
                </div>
                <!-- End tax calculation header -->
                
                <!-- Normal tax -->
                <div class="row">
                	<div class="col-xs-6">
                        <strong>Normal tax</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($paye), 2);?>
                    </div>
                </div>
                <!-- End normal tax -->
                
                <!-- Monthly relief -->
                <div class="row">
                	<div class="col-xs-6">
                        <strong>Monthly relief</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($monthly_relief), 2);?>
                    </div>
                </div>
                <!-- End monthly relief -->
                
                <!-- Insurance relief -->
                <?php if($insurance_relief > 0){?>
                <div class="row">
                	<div class="col-xs-6">
                        <strong>Insurance relief</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($insurance_relief), 2);?>
                    </div>
                </div>
                <?php }?>
                <!-- End insurance relief -->
                
                <!-- PAYE -->
                <div class="row">
                	<div class="col-xs-6">
                        <strong>PAYE</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($paye_less_relief), 2);?>
                    </div>
                </div>
                <!-- End PAYE -->
                
                <!-- Deductions header -->
                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
                	<div class="col-xs-12">
                        Other deductions
                    </div>
                </div>
                <!-- End deductions header -->
                
                <!-- Life insurance -->
                <?php if($insurance_amount > 0){?>
                <div class="row">
                	<div class="col-xs-6">
                        <strong>Life insurance</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($insurance_amount), 2);?>
                    </div>
                </div>
                <?php }?>
                <!-- End life insurance -->
                
                <!-- Deductions -->
                <div class="row">
                    <div class="col-xs-6">
                        <strong>NSSF</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format($nssf, 2);?>
                    </div>
                    
                    <div class="col-xs-6">
                        <strong>NHIF</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format($nhif, 2);?>
                    </div>
                    <?php
					
                    /*********** Deductions ***********/
					$total_deductions = 0;
					$table = $this->payroll_model->get_table_id("deduction");
											
					if($deductions->num_rows() > 0)
					{
						foreach ($deductions->result() as $row2)
						{
							$deduction_id = $row2->deduction_id;
							$deduction_name = $row2->deduction_name;
							$deduction_taxable = $row2->deduction_taxable;
						
							$table_id = $deduction_id;
							$amount = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
							
							?>
                            <div class="col-xs-6">
                                <strong><?php echo $deduction_name;?></strong>
                            </div>
                            
                            <div class="col-xs-6">
                                <?php echo number_format($amount, 2);?>
                            </div>
                            <?php
							$total_deductions += $amount;
						}
					}
					
                    /*********** Other deductions ***********/
					$total_other_deductions = 0;
					$table = $this->payroll_model->get_table_id("other_deduction");
											
					if($other_deductions->num_rows() > 0)
					{
						foreach ($other_deductions->result() as $row2)
						{
							$other_deduction_id = $row2->other_deduction_id;
							$other_deduction_name = $row2->other_deduction_name;
							$other_deduction_taxable = $row2->other_deduction_taxable;
							$table_id = $other_deduction_id;
							$amount = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
							
							$total_other_deductions += $amount;
						}
					}
					
					if($total_other_deductions > 0)
					{
                    ?>
                    <div class="col-xs-6">
                        <strong>Other advances</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format($total_other_deductions, 2);?>
                    </div>
                    <?php } ?>
                </div>
                <!-- End deductions -->
                
                <!-- Savings header -->
                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
                	<div class="col-xs-12">
                        Savings
                    </div>
                </div>
                <!-- End savings header -->
                
                <!-- Savings -->
                <div class="row">
                    <?php
                    
                    $total_savings = 0;
					$table = $this->payroll_model->get_table_id("savings");
											
					if($savings->num_rows() > 0)
					{
						foreach ($savings->result() as $row2)
						{
							$saving_id = $row2->savings_id;
							$saving_name = $row2->savings_name;
							$amount += $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $saving_id);
							
							?>
                            <div class="col-xs-6">
                                <strong><?php echo $saving_name;?></strong>
                            </div>
                            
                            <div class="col-xs-6">
                                <?php echo number_format($amount, 2);?>
                            </div>
                            <?php
							$total_savings += ($amount);
						}
					}
                    ?>
                </div>
                <!-- End savings -->
                
                <!-- Loans header -->
                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
                	<div class="col-xs-12">
                        Loans
                    </div>
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
					$table = $this->payroll_model->get_table_id("loan_scheme");
                    
                    foreach ($loan_schemes->result() as $row2)
                    {
                        $loan_scheme_id = $row2->loan_scheme_id;
                        $loan_scheme_name = $row2->loan_scheme_name;
                        $amount += $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $loan_scheme_id);
						?>
                        <div class="row">
                            <div class="col-xs-6">
                                <strong><?php echo $loan_scheme_name;?></strong>
                            </div>
                            
                            <div class="col-xs-6" style="text-align:left">
                                <?php echo number_format($amount, 2);?>
                            </div>
                        </div>
						<?php
                        $total_loan_schemes += $amount;
                    }
                }
				
				$all_deductions = $paye_less_relief + $nssf + $nhif + $total_deductions + $total_other_deductions + $total_loan_schemes + $total_savings + $insurance_amount;
				
				$net_pay = $gross - $all_deductions;
                ?>
                <!-- End loans -->
                
                <!-- Gross pay -->
                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
                	<div class="col-xs-6">
                        <strong>Gross pay</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format(($gross), 2);?>
                    </div>
                </div>
                <!-- End Gross -->
                
                <!-- Total deductions -->
                <div class="row">
                	<div class="col-xs-6">
                        <strong>Total deductions</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        (<?php echo number_format(($all_deductions), 2);?>)
                    </div>
                </div>
                <!-- End Total deductions -->
								
                <!-- Net pay -->		
                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-top:none;">
                    <div class="col-xs-6">
                        <strong>Net pay</strong>
                    </div>
                    
                    <div class="col-xs-6">
                        <?php echo number_format($net_pay, 2);?>
                    </div>
                </div>
                <!-- End Net -->
                
                <div class="row receipt_bottom_border" >
                    <div class="col-md-12 center-align">
                        <?php echo 'Prepared By:	'.$created_by.' '.date('jS M Y H:i:s',strtotime(date('Y-m-d H:i:s')));?>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>
