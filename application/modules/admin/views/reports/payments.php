<?php			
		//if users exist display them
		if ($users->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			'
				<table class="table table-hover table-bordered ">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Registration Date</th>
					  <th>First Name</th>
					  <th>Other Name</th>
					  <th>Type</th>
					  <th>Last Login</th>
					  <th>Status</th>
					  <th>Payment Status</th>
					  <th colspan="5">Actions</th>
					</tr>
				  </thead>
				  <tbody>
			';
			
			foreach ($users->result() as $row)
			{
				$user_id = $row->user_id;
				$created = $row->registration_date;
				$fname = $row->user_fname;
				$oname = $row->user_oname;
				$type = $row->user_type_name;
				$last_login = $row->last_login;
				$status = $row->user_status_id;
				$payment_status = $row->activation_status;
				
				if($last_login == NULL)
				{
					$last_login = ' - ';
				}
				
				else
				{
					$last_login = date('jS M Y H:i a',strtotime($last_login));
				}
				
				if($created == '0000-00-00 00:00:00')
				{
					$created = ' - ';
				}
				
				//get paid grades
				$grades = $this->reports_model->get_user_paid_grades($user_id);
				$user_grades = '';
				
				if($grades->num_rows() > 0)
				{
					$grade_result = $grades->result();
					
					foreach($grade_result as $gr)
					{
						$grade_name = $gr->grade_name;
						$subscription_date = $gr->created;
						$payment_date = $gr->payment_date;
						$payment_amount = $gr->payment_amount;
						
						$user_grades.=
						'
							<tr>
								<td>'.$grade_name.'</td>
								<td>'.$subscription_date.'</td>
								<td>'.$payment_date.'</td>
								<td>'.$payment_amount.'</td>
							</tr>
						';
					}
				}
				
				else
				{
					//get paid grades
					$grades = $this->reports_model->get_user_unpaid_grades($user_id);
					$user_grades = '';
					
					if($grades->num_rows() > 0)
					{
						$grade_result = $grades->result();
						
						foreach($grade_result as $gr)
						{
							$grade_name = $gr->grade_name;
							$subscription_date = $gr->created;
							
							$user_grades.=
							'
								<tr>
									<td>'.$grade_name.'</td>
									<td>'.$subscription_date.'</td>
									<td></td>
									<td></td>
								</tr>
							';
						}
					}
					
					else
					{
							
						$user_grades.=
						'
							<tr colspan="4">
								<td>This user has not subscribed to any grades</td>
							</tr>
						';
					}
				}
				
				//create deactivated status display
				if($status == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'activate-user/'.$user_id.'" onclick="return confirm(\'Do you want to activate '.$fname.'?\');">Activate</a>';
				}
				//create activated status display
				else if($status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'deactivate-user/'.$user_id.'" onclick="return confirm(\'Do you want to deactivate '.$fname.'?\');">Deactivate</a>';
				}
				
				//create deactivated status display
				if($payment_status == 0)
				{
					$payment_status = '<span class="label label-important">Not Paid</span>';
				}
				//create activated status display
				else if($payment_status == 1)
				{
					$payment_status = '<span class="label label-success">Paid</span>';
				}
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.date('jS M Y H:i a',strtotime($created)).'</td>
						<td>'.$fname.'</td>
						<td>'.$oname.'</td>
						<td>'.$type.'</td>
						<td>'.$last_login.'</td>
						<td>'.$status.'</td>
						<td>'.$payment_status.'</td>
						<td>
							
							<!-- Button to trigger modal -->
							<a href="#user'.$user_id.'" class="btn btn-primary" data-toggle="modal">View</a>
							
							<!-- Modal -->
							<div id="user'.$user_id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">'.$fname.' '.$oname.'</h4>
										</div>
										
										<div class="modal-body">
											<table class="table table-stripped table-condensed table-hover">
												'.$user_grades.'
											</table>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
											<a href="'.site_url().'edit-user/'.$user_id.'" class="btn btn-sm btn-success">Edit</a>
											'.$button.'
											<a href="'.site_url().'reset-user-password/'.$user_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you want to reset '.$fname.'\'s password?\');">Reset Password</a>
											<a href="'.site_url().'delete-user/'.$user_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$fname.'?\');">Delete</a>
										</div>
									</div>
								</div>
							</div>
						
						</td>
						<td><a href="'.site_url().'edit-user/'.$user_id.'" class="btn btn-sm btn-success">Edit</a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'reset-user-password/'.$user_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you want to reset '.$fname.'\'s password?\');">Reset Password</a></td>
						<td><a href="'.site_url().'delete-user/'.$user_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$fname.'?\');">Delete</a></td>
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
			$result .= "There are no users";
		}
		
		echo $result;
?>