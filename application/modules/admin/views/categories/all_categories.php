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
						<th>Image</th>
						<th><a href="'.site_url().'admin/categories/category_name/'.$order_method.'/'.$page.'">Category name</a></th>
						<th><a href="'.site_url().'admin/categories/category_parent/'.$order_method.'/'.$page.'">Category parent</a></th>
						<th><a href="'.site_url().'admin/categories/created/'.$order_method.'/'.$page.'">Date Created</a></th>
						<th><a href="'.site_url().'admin/categories/last_modified/'.$order_method.'/'.$page.'">Last modified</a></th>
						<th><a href="'.site_url().'admin/categories/category_status/'.$order_method.'/'.$page.'">Status</a></th>
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
				$category_id = $row->category_id;
				$category_name = $row->category_name;
				$parent = $row->category_parent;
				$category_status = $row->category_status;
				$image = $row->category_image_name;
				$created_by = $row->created_by;
				$modified_by = $row->modified_by;
				$category_image_name = $row->category_image_name;
				
				//status
				if($category_status == 1)
				{
					$status = 'Active';
				}
				else
				{
					$status = 'Disabled';
				}
				$category_parent = '-';
				
				//category parent
				foreach($all_categories->result() as $row2)
				{
					$category_id2 = $row2->category_id;
					
					if($parent == $category_id2)
					{
						$category_parent = $row2->category_name;
						break;
					}
				}
				
				//create deactivated status display
				if($category_status == 0)
				{
					$status = '<span class="label label-important">Deactivated</span>';
					$button = '<a class="btn btn-info" href="'.site_url().'admin/activate-category/'.$category_id.'" onclick="return confirm(\'Do you want to activate '.$category_name.'?\');" title="Activate '.$category_name.'"><i class="fa fa-thumbs-up"></i></a>';
				}
				//create activated status display
				else if($category_status == 1)
				{
					$status = '<span class="label label-success">Active</span>';
					$button = '<a class="btn btn-default" href="'.site_url().'admin/deactivate-category/'.$category_id.'" onclick="return confirm(\'Do you want to deactivate '.$category_name.'?\');" title="Deactivate '.$category_name.'"><i class="fa fa-thumbs-down"></i></a>';
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
						<td><img src="'.base_url()."assets/images/categories/thumbnail_".$image.'"></td>
						<td>'.$category_name.'</td>
						<td>'.$category_parent.'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
						<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
						<td>'.$status.'</td>
						<td>
							
							<!-- Button to trigger modal -->
							<a href="#user'.$category_id.'" class="btn btn-primary" data-toggle="modal" title="Expand '.$category_name.'"><i class="fa fa-plus"></i></a>
							
							<!-- Modal -->
							<div id="user'.$category_id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">'.$category_name.'</h4>
										</div>
										
										<div class="modal-body">
											<table class="table table-stripped table-condensed table-hover">
												<tr>
													<th>Category Name</th>
													<td>'.$category_name.'</td>
												</tr>
												<tr>
													<th>Category Parent</th>
													<td>'.$category_parent.'</td>
												</tr>
												<tr>
													<th>Status</th>
													<td>'.$status.'</td>
												</tr>
												<tr>
													<th>Category Preffix</th>
													<td>'.$row->category_preffix.'</td>
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
													<th>Category Image</th>
													<td><img src="'.base_url()."assets/images/categories/".$image.'"></td>
												</tr>
											</table>
										</div>
										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
											<a href="'.site_url().'admin/edit-category/'.$category_id.'" class="btn btn-sm btn-success" title="Edit '.$category_name.'"><i class="fa fa-pencil"></i></a>
											'.$button.'
											<a href="'.site_url().'admin/delete-category/'.$category_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$category_name.'?\');" title="Delete '.$category_name.'"><i class="fa fa-trash"></i></a>
										</div>
									</div>
								</div>
							</div>
						
						</td>
						<td><a href="'.site_url().'admin/edit-category/'.$category_id.'" class="btn btn-sm btn-success" title="Edit '.$category_name.'"><i class="fa fa-pencil"></i></a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'admin/delete-category/'.$category_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$category_name.'?\');" title="Delete '.$category_name.'"><i class="fa fa-trash"></i></a></td>
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
			$result .= "There are no categories";
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
                                    	<a href="<?php echo site_url();?>admin/add-category" class="btn btn-success pull-right">Add Category</a>
                                    </div>
                                </div>
								<div class="table-responsive">
                                	
									<?php echo $result;?>
							
                                </div>
							</div>
						</section>