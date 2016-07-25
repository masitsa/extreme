<?php
			$total_gross = 0;
	$total_paye = 0;
	$total_nssf = 0;
	$total_nhif = 0;
	$total_life_ins = 0;
	$total_payments = 0;
	$total_savings = 0;
	$total_loans = 0;
	$total_net = 0;
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
						<th>Month</th>
						<th>Year</th>
						<th>Status</th>
						<th>Payments</th>
						<th>Benefits</th>
						<th>Allowances</th>
						<th>PAYE</th>
						<th>NSSF</th>
						<th>NHIF</th>
						<th>Deductions</th>
						<th>Net</th>
						<th colspan="6">Actions</th>
					</tr>
				</thead>
				<tbody>
				  
			';
			
			//get all administrators
			$administrators = $this->users_model->get_active_users();
			
			foreach ($query->result() as $row)
			{
				$payroll_id = $row->payroll_id;
				$payroll_year = $row->payroll_year;
				$month_name = $row->month_name;
				$created = date('jS M Y H:i a',strtotime($row->created));
				$created_by = $row->created_by;
				$payroll_status = $row->payroll_status;
				$payroll_name = $month_name.' '.$payroll_year;
				
				$where = 'personnel_status = 1 AND personnel_type_id = 1';
				$tables = $this->db->get('payroll, branch');
				$branches = $this->db->get('payroll, branch');
		
				if($branches->num_rows() > 0)
				{
					$row = $branches->result();
					$branch_id = $row[0]->branch_id;
					$branch_name = $row[0]->branch_name;
					$branch_image_name = $row[0]->branch_image_name;
					$branch_address = $row[0]->branch_address;
					$branch_post_code = $row[0]->branch_post_code;
					$branch_city = $row[0]->branch_city;
					$branch_phone = $row[0]->branch_phone;
					$branch_email = $row[0]->branch_email;
					$branch_location = $row[0]->branch_location;
					$where .= ' AND branch_id = '.$branch_id;
				}	

				$payroll = $this->payroll_model->get_payroll($payroll_id);
				$individual_query = $this->personnel_model->retrieve_payroll_personnel($where);	
				if ($individual_query->num_rows() > 0)
				{
					
					$total_payments = 0;
					$total_savings = 0;
					$total_loans = 0;
					$total_net = 0;
					
						$personnel_id = $this->session->userdata('personnel_id');
						$gross = 0;
						//basic
						$table = $this->payroll_model->get_table_id("basic_pay");
						$table_id = 0;
						$basic_pay = $this->payroll_model->get_payroll_amount($personnel_id, $payroll_id, $table, $table_id);
						//$total_basic_pay += $basic_pay;
						//$gross += $basic_pay;
						
						$total_payments = 0;
						
						//payments
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
							}
						}
						//benefits
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
							}
						}
						else
						{
							$total_benefits = 0;
						}
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
							}
						}
						else
						{
							$total_allowances = 0;
						}
						$gross = $total_allowances + $total_payments;
						$total_gross += $gross;
						
						/*
							--------------------------------------------------------------------------------------
							Select & display untaxable deductions for the personnel
							--------------------------------------------------------------------------------------
						*/
						
						//nssf
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
						//echo $insurance_relief;
						$paye -= ($relief + $insurance_relief);
										
						if($paye < 0)
						{
							$paye = 0;
						}
						$total_paye += $paye;
						$total_life_ins += $insurance_amount;
					
						
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
							}
						}
						else
						{
							$total_deductions = 0;
						}
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
							}
						}
						
						//savings
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
						
						//get loan schemes
						$date = date("Y-m-d");
						$rs_schemes = $this->payroll_model->get_loan_schemes();
						$total_schemes = 0;
						$interest = 0;
						
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
							}
						}
						$total_loans += ($total_schemes + $interest);
						
						//total deductions
						$total_deductions_2 = $total_deductions + $total_other_deductions + $total_schemes + $total_savings + $insurance_amount;
						
						//net
						$net = $gross - ($paye + $total_nssf + $total_nhif + $total_deductions);
						$total_net += $net;
						
						//get branch
						if($administrators->num_rows() > 0)
						{
							foreach($administrators->result() as $res)
							{
								$personnel_id = $res->personnel_id;
								if($personnel_id == $created_by)
								{
									$personnel_fname = $res->personnel_fname;
									$personnel_onames = $res->personnel_onames;
									$created_by = $personnel_onames.' '.$personnel_fname;
								}
							}
						}
						
						//create deactivated status display
						if($payroll_status == 0)
						{
							$status = '<span class="label label-default">Deactivated</span>';
							//$button = '<a class="btn btn-info" href="'.site_url().'accounts/activate-payroll/'.$payroll_id.'" onclick="return confirm(\'Do you want to activate '.$payroll_name.'?\');" title="Activate '.$payroll_name.'"><i class="fa fa-thumbs-up"></i></a>';
							$button = '';
						}
						//create activated status display
						else if($payroll_status == 1)
						{
							$status = '<span class="label label-success">Active</span>';
							$button = '<a class="btn btn-default" href="'.site_url().'accounts/deactivate-payroll/'.$payroll_id.'" onclick="return confirm(\'Do you want to deactivate '.$payroll_name.'?\');" title="Deactivate '.$payroll_name.'"><i class="fa fa-thumbs-down"></i></a>';
						}
						
						$count++;
						$result .= 
						'
							<tr>
								<td>'.$count.'</td>
								<td>'.$month_name.'</td>
								<td>'.$payroll_year.'</td>
								<td>'.$status.'</td>
								<td>'.number_format($total_payments, 2).'</td>
								<td>'.number_format($total_benefits, 2).'</td>
								<td>'.number_format($total_allowances, 2).'</td>
								<td>'.number_format($paye, 2).'</td>
								<td>'.number_format($total_nssf, 2).'</td>
								<td>'.number_format($total_nhif, 2).'</td>
								<td>'.number_format($total_deductions_2, 2).'</td>
								<td>'.number_format($net, 2).'</td>
								<td><a href="'.site_url().'print-payslip/'.$payroll_id.'" class="btn btn-sm btn-primary" title="Print '.$payroll_name.'" target="_blank">Payslip</a></td>
							</tr> 
						';
					
				}
			}
			
			$result .= 
			'
						  </tbody>
						</table>
			';
		}
		
		else
		{
			$result .= "There are no payrolls";
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
    
		<div class="table-responsive">
        	
			<?php echo $result;?>
	
        </div>
	</div>
    <div class="panel-footer">
    	<?php if(isset($links)){echo $links;}?>
    </div>
</section>