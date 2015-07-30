<?php
		if($post_id != NULL)
		{
			$result = '<a href="'.site_url().'add-comment/'.$post_id.'" class="btn btn-success pull-right">Add Comment</a> <p><strong>Post Title: </strong>'.$title.'</p>';
		}
		
		else
		{
			$result = '';
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
					  <th>Post Title</th>
					  <th>Viewer Name</th>
					  <th>Post Date</th>
					  <th>Comment Date</th>
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
				$post_comment_id = $row->post_comment_id;
				$post_title = $row->post_title;
				$post_comment_status = $row->post_comment_status;
				$post_comment_user = $row->post_comment_user;
				$post_comment_description = $row->post_comment_description;
				$image = $row->post_image;
				
				//status
				if($post_comment_status == 1)
				{
					$status = 'Active';
				}
				else
				{
					$status = 'Disabled';
				}
				
				//create deactivated status display
				if($post_comment_status == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'activate-comment/'.$post_comment_id.'/'.$post_id.'" onclick="return confirm(\'Do you want to activate '.$post_comment_user.' comment?\');">Activate</a>';
				}
				//create activated status display
				else if($post_comment_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'deactivate-comment/'.$post_comment_id.'/'.$post_id.'" onclick="return confirm(\'Do you want to deactivate '.$post_comment_user.' comment?\');">Deactivate</a>';
				}
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td><img src="'.base_url()."assets/images/posts/thumbnail_".$image.'"></td>
						<td>'.$post_title.'</td>
						<td>'.$post_comment_user.'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->comment_created)).'</td>
						<td>'.$status.'</td>
						<td>
							
							<!-- Button to trigger modal -->
							<a href="#user'.$post_comment_id.'" class="btn btn-primary" data-toggle="modal">View</a>
							
							<!-- Modal -->
							<div id="user'.$post_comment_id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">By '.$post_comment_user.' on '.date('jS M Y H:i a',strtotime($row->comment_created)).'</h4>
										</div>
										
										<div class="modal-body">
											<p>
											`'.$post_comment_description.'
											</p>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
											'.$button.'
											<a href="'.site_url().'delete-comment/'.$post_comment_id.'/'.$post_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$post_comment_user.' comment?\');">Delete</a>
										</div>
									</div>
								</div>
							</div>
						
						</td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'delete-comment/'.$post_comment_id.'/'.$post_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$post_comment_user.' comment?\');">Delete</a></td>
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