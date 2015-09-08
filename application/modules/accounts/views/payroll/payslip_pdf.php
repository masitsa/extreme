
<?php
//$personnel_id = $this->session->userdata('personnel_id');
$created_by = $this->session->userdata('first_name');

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Payslip for <?php echo $personnel_name;?></title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" media="all"/>
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css" media="all"/>
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
            img.logo{height:70px; padding-left:10%;}
            .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td { border: none;}
            .table > tbody > tr > th {border-bottom: 1px solid #dddddd;border-top: 1px solid #dddddd;}
			table { border-width:0.01em;}
			tr td{
				text-align:right;
			}
        </style>
    </head>
    <body class="receipt_spacing">
        
         <div class="center-align" style="height:70px; width:100%;">
         	<img src="<?php echo base_url().'assets/logo/'.$contacts['logo'];?>" alt="<?php echo $contacts['company_name'];?>" class="logo"/>
         </div>
        
        <div class="center-align">
            <h5>Payslip for <?php echo $personnel_name;?> <?php echo $personnel_number;?> <?php echo $date;?></h5>
        </div>
        <table class="table table-condensed">
            <tr>
                <td></td>
                <td></td>
            </tr>
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
                    <tr>
                        <td><?php echo $payment_name;?></td>
                        <td><?php echo number_format($amount, 2);?></td>
                    </tr>
                    <?php
                    $total_payments += $amount;
                }
            }
            
			?>
			<tr>
				<th colspan="2">Non cash benefits</th>
			</tr>
            
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
                    <tr>
                        <td><?php echo $benefit_name;?></td>
                        <td><?php echo number_format($amount, 2);?></td>
                    </tr>
                    <?php
                    
                    if($benefit_taxable == 1)
                    {
                        $total_benefits += $amount;
                    }
                }
            }
            ?>
            <!-- Allowances header -->
			<tr>
				<th colspan="2">Allowances</th>
			</tr>
            <!-- End allowances header -->
                
            <!-- Allowances -->
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
                    <tr>
                        <td><?php echo $allowance_name;?></td>
                        <td><?php echo number_format($amount, 2);?></td>
                    </tr>
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
            <!-- Gross Taxable -->
            <tr>
                <td>Gross taxable amount</td>
                <td><?php echo number_format($gross_taxable, 2);?></td>
            </tr>
            <!-- End Gross taxable -->
                
            <!-- NSSF -->
            <tr>
                <td>NSSF</td>
                <td><?php echo number_format($nssf, 2);?></td>
            </tr>
            <!-- End NSSF -->
                
            <!-- Taxable -->
            <tr>
                <td>Taxable amount</td>
                <td><?php echo number_format($taxable, 2);?></td>
            </tr>
            <!-- End taxable -->
            
            <!-- End allowances-->
            
            <!-- Tax calculation header -->
			<tr>
				<th colspan="2">Tax calculation</th>
			</tr>
            <!-- End tax calculation header -->
            
            <!-- Normal tax -->
            <tr>
                <td>Normal tax</td>
                <td><?php echo number_format($paye, 2);?></td>
            </tr>
            <!-- End normal tax -->
            
            <!-- Monthly relief -->
            <tr>
                <td>Monthly relief</td>
                <td><?php echo number_format($monthly_relief, 2);?></td>
            </tr>
            <!-- End monthly relief -->
            
            <!-- Insurance relief -->
            <?php if($insurance_relief > 0){?>
            <tr>
                <td>Insurance relief</td>
                <td><?php echo number_format($insurance_relief, 2);?></td>
            </tr>
            <?php }?>
            <!-- End insurance relief -->
            
            <!-- PAYE -->
            <tr>
                <td>PAYE</td>
                <td><?php echo number_format($paye_less_relief, 2);?></td>
            </tr>
            <!-- End PAYE -->
            
            <!-- Deductions header -->
			<tr>
				<th colspan="2">Other deductions</th>
			</tr>
            <!-- End deductions header -->
            
            <!-- Life insurance -->
            <?php if($insurance_amount > 0){?>
            <tr>
                <td>Life insurance</td>
                <td><?php echo number_format($insurance_amount, 2);?></td>
            </tr>
            <?php }?>
            <!-- End life insurance -->
                
            <!-- Deductions -->
            <tr>
                <td>NSSF</td>
                <td><?php echo number_format($nssf, 2);?></td>
            </tr>
            <tr>
                <td>NHIF</td>
                <td><?php echo number_format($nhif, 2);?></td>
            </tr>
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
                    <tr>
                        <td><?php echo $deduction_name;?></td>
                        <td><?php echo number_format($amount, 2);?></td>
                    </tr>
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
            <tr>
                <td>Other advances</td>
                <td><?php echo number_format($total_other_deductions, 2);?></td>
            </tr>
            <?php } ?>
            <!-- End deductions -->
            
            <!-- Savings header -->
			<tr>
				<th colspan="2">Savings</th>
			</tr>
            <!-- End savings header -->
            
            <!-- Savings -->
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
                    <tr>
                        <td><?php echo $saving_name;?></td>
                        <td><?php echo number_format($amount, 2);?></td>
                    </tr>
                    <?php
                    $total_savings += ($amount);
                }
            }
            ?>
            <!-- End savings -->
            
            <!-- Loans header -->
			<tr>
				<th colspan="2">Loans</th>
			</tr>
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
                    <tr>
                        <td><?php echo $loan_scheme_name;?></td>
                        <td><?php echo number_format($amount, 2);?></td>
                    </tr>
                    <?php
                    $total_loan_schemes += $amount;
                }
            }
            
            $all_deductions = $paye_less_relief + $nssf + $nhif + $total_deductions + $total_other_deductions + $total_loan_schemes + $total_savings + $insurance_amount;
            
            $net_pay = $gross - $all_deductions;
            ?>
            <!-- End loans -->
            
            <!-- Gross pay -->
            <tr>
                <td>Gross pay</td>
                <td><?php echo number_format($gross, 2);?></td>
            </tr>
            <!-- End Gross -->
            
            <!-- Total deductions -->
            <tr>
                <td>Total deductions</td>
                <td><?php echo number_format($all_deductions, 2);?></td>
            </tr>
            <!-- End Total deductions -->
                            
            <!-- Net pay -->		
            <tr>
                <th>Net pay</th>
                <th><?php echo number_format($net_pay, 2);?></th>
            </tr>
            <!-- End Net -->
        </table>
            
        <div class="row receipt_bottom_border" >
            <div class="col-md-12 center-align">
                <?php echo 'Prepared By:	'.$created_by.' '.date('jS M Y H:i:s',strtotime(date('Y-m-d H:i:s')));?>
            </div>
        </div>
    </body>
</html>
