<?php
$personnel_id = $this->session->userdata('personnel_id');
$prepared_by = $this->session->userdata('first_name');
$roll = $cc_payment->row();
$year = $roll->cc_payment_year;
$month = $roll->month_id;
$totals = array();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Payslips</title>
        <!-- For mobile content -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- IE Support -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- Bootstrap -->
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/vendor/bootstrap/css/bootstrap.css" media="all" />
        <link rel="stylesheet" href="<?php echo base_url()."assets/themes/porto-admin/1.4.1/";?>assets/stylesheets/theme-custom.css" media="all">
		<link rel="stylesheet" href="<?php echo base_url()."/";?>assets/themes/custom/custom.css">
		
		<style type="text/css">
            .receipt_spacing{letter-spacing:0px; font-size: 12px;}
            .center-align{margin:0 auto; text-align:center;}
            
            .receipt_bottom_border{border-bottom: #888888 medium solid;}
            .row .col-md-12 table {
                
                border-width:1px 0 0 1px !important;
                font-size:10px;
            }
            .table-condensed > thead > tr > th, .table-condensed > tbody > tr > th, .table-condensed > tfoot > tr > th, .table-condensed > thead > tr > td, .table-condensed > tbody > tr > td, .table-condensed > tfoot > tr > td {
        padding: 0px !important;
    }
            @media print{
                
                #page-break{
                    page-break-before:always !important;
                }
            }
            .table {
              margin-bottom: 10px;
            }
            .table-condensed > tbody > tr > th {
                font-size: 15px !important;
            }
            .table > tr > th {
                font-size: 15px !important;
            }
            .col-md-12 td {
                border-width: 0 1px 1px 0 !important;
                font-size:13px !important;
            }
            .tr .th {
                font-size:12px !important;
                padding-left: 0px !important;
            }
            
            .row .col-md-12 th, .row .col-md-12 td {	
                border-width:0 1px 1px 0 !important;
            }
            .col-xs-6{
                min-height:600px;
            }
            .row .col-md-12 .title-item{float:left;width: 130px; font-weight:bold; text-align:right; padding-right: 20px;}
            .title-img{float:left; padding-left:30px;}
            img.logo{max-height:70px; margin:0 auto;}
            .left-align{text-align:left !important;}
            .right-align{text-align:right !important;}
             
    
            .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td{border-top:none;}
        </style>
    </head>
    <body class="receipt_spacing">
		<?php
		$result = '';
		
			if ($query->num_rows() > 0)
			{
				
				$count = 0;
				$result = '';
				
				$total_payments = 0;
				$total_savings = 0;
				$total_loans = 0;
				$total_net = 0;
				$benefits_amount = $cc_payment_data->benefits;
				$total_benefits2 = $cc_payment_data->total_benefits;
				$payments_amount = $cc_payment_data->payments;
				$total_payments2 = $cc_payment_data->total_payments;
				$allowances_amount = $cc_payment_data->allowances;
				$total_allowances2 = $cc_payment_data->total_allowances;
				$deductions_amount = $cc_payment_data->deductions;
				$total_deductions2 = $cc_payment_data->total_deductions;
				$other_deductions_amount2 = $cc_payment_data->other_deductions;
				$total_other_deductions2 = $cc_payment_data->total_other_deductions;
				$nssf_amount = $cc_payment_data->nssf;
				$nhif_amount = $cc_payment_data->nhif;
				$life_ins_amount = $cc_payment_data->life_ins;
				$paye_amount = $cc_payment_data->paye;
				$monthly_relief_amount = $cc_payment_data->monthly_relief;
				$insurance_relief_amount = $cc_payment_data->insurance_relief;
				$insurance_amount_amount = $cc_payment_data->insurance;
				$scheme = $cc_payment_data->scheme;
				$savings = $cc_payment_data->savings;
				
				foreach ($query->result() as $row)
				{
					if($query->num_rows() != 1)
					{
						$count++;
					}
					$personnel_id = $row->personnel_id;
					$personnel_number = $row->personnel_number;
					$personnel_fname = $row->personnel_fname;
					$personnel_onames = $row->personnel_onames;
					$nhif_number =$row->personnel_nhif_number;
					$nssf_number = $row->personnel_nssf_number;
					$kra_pin = $row-> personnel_kra_pin;
					$gross = 0;
					$page_break = '';
					if(($count%2) == 0)
					{
						$result .= '
								<td>';
					}
					
					else
					{
						$result .= '
						<table class="table" id ="page-break">
							<tr>
								<td style = "padding-right:20px !important">';
					}
					$result .= '
									<table class="table receipt_bottom_border" style = "center-align" >
										<tr>
											<th>
												<strong>
													INSIGHT MANAGEMENT CONSULTANTS LTD<br/>
												</strong>
												<h5>'.$personnel_onames.' '.$personnel_fname.' </br>
												STAFF NUMBER. '.$personnel_number.' </br>
												NSSF NO.. '.$nssf_number.' </br>
												NHIF NO. '.$nhif_number.' </br>
												KRA PIN: '.$kra_pin.'</br> '.date('M Y',strtotime($year.'-'.$month)).'</h5>
											</th>
										</tr>
									</table>
									';
									$result .= 
									'<table class="table table-condensed">
										<tr>
											<th>EARNINGS</th>
										</tr>
									';
											$total_payments = 0;
											$payment_amt = 0;					
											
											if($payments->num_rows() > 0)
											{
												foreach($payments->result() as $res)
												{
													$payment_abbr = $res->payment_name;
													$payment_id = $res->payment_id;
													$table_id = $payment_id;
													$total_payment_amount[$payment_id] = 0;

													if(isset($total_payments2->$payment_id))
													{
														$total_payment_amount[$payment_id] = $total_payments2->$payment_id;
													}
													if($total_payment_amount[$payment_id] != 0)
													{
														if(isset($payments_amount->$personnel_id->$table_id))
														{
															$payment_amt = $payments_amount->$personnel_id->$table_id;
															$gross += $payment_amt;
														}
														if(!isset($total_personnel_payments[$payment_id]))
														{
															$total_personnel_payments[$payment_id] = 0;
														}
													 $result.='
														<tr>
															<td class = "left-align">
																'.strtoupper ($payment_abbr).'
															</td>
															<td class="right-align">
																'.number_format($payment_amt, 2).'
															</td>
														</tr>';
													}
												}
											}
									'<div class="row">';
										$total_allowances = 0;				
										$allowance_amt = 0;
										//allowances
										
											if($allowances->num_rows() > 0)
											{
												foreach($allowances->result() as $res)
												{
													$allowance_abbr = $res->allowance_name;
													$allowance_id = $res->allowance_id;
													$table_id = $allowance_id;
													$total_allowance_amount[$allowance_id] = 0;

													if(isset($total_allowances2->$allowance_id))
													{
														$total_allowance_amount[$allowance_id] = $total_allowances2->$allowance_id;
													}
													if($total_allowance_amount[$allowance_id] != 0)
													{
														if(isset($allowances_amount->$personnel_id->$table_id))
														{
															$allowance_amt = $allowances_amount->$personnel_id->$table_id;
															$gross += $allowance_amt;
														}
														if(!isset($total_personnel_allowances[$allowance_id]))
														{
															$total_personnel_allowances[$allowance_id] = 0;
														}
														if($allowance_amt > 0)
														{
															 $result.='
																<tr>
																	<td class = "left-align">
																		'.strtoupper ($allowance_abbr).'
																	</td>
																	<td class="right-align">
																		'.number_format($allowance_amt, 2).'
																	</td>
																</tr>';
														}
													}
												}
											}
									$result .='</div>
									</table>';
									$result .= ' <table class="table table-condensed">
									<tr>
										<td class="left-align">
											<th>TOTAL EARNINGS</th>
										</td>
										<td class="right-align">
											'.number_format(($gross), 2).'
										</td>
									</tr>';
									$result .='</table>';
									
									$result .='<table class="table table-condensed">
									<tr>
										<th>NON CASH BENEFITS</th>
									</tr>
									';
									
										$total_benefits = 0;
										$benefit_amt = 0;
										if($benefits->num_rows() > 0)
											{
												foreach($benefits->result() as $res)
												{
													$benefit_id = $res->benefit_id;
													$benefit_name = $res->benefit_name;
													$table_id = $benefit_id;
													$total_benefit_amount[$benefit_id] = 0;

													if(isset($total_benefits2->$benefit_id))
													{
														$total_benefit_amount[$benefit_id] = $total_benefits2->$benefit_id;
													}
													if($total_benefit_amount[$benefit_id] != 0)
													{
																							
														$benefit_amt = 0;
														if(isset($benefits_amount->$personnel_id->$table_id))
														{
															$benefit_amt = $benefits_amount->$personnel_id->$table_id;
														}
														if(!isset($total_personnel_benefits[$benefit_id]))
														{
															$total_personnel_benefits[$benefit_id] = 0;
														}
														if($benefit_amt > 0)
														{
													 $result.='
														<tr>
															<td class = "left-align">
																'.strtoupper ($benefit_name).'
															</td>
															<td class="right-align">
																'.number_format($benefit_amt, 2).'
															</td>
														</tr>';
														}
													}
												}
											}		
										/*********** Taxable ***********/
										
										$gross_taxable = $gross += $benefit_amt;
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
									
									//nssf
									$nssf = $nssf_amount->$personnel_id;
									$total_nssf += $nssf;
									
									//nhif
									$nhif = $nhif_amount->$personnel_id;
									$total_nhif += $nhif;
									
									//paye
									$paye =$paye_amount->$personnel_id;
									
									//relief
									$relief = $monthly_relief_amount->$personnel_id;
									
									//insurance_relief
									$insurance_relief = $insurance_relief_amount->$personnel_id;
									
									//relief
									$insurance_amount = $insurance_amount_amount->$personnel_id;
									//echo $insurance_relief;
									$paye_less_relief -= ($relief + $insurance_relief);
													
									if($paye < 0)
									{
										$paye = 0;
									}
									if($gross <=0)
									{
										$relief = 0;
									}
								
									$total_paye += $paye;
									$total_life_ins += $insurance_amount;
										
									$result .='</table>';
									
									$result .= ' <table class="table table-condensed">
									<tr>
										<th>P.A.Y.E</th>
									</tr>
									<tr>
										<td class="left-align">
											LIABLE PAY
										</td>
										<td class="right-align">
											'.number_format(($gross_taxable), 2).'
										</td>
									</tr>';
														
									$result .='
												<tr>
												<td class="left-align">
												LESS PENSIONS/NSSF
												</td>
												<td class="right-align">
													'.number_format($total_nssf, 2).'
												</td>
											</tr>';
									$taxable = $gross_taxable - $total_nssf;
									$result .= ' 
												<tr>
												<td class="left-align">
													CHARGEABLE AMT KSHS
												</td>
												<td class="right-align">
													'.number_format($taxable, 2).'
												</td>
											</tr>';
									$result .= ' 
										<tr>
										<td class="left-align">
											TAX CHARGED
										</td>
										<td class="right-align">
											'.number_format($paye, 2).'
										</td>
									</tr>';
									$result .= ' 
										<tr>
										<td class="left-align">
											PERSONAL RELIEF
										</td>
										<td class="right-align">
											'.number_format($relief, 2).'
										</td>
									</tr>';
									$result .= ' <!-- Insurance relief -->';
												if($insurance_relief > 0){
													$result .='
														<tr>
															<td class="left-align">
																Insurance Relief
															</td>
															<td class="right-align">
																'.number_format($insurance_relief, 2).'
															</td>
														</tr>';
												 }
											
									$result .='</table>';
							
									$result .= ' <table class="table table-condensed">
									<tr>
										<th>DEDUCTIONS</th>
									</tr>';
										if($insurance_amount > 0){
											$result .='
														
													 <tr>
													<td class="left-align">
														Life Insurance
													</td>
													<td class="right-align">
														'.number_format($insurance_amount, 2).'
													</td>
												</tr>';
										}
										$paye_less_relief = $paye- ($relief + $insurance_relief);
										if($paye_less_relief < 0){
											$paye_less_relief = 0;
										}
									$result .= '  <!-- PAYE -->
									
											<tr>
												<td class="left-align">
													PAYE
												</td>
												<td class="right-align">
													'.number_format($paye_less_relief, 2).'
												</td>
											</tr>';
								
									$result .=
										'
										<tr>
											<td class="left-align">
												NSSF
											</td>
											<td class="right-align">
												'.number_format($total_nssf, 2).'
											</td>
										</tr>';
					   
									$result .='
										<tr>
											<td class="left-align">
												NHIF
											</td>
											<td class="right-align">
												'.number_format($total_nhif, 2).'
											</td>
										</tr>';
										
										/*********** Deductions ***********/
										$total_deductions = 0;
										//deductions
										$total_deductions = 0;
										if($deductions->num_rows() > 0)
										{
											foreach($deductions->result() as $res)
											{
												$deduction_id = $res->deduction_id;
												$deduction_name = $res->deduction_name;
												
												$table_id = $deduction_id;
												$total_deduction_amount[$deduction_id] = 0;
			
												if(isset($total_deductions2->$deduction_id))
												{
													$deduction_amt = 0;
													if(isset($deductions_amount->$personnel_id->$table_id))
													{
														$deduction_amt = $deductions_amount->$personnel_id->$table_id;
													}
													$total_deductions += $deduction_amt;
													if(!isset($total_personnel_deductions[$deduction_id]))
													{
														$total_personnel_deductions[$deduction_id] = 0;
													}
													if($deduction_amt > 0)
													{
														$result .='
																 <tr>
																<td class="left-align">
																	'.strtoupper ($deduction_name).'
																</td>
																<td class="right-align">
																	'.number_format($deduction_amt, 2).'
																</td>
															</tr>';
													}
												}
											}
										}						
									
												
												/*********** Other deductions ***********/
												$total_other_deductions = 0;
												//other_deductions
												$total_other_deductions = 0;
											if($other_deductions->num_rows() > 0)
											{
												foreach($other_deductions->result() as $res)
												{
													$other_deduction_id = $res->other_deduction_id;
													$other_deduction_name = $res->other_deduction_name;
													
													$table_id = $other_deduction_id;
													
													$total_other_deduction_amount[$other_deduction_id] = 0;
													
													if(isset($total_other_deductions2->$other_deduction_id))
													{
														$other_deduction_amt = 0;
														if(isset($other_deductions_amount2->$personnel_id->$table_id))
														{
															$other_deduction_amt = $other_deductions_amount2->$personnel_id->$table_id;
														}
														$total_other_deductions += $other_deduction_amt;
														if(!isset($total_personnel_other_deductions[$other_deduction_id]))
														{
															$total_personnel_other_deductions[$other_deduction_id] = 0;
														}	
														if($other_deduction_amt > 0)
														{
														$result .='<tr>
																																								<td class="left-align">
																		'.strtoupper ($other_deduction_name).'
																	</td>
																	<td class="right-align">
																		'.number_format($other_deduction_amt, 2).'
																	</td>
																</tr>';
														}
													}
												}
											}						
												
												$rs_savings = $this->cc_payment_model->get_savings();
												$total_savings = 0;
												
												if($rs_savings->num_rows() > 0)
												{
													foreach($rs_savings->result() as $res)
													{
														$savings_name = $res->savings_name;
														$savings_id = $res->savings_id;
													
														//get schemes
														$total_savings += $this->cc_payment_model->get_cc_payment_amount($personnel_id, $cc_payment_items, $savings_table, $savings_id);
													}
												}

												
												$total_savings += $total_savings;
												if($total_savings > 0)
												{
												$result .='<tr>
																	<td class="left-align">
																		Savings
																	</td>
																	<td class="right-align">
																		'.number_format($total_savings, 2).'
																	</td>
																</tr>';
												}
											$result .='</table>';
											$result .= 
										   '<table class="table table-condensed">
											<tr>
												<th>LOANS</th>
											</tr>
										';
											
											$total_loan_schemes = 0;
											//get loan schemes
											$date = date("Y-m-d");
											$rs_schemes = $this->cc_payment_model->get_loan_schemes();
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
												
													//get schemes
													$total_schemes += $this->cc_payment_model->get_cc_payment_amount($personnel_id, $cc_payment_items, $loan_scheme_table, $loan_scheme_id);
													
													//get interest
													$rs_interest = $this->cc_payment_model->get_loan_scheme_interest($personnel_id, $date, $loan_scheme_id);
													
													if($rs_interest->num_rows() > 0)
													{
														foreach($rs_interest->result() as $res2)
														{
															$interest += $res2->interest;
														}
													}
													if($total_schemes >0)
													{
													$result .='<tr>
																	<td class="left-align">
																		'.strtoupper ($loan_scheme_name).'
																	</td>
																	<td class="right-align">
																		'.number_format($total_schemes, 2).'
																	</td>
																</tr>';
													}
												}
											}
											$total_loans += ($total_schemes + $interest);
											if($total_loans > 0)
											{
												$result .='
													<tr>
																	<td class="left-align">
																		Loan Balance
																	</td>
																	<td class="right-align">
																		'.number_format($total_schemes + $interest, 2).'
																	</td>
																</tr>';

											}
											$all_deductions = $paye- ($relief + $insurance_relief) + $total_nssf + $total_nhif + $total_deductions + $total_other_deductions + $total_loan_schemes + $total_savings + $insurance_amount;
											
											$net_pay = $gross - $all_deductions;											
												$result .='
													<tr>
														<td class="left-align">
															TOTAL DEDUCTIONS
														</td>
														<td class="right-align">
															'.number_format($all_deductions, 2).'
														</td>
													</tr>';											
										   
											$result .='</table>';
											
											
										$result .= ' <table class="table table-condensed">
											';
											 $result .= '
											<tr>
												<td class="left-align">
													<th>Net Pay</th>
												</td>
												<td class="right-align">
													'.number_format(($net_pay), 2).'
												</td>
											</tr></table>';

										
					if(($count%2) == 0)
					{
						$result .= '
								</td>
							</tr>
						</table>';
					}
					
					else
					{
						$result .= '
								</td>';
					}
				}
			}
			echo $result;
			?>
    </body>
</html>
