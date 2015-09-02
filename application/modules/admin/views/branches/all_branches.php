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
						<th>Logo</th>
						<th><a href="'.site_url().'admin/categories/branch_name/'.$order_method.'/'.$page.'">Branch name</a></th>
						<th><a href="'.site_url().'admin/categories/branch_phone/'.$order_method.'/'.$page.'">Phone</a></th>
						<th><a href="'.site_url().'admin/categories/branch_email/'.$order_method.'/'.$page.'">Email</a></th>
						<th><a href="'.site_url().'admin/categories/created/'.$order_method.'/'.$page.'">Date Created</a></th>
						<th><a href="'.site_url().'admin/categories/last_modified/'.$order_method.'/'.$page.'">Last modified</a></th>
						<th><a href="'.site_url().'admin/categories/branch_status/'.$order_method.'/'.$page.'">Status</a></th>
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
				$branch_id = $row->branch_id;
				$branch_name = $row->branch_name;
				$branch_phone = $row->branch_phone;
				$branch_email = $row->branch_email;
				$branch_status = $row->branch_status;
				$image = $row->branch_image_name;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$branch_building = $row->branch_building;
				$branch_floor = $row->branch_floor;
				$branch_location = $row->branch_location;
				$branch_address = $row->branch_address;
				$branch_city = $row->branch_city;
				$branch_post_code = $row->branch_post_code;
				$branch_working_weekday = $row->branch_working_weekday;
				$branch_working_weekend = $row->branch_working_weekend;
				
				//create deactivated status display
				if($branch_status == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'admin/activate-branch/'.$branch_id.'" onclick="return confirm(\'Do you want to activate '.$branch_name.'?\');" title="Activate '.$branch_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($branch_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'admin/deactivate-branch/'.$branch_id.'" onclick="return confirm(\'Do you want to deactivate '.$branch_name.'?\');" title="Deactivate '.$branch_name.'"><i class="fa fa-thumbs-down"></i></a>';
				}
				
				//creators & editors
				if($admins != NULL)
				{
					foreach($admins as $adm)
					{
						$personnel_id = $adm->personnel_id;
						
						if($personnel_id == $created_by)
						{
							$created_by = $adm->personnel_fname;
						}
						
						if($personnel_id == $modified_by)
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
						<td><img src="'.base_url()."assets/logo/".$image.'"></td>
						<td>'.$branch_name.'</td>
						<td>'.$branch_phone.'</td>
						<td>'.$branch_email.'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
						<td>'.$status.'</td>
						<td>
							
							<!-- Button to trigger modal -->
							<a href="#user'.$branch_id.'" class="btn btn-primary" data-toggle="modal" title="Expand '.$branch_name.'"><i class="fa fa-plus"></i></a>
							
							<!-- Modal -->
							<div id="user'.$branch_id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">'.$branch_name.'</h4>
										</div>
										
										<div class="modal-body">
											<table class="table table-stripped table-condensed table-hover">
												<tr>
													<th>Branch Name</th>
													<td>'.$branch_name.'</td>
												</tr>
												<tr>
													<th>Branch Phone</th>
													<td>'.$branch_phone.'</td>
												</tr>
												<tr>
													<th>Branch Email</th>
													<td>'.$branch_email.'</td>
												</tr>
												<tr>
													<th>Branch Address</th>
													<td>'.$branch_address.'</td>
												</tr>
												<tr>
													<th>Branch City</th>
													<td>'.$branch_city.'</td>
												</tr>
												<tr>
													<th>Branch Post Code</th>
													<td>'.$branch_post_code.'</td>
												</tr>
												<tr>
													<th>Branch Location</th>
													<td>'.$branch_location.'</td>
												</tr>
												<tr>
													<th>Branch Building</th>
													<td>'.$branch_building.'</td>
												</tr>
												<tr>
													<th>Branch Floor</th>
													<td>'.$branch_floor.'</td>
												</tr>
												<tr>
													<th>Weekday working hours</th>
													<td>'.$branch_working_weekday.'</td>
												</tr>
												<tr>
													<th>Weekend working hours</th>
													<td>'.$branch_working_weekend.'</td>
												</tr>
												<tr>
													<th>Status</th>
													<td>'.$status.'</td>
												</tr>
												<tr>
													<th>Date Created</th>
													<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
												</tr>
												<tr>
													<th>Created By</th>
													<td>'.$created_by.'</td>
												</tr>
												<tr>
													<th>Date Modified</th>
													<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
												</tr>
												<tr>
													<th>Modified By</th>
													<td>'.$modified_by.'</td>
												</tr>
												<tr>
													<th>Branch Logo</th>
													<td><img src="'.base_url()."assets/logo/".$image.'"></td>
												</tr>
											</table>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
											<a href="'.site_url().'administration/edit-branch/'.$branch_id.'" class="btn btn-sm btn-success" title="Edit '.$branch_name.'"><i class="fa fa-pencil"></i></a>
											'.$button.'
											<a href="'.site_url().'administration/delete-branch/'.$branch_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$branch_name.'?\');" title="Delete '.$branch_name.'"><i class="fa fa-trash"></i></a>
										</div>
									</div>
								</div>
							</div>
						
						</td>
						<td><a href="'.site_url().'administration/edit-branch/'.$branch_id.'" class="btn btn-sm btn-success" title="Edit '.$branch_name.'"><i class="fa fa-pencil"></i></a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'administration/delete-branch/'.$branch_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$branch_name.'?\');" title="Delete '.$branch_name.'"><i class="fa fa-trash"></i></a></td>
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
			$result .= "There are no branches";
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
                                    	<a href="<?php echo site_url();?>administration/add-branch" class="btn btn-success pull-right">Add Branch</a>
                                    </div>
                                </div>
								<div class="table-responsive">
                                	
									<?php echo $result;?>
							
                                </div>
							</div>
						</section>