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
						<th><a href="'.site_url().'microfinance/group/group_name/'.$order_method.'/'.$page.'">Group name</a></th>
						<th><a href="'.site_url().'microfinance/group/group_contact_onames/'.$order_method.'/'.$page.'">Contact other names</a></th>
						<th><a href="'.site_url().'microfinance/group/group_contact_fname/'.$order_method.'/'.$page.'">Contact first name</a></th>
						<th><a href="'.site_url().'microfinance/group/group_phone/'.$order_method.'/'.$page.'">Phone</a></th>
						<th><a href="'.site_url().'microfinance/group/group_status/'.$order_method.'/'.$page.'">Status</a></th>
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
				$group_id = $row->group_id;
				$group_contact_fname = $row->group_contact_fname;
				$group_name = $row->group_name;
				$group_contact_onames = $row->group_contact_onames;
				$group_phone = $row->group_phone;
				$group_email = $row->group_email;
				$group_status = $row->group_status;
				
				//status
				if($group_status == 1)
				{
					$status = 'Active';
				}
				else
				{
					$status = 'Disabled';
				}
				
				//create deactivated status display
				if($group_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'microfinance/activate-group/'.$group_id.'" onclick="return confirm(\'Do you want to activate '.$group_name.'?\');" title="Activate '.$group_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($group_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'microfinance/deactivate-group/'.$group_id.'" onclick="return confirm(\'Do you want to deactivate '.$group_name.'?\');" title="Deactivate '.$group_name.'"><i class="fa fa-thumbs-down"></i></a>';
				}
				
				$count++;
				$result .= 
				'
					<tr>
						<td>'.$count.'</td>
						<td>'.$group_name.'</td>
						<td>'.$group_contact_onames.'</td>
						<td>'.$group_contact_fname.'</td>
						<td>'.$group_phone.'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'microfinance/edit-group/'.$group_id.'" class="btn btn-sm btn-success" title="Edit '.$group_name.'"><i class="fa fa-pencil"></i></a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'microfinance/delete-group/'.$group_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$group_name.'?\');" title="Delete '.$group_name.'"><i class="fa fa-trash"></i></a></td>
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
			$result .= "There are no groups";
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
                                    <div class="col-lg-2 col-lg-offset-8">
                                        <a href="<?php echo site_url();?>microfinance/export-group" class="btn btn-sm btn-success pull-right">Export</a>
                                    </div>
                                    <div class="col-lg-2">
                                    	<a href="<?php echo site_url();?>microfinance/add-group" class="btn btn-sm btn-info pull-right">Add Group</a>
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