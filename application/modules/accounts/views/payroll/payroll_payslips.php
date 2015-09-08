<?php
		
		$result = '';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			'
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th><a href="'.site_url().'accounts/salary-data/personnel_onames/'.$order_method.'/'.$page.'">Other names</a></th>
						<th><a href="'.site_url().'accounts/salary-data/personnel_fname/'.$order_method.'/'.$page.'">First name</a></th>
						<th><a href="'.site_url().'accounts/salary-data/personnel_phone/'.$order_method.'/'.$page.'">Phone</a></th>
						<th>Basic</th>
						<th>Benefits</th>
						<th>Allowances</th>
						<th>Gross</th>
						<th>PAYE</th>
						<th>NSSF</th>
						<th>NHIF</th>
						<th>Other deductions</th>
						<th>Net pay</th>
						<th colspan="2">Actions</th>
					</tr>
				</thead>
				<tbody>
			';
			
			foreach ($query->result() as $row)
			{
				$personnel_id = $row->personnel_id;
				$personnel_fname = $row->personnel_fname;
				$personnel_onames = $row->personnel_onames;
				$personnel_username = $row->personnel_username;
				$personnel_phone = $row->personnel_phone;
				$personnel_email = $row->personnel_email;
				$personnel_status = $row->personnel_status;
				$personnel_name = $personnel_fname.' '.$personnel_onames;
				
				//get salary details
		
				//payments
				$total_payments = 0;
				if($payments->num_rows() > 0)
				{
					foreach($payments->result() as $res)
					{
						$payment_id = $res->payment_id;
						$table = $this->payroll_model->get_table_id("payment");
						$table_id = $payment_id;
						
						$payment_amt = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
						$total_payments += $payment_amt;
					}
				}
				
				//benefits
				$total_benefits = 0;
				if($benefits->num_rows() > 0)
				{
					foreach($benefits->result() as $res)
					{
						$benefit_id = $res->benefit_id;
						$table = $this->payroll_model->get_table_id("benefit");
						$table_id = $benefit_id;
						
						$benefit_amt = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
						$total_benefits += $benefit_amt;
					}
				}
		
				//allowances
				$total_allowances = 0;
				if($allowances->num_rows() > 0)
				{
					foreach($allowances->result() as $res)
					{
						$allowance_id = $res->allowance_id;
						$table = $this->payroll_model->get_table_id("allowance");
						$table_id = $allowance_id;
						
						$allowance_amt = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
						$total_allowances += $allowance_amt;
					}
				}
				
				$gross = $total_payments + $total_allowances;
		
				//nssf
				$table = $this->payroll_model->get_table_id("nssf");
				$nssf = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
				
				//nhif
				$table = $this->payroll_model->get_table_id("nhif");
				$nhif = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
				
				//paye
				$table = $this->payroll_model->get_table_id("paye");
				$paye =$this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
				
				//relief
				$table = $this->payroll_model->get_table_id("relief");
				$relief = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
				
				//insurance_relief
				$table = $this->payroll_model->get_table_id("insurance_relief");
				$insurance_relief = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
				
				//insurance_amount
				$table = $this->payroll_model->get_table_id("insurance_amount");
				$insurance_amount = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, 1);
				//echo $insurance_relief;
				$paye -= ($relief + $insurance_relief);
				
				//deductions
				$total_deductions = 0;
				if($deductions->num_rows() > 0)
				{
					foreach($deductions->result() as $res)
					{
						$deduction_id = $res->deduction_id;
						
						$table_id = $deduction_id;
						$deduction_amt = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
						$total_deductions += $deduction_amt;
					}
				}
				
				//other_deductions
				$table = $this->payroll_model->get_table_id("other_deduction");
				$total_other_deductions = 0;
				if($other_deductions->num_rows() > 0)
				{
					foreach($other_deductions->result() as $res)
					{
						$other_deduction_id = $res->other_deduction_id;
						
						$table_id = $other_deduction_id;
						$other_deduction_amt = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
						$total_other_deductions += $other_deduction_amt;
					}
				}
				
				//savings
				$rs_savings = $this->payroll_model->get_savings();
				$total_savings = 0;
						
				$table = $this->payroll_model->get_table_id("savings");
				
				if($rs_savings->num_rows() > 0)
				{
					foreach($rs_savings->result() as $res)
					{
						$savings_name = $res->savings_name;
						$savings_id = $res->savings_id;
					
						//get schemes
						$total_savings += $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $savings_id);
					}
				}
				
				//get loan schemes
				$date = date("Y-m-d");
				$rs_schemes = $this->payroll_model->get_loan_schemes();
				$total_schemes = 0;
				$interest = 0;
				$table = $this->payroll_model->get_table_id("loan_scheme");
				
				if($rs_schemes->num_rows() > 0)
				{
					foreach($rs_schemes->result() as $res)
					{
						$loan_scheme_name = $res->loan_scheme_name;
						$loan_scheme_id = $res->loan_scheme_id;
						
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
					}
				}
				
				//total deductions
				$total_deductions = $total_deductions + $total_other_deductions + $total_schemes + $total_savings + $insurance_amount;
				
				//net
				$net = $gross - ($paye + $nssf + $nhif + $total_deductions);
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$personnel_onames.'</td>
						<td>'.$personnel_fname.'</td>
						<td>'.$personnel_phone.'</td>
						<td>'.number_format($total_payments, 2).'</td>
						<td>'.number_format($total_benefits, 2).'</td>
						<td>'.number_format($total_allowances, 2).'</td>
						<td>'.number_format($gross, 2).'</td>
						<td>'.number_format($paye, 2).'</td>
						<td>'.number_format($nssf, 2).'</td>
						<td>'.number_format($nhif, 2).'</td>
						<td>'.number_format($total_deductions, 2).'</td>
						<td>'.number_format($net, 2).'</td>
						<td><a href="'.site_url().'accounts/payroll/print-payslip/'.$personnel_id.'/'.$payroll_id.'" class="btn btn-sm btn-info" title="Print payslip for '.$personnel_name.'" target="_blank"><i class="fa fa-print"></i></td>
						<td><a href="'.site_url().'accounts/payroll/download-payslip/'.$personnel_id.'/'.$payroll_id.'" class="btn btn-sm btn-danger" title="Download payslip for '.$personnel_name.'" target="_blank"><i class="fa fa-file-pdf-o"></i></td>
					</tr> 
				';
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no personnel";
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
								?>
                            	
                            	<div class="row" style="margin-bottom:20px;">
                                    <div class="col-sm-2 col-sm-offset-10">
                                        <a href="<?php echo site_url();?>accounts/payroll" class="btn btn-sm btn-info">Back to payroll</a>
                                        
                                    </div>
                                </div>
                                
								<div class="table-responsive">
                                	
									<?php echo $result;?>
							
                                </div>
							</div>
                            <div class="panel-footer">
                            	<?php if(isset($links)){echo $links;}?>
                            </div>
						</section>