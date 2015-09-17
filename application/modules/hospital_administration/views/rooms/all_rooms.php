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
						<th><a href="'.site_url().'hospital-administration/rooms/room_name/'.$ward_id.'/'.$order_method.'/'.$page.'">Room</a></th>
						<th><a href="'.site_url().'hospital-administration/rooms/room_preffix/'.$ward_id.'/'.$order_method.'/'.$page.'">Preffix</a></th>
						<th><a href="'.site_url().'hospital-administration/rooms/room_capacity/'.$ward_id.'/'.$order_method.'/'.$page.'">Capacity</a></th>
						<th><a href="'.site_url().'hospital-administration/rooms/last_modified/'.$ward_id.'/'.$order_method.'/'.$page.'">Last modified</a></th>
						<th><a href="'.site_url().'hospital-administration/rooms/modified_by/'.$ward_id.'/'.$order_method.'/'.$page.'">Modified by</a></th>
						<th><a href="'.site_url().'hospital-administration/rooms/room_status/'.$ward_id.'/'.$order_method.'/'.$page.'">Status</a></th>
						<th colspan="5">Actions</th>
					</tr>
				</thead>
				  <tbody>
				  
			';
			
			//get all administrators
			$administrators = $this->personnel_model->retrieve_personnel();
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
				$room_id = $row->room_id;
				$room_name = $row->room_name;
				$room_capacity = $row->room_capacity;
				$room_preffix = $row->room_preffix;
				$room_status = $row->room_status;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$last_modified = date('jS M Y H:i a',strtotime($row->last_modified));
				$created = date('jS M Y H:i a',strtotime($row->created));
				
				//create deactivated status display
				if($room_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					$button = '<a class="btn btn-info btn-sm" href="'.site_url().'hospital-administration/activate-room/'.$ward_id.'/'.$room_id.'" onclick="return confirm(\'Do you want to activate '.$room_name.'?\');" title="Activate '.$room_name.'"><i class="fa fa-thumbs-up"></i> Activate</a>';
				}
				//create activated status display
				else if($room_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default btn-sm" href="'.site_url().'hospital-administration/deactivate-room/'.$ward_id.'/'.$room_id.'" onclick="return confirm(\'Do you want to deactivate '.$room_name.'?\');" title="Deactivate '.$room_name.'"><i class="fa fa-thumbs-down"></i> Deactivate</a>';
				}
				
				//creators & editors
				if($admins != NULL)
				{
					foreach($admins as $adm)
					{
						$user_id = $adm->personnel_id;
						
						if($user_id == $created_by)
						{
							$created_by = $adm->personnel_fname;
						}
						
						if($user_id == $modified_by)
						{
							$modified_by = $adm->personnel_fname;
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
						<td>'.$room_name.'</td>
						<td>'.$room_preffix.'</td>
						<td>'.$room_capacity.'</td>
						<td>'.$last_modified.'</td>
						<td>'.$modified_by.'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'hospital-administration/beds/'.$room_id.'" class="btn btn-sm btn-warning" title="View rooms for '.$room_name.'"><i class="fa fa-bed"></i> Beds</a></td>
						<td><a href="'.site_url().'hospital-administration/edit-room/'.$ward_id.'/'.$room_id.'" class="btn btn-sm btn-info" title="Edit '.$room_name.'"><i class="fa fa-pencil"></i> Edit</a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'hospital-administration/delete-room/'.$ward_id.'/'.$room_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$room_name.'?\');" title="Delete '.$room_name.'"><i class="fa fa-trash"></i> Delete</a></td>
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
			$result .= "There are no rooms";
		}
?>

						<section class="panel">
							<header class="panel-heading">
								<div class="panel-actions">
									<a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
								</div>
						
								<h2 class="panel-title"><?php echo $title;?></h2>
							</header>
							<div class="panel-body">
                            	<div class="row" style="margin-bottom:20px;">
                                    <div class="col-lg-12">
                                    	<a href="<?php echo site_url();?>hospital-administration/wards" class="btn btn-primary btn-sm pull-right">Back to wards</a>
                                    	<a href="<?php echo site_url();?>hospital-administration/add-room/<?php echo $ward_id;?>" class="btn btn-success btn-sm pull-right" style="margin-right:20px;">Add room</a>
                                    </div>
                                </div>
                                <?php
								$error = $this->session->userdata('error_message');
								$success = $this->session->userdata('success_message');
								
								if(!empty($success))
								{
									echo '
										<div class="alert alert-success">'.$success.'</div>
									';
									$this->session->unset_userdata('success_message');
								}
								
								if(!empty($error))
								{
									echo '
										<div class="alert alert-danger">'.$error.'</div>
									';
									$this->session->unset_userdata('error_message');
								}
								?>
								<div class="table-responsive">
                                	
									<?php echo $result;?>
							
                                </div>
							</div>
                            
                            <div class="panel-foot">
                                
								<?php if(isset($links)){echo $links;}?>
                            
                                <div class="clearfix"></div> 
                            
                            </div>
						</section>