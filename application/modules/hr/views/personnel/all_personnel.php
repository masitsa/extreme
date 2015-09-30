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
						<th><a href="'.site_url().'human-resource/personnel/branch_id/'.$order_method.'/'.$page.'">Branch</a></th>
						<th><a href="'.site_url().'human-resource/personnel/personnel.personnel_type_id/'.$order_method.'/'.$page.'">Type</a></th>
						<th><a href="'.site_url().'human-resource/personnel/personnel_onames/'.$order_method.'/'.$page.'">Other names</a></th>
						<th><a href="'.site_url().'human-resource/personnel/personnel_fname/'.$order_method.'/'.$page.'">First name</a></th>
						<th><a href="'.site_url().'human-resource/personnel/personnel_username/'.$order_method.'/'.$page.'">Username</a></th>
						<th><a href="'.site_url().'human-resource/personnel/personnel_phone/'.$order_method.'/'.$page.'">Phone</a></th>
						<th><a href="'.site_url().'human-resource/personnel/personnel_status/'.$order_method.'/'.$page.'">Status</a></th>
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
				$personnel_id = $row->personnel_id;
				$branch_id = $row->branch_id;
				$personnel_fname = $row->personnel_fname;
				$personnel_onames = $row->personnel_onames;
				$personnel_username = $row->personnel_username;
				$personnel_phone = $row->personnel_phone;
				$personnel_email = $row->personnel_email;
				$personnel_status = $row->personnel_status;
				$personnel_type_name = $row->personnel_type_name;
				$personnel_name = $personnel_fname.' '.$personnel_onames;
				
				//status
				if($personnel_status == 1)
				{
					$status = 'Active';
				}
				else
				{
					$status = 'Disabled';
				}
				
				//create deactivated status display
				if($personnel_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'human-resource/activate-personnel/'.$personnel_id.'" onclick="return confirm(\'Do you want to activate '.$personnel_name.'?\');" title="Activate '.$personnel_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($personnel_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'human-resource/deactivate-personnel/'.$personnel_id.'" onclick="return confirm(\'Do you want to deactivate '.$personnel_name.'?\');" title="Deactivate '.$personnel_name.'"><i class="fa fa-thumbs-down"></i></a>';
				}
				
				//get branch
				$branch = '';
				if($branches->num_rows() > 0)
				{
					foreach($branches->result() as $res)
					{
						$branch_id2 = $res->branch_id;
						if($branch_id == $branch_id2)
						{
							$branch = $res->branch_name;
						}
					}
				}
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$branch.'</td>
						<td>'.$personnel_type_name.'</td>
						<td>'.$personnel_onames.'</td>
						<td>'.$personnel_fname.'</td>
						<td>'.$personnel_username.'</td>
						<td>'.$personnel_phone.'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'human-resource/reset-password/'.$personnel_id.'" class="btn btn-sm btn-warning" onclick="return confirm(\'Reset password for '.$personnel_fname.'?\');">Reset Password</a></td>
						<td><a href="'.site_url().'human-resource/edit-personnel/'.$personnel_id.'" class="btn btn-sm btn-success" title="Edit '.$personnel_name.'"><i class="fa fa-pencil"></i></a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'human-resource/delete-personnel/'.$personnel_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$personnel_name.'?\');" title="Delete '.$personnel_name.'"><i class="fa fa-trash"></i></a></td>
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
                                    <!--<div class="col-lg-2 col-lg-offset-8">
                                        <a href="<?php echo site_url();?>human-resource/export-personnel" class="btn btn-sm btn-success pull-right">Export</a>
                                    </div>-->
                                    <div class="col-lg-12">
                                    	<a href="<?php echo site_url();?>human-resource/add-personnel" class="btn btn-sm btn-info pull-right">Add Personnel</a>
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