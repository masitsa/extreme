<?php
$personnel_id = $this->session->userdata('personnel_id');
$prepared_by = $this->session->userdata('first_name');
$roll = $payroll->row();
$year = $roll->payroll_year;
$month = $roll->month_id;
$totals = array();
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
        <title>Payroll</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" />
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css">
    </head>
    <body class="receipt_spacing">
    	
        
     
        
        <div class="row receipt_bottom_border" >
        	<div class="col-md-12 center-align">
        	<?php
        		if ($query->num_rows() > 0)
					{
						$count = 0;
						$result = '';
						
						$total_payments = 0;
						$total_savings = 0;
						$total_loans = 0;
						$total_net = 0;
						
						
							$personnel_id = $this->session->userdata('personnel_id');
							$personnel_number = $this->session->userdata('personnel_number');
							$personnel_fname = $this->session->userdata('first_name');
							$personnel_onames = $this->session->userdata('other_names');
							$gross = 0;
							$result .= '<div class="row" >
								        	<img src="'.base_url().'assets/logo/'.$branch_image_name.'" alt="'.$branch_name.'" class="img-responsive logo"/>
								        	<div class="col-md-12 center-align receipt_bottom_border">
								            	<strong>
								                	'.$branch_name.'<br/>
								                    '.$branch_address.' '.$branch_post_code.' '.$branch_city.'<br/>
								                    E-mail: '.$branch_email.'. Tel : '.$branch_phone.'<br/>
								                    '.$branch_location.'<br/>
								                </strong>
								            </div>
								        </div>
										<div class="row">
								        	<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
								                <div class="row">
								                    <div class="col-xs-12">
								                    	<div class="center-align">
								                        	<h5>Payslip for '.$personnel_fname.' '.$personnel_onames.' No. '.$personnel_number.' '.date('M Y',strtotime($year.'-'.$month)).'</h5>
								                        </div>
								                    </div>
								                </div>';
								                $result .= 
												'<div class="row">';
										                    $total_payments = 0;
										                                                                
										                    
										                    if($payments->num_rows() > 0)
															{
																foreach($payments->result() as $res)
																{
																	$payment_id = $res->payment_id;
																	$payment_abbr = $res->payment_name;
																	$table = $this->payroll_model->get_table_id("payment");
																	$table_id = $payment_id;
																	
																	$payment_amt = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
																	$total_payments += $payment_amt;
																	 $result.='
										                                        <div class="col-xs-6">
										                                        	<strong>'.$payment_abbr.'</strong>
										                                        </div>
										                                        
										                                        <div class="col-xs-6">
										                                            '.number_format($payment_amt, 2).'
										                                        </div>
										                                        ';
																}
															}
										        
										          	$result .='</div>';

										          	$result .=' <div class="row">
												                	<div class="col-xs-12">
												                        Non cash benefits
												                    </div>
												                </div>';
												    $result .=
												    '<div class="row">';
									                    $total_benefits = 0;
														if($benefits->num_rows() > 0)
														{
															foreach($benefits->result() as $res)
															{
																$benefit_id = $res->benefit_id;
																$benefit_name = $res->benefit_name;
																$table = $this->payroll_model->get_table_id("benefit");
																$table_id = $benefit_id;
																
																$benefit_amt = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
																$total_benefits += $benefit_amt;
																// $result .= '<td>'.number_format($benefit_amt, 2).'</td>';
																 $result .='
									                                        <div class="col-xs-6">
									                                        	<strong>'.$benefit_name.'</strong>
									                                        </div>
									                                        
									                                        <div class="col-xs-6">
									                                            '.number_format($benefit_amt, 2).'
									                                        </div>';
															}
														}
									                   
									                $result .='</div>';
									                $result .=' <div class="row">
												                	<div class="col-xs-12">
												                        Allowances
												                    </div>
												                </div>';
												     $result .=
												    '<div class="row">';
									                    $total_allowances = 0;
									                    //allowances
														if($allowances->num_rows() > 0)
														{
															foreach($allowances->result() as $res)
															{
																$allowance_id = $res->allowance_id;
																$allowance_name = $res->allowance_name;
																$table = $this->payroll_model->get_table_id("allowance");
																$table_id = $allowance_id;
																
																$allowance_amt = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
																$total_allowances += $allowance_amt;
																// $result .= '<td>'.number_format($allowance_amt, 2).'</td>';
																 $result .='
									                                        <div class="col-xs-6">
									                                        	<strong>'.$allowance_name.'</strong>
									                                        </div>
									                                        
									                                        <div class="col-xs-6">
									                                            '.number_format($allowance_amt, 2).'
									                                        </div>';
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
															$total_gross = 0;
														$total_paye = 0;
														$total_nssf = 0;
														$total_nhif = 0;
														$total_life_ins = 0;
													
														$table = $this->payroll_model->get_table_id("nssf");
														$nssf = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
														$total_nssf += $nssf;
														
														//nhif
														$table = $this->payroll_model->get_table_id("nhif");
														$nhif = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
														$total_nhif += $nhif;
														
														//paye
														$table = $this->payroll_model->get_table_id("paye");
														$paye =$this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
														
														//relief
														$table = $this->payroll_model->get_table_id("relief");
														$relief = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
														
														//insurance_relief
														$table = $this->payroll_model->get_table_id("insurance_relief");
														$insurance_relief = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
														
														//relief
														$table = $this->payroll_model->get_table_id("insurance_amount");
														$insurance_amount = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
														$taxable = $gross_taxable - $total_nssf;
														$monthly_relief = $this->payroll_model->get_monthly_relief();
														$paye_less_relief = $paye - $monthly_relief - $insurance_relief;
															
														if($paye_less_relief < 0)
														{
															$paye_less_relief = 0;
														}
									                $result .='</div>';
									                 $result .= ' <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
												                	<div class="col-xs-6">
												                        <strong>Gross taxable amount</strong>
												                    </div>
												                    
												                    <div class="col-xs-6">
												                    	'.number_format(($gross_taxable), 2).'
												                    </div>
												                </div>';
											        $result .= ' 
												                <!-- NSSF -->
												                <div class="row">
												                	<div class="col-xs-6">
												                        <strong>NSSF</strong>
												                    </div>
												                    
												                    <div class="col-xs-6">
												                       ('.number_format(($total_nssf), 2).')
												                    </div>
												                </div>
												                <!-- End NSSF -->';
												   	$result .= ' <!-- Taxable -->
												                <div class="row">
												                	<div class="col-xs-6">
												                        <strong>Taxable amount</strong>
												                    </div>
												                    
												                    <div class="col-xs-6">
												                       	'.number_format(($taxable), 2).'
												                      
												                    </div>
												                </div>
												                <!-- End taxable -->';
												    $result .= '  <!-- Tax calculation header -->
												                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
												                	<div class="col-xs-12">
												                        Tax calculation
												                    </div>
												                </div>
												                <!-- End tax calculation header -->';

												    $result .= ' <!-- Normal tax -->
												                <div class="row">
												                	<div class="col-xs-6">
												                        <strong>Normal tax</strong>
												                    </div>
												                    
												                    <div class="col-xs-6">
												                        '.number_format(($paye), 2).'
												                    
												                    </div>
												                </div>
												                <!-- End normal tax -->';
												    $result .= '  <!-- Monthly relief -->
												                <div class="row">
												                	<div class="col-xs-6">
												                        <strong>Monthly relief</strong>
												                    </div>
												                    
												                    <div class="col-xs-6">
												                    	'.number_format(($monthly_relief), 2).'
												                   
												                    </div>
												                </div>
												                <!-- End monthly relief -->';
												    $result .= ' <!-- Insurance relief -->';
												    			if($insurance_relief > 0){
												    				$result .='
																                <div class="row">
																                	<div class="col-xs-6">
																                        <strong>Insurance relief</strong>
																                    </div>
																                    
																                    <div class="col-xs-6">
																                        '.number_format(($insurance_relief), 2).'
																                  
																                    </div>
																                </div>';
												             	 }
												    $result .= '  <!-- PAYE -->
												                <div class="row">
												                	<div class="col-xs-6">
												                        <strong>PAYE</strong>
												                    </div>
												                    
												                    <div class="col-xs-6">
												                        '.number_format(($paye_less_relief), 2).'
												                  
												                    </div>
												                </div>
												                <!-- End PAYE -->';
												    $result .= '  <!-- Deductions header -->
												                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
												                	<div class="col-xs-12">
												                        Other deductions
												                    </div>
												                </div>
												                <!-- End deductions header -->';
												     			if($insurance_amount > 0){
												     				$result .='
																                <div class="row">
																                	<div class="col-xs-6">
																                        <strong>Life insurance</strong>
																                    </div>
																                    
																                    <div class="col-xs-6">
																                        '.number_format(($insurance_amount), 2).'
																                    
																                    </div>
																                </div>';
					                							}
					                			
					                				        		$result .=
					                				'<div class="row">
									                    <div class="col-xs-6">
									                        <strong>NSSF</strong>
									                    </div>
									                    
									                    <div class="col-xs-6">
									                        '.number_format($total_nssf, 2).'
									                    </div>';
									                   
														$result .='
									                    <div class="col-xs-6">
									                        <strong>NHIF</strong>
									                    </div>
									                    
									                    <div class="col-xs-6">
									                        '.number_format($total_nhif, 2).'
									                    </div>';
														
									                    /*********** Deductions ***********/
														$total_deductions = 0;
														//deductions
														$table = $this->payroll_model->get_table_id("deduction");
														$total_deductions = 0;
														if($deductions->num_rows() > 0)
														{
															foreach($deductions->result() as $res)
															{
																$deduction_id = $res->deduction_id;
																$deduction_name = $res->deduction_name;
																
																$table_id = $deduction_id;
																$deduction_amt = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
																$total_deductions += $deduction_amt;
																$result .='
										                                        <div class="col-xs-6">
										                                        	<strong>'.$deduction_name.'</strong>
										                                        </div>
										                                        
										                                        <div class="col-xs-6">
										                                            '.number_format($deduction_amt, 2).'
										                                        </div>';
															}
														}						
													
														
									                    /*********** Other deductions ***********/
														$total_other_deductions = 0;
														//other_deductions
														$table = $this->payroll_model->get_table_id("other_deduction");
														$total_other_deductions = 0;
														if($other_deductions->num_rows() > 0)
														{
															foreach($other_deductions->result() as $res)
															{
																$other_deduction_id = $res->other_deduction_id;
																$other_deduction_name = $res->other_deduction_name;
																
																$table_id = $other_deduction_id;
																$other_deduction_amt = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
																$total_other_deductions += $other_deduction_amt;
																$result .='
									                                        <!--<div class="col-xs-6">
									                                        	<strong>'.$other_deduction_name.'</strong>
									                                        </div>
									                                        
									                                        <div class="col-xs-6">
									                                            '.number_format($other_deduction_amt, 2).'
									                                        </div>-->
									                                        ';
															}
														}						
														
														
														if($total_other_deductions > 0)
														{
									                    $result .='
										                    <div class="col-xs-6">
										                        <strong>Other advances</strong>
										                    </div>
										                    
										                    <div class="col-xs-6">
										                        '.number_format($total_other_deductions, 2).'
										                    </div>';
									                    }
									                    $rs_savings = $this->payroll_model->get_savings();
														$total_savings = 0;
														
														if($rs_savings->num_rows() > 0)
														{
															foreach($rs_savings->result() as $res)
															{
																$savings_name = $res->savings_name;
																$savings_id = $res->savings_id;
																
																$table = $this->payroll_model->get_table_id("savings");
															
																//get schemes
																$total_savings += $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $savings_id);
															}
														}

														
														$total_savings += $total_savings;
														if($total_savings > 0)
														{
									                    $result .='
										                    <div class="col-xs-6">
										                        <strong>Savings</strong>
										                    </div>
										                    
										                    <div class="col-xs-6">
										                        '.number_format($total_savings, 2).'
										                    </div>';
									                    }
									                $result .='</div>';
												    $result .= 
											       ' <!-- Loans header -->
									                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
									                	<div class="col-xs-12">
									                        Loans
									                    </div>
									                </div>
									                <!-- End loans header -->
									                
									                <!-- Loans -->';
									                
									                $total_loan_schemes = 0;
									                //get loan schemes
													$date = date("Y-m-d");
													$rs_schemes = $this->payroll_model->get_loan_schemes();
													$total_schemes = 0;
													$interest = 0;
													$monthly = 0;
								                    $interest = 0;
								                    $interest2 = 0;
								                    $sdate = '';
								                    $edate = '';
								                    $today = date("y-m-d");
								                    $prev_payments = "";
								                    $prev_interest = "";
												
													if($rs_schemes->num_rows() > 0)
													{
														foreach($rs_schemes->result() as $res)
														{
															$loan_scheme_name = $res->loan_scheme_name;
															$loan_scheme_name = $res->loan_scheme_name;
															$loan_scheme_id = $res->loan_scheme_id;
															
															$table = $this->payroll_model->get_table_id("loan_scheme");
														
															//get schemes
															$total_schemes += $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $loan_scheme_id);
															
															//get interest
															$rs_interest = $this->payroll_model->get_loan_scheme_interest($personnel_id, $date, $loan_scheme_id);
															
															if($rs_interest->num_rows() > 0)
															{
																foreach($rs_interest->result() as $res2)
																{
																	$interest += $res2->interest;
																}
															}
															$result .='
																			<div class="row">
																				<div class="col-xs-6">
																					<strong>'.$loan_scheme_name.'</strong>
																				</div>
																				
																				<div class="col-xs-6" style="text-align:left">
																					'.number_format($total_schemes, 2).'
																				</div>
																			</div>
																			';
														}
													}
													$total_loans += ($total_schemes + $interest);
													if($total_loans > 0)
													{
														$result .='
										                    <div class="col-xs-6">
										                        <strong>Loan Balance</strong>
										                    </div>
										                    
										                    <div class="col-xs-6">
										                        '.number_format(($total_schemes + $interest), 2).'
										                    </div>';

													}                       
									               
													
													$all_deductions = $paye_less_relief + $nssf + $nhif + $total_deductions + $total_other_deductions + $total_loan_schemes + $total_savings + $insurance_amount;
													
													$net_pay = $gross - $all_deductions;
													 $result .=
									            '  <!-- Gross pay -->
									                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-bottom:none;">
									                	<div class="col-xs-6">
									                        <strong>Gross pay</strong>
									                    </div>
									                    
									                    <div class="col-xs-6">
									                        '.number_format(($gross), 2).'
									                    </div>
									                </div>
									                <!-- End Gross -->
									                
									                <!-- Total deductions -->
									                <div class="row">
									                	<div class="col-xs-6">
									                        <strong>Total deductions</strong>
									                    </div>
									                    
									                    <div class="col-xs-6">
									                        ('.number_format(($all_deductions), 2).')
									                    </div>
									                </div>
									                <!-- End Total deductions -->
																	
									                <!-- Net pay -->		
									                <div class="row" style="border:thin solid #000; border-left:none; border-right:none; border-top:none;">
									                    <div class="col-xs-6">
									                        <strong>Net pay</strong>
									                    </div>
									                    
									                    <div class="col-xs-6">
									                        '.number_format($net_pay, 2).'
									                    </div>
									                </div>
									                <!-- End Net -->';

												
									    $result .= 
									    '</div>
					        </div>';
						
					}
					echo $result;
					?>
        		<?php echo 'Prepared By:	'.$prepared_by.' '.date('jS M Y H:i:s',strtotime(date('Y-m-d H:i:s')));?>
            </div>
        </div>
    </body>
</html>
