<?php
		
		$result = '<a href="'.site_url().'add-post" class="btn btn-success pull-right">Add Post</a>';
            
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
		
		//if users exist display them
		if ($query->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			'
				<table class="table table-hover table-bordered table-responsive">
				  <thead>
					<tr>
					  <th>#</th>
					  <th>Image</th>
					  <th>Category</th>
					  <th>Post Title</th>
					  <th>Date Created</th>
					  <th>Views</th>
					  <th>Comments</th>
					  <th>Status</th>
					  <th colspan="5">Actions</th>
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
				$post_id = $row->post_id;
				$blog_category_name = $row->blog_category_name;
				$post_title = $row->post_title;
				$post_status = $row->post_status;
				$post_views = $row->post_views;
				$image = $row->post_image;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$comments = $this->users_model->count_items('post_comment', 'post_id = '.$post_id);
				
				//status
				if($post_status == 1)
				{
					$status = 'Active';
				}
				else
				{
					$status = 'Disabled';
				}
				
				//create deactivated status display
				if($post_status == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'activate-post/'.$post_id.'" onclick="return confirm(\'Do you want to activate '.$post_title.'?\');">Activate</a>';
				}
				//create activated status display
				else if($post_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'deactivate-post/'.$post_id.'" onclick="return confirm(\'Do you want to deactivate '.$post_title.'?\');">Deactivate</a>';
				}
				
				//creators & editors
				if($admins != NULL)
				{
					foreach($admins as $adm)
					{
						$user_id = $adm->user_id;
						
						if($user_id == $created_by)
						{
							$created_by = $adm->first_name;
						}
						
						if($user_id == $modified_by)
						{
							$modified_by = $adm->first_name;
						}
					}
				}
				
				else
				{
				}
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td><img src="'.base_url()."assets/images/posts/thumbnail_".$image.'"></td>
						<td>'.$blog_category_name.'</td>
						<td>'.$post_title.'</td>
						<td>'.date('jS M Y',strtotime($row->created)).'</td>
						<td>'.$post_views.'</td>
						<td>'.$comments.'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'edit-post/'.$post_id.'" class="btn btn-sm btn-success">Edit</a></td>
						<td><a href="'.site_url().'comments/'.$post_id.'" class="btn btn-sm btn-warning">Comments</a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'delete-post/'.$post_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$post_title.'? This will also delete all comments associated with this post\');">Delete</a></td>
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
			$result .= "There are no posts";
		}
		
		echo $result;
?>