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
						<th>Icon</th>
						<th><a href="'.site_url().'administration/sections/section_name/'.$order_method.'/'.$page.'">Section name</a></th>
						<th><a href="'.site_url().'administration/sections/section_parent/'.$order_method.'/'.$page.'">Section parent</a></th>
						<th><a href="'.site_url().'administration/sections/created/'.$order_method.'/'.$page.'">Date Created</a></th>
						<th><a href="'.site_url().'administration/sections/last_modified/'.$order_method.'/'.$page.'">Last modified</a></th>
						<th><a href="'.site_url().'administration/sections/section_status/'.$order_method.'/'.$page.'">Status</a></th>
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
				$section_id = $row->section_id;
				$section_name = $row->section_name;
				$parent = $row->section_parent;
				$section_status = $row->section_status;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$icon = $row->section_icon;
				
				//status
				if($section_status == 1)
				{
					$status = 'Active';
				}
				else
				{
					$status = 'Disabled';
				}
				$section_parent = '-';
				
				//section parent
				foreach($all_sections->result() as $row2)
				{
					$section_id2 = $row2->section_id;
					
					if($parent == $section_id2)
					{
						$section_parent = $row2->section_name;
						break;
					}
				}
				
				//create deactivated status display
				if($section_status == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'administration/activate-section/'.$section_id.'" onclick="return confirm(\'Do you want to activate '.$section_name.'?\');" title="Activate '.$section_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($section_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'administration/deactivate-section/'.$section_id.'" onclick="return confirm(\'Do you want to deactivate '.$section_name.'?\');" title="Deactivate '.$section_name.'"><i class="fa fa-thumbs-down"></i></a>';
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
						<td><i class="fa fa-'.$icon.' fa-2x"></i></td>
						<td>'.$section_name.'</td>
						<td>'.$section_parent.'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
						<td>'.$status.'</td>
						<td>
							
							<!-- Button to trigger modal -->
							<a href="#user'.$section_id.'" class="btn btn-primary" data-toggle="modal" title="Expand '.$section_name.'"><i class="fa fa-plus"></i></a>
							
							<!-- Modal -->
							<div id="user'.$section_id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">'.$section_name.'</h4>
										</div>
										
										<div class="modal-body">
											<table class="table table-stripped table-condensed table-hover">
												<tr>
													<th>Section name</th>
													<td>'.$section_name.'</td>
												</tr>
												<tr>
													<th>Section parent</th>
													<td>'.$section_parent.'</td>
												</tr>
												<tr>
													<th>Status</th>
													<td>'.$status.'</td>
												</tr>
												<tr>
													<th>Date created</th>
													<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
												</tr>
												<tr>
													<th>Created by</th>
													<td>'.$created_by.'</td>
												</tr>
												<tr>
													<th>Date modified</th>
													<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
												</tr>
												<tr>
													<th>Modified by</th>
													<td>'.$modified_by.'</td>
												</tr>
												<tr>
													<th>Section icon</th>
													<td><i class="fa fa-'.$icon.' fa-3x"></i></td>
												</tr>
											</table>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
											<a href="'.site_url().'administration/edit-section/'.$section_id.'" class="btn btn-sm btn-success" title="Edit '.$section_name.'"><i class="fa fa-pencil"></i></a>
											'.$button.'
											<a href="'.site_url().'administration/delete-section/'.$section_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$section_name.'?\');" title="Delete '.$section_name.'"><i class="fa fa-trash"></i></a>
										</div>
									</div>
								</div>
							</div>
						
						</td>
						<td><a href="'.site_url().'administration/edit-section/'.$section_id.'" class="btn btn-sm btn-success" title="Edit '.$section_name.'"><i class="fa fa-pencil"></i></a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'administration/delete-section/'.$section_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$section_name.'?\');" title="Delete '.$section_name.'"><i class="fa fa-trash"></i></a></td>
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
			$result .= "There are no section";
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
                                    <div class="col-lg-12">
                                    	<a href="<?php echo site_url();?>administration/add-section" class="btn btn-success pull-right">Add Section</a>
                                    </div>
                                </div>
								<div class="table-responsive">
                                	
									<?php echo $result;?>
							
                                </div>
							</div>
							<div class="panel-body">
                            	<?php if(isset($links)){echo $links;}?>
							</div>
						</section>