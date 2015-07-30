<?php	
		$result = '';
		
		
		//if users exist display them
		if ($users->num_rows() > 0)
		{
			$count = $page;
			
			$result .= 
			'
			<table class="table table-bordered table-striped table-condensed">
				<thead>
					<tr>
						<th>#</th>
						<th><a href="'.site_url().'admin/administrators/first_name/'.$order_method.'/'.$page.'">Name</a></th>
						<th><a href="'.site_url().'admin/administrators/created/'.$order_method.'/'.$page.'">Date Created</a></th>
						<th><a href="'.site_url().'admin/administrators/last_login/'.$order_method.'/'.$page.'">Last Login</a></th>
						<th><a href="'.site_url().'admin/administrators/category_status/'.$order_method.'/'.$page.'">Status</a></th>
						<th colspan="5">Actions</th>
					</tr>
				</thead>
				  <tbody>
			';
			foreach ($users->result() as $row)
			{
				$user_id = $row->user_id;
				$fname = $row->first_name;
				//create deactivated status display
				if($row->activated == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'activate-user/'.$user_id.'" onclick="return confirm(\'Do you want to activate '.$fname.'?\');" title="Activate '.$fname.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($row->activated == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'deactivate-user/'.$user_id.'" onclick="return confirm(\'Do you want to deactivate '.$fname.'?\');" title="Deactivate '.$fname.'"><i class="fa fa-thumbs-down"></i></a>';
				}
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$row->first_name.' '.$row->other_names.'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->last_login)).'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'edit-user/'.$user_id.'" class="btn btn-sm btn-success"title="Edit '.$fname.'"><i class="fa fa-pencil"></i></a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'reset-user-password/'.$user_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Do you want to reset '.$fname.'\'s password?\');">Reset Password</a></td>
						<td><a href="'.site_url().'delete-user/'.$user_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$fname.'?\');" title="Delete '.$fname.'"><i class="fa fa-trash"></i></a></td>
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
		
?>
						<section class="panel">
							<header class="panel-heading">
								<div class="panel-actions">
									<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
									<a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
								</div>
						
								<h2 class="panel-title"><?php echo $title;?></h2>
							</header>
							<div class="panel-body">
                            	<div class="row" style="margin-bottom:20px;">
                                    <div class="col-lg-12">
                                    	<a href="<?php echo site_url();?>add-user" class="btn btn-success pull-right">Add administrator</a>
                                    </div>
                                </div>
								<div class="table-responsive">
                                	
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
									echo $result;
									
									?>
							
                                </div>
							</div>
						</section>