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
						<th><a href="'.site_url().'admin/beds/bed_number/'.$room_id.'/'.$order_method.'/'.$page.'">Number</a></th>
						<th><a href="'.site_url().'admin/beds/last_modified/'.$room_id.'/'.$order_method.'/'.$page.'">Last modified</a></th>
						<th><a href="'.site_url().'admin/beds/modified_by/'.$room_id.'/'.$order_method.'/'.$page.'">Modified by</a></th>
						<th><a href="'.site_url().'admin/beds/bed_status/'.$room_id.'/'.$order_method.'/'.$page.'">Status</a></th>
						<th colspan="3">Actions</th>
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
				$bed_id = $row->bed_id;
				$bed_number = $row->bed_number;
				$bed_status = $row->bed_status;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$last_modified = date('jS M Y H:i a',strtotime($row->last_modified));
				$created = date('jS M Y H:i a',strtotime($row->created));
				
				//create deactivated status display
				if($bed_status == 0)
				{
					$status = '<span class="label label-default">Deactivated</span>';
					$button = '<a class="btn btn-info btn-sm" href="'.site_url().'hospital-administration/activate-bed/'.$room_id.'/'.$bed_id.'" onclick="return confirm(\'Do you want to activate '.$bed_number.'?\');" title="Activate '.$bed_number.'"><i class="fa fa-thumbs-up"></i> Activate</a>';
				}
				//create activated status display
				else if($bed_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default btn-sm" href="'.site_url().'hospital-administration/deactivate-bed/'.$room_id.'/'.$bed_id.'" onclick="return confirm(\'Do you want to deactivate '.$bed_number.'?\');" title="Deactivate '.$bed_number.'"><i class="fa fa-thumbs-down"></i> Deactivate</a>';
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
						<td>'.$bed_number.'</td>
						<td>'.$last_modified.'</td>
						<td>'.$modified_by.'</td>
						<td>'.$status.'</td>
						<td><a href="'.site_url().'hospital-administration/edit-bed/'.$room_id.'/'.$bed_id.'" class="btn btn-sm btn-info" title="Edit '.$bed_number.'"><i class="fa fa-pencil"></i> Edit</a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'hospital-administration/delete-bed/'.$room_id.'/'.$bed_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$bed_number.'?\');" title="Delete '.$bed_number.'"><i class="fa fa-trash"></i> Delete</a></td>
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
			$result .= "There are no beds";
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
                                    	<a href="<?php echo site_url();?>hospital-administration/rooms/<?php echo $ward_id;?>" class="btn btn-primary btn-sm pull-right">Back to rooms</a>
                                    	<a href="<?php echo site_url();?>hospital-administration/add-bed/<?php echo $room_id;?>" class="btn btn-success btn-sm pull-right" style="margin-right:20px;">Add bed</a>
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