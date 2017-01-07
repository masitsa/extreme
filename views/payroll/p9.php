<?php
$personnel_id = $this->session->userdata('personnel_id');

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
		img.logo{max-height:70px; margin:0 auto;}
	</style>
    <head>
        <title>P9 Form</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css">
    </head>
    <body class="receipt_spacing">
        <div>
            <div align="center">
            	<strong>
                KENYA REVENUE AUTHORITY </br>
                DOMESTIC TAXES DEPARTMENT </br>
                TAX DEDUCTION CARD YEAR 2016 </br>
                </strong>
            </div>
            </br>
            <div>
            	<div class="col-md-6">
                	<div class="form-group">
                    	<label class="col-md-5">Employer's Name:</label>
                        <div class="col-md-7">
                        ..............................................
                        </div>
                    </div>
                    <div>
                    	<div class="form-group">
                        	
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                	 <div class="form-group">
                    	<label class="col-md-5">Employer's Pin:</label>
                        <div class="col-md-7">
                        	<input type="text">
                        </div>
                    </div>
                    </br>
                     <div class="form-group">
                    	<label class="col-md-5">Employee's Pin:</label>
                        <div class="col-md-7">
                        	<input type="text">
                        </div>
                    </div>
                    </br>
                </div>
            </div>
        	<table class="table table-bordered table-striped table-condensed">
            	<thead>
                	<tr rowspan="2">
                    	<td>MONTH</td>
                        <td>Basic Salary </br> </br>  Kshs.</td>
                        <td>Benefits Non Cash </br> </br> Kshs.</td>
                        <td>Value of Quarters </br> </br> Kshs.</td>
                        <td align="center">Total Gross Pay </br> </br> Kshs.</td>
                        <td align="center" colspan="3">Defined Contribution Retirement Scheme </br> </br> Kshs.</td>
                        <td align="center">Owner-Occupied Interest </br> </br> Kshs.</td>
                        <td align="center">Retirement Contribution & Owner Occupied Interest </br> </br> Kshs.</td>
                        <td align="center">Chargeable Pay </br> </br> Kshs.</td>
                        <td align="center">Tax Charged </br> </br> Kshs.</td>
                        <td align="center">Personal Relief</br> </br>Kshs. </br>1162</td>
                        <td align="center">Insurance Relief</br> </br> Kshs.</br>-</td>
                        <td align="center">PAYE</br></br> Kshs.</td>
                     </tr>
                     <tr>
                    	<td></td>
                        <td align="center">A</td>
                        <td align="center">B</td>
                        <td align="center">C</td>
                        <td align="center">D</td>
                        <td colspan="3" align="center">E</td>
                        <td align="center">F</td>
                        <td align="center">G</td>
                        <td align="center">H</td>
                        <td align="center">J</td>
                        <td colspan="2" align="center">K</td>
                        <td align="center">L</td>
                     </tr>
                     <tr>
                    	<td colspan="5"></td>
                        <td align="center">E1 30 % of A</td>
                        <td align="center">E2 Actual</td>
                        <td align="center">E3 Fixed</td>
                        <td align="center">Amount of Interest</td>
                        <td align="center">The lowest of E added to F</td>
                        <td></td>
                        <td></td>
                        <td colspan="2" align="center">Total
                        Kshs.1162</td>
                        <td></td>
                     </tr>
                     
                </thead>
                
                <tbody>
                <?php 
					$months = $this->payroll_model->get_months();
					$result = '';
					if ($months ->num_rows()>0)
					{
						foreach($months->result() as $month)
						{
							
							$month_id = $month->month_id;
							$month_name = $month->month_name;
							
							if(($month_id>=$from_month_id) &&($month_id<=$to_month_id))
							{
							
							/*$month_id = $this->input->post('from_month');
							$to_month_id = $this->input->post('to_month');
							$year = $this->input->post('year');*/
							$p9_data = $this->payroll_model->get_p9_form_data($personnel_id, $from_month_id,$to_month_id,$year);
							
							//payments
							$total_payments = 0;
							if($payments->num_rows() > 0)
							{
								foreach($payments->result() as $res)
								{
									$payment_id = $res->payment_id;
									$table = $this->payroll_model->get_table_id("payment");
									$table_id = $payment_id;
									
									if ($p9_data->num_rows()>0)
									{
										foreach($p9_data ->result() as $data)
										{
											$p9_table = $data->table;
											$p9_table_id = $data->table_id;
											
											$p9_month_id = $data->month_id;
											
											if(($p9_table == $table) && ($p9_table_id == $table_id) && ($p9_month_id == $month_id))
											{
												$total_payments += $data ->payroll_item_amount;
											}
										}
									}
								}
							}
							//benefits(non cash benefits)
							$total_benefits = 0;
							if($benefits->num_rows() > 0)
							{
								foreach($benefits->result() as $res)
								{
									$benefit_id = $res->benefit_id;
									$table = $this->payroll_model->get_table_id("benefit");
									$table_id = $benefit_id;
									
									if ($p9_data->num_rows()>0)
									{
										foreach($p9_data ->result() as $data)
										{
											$p9_table = $data->table;
											$p9_table_id = $data->table_id;
											
											$p9_month_id = $data->month_id;
											
											if(($p9_table == $table) && ($p9_table_id == $table_id) && ($p9_month_id == $month_id))
											{
												$total_benefits += $data ->payroll_item_amount;
											}
										}
									}
								}
							}
							
							//allowances
							$total_personnel_allowances = 0;
							if($allowances->num_rows() > 0)
							{
								foreach($allowances->result() as $res)
								{
									$allowance_id = $res->allowance_id;
									$table = $this->payroll_model->get_table_id("allowance");
									$table_id = $allowance_id;
									
									if ($p9_data->num_rows()>0)
									{
										foreach($p9_data ->result() as $data)
										{
											$p9_table = $data->table;
											$p9_table_id = $data->table_id;
											
											$p9_month_id = $data->month_id;
											
											if(($p9_table == $table) && ($p9_table_id == $table_id) && ($p9_month_id == $month_id))
											{
												$total_personnel_allowances += $data ->payroll_item_amount;
											}
										}
									}
								}
							}
							//nssf = employer pension contribution
							$total_nssf = 0;
							if($nssf->num_rows() > 0)
							{
								foreach($nssf->result() as $res)
								{
									$nssf_id = $res->nssf_id;
									$table = $this->payroll_model->get_table_id("nssf");
									$table_id = $nssf_id;
									
									if ($p9_data->num_rows()>0)
									{
										foreach($p9_data ->result() as $data)
										{
											$p9_table = $data->table;
											$p9_table_id = $data->table_id;
											
											$p9_month_id = $data->month_id;
											
											if(($p9_table == $table) && ($p9_table_id == $table_id) && ($p9_month_id == $month_id))
											{
												$total_nssf += $data ->payroll_item_amount;
											}
										}
									}
								}
							}
							$gross_pay =$total_payments + $total_benefits + $total_personnel_allowances;
							$e1_gross = 0.3 * $gross_pay;
							$fixed_monthly_limit = 20000;
							$allowable_interest_amount =0;
							$least_e = 0 ;
								
							if($e1_gross < $total_nssf)
							{
								
								if($e1_gross < $fixed_monthly_limit)
								{
									
									if($fixed_monthly_limit < $total_nssf)
									{
										$least_e = $fixed_monthly_limit;
									}
									else
									{
										$least_e = $e1_gross;
									}
								}
								else
								{
									$least_e = 1600;
								}
							}
							else
							{
								if($e1_gross < $fixed_monthly_limit)
								{
									
									if($fixed_monthly_limit < $total_nssf)
									{
										$least_e = $fixed_monthly_limit;
									}
									else
									{
										$least_e = $total_nssf;
									}
								}
								else
								{
									$least_e = $e1_gross;
								}								
							}
							
							$total_retirement_interest = $least_e + $allowable_interest_amount;
							$basic_salary = $total_payments + $total_personnel_allowances;
							$balance_pay = $gross_pay - $total_retirement_interest;
							$taxable = $gross_pay - $total_nssf;
							$monthly_relief = $insurance_relief = $paye = $paye_less_relief = 0;
							$house_benefit = 0;
							//tax calculation -paye
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
							$result .='
								<tr>
									<td>'.$month_name.'</td>
									<td>'.number_format($basic_salary,2).'</td>
									<td>'.number_format($total_benefits,2).'</td>
									<td>'.number_format($house_benefit,2).'</td>
									<td>'.number_format($gross_pay,2).'</td>
									<td>'.number_format($e1_gross,2).'</td>
									<td>'.number_format($total_nssf,2).'</td>
									<td>'.number_format($fixed_monthly_limit,2).'</td>
									<td>'.number_format($allowable_interest_amount,2).'</td>
									<td>'.number_format($total_retirement_interest,2).'</td>
									<td>'.number_format($balance_pay,2).'</td>
									<td>'.number_format($paye,2).'</td>
									<td>'.number_format($monthly_relief,2).'</td>
									<td>'.number_format($insurance_relief,2).'</td>
									<td>'.number_format($paye_less_relief,2).'</td>
								</tr>';
						}
						}
						echo $result;
						
					}
					?>
               	</tbody>
							</table>
						</div>
					</body>
				</html>
