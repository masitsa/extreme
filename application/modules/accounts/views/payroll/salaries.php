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
						<th><a href="'.site_url().'accounts/salaries/personnel_onames/'.$order_method.'/'.$page.'">Other names</a></th>
						<th><a href="'.site_url().'accounts/salaries/personnel_fname/'.$order_method.'/'.$page.'">First name</a></th>
						<th><a href="'.site_url().'accounts/salaries/personnel_phone/'.$order_method.'/'.$page.'">Phone</a></th>
						<th><a href="'.site_url().'accounts/salaries/basic_pay/'.$order_method.'/'.$page.'">Basic</a></th>
						<th>Allowances</th>
						<th>Gross</th>
						<th>NSSF</th>
						<th>Insurance</th>
						<th>Pension</th>
						<th>Taxable</th>
						<th>PAYE</th>
						<th>Deductions</th>
						<th>Net pay</th>
						<th colspan="1">Actions</th>
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
				$basic_pay = $row->basic_pay;
				$personnel_name = $personnel_fname.' '.$personnel_onames;
				
				$allowances = $this->payroll_model->allowances_view($personnel_id);
				$gross = $basic_pay + $allowances;
				
				$nssf = $this->payroll_model->nssf_view($personnel_id);
				$insurance = $this->payroll_model->insurance_view($personnel_id);
				$pension = $this->payroll_model->pension_view($personnel_id);
				$taxable = $this->payroll_model->taxable_view($gross, $nssf, $insurance, $pension);
				$paye = $this->payroll_model->paye_view($taxable);
				$deductions = $this->payroll_model->deductions_view($personnel_id);
				$net = $this->payroll_model->net_view($gross, $paye, $deductions);
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$personnel_onames.'</td>
						<td>'.$personnel_fname.'</td>
						<td>'.$personnel_phone.'</td>
						<td>'.$basic_pay.'</td>
						<td>'.$allowances.'</td>
						<td>'.$gross.'</td>
						<td>'.$nssf.'</td>
						<td>'.$insurance.'</td>
						<td>'.$pension.'</td>
						<td>'.$taxable.'</td>
						<td>'.$paye.'</td>
						<td>'.$deductions.'</td>
						<td>'.$net.'</td>
						<td><a href="'.site_url().'accounts/payment-details/'.$personnel_id.'" class="btn btn-sm btn-success" title="Edit '.$personnel_name.'"><i class="fa fa-pencil"></i></a></td>
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
						<div class="row">
                        	<div class="col-sm-6">
                            	<section class="panel">
                                    <header class="panel-heading">						
                                        <h2 class="panel-title">Print payroll</h2>
                                    </header>
                                    <div class="panel-body">
                                    	<?php 
										$attributes = array('target' => '_blank');
										echo form_open('accounts/print-payroll', $attributes);
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
                                                        <i class='fa fa-print'></i> Print
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <?php echo form_close();?>
                                    </div>
                                </section>
                            </div>
                            
                        	<div class="col-sm-6">
                            	<section class="panel">
                                    <header class="panel-heading">						
                                        <h2 class="panel-title">Print payslips</h2>
                                    </header>
                                    <div class="panel-body">
                                    	<?php 
										$attributes = array('target' => '_blank');
										echo form_open('accounts/print-payslips', $attributes);
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
                                                        <i class='fa fa-print'></i> Print
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
                            	<!--<div class="row" style="margin-bottom:20px;">
                                    <div class="col-lg-2 col-lg-offset-8">
                                        <a href="<?php echo site_url();?>human-resource/export-personnel" class="btn btn-sm btn-success pull-right">Export</a>
                                    </div>
                                </div>-->
                                
								<div class="table-responsive">
                                	
									<?php echo $result;?>
							
                                </div>
							</div>
                            <div class="panel-footer">
                            	<?php if(isset($links)){echo $links;}?>
                            </div>
						</section>