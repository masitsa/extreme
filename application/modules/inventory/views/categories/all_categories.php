<?php 
$v_data['view_type'] = 0;
echo $this->load->view('vendor/search/search_categories', $v_data, TRUE); ?>
<?php

		$error = $this->session->userdata('error_message');
		$success = $this->session->userdata('success_message');
		$search_result ='';
		$search_result2  ='';
		if(!empty($error))
		{
			$search_result2 = '<div class="alert alert-danger">'.$error.'</div>';
			$this->session->unset_userdata('error_message');
		}
		
		if(!empty($success))
		{
			$search_result2 ='<div class="alert alert-success">'.$success.'</div>';
			$this->session->unset_userdata('success_message');
		}
				
		$search = $this->session->userdata('category_search');
		
		if(!empty($search))
		{
			$search_result = '<a href="'.site_url().'vendor/close-categories-search" class="btn btn-danger">Close Search</a>';
		}


		$result = '<div class="padd">';	
		$result .= ''.$search_result2.'';
		$result .= '
					<div class="row" style="margin-bottom:8px;">
						<div class="pull-left">
						'.$search_result.'
						</div>
	            		<div class="pull-right">
							<a href="'.site_url().'vendor/account-profile/subscription" class="btn btn-primary pull-right">Add Category</a>
						
						</div>
					</div>
				';
			
			
			//if users exist display them
			if ($query->num_rows() > 0)
			{
				$count = $page;
				
				$result .= 
				'
				<div class="row">
					<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
					  <thead>
						<tr>
						  <th class="table-sortable:default table-sortable" title="Click to sort">#</th>
						  <th class="table-sortable:default table-sortable" title="Click to sort">Category Name</th>
						  <th class="table-sortable:default table-sortable" title="Click to sort">Date Created</th>
						  <th class="table-sortable:default table-sortable" title="Click to sort">Last Modified</th>
						  <th>Status</th>
						  <th colspan="3">Actions</th>
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
					foreach($query->result() as $row2)
					{
						$category_id2 = $row2->category_id;
						if($parent == $category_id2)
						{
							$category_parent = $row2->category_name;
							break;
						}
					}
					
					/*$children = '';
					//Display child categories
					if($child_categories->num_rows() > 0)
					{
						foreach($child_categories->result() as $res)
						{
							$child_category_id = $row->category_id;
							$child_category_name = $row->category_name;
							$child_parent = $row->category_parent;
							$child_category_status = $row->category_status;
							$child_image = $row->category_image_name;
							
							//display only the particular category's children
							if($child_parent == $category_id)
							{
								
							}
						}
					}*/
					
					//create deactivated status display
					if($category_status == 0)
					{
						$status = '<span class="label label-danger">Deactivated</span>';
						$button = '<a class="btn btn-info" href="'.site_url().'vendor/activate-category/'.$category_id.'" onclick="return confirm(\'Do you want to activate '.$category_name.'?\');">Activate</a>';
					}
					//create activated status display
					else if($category_status == 1)
					{
						$status = '<span class="label label-success">Active</span>';
						$button = '<a class="btn btn-default" href="'.site_url().'vendor/deactivate-category/'.$category_id.'" onclick="return confirm(\'Do you want to deactivate '.$category_name.'?\');">Deactivate</a>';
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
					
					if($created_by == $this->session->userdata('vendor_id'))
					{
						$actions = '
						<td><a href="'.site_url().'vendor/edit-category/'.$category_id.'" class="btn btn-sm btn-success">Edit</a></td>
						<td>'.$button.'</td>
						<td><a href="'.site_url().'vendor/delete-category/'.$category_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$category_name.'?\');">Delete</a></td>
						';
					}
					
					else
					{
						$actions = '<td colspan="1"></td>';
					}
					$count++;
					$result .= 
					'
						<tr>
							<td>'.$count.'</td>
							<td>'.$category_name.'</td>
							<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
							<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
							<td>'.$status.'</td>
							<td>
								
								<!-- Button to trigger modal -->
								<a href="#user'.$category_id.'" class="btn btn-primary" data-toggle="modal">View</a>
								
								<!-- Modal -->
								<div id="user'.$category_id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
												<h4 class="modal-title">'.$category_name.'</h4>
											</div>
											
											<div class="modal-body">
												<div class="tabbable">
													<ul id="myTab" class="nav nav-tabs">
														<li class="active">
															<a data-toggle="tab" href="#category">Category details</a>
														</li>
														<!--<li>
															<a data-toggle="tab" href="#level1">Sub categories</a>
														</li>-->
													</ul>
													<div id="myTabContent" class="tab-content">
														<div class="tab-pane fade in active" id="category">
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
														<div class="tab-pane fade in" id="level1">
															
														</div>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												'.$actions.'
											</div>
										</div>
									</div>
								</div>
							
							</td>
							'.$actions.'
						</tr> 
					';
				}
				
				$result .= 
				'
							  </tbody>
							</table>
						</div>
				';
			}
			
			else
			{
				$result .= "There are no categories";
			}
			$result .= '</div>';
			echo $result;
	?>
