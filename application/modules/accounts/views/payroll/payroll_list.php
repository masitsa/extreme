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
						<th><a href="'.site_url().'accounts/payroll/month_id/'.$order_method.'/'.$page.'">Month</a></th>
						<th><a href="'.site_url().'accounts/payroll/payroll_year/'.$order_method.'/'.$page.'">Year</a></th>
						<th><a href="'.site_url().'accounts/payroll/created/'.$order_method.'/'.$page.'">Created</a></th>
						<th><a href="'.site_url().'accounts/payroll/created_by/'.$order_method.'/'.$page.'">Created by</a></th>
						<th><a href="'.site_url().'accounts/payroll/payroll_status/'.$order_method.'/'.$page.'">Status</a></th>
						<th>Payments</th>
						<th>Benefits</th>
						<th>Allowances</th>
						<th>PAYE</th>
						<th>NSSF</th>
						<th>NHIF</th>
						<th>Deductions</th>
						<th>Net</th>
						<th colspan="5">Actions</th>
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
				
				//payments
				$table = $this->payroll_model->get_table_id("payment");
				$payment = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//benefits
				$table = $this->payroll_model->get_table_id("benefit");
				$benefit = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//allowance
				$table = $this->payroll_model->get_table_id("allowance");
				$allowance = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//paye
				$table = $this->payroll_model->get_table_id("paye");
				$paye = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//paye
				$table = $this->payroll_model->get_table_id("paye");
				$paye = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//relief
				$table = $this->payroll_model->get_table_id("relief");
				$relief = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
		
				//insurance_relief
				$table = $this->payroll_model->get_table_id("insurance_relief");
				$insurance_relief = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//relief
				$table = $this->payroll_model->get_table_id("insurance_amount");
				$insurance_amount = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				$paye -= ($relief + $insurance_relief);
				
				//nssf
				$table = $this->payroll_model->get_table_id("nssf");
				$nssf = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//nhif
				$table = $this->payroll_model->get_table_id("nhif");
				$nhif = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//deduction
				$table = $this->payroll_model->get_table_id("deduction");
				$deduction = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//other_deduction
				$table = $this->payroll_model->get_table_id("other_deduction");
				$other_deduction = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//savings
				$table = $this->payroll_model->get_table_id("savings");
				$savings = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				//loan_scheme
				$table = $this->payroll_model->get_table_id("loan_scheme");
				$loan_scheme = $this->payroll_model->get_payroll_amount2($payroll_id, $table);
				
				$deductions = $deduction + $other_deduction + $loan_scheme + $savings;
				$total_deductions = $deductions + $nssf + $nhif + $paye + $insurance_amount;
				$gross = $payment + $allowance;
				
				$net = $gross - $total_deductions;
				
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
						<td><a href="'.site_url().'accounts/print-payroll/'.$payroll_id.'" class="btn btn-sm btn-success" title="Print '.$payroll_name.'" target="_blank"><i class="fa fa-print"></i></a></td>
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
			$result .= "There are no payrolls";
		}
?>
						<div class="row">
                        	<div class="col-sm-4">
                            	<section class="panel">
                                    <header class="panel-heading">						
                                        <h2 class="panel-title">Search payroll</h2>
                                    </header>
                                    <div class="panel-body">
                                    	<?php 
										echo form_open('accounts/search-payroll');
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
                                        <h2 class="panel-title">Create payroll</h2>
                                    </header>
                                    <div class="panel-body">
                                    	<?php 
										echo form_open('accounts/create-payroll');
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
                                       <!-- <a href="<?php echo site_url();?>accounts/export-payroll" class="btn btn-sm btn-success">Export</a>-->
                                        <a href="<?php echo site_url();?>accounts/salary-data" class="btn btn-sm btn-info pull-right">Edit personnel payment data</a>
                                        <?php
										$search = $this->session->userdata('payroll_search');
		
										if(!empty($search))
										{
											?>
                                            <a href="<?php echo site_url();?>accounts/close-payroll-search" class="btn btn-sm btn-warning">Close search</a>
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