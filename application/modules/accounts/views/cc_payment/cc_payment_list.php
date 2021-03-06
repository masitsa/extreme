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
						<!--<th><a href="'.site_url().'accounts/cc-payment/branch.branch_name/'.$order_method.'/'.$page.'">Branch</a></th>-->
						<th><a href="'.site_url().'accounts/cc-payment/cc-payment.month_id/'.$order_method.'/'.$page.'">Month</a></th>
						<th><a href="'.site_url().'accounts/cc-payment/cc-payment.cc-payment_year/'.$order_method.'/'.$page.'">Year</a></th>
						<th><a href="'.site_url().'accounts/cc-payment/cc-payment.created/'.$order_method.'/'.$page.'">Created</a></th>
						<th><a href="'.site_url().'accounts/cc-payment/cc-payment.created_by/'.$order_method.'/'.$page.'">Created by</a></th>
						<th><a href="'.site_url().'accounts/cc-payment/cc-payment.cc-payment_status/'.$order_method.'/'.$page.'">Status</a></th>
						<!--<th>Payments</th>
						<th>Benefits</th>
						<th>Allowances</th>
						<th>PAYE</th>
						<th>NSSF</th>
						<th>NHIF</th>
						<th>Deductions</th>
						<th>Net</th>-->
						<th colspan="9">Reports</th>
					</tr>
				</thead>
				<tbody>
				  
			';
			
			//get all administrators
			$administrators = $this->users_model->get_active_users();
			
			foreach ($query->result() as $row)
			{
				$branch_id = $row->branch_id;
				//$branch = $row->branch_name;
				$cc_payment_id = $row->cc_payment_id;
				$cc_payment_year = $row->cc_payment_year;
				$month_name = $row->month_name;
				$created = date('jS M Y H:i a',strtotime($row->created));
				$created_by = $row->created_by;
				$cc_payment_status = $row->cc_payment_status;
				$cc_payment_name = $month_name.' '.$cc_payment_year;
				
				//payments
				/*$table = $this->cc_payment_model->get_table_id("payment");
				$payment = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//benefits
				$table = $this->cc_payment_model->get_table_id("benefit");
				$benefit = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//allowance
				$table = $this->cc_payment_model->get_table_id("allowance");
				$allowance = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//paye
				$table = $this->cc_payment_model->get_table_id("paye");
				$paye = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//paye
				$table = $this->cc_payment_model->get_table_id("paye");
				$paye = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//relief
				$table = $this->cc_payment_model->get_table_id("relief");
				$relief = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
		
				//insurance_relief
				$table = $this->cc_payment_model->get_table_id("insurance_relief");
				$insurance_relief = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//relief
				$table = $this->cc_payment_model->get_table_id("insurance_amount");
				$insurance_amount = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				$paye -= ($relief + $insurance_relief);
				
				//nssf
				$table = $this->cc_payment_model->get_table_id("nssf");
				$nssf = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//nhif
				$table = $this->cc_payment_model->get_table_id("nhif");
				$nhif = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//deduction
				$table = $this->cc_payment_model->get_table_id("deduction");
				$deduction = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//other_deduction
				$table = $this->cc_payment_model->get_table_id("other_deduction");
				$other_deduction = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//savings
				$table = $this->cc_payment_model->get_table_id("savings");
				$savings = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				//loan_scheme
				$table = $this->cc_payment_model->get_table_id("loan_scheme");
				$loan_scheme = $this->cc_payment_model->get_cc_payment_amount2($cc_payment_id, $table);
				
				$deductions = $deduction + $other_deduction + $loan_scheme + $savings;
				$total_deductions = $deductions + $nssf + $nhif + $paye + $insurance_amount;
				$gross = $payment + $allowance;
				
				$net = $gross - $total_deductions;*/
				
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
				if($cc_payment_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					//$button = '<a class="btn btn-info" href="'.site_url().'accounts/activate-cc-payment/'.$cc_payment_id.'" onclick="return confirm(\'Do you want to activate '.$cc_payment_name.'?\');" title="Activate '.$cc_payment_name.'"><i class="fa fa-thumbs-up"></i></a>';
					$button = '';
				}
				//create activated status display
				else if($cc_payment_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'accounts/deactivate-cc-payment/'.$cc_payment_id.'" onclick="return confirm(\'Do you want to deactivate '.$cc_payment_name.'?\');" title="Deactivate '.$cc_payment_name.'"><i class="fa fa-thumbs-down"></i></a>';
				}
				
				$count++;
				/*$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$month_name.'</td>
						<td>'.$cc_payment_year.'</td>
						<td>'.$created.'</td>
						<td>'.$created_by.'</td>
						<td>'.$status.'</td>
						<td>'.number_format($payment, 2).'</td>
						<td>'.number_format($benefit, 2).'</td>
						<td>'.number_format($allowance, 2).'</td>
						<td>'.number_format($paye, 2).'</td>
						<td>'.number_format($nssf, 2).'</td>
						<td>'.number_format($nhif, 2).'</td>
						<td>'.number_format($deductions, 2).'</td>
						<td>'.number_format($net, 2).'</td>
						<td><a href="'.site_url().'accounts/print-month-payslips/'.$cc_payment_id.'" class="btn btn-sm btn-primary" title="Print '.$cc_payment_name.'" target="_blank">Payslips</a></td>

						<td><a href="'.site_url().'accounts/print-cc-payment/'.$cc_payment_id.'" class="btn btn-sm btn-success" title="Print '.$cc_payment_name.'" target="_blank"><i class="fa fa-print"></i></a></td>

						<td>'.$button.'</td>
					</tr> 
				';*/
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$month_name.'</td>
						<td>'.$cc_payment_year.'</td>
						<td>'.$created.'</td>
						<td>'.$created_by.'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'accounts/print-cc-paye-report/'.$cc_payment_id.'" class="btn btn-sm btn-danger" title="Print '.$cc_payment_name.' PAYE report" target="_blank">PAYE</a></td>
						<td><a href="'.site_url().'accounts/print-cc-nhif-report/'.$cc_payment_id.'" class="btn btn-sm btn-info" title="Print '.$cc_payment_name.' NHIF report" target="_blank">NHIF</a></td>
						<td><a href="'.site_url().'accounts/print-cc-nssf-report/'.$cc_payment_id.'" class="btn btn-sm btn-warning" title="Print '.$cc_payment_name.' NSSF report" target="_blank">NSSF</a></td>
						<td><a href="'.site_url().'accounts/print-cc-payment/'.$cc_payment_id.'" class="btn btn-sm btn-success" title="Print '.$cc_payment_name.'" target="_blank">Payroll</a></td>
						<td><a href="'.site_url().'accounts/print-month-payslips/'.$cc_payment_id.'" class="btn btn-sm btn-primary" title="Print '.$cc_payment_name.'" target="_blank">Payslips</a></td>
						<td><a href="'.site_url().'accounts/cc_payment/generate_bank_report/'.$cc_payment_id.'" class="btn btn-sm btn-default" title="Generate Bank '.$cc_payment_name.'" target="_blank">Bank</a></td>						
						<td><a href="'.site_url().'accounts/print-cc-month-summary/'.$cc_payment_id.'/'.$branch_id.'" class="btn btn-sm btn-primary" title="Summary '.$cc_payment_name.'" target="_blank">Summary</a></td>
						<td><a href="'.site_url().'accounts/send-cc-month-payslips/'.$cc_payment_id.'" class="btn btn-sm btn-success" title="Send Payslips for '.$cc_payment_name.'">Send Payslips</a></td>

						<td>'.$button.'</td>
						<td>'.$button.'</td>
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
			$result .= "There are no cc_payments";
		}
