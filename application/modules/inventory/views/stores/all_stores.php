 
 <section class="panel">
    <header class="panel-heading">
          <h4 class="pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
            	<a href="<?php echo base_url();?>inventory-setup/add-store" class="btn btn-primary pull-right btn-sm">Add store</a>
          </div>
          <div class="clearfix"></div>
        </header>
      	<div class="panel-body">
		<?php 
		$v_data['view_type'] = 0;
		//echo $this->load->view('inventory-setup/search/search_stores', $v_data, TRUE); ?>
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
						
				$search = $this->session->userdata('store_search');
				
				if(!empty($search))
				{
					$search_result = '<a href="'.site_url().'inventory-setup/close-stores-search" class="btn btn-danger">Close Search</a>';
				}


				$result = '<div class="padd">';	
				$result .= ''.$search_result2.'';
				$result .= '
							<div class="row" style="margin-bottom:8px;">
								<div class="pull-left">
								'.$search_result.'
								</div>
			            		<div class="pull-right">
								
								
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
								<div class="col-md-12">
								<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
								  <thead>
									<tr>
									  <th class="table-sortable:default table-sortable" title="Click to sort">#</th>
									  <th class="table-sortable:default table-sortable" title="Click to sort">store Name</th>
									  <th class="table-sortable:default table-sortable" title="Click to sort">Date Created</th>
									  <th class="table-sortable:default table-sortable" title="Click to sort">Last Modified</th>
									  <th>Status</th>
									  <th colspan="3">Actions</th>
									</tr>
								  </thead>
								  <tbody>
							';
							
							//get all administrators
							$personnel_query = $this->personnel_model->get_all_personnel();
							
							
							foreach ($query->result() as $row)
							{
								$store_id = $row->store_id;
								$store_name = $row->store_name;
								$parent = $row->store_parent;
								$store_status = $row->store_status;
								$image = $row->store_image_name;
								$created_by = $row->created_by;
								$modified_by = $row->modified_by;
								$store_image_name = $row->store_image_name;
								
								//status
								if($store_status == 1)
								{
									$status = 'Active';
								}
								else
								{
									$status = 'Disabled';
								}
								$store_parent = '-';
								
								//store parent
								foreach($query->result() as $row2)
								{
									$store_id2 = $row2->store_id;
									if($parent == $store_id2)
									{
										$store_parent = $row2->store_name;
										break;
									}
								}
								
								/*$children = '';
								//Display child stores
								if($child_stores->num_rows() > 0)
								{
									foreach($child_stores->result() as $res)
									{
										$child_store_id = $row->store_id;
										$child_store_name = $row->store_name;
										$child_parent = $row->store_parent;
										$child_store_status = $row->store_status;
										$child_image = $row->store_image_name;
										
										//display only the particular store's children
										if($child_parent == $store_id)
										{
											
										}
									}
								}*/
								
								//create deactivated status display
								if($store_status == 0)
								{
									$status = '<span class="label label-danger">Deactivated</span>';
									$button = '<a class="btn btn-info" href="'.site_url().'inventory-setup/activate-store/'.$store_id.'" onclick="return confirm(\'Do you want to activate '.$store_name.'?\');">Activate</a>';
								}
								//create activated status display
								else if($store_status == 1)
								{
									$status = '<span class="label label-success">Active</span>';
									$button = '<a class="btn btn-default" href="'.site_url().'inventory-setup/deactivate-store/'.$store_id.'" onclick="return confirm(\'Do you want to deactivate '.$store_name.'?\');">Deactivate</a>';
								}
								
								//creators & editors
								//creators and editors
							if($personnel_query->num_rows() > 0)
							{
								$personnel_result = $personnel_query->result();
								
								foreach($personnel_result as $adm)
								{
									$personnel_id2 = $adm->personnel_id;
									
									if($created_by == $personnel_id2)
									{
										$created_by = $adm->personnel_fname;
										break;
									}
									
									else
									{
										$created_by = '-';
									}
								}
							}
							
							else
							{
								$created_by = '-';
							}
								
								
								$count++;
								$result .= 
								'
									<tr>
										<td>'.$count.'</td>
										<td>'.$store_name.'</td>
										<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
										<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
										<td>'.$status.'</td>
										<td><a href="'.site_url().'inventory-setup/edit-store/'.$store_id.'" class="btn btn-sm btn-success">Edit</a></td>
										<td>'.$button.'</td>
										<td><a href="'.site_url().'inventory-setup/delete-store/'.$store_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$store_name.'?\');">Delete</a></td>
										
									
									</tr> 
								';
							}
							
							$result .= 
							'
										  </tbody>
										</table>
								</div>
							</div>
						';
					}
					
					else
					{
						$result .= "There are no stores";
					}
					$result .= '</div>';
					echo $result;
			?>
		</div>
	</section>
