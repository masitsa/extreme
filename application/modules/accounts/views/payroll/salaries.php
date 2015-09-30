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
						<th>Allowances</th>
						<th>Gross</th>
						<th>PAYE</th>
						<th>NSSF</th>
						<th>NHIF</th>
						<th>Deductions</th>
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
				$payments = $this->payroll_model->payments_view($personnel_id);
				$benefits = $this->payroll_model->benefits_view($personnel_id);
				$allowances = $this->payroll_model->allowances_view($personnel_id);
				$deductions = $this->payroll_model->deductions_view($personnel_id);
				$other_deductions = $this->payroll_model->other_deductions_view($personnel_id);
				$savings = $this->payroll_model->savings_view($personnel_id);
				$loan_schemes = $this->payroll_model->scheme_view($personnel_id);
                $monthly_relief = $this->payroll_model->get_monthly_relief();
				$insurance_res = $this->payroll_model->get_insurance_relief($personnel_id);
				$insurance_relief = $insurance_res['relief'];
				$insurance_amount = $insurance_res['amount'];
				
				$taxable = $payments + $benefits + $allowances;
				$gross = ($payments + $allowances);
				$paye = $this->payroll_model->calculate_paye($taxable);
				$paye = $paye - ($insurance_relief + $monthly_relief);
				$nssf = $this->payroll_model->nssf_view($gross);
				$nhif = $this->payroll_model->nhif_view($gross);
				$total_deductions = $nssf + $nhif + $deductions + $other_deductions + $paye + $savings + $loan_schemes;
				$net = $gross - $total_deductions;
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$personnel_onames.'</td>
						<td>'.$personnel_fname.'</td>
						<td>'.$personnel_phone.'</td>
						<td>'.number_format($payments, 2).'</td>
						<td>'.number_format($allowances, 2).'</td>
						<td>'.number_format($gross, 2).'</td>
						<td>'.number_format($paye, 2).'</td>
						<td>'.number_format($nssf, 2).'</td>
						<td>'.number_format($nhif, 2).'</td>
						<td>'.number_format($total_deductions, 2).'</td>
						<td>'.number_format($net, 2).'</td>
						<td><a href="'.site_url().'accounts/payment-details/'.$personnel_id.'" class="btn btn-sm btn-success" title="Edit '.$personnel_name.'"><i class="fa fa-pencil"></i></a></td>
						<td><a href="'.site_url().'accounts/payroll/view-payslip/'.$personnel_id.'" class="btn btn-sm btn-info" title="Payslip for '.$personnel_name.'" target="_blank">Payslip</td>
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
                                        <a href="<?php echo site_url();?>accounts/payroll" class="btn btn-sm btn-warning pull-right">Back to payroll</a>
                                        
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