?>
						<div class="row">
                        	<div class="col-sm-4">
                            	<section class="panel">
                                    <header class="panel-heading">						
                                        <h2 class="panel-title">Search cc_payment</h2>
                                    </header>
                                    <div class="panel-body">
                                    	<?php 
										echo form_open('accounts/search-cc-payment');
										?>
                                        <div class="form-group">
                                            <label class="col-lg-5 control-label">Year: </label>
                                            
                                            <div class="col-lg-7">
                                                <input type="text" name="year" class="form-control" size="54" value="<?php echo date("Y");?>" />
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-5 control-label">Month: </label>
                                            
                                            <div class="col-lg-7">
                                                <select name="month" class="form-control">
                                                    <?php
                                                        if($month->num_rows() > 0){
                                                            foreach ($month->result() as $row):
                                                                $mth = $row->month_name;
                                                                $mth_id = $row->month_id;
                                                                if($mth == date("M")){
                                                                    echo "<option value=".$mth_id." selected>".$row->month_name."</option>";
                                                                }
                                                                else{
                                                                    echo "<option value=".$mth_id.">".$row->month_name."</option>";
                                                                }
                                                            endforeach;
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row" style="margin-top:10px;">
                                            <div class="col-lg-7 col-lg-offset-5">
                                                <div class="form-actions center-align">
                                                    <button class="submit btn btn-primary" type="submit">
                                                        <i class='fa fa-search'></i> Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close();?>
                                    </div>
                                </section>
                            </div>
                            
                        	<div class="col-sm-4">
                            	<section class="panel">
                                    <header class="panel-heading">						
                                        <h2 class="panel-title">Create cc_payment</h2>
                                    </header>
                                    <div class="panel-body">
                                    	<?php 
										echo form_open('accounts/create-cc-payment');
										?>
                                        <div class="form-group">
                                            <label class="col-lg-5 control-label">Year: </label>
                                            
                                            <div class="col-lg-7">
                                                <input type="text" name="year" class="form-control" size="54" value="<?php echo date("Y");?>" />
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="col-lg-5 control-label">Month: </label>
                                            
                                            <div class="col-lg-7">
                                                <select name="month" class="form-control">
                                                    <?php
                                                        if($month->num_rows() > 0){
                                                            foreach ($month->result() as $row):
                                                                $mth = $row->month_name;
                                                                $mth_id = $row->month_id;
                                                                if($mth == date("M")){
                                                                    echo "<option value=".$mth_id." selected>".$row->month_name."</option>";
                                                                }
                                                                else{
                                                                    echo "<option value=".$mth_id.">".$row->month_name."</option>";
                                                                }
                                                            endforeach;
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row" style="margin-top:10px;">
                                            <div class="col-lg-7 col-lg-offset-5">
                                                <div class="form-actions center-align">
                                                    <button class="submit btn btn-primary" type="submit">
                                                        Create
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close();?>
                                    </div>
                                </section>
                            </div><div class="col-sm-4">
                            	<section class="panel">
                                    <header class="panel-heading">						
                                        <h2 class="panel-title">Change branch</h2>
                                    </header>
                                    <div class="panel-body">
                                    	<?php 
										$attributes = array('class' => 'form-horizontal');
										echo form_open('accounts/change-branch', $attributes);
										?>
                                        <div class="form-group">
                                            <label class="col-lg-5 control-label">Branch: </label>
                                            
                                            <div class="col-lg-7">
                                                <select name="branch_id" class="form-control">
                                                    <?php
                                                        if($branches->num_rows() > 0)
														{
                                                            foreach ($branches->result() as $row):
                                                                $branch_name = $row->branch_name;
                                                                $branch_id = $row->branch_id;
                                                                echo "<option value=".$branch_id.">".$branch_name."</option>";
                                                            endforeach;
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="row" style="margin-top:10px;">
                                            <div class="col-lg-7 col-lg-offset-5">
                                                <div class="form-actions center-align">
                                                    <button class="submit btn btn-primary" type="submit">
                                                        Change
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close();?>
                                    </div>
                                </section>
                            </div>
                        </div>
                        
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
                                    <div class="col-sm-4 col-sm-offset-8">
                                       <!-- <a href="<?php echo site_url();?>accounts/export-cc_payment" class="btn btn-sm btn-success">Export</a>-->
                                        <a href="<?php echo site_url();?>accounts/salary-data" class="btn btn-sm btn-info pull-right">Edit personnel payment data</a>
                                        <?php
										$search = $this->session->userdata('cc_payment_search');
		
										if(!empty($search))
										{
											?>
                                            <a href="<?php echo site_url();?>accounts/close-cc-payment-search" class="btn btn-sm btn-warning">Close search</a>
                                            <?php
										}
                                        
										?>
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