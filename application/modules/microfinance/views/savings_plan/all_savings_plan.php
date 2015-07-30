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
						<th><a href="'.site_url().'microfinance/savings_plan/savings_plan_name/'.$order_method.'/'.$page.'">Name</a></th>
						<th><a href="'.site_url().'microfinance/savings_plan/savings_plan_min_opening_balance/'.$order_method.'/'.$page.'">Min opening balance</a></th>
						<th><a href="'.site_url().'microfinance/savings_plan/savings_plan_min_account_balance/'.$order_method.'/'.$page.'">Min account balance</a></th>
						<th><a href="'.site_url().'microfinance/savings_plan/compounding_period_id/'.$order_method.'/'.$page.'">Compounded</a></th>
						<th><a href="'.site_url().'microfinance/savings_plan/charge_withdrawal/'.$order_method.'/'.$page.'">Charge withdrawal</a></th>
						<th><a href="'.site_url().'microfinance/savings_plan/savings_plan_status/'.$order_method.'/'.$page.'">Status</a></th>
						<th colspan="5">Actions</th>
					</tr>
				</thead>
				  <tbody>
				  
			';
			
			//get all administrators
			$administrators = $this->users_model->get_active_users();
			if ($administrators->num_rows() > 0)
			{
				$admins = $administrators->result();
			}
			
			else
			{
				$admins = NULL;
			}
			
			foreach ($query->result() as $row)
			{
				$savings_plan_id = $row->savings_plan_id;
				$savings_plan_name = $row->savings_plan_name;
				$savings_plan_min_opening_balance = number_format($row->savings_plan_min_opening_balance, 0);
				$savings_plan_min_account_balance = number_format($row->savings_plan_min_account_balance, 0);
				$charge_withdrawal = $row->charge_withdrawal;
				$compounding_period_name = $row->compounding_period_name;
				$savings_plan_status = $row->savings_plan_status;
				
				//status
				if($charge_withdrawal == 1)
				{
					$charge_withdrawal = 'Yes';
				}
				else
				{
					$charge_withdrawal = 'No';
				}
				
				//create deactivated status display
				if($savings_plan_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'microfinance/activate-savings-plan/'.$savings_plan_id.'" onclick="return confirm(\'Do you want to activate '.$savings_plan_name.'?\');" title="Activate '.$savings_plan_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($savings_plan_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'microfinance/deactivate-savings-plan/'.$savings_plan_id.'" onclick="return confirm(\'Do you want to deactivate '.$savings_plan_name.'?\');" title="Deactivate '.$savings_plan_name.'"><i class="fa fa-thumbs-down"></i></a>';
				}
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$savings_plan_name.'</td>
						<td>'.$savings_plan_min_opening_balance.'</td>
						<td>'.$savings_plan_min_account_balance.'</td>
						<td>'.$compounding_period_name.'</td>
						<td>'.$charge_withdrawal.'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'microfinance/edit-savings_plan/'.$savings_plan_id.'" class="btn btn-sm btn-success" title="Edit '.$savings_plan_name.'"><i class="fa fa-pencil"></i></a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'microfinance/delete-savings_plan/'.$savings_plan_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$savings_plan_name.'?\');" title="Delete '.$savings_plan_name.'"><i class="fa fa-trash"></i></a></td>
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
			$result .= "There are no savings plans";
		}
?>

						<savings_plan class="panel">
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
                                    <div class="col-lg-2 col-lg-offset-8">
                                        <a href="<?php echo site_url();?>microfinance/export-savings-plan" class="btn btn-sm btn-success pull-right">Export</a>
                                    </div>
                                    <div class="col-lg-2">
                                    	<a href="<?php echo site_url();?>microfinance/add-savings-plan" class="btn btn-sm btn-info pull-right">Add savings plan</a>
                                    </div>
                                </div>
								<div class="table-responsive">
                                	
									<?php echo $result;?>
							
                                </div>
							</div>
                            <div class="panel-footer">
                            	<?php if(isset($links)){echo $links;}?>
                            </div>
						</savings_plan>