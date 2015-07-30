<?php
		
		$result = '<a href="'.site_url().'add-user_type" class="btn btn-success pull-right">Add User Type</a>';
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			'
				<table class="table table-hover table-bordered ">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>User Type</th>
					  <th>Monthly Cost ($)</th>
					  <th>Users</th>
					  <th>Last Modified</th>
					  <th>Modified By</th>
					  <th>Status</th>
					  <th colspan="3">Actions</th>
					</tr>
				  </thead>
				  <tbody>
			';
			
			//get all administrators
			$administrators = $this->users_model->get_all_administrators();
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
				$user_type_id = $row->user_type_id;
				$user_type_name = $row->user_type_name;
				$cost = number_format($row->user_type_cost, 2, '.', ',');
				$user_type_status = $row->user_type_status;
				$status = $row->user_status_name;
				$modified = $row->modified;
				$modified_by = $row->modified_by;
				
				$users = $this->users_model->count_items('user', 'user_type_id = '.$user_type_id);
				
				//creators & editors
				if($admins != NULL)
				{
					foreach($admins as $adm)
					{
						$user_id = $adm->user_id;
						
						if($user_id == $modified_by)
						{
							$modified_by = $adm->user_fname;
						}
					}
				}
				
				//create deactivated status display
				if($user_type_status == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'activate-user_type/'.$user_type_id.'" onclick="return confirm(\'Do you want to activate '.$user_type_name.'?\');">Activate</a>';
				}
				//create activated status display
				else if($user_type_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'deactivate-user_type/'.$user_type_id.'" onclick="return confirm(\'Do you want to deactivate '.$user_type_name.'?\');">Deactivate</a>';
				}
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$user_type_name.'</td>
						<td>'.$cost.'</td>
						<td>'.$users.'</td>
						<td>'.date('jS M Y H:i a',strtotime($modified)).'</td>
						<td>'.$modified_by.'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'edit-user_type/'.$user_type_id.'" class="btn btn-sm btn-success">Edit</a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'delete-user_type/'.$user_type_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$user_type_name.'?\');">Delete</a></td>
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
			$result .= "There are no user_types";
		}
		
		echo $result;
?>