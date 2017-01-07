<?php 
echo $this->load->view('inventory/search/search_items', '' , TRUE);
?>
<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
		    <header class="panel-heading">
		        <h2 class="panel-title pull-left"><?php echo $title;?></h2>
		         <div class="widget-icons pull-right">
			
						<a href="<?php echo base_url();?>inventory/add-item" class="btn btn-success btn-sm fa fa-plus"> Add New item</a>
						
			         	
		          </div>
		          <div class="clearfix"></div>
		    </header>
		    <div class="panel-body">
            <?php
    $search = $this->session->userdata('item_search');
		
	if(!empty($search))
	{
		echo '
		<a href="'.site_url().'items/close-request-search" class="btn btn-warning btn-sm ">Close Search</a>
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
								
						$search = $this->session->userdata('item_inventory_search');
						
						if(!empty($search))
						{
							$search_result = '<a href="'.site_url().'inventory/close-item-search" class="btn btn-success btn-sm">Close Search</a>';
						}


						$result = '';	
						$result .= ''.$search_result2.'';
						$result .= '
								';

						
						
						//if users exist display them
						if ($query->num_rows() > 0)
						{
							$count = $page;
							$cols_items ='';
								$colspan = 2;
							
							$result .= 
							'
							<div class="row">
							<div class="col-md-12">
								<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
								 
								  <thead> 
		                                <th>#</th>
		                                <th><a href="'.site_url().'inventory/items/item_name/'.$order_method.'/'.$page.'">Item Name</a></th>
										<th><a href="'.site_url().'inventory/items/item.item_category_id/'.$order_method.'/'.$page.'">Category Parent</a></th>
		                                <th><a href="'.site_url().'inventory/items/minimum_hiring_price/'.$order_method.'/'.$page.'">Minimum Hiring Price</a></th>
		                                <th><a href="'.site_url().'inventory/items/quantity/'.$order_method.'/'.$page.'">Quantity</a></th>
		                                
		                                <th class="table-sortable:default table-sortable" title="Click to sort">Description</th>
		                                <th>Status</th>
		                                <th colspan=5"'.$colspan.'">Actions</th>
		                            </thead>
								  <tbody>
							';
							
							//get all administrators
							$personnel_query = $this->personnel_model->all_personnel();
							
							
							foreach ($query->result() as $row)
							{
								
								$item_id = $row->item_id;
								$item_name = $row->item_name;
								$item_status_id = $row->item_status_id;
								$minimum_hiring_price = $row->minimum_hiring_price_kshs;
								$item_description = $row->item_description;
								//$store_name = $row->store_name;
								//$branch_code = $row->branch_code;
								$created = $row->created;
								$created_by = $row->created_by;
								$last_modified = $row->last_modified;
								//$modified_by = $row->modified_by;
								$category_id = $row->item_category_id;
								//$store_id = $row->store_id;
								//$quantity = $row->quantity;

								$item_hiring_price = $row->item_hiring_price_kshs;
		                        
		                        $item_deleted = $row->product_deleted;
		                       
							   
							   	$button_two = ' <td><a href="'.site_url().'inventory/edit-item/'.$item_id.'" class="btn btn-sm btn-primary">Edit</a></td>';
								$button_three= '<td><a href="'.site_url().'inventory/delete-item/'.$item_id.'" class="btn btn-sm btn-danger">Delete</a></td>
								';

								$parent_query=$this->items_model->get_item_category($item_id);
								
								if($parent_query->num_rows() > 0)
								{
									$parent_result = $parent_query->result();
									
									foreach($parent_result as $parent)
									{
										$parent_category = $parent->category_name;
										
									}
								}
								else{
									$parent_category ='-';
									}
								//status
								if($item_status_id == 1)
								{
									$status = 'Active';
								}
								else
								{
									$status = 'Disabled';
								}

								
							
								//create deactivated status display
								if($item_status_id == 0)
								{
									$status = '<span class="label label-danger">Deactivated</span>';
									$button = '<a class="btn btn-info btn-sm" href="'.site_url().'inventory/activation/activate/'.$item_id.'" onclick="return confirm(\'Do you want to activate '.$item_name.'?\');">Activate</a>';
								}
								//create activated status display
								else 
								{
									$status = '<span class="label label-success">Active</span>';
									$button = '<a class="btn btn-default btn-sm" href="'.site_url().'inventory/activation/deactivate/'.$item_id.'" onclick="return confirm(\'Do you want to deactivate '.$item_name.'?\');">Deactivate</a>';
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
										<td>'.$item_name.'</td>
										<td>'.$parent_category.'</td>
		                              <td>'.$minimum_hiring_price.'</td>
									   <td></td>
									   <td>'.$item_description.'</td>
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
							$result .= 'No Item Matches Your Search';
							
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