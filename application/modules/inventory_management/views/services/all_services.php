<?php 
echo $this->load->view('inventory/search/search_services', '' , TRUE);
?>
<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
		    <header class="panel-heading">
		        <h2 class="panel-title pull-left"><?php echo $title;?></h2>
		         <div class="widget-icons pull-right">
			
						<a href="<?php echo base_url();?>inventory/add-service" class="btn btn-success btn-sm fa fa-plus"> Add New service</a>
						
			         	
		          </div>
		          <div class="clearfix"></div>
		    </header>
		    <div class="panel-body">
            <?php
    $search = $this->session->userdata('service_search');
		
	if(!empty($search))
	{
		echo '
		<a href="'.site_url().'services/close-request-search" class="btn btn-warning btn-sm ">Close Search</a>
		';
	}
	
	?>
            
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
								
						$search = $this->session->userdata('service_inventory_search');
						
						if(!empty($search))
						{
							$search_result = '<a href="'.site_url().'inventory/close-service-search" class="btn btn-success btn-sm">Close Search</a>';
						}


						$result = '';	
						$result .= ''.$search_result2.'';
						$result .= '
								';

						
						
						//if users exist display them
						if ($query->num_rows() > 0)
						{
							$count = $page;
							$cols_services ='';
								$colspan = 2;
							
							$result .= 
							'
							<div class="row">
							<div class="col-md-12">
								<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
								 
								  <thead> 
		                                <th>#</th>
		                                <th><a href="'.site_url().'inventory/services/service_name/'.$order_method.'/'.$page.'">Service Name</a></th>
										<th><a href="'.site_url().'inventory/services/service.service_category_id/'.$order_method.'/'.$page.'">Servce Cost</a></th>
		                                <th><a href="'.site_url().'inventory/services/minimum_hiring_price/'.$order_method.'/'.$page.'">Service Price</a></th>
		                                <th class="table-sortable:default table-sortable" title="Click to sort">Description</th>
		                                <th>Status</th>
		                                <th colspan=5"'.$colspan.'">Actions</th>
		                            </thead>
								  <tbody>
							';
							
							//get all administrators
							$personnel_query = $this->personnel_model->get_all_personnel();
							
							
							foreach ($query->result() as $row)
							{
								
								$service_id = $row->service_id;
								$service_name = $row->service_name;
								$service_status_id = $row->service_status_id;
								$service_price= $row->service_price;
								$service_cost = $row->service_cost;
								$service_description = $row->service_description;
								$created = $row->created;
								$created_by = $row->created_by;
								$last_modified = $row->last_modified;
								//$modified_by = $row->modified_by;
								//$category_id = $row->service_category_id;
								//$store_id = $row->store_id;
								
		                        $service_deleted = $row->service_deleted;
		                       
							   
							   	$button_two = ' <td><a href="'.site_url().'inventory/edit-service/'.$service_id.'" class="btn btn-sm btn-primary">Edit</a></td>';
								$button_three= '<td><a href="'.site_url().'inventory/delete-service/'.$service_id.'" class="btn btn-sm btn-danger">Delete</a></td>
								';

								//status
								if($service_status_id == 1)
								{
									$status = 'Active';
								}
								else
								{
									$status = 'Disabled';
								}

								
							
								//create deactivated status display
								if($service_status_id == 0)
								{
									$status = '<span class="label label-danger">Deactivated</span>';
									$button = '<a class="btn btn-info btn-sm" href="'.site_url().'inventory/activation/activate/'.$service_id.'" onclick="return confirm(\'Do you want to activate '.$service_name.'?\');">Activate</a>';
								}
								//create activated status display
								else 
								{
									$status = '<span class="label label-success">Active</span>';
									$button = '<a class="btn btn-default btn-sm" href="'.site_url().'inventory/activation/deactivate/'.$service_id.'" onclick="return confirm(\'Do you want to deactivate '.$service_name.'?\');">Deactivate</a>';
								}

								
					
								//creators & editors
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
										<td>'.$service_name.'</td>
										<td>'.$service_cost.'</td>
		                              <td>'.$service_price.'</td>
									   <td>'.$service_description.'</td>
		                                <td>'.$status.'</td>
		                                <td>'.$button.'</td>
										<td>'.$button_two.'</td>
										<td>'.$button_three.'</td>
										
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
							$result .= 'No Service Matches Your Search';
							
						}
						
						$result .= '</div>';
						echo $result;
				?>
				<div class="widget-foot">
			    <?php
			    if(isset($links)){echo $links;}
			    ?>
			    </div>
			</div>
			
		</section>
	</div>
</div>