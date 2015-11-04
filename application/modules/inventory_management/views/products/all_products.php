<?php 
$v_data['all_categories'] = $all_categories;
$v_data['all_brands'] = $all_brands;
$v_data['all_generics'] = $all_generics;
$v_data['store_priviledges'] = $store_priviledges;
echo $this->load->view('search_products', $v_data, TRUE); 


?>
<div class="row">
    <div class="col-md-12">
		<section class="panel panel-featured panel-featured-info">
		    <header class="panel-heading">
		        <h2 class="panel-title pull-left"><?php echo $title;?></h2>
		         <div class="widget-icons pull-right">
			         <?php 
			         if($type == 1)
			         {
			         	?>
			         	 <a href="<?php echo base_url();?>inventory/manage-store" class="btn btn-success btn-sm fa fa-plus"> Manage Store</a>
			         	<?php
			         }
			         else
			         {
			         	?>
			         	<!-- <a href="<?php echo base_url();?>inventory/import-product" class="btn btn-success btn-sm" style="margin-left:10px;">Import Product</a>
						<a href="<?php echo base_url();?>inventory/export-product" class="btn btn-success btn-sm" style="margin-left:10px;">Export Product</a> -->
						<a href="<?php echo base_url();?>inventory/product-deductions" class="btn btn-warning btn-sm fa fa-minus"> Manage Orders</a>
						<a href="<?php echo base_url();?>inventory/add-product" class="btn btn-success btn-sm fa fa-plus"> Add New Product</a>
						
			         	<?php
			         }
			         ?>
		          </div>
		          <div class="clearfix"></div>
		    </header>
		    <div class="panel-body">
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
								
						$search = $this->session->userdata('product_inventory_search');
						
						if(!empty($search))
						{
							$search_result = '<a href="'.site_url().'inventory/close-product-search" class="btn btn-success btn-sm">Close Search</a>';
						}


						$result = '';	
						$result .= ''.$search_result2.'';
						$result .= '
								';

						
						
						//if users exist display them
						if ($query->num_rows() > 0)
						{
							$count = $page;

							if($type == 1)
							{
								$cols_items ='';
								$colspan = 2;
							}
							else
							{
								$cols_items = 
										'
										<th class="table-sortable:default table-sortable" title="Click to sort">Unit Price</th>
		                                <th class="table-sortable:default table-sortable" title="Click to sort">33% MU</th>
		                                <th class="table-sortable:default table-sortable" title="Click to sort">Opening</th>
		                                <th class="table-sortable:default table-sortable" title="Click to sort">P</th>
		                                <th class="table-sortable:default table-sortable" title="Click to sort">S</th>
		                                <th class="table-sortable:default table-sortable" title="Click to sort">D</th>
		                                ';
		                         $colspan = 6;
							}
							$result .= 
							'
							<div class="row">
							<div class="col-md-12">
								<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
								 
								  <thead> 
		                                <th>#</th>
		                                <th class="table-sortable:default table-sortable" title="Click to sort">Store</th>
		                                <th class="table-sortable:default table-sortable" title="Click to sort">Name</th>
		                                <th class="table-sortable:default table-sortable" title="Click to sort">Category</th>
		                                '.$cols_items.'
		                                <th class="table-sortable:default table-sortable" title="Click to sort">Stock</th>
		                                <th>Status</th>
		                                <th colspan="'.$colspan.'">Actions</th>
		                            </thead>
								  <tbody>
							';
							
							//get all administrators
							$personnel_query = $this->personnel_model->get_all_personnel();
							
							foreach ($query->result() as $row)
							{
								$product_id = $row->product_id;
								$product_name = $row->product_name;
								$product_status = $row->product_status;
								$product_description = $row->product_description;
								$store_name = $row->store_name;
								$category_id = $row->category_id;
								$created = $row->created;
								$created_by = $row->created_by;
								$last_modified = $row->last_modified;
								$modified_by = $row->modified_by;
								$category_name = $row->category_name;
								$store_id = $row->store_id;
								$quantity = $row->quantity;

								$product_unitprice = $row->product_unitprice;
		                        
		                        $product_deleted = $row->product_deleted;
		                        $in_service_charge_status = $row->in_service_charge_status;

								
								//status
								if($product_status == 1)
								{
									$status = 'Active';
								}
								else
								{
									$status = 'Disabled';
								}

								
							
								//create deactivated status display
								if($product_status == 0)
								{
									$status = '<span class="label label-danger">Deactivated</span>';
									$button = '<a class="btn btn-info btn-sm" href="'.site_url().'inventory/activate-product/'.$product_id.'" onclick="return confirm(\'Do you want to activate '.$product_name.'?\');">Activate</a>';
								}
								//create activated status display
								else if($product_status == 1)
								{
									$status = '<span class="label label-success">Active</span>';
									$button = '<a class="btn btn-default btn-sm" href="'.site_url().'inventory/deactivate-product/'.$product_id.'" onclick="return confirm(\'Do you want to deactivate '.$product_name.'?\');">Deactivate</a>';
								}

								if($product_deleted == 0)
								{
									$button = '<a href="'.site_url().'/inventory/activation/deactivate/'.$page.'/'.$product_id.'" class="btn btn-sm btn-default" onclick="return confirm(\'Do you want to disable '.$product_name.'?\');">Disable</a>';
								}
								
								else
								{
									$button = '<a href="'.site_url().'/inventory/activation/activate/'.$page.'/'.$product_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you want to enable '.$product_name.'?\');">Enable</a>';
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

								if($type == 1)
								{
									$in_stock = $quantity;
									$other_items ='';
									$button_two = '';

								}
								else
								{
									$markup = round(($product_unitprice * 1.33), 0);
									$markdown = $markup;//round(($markup * 0.9), 0);


									$purchases = $this->inventory_management_model->item_purchases($product_id);
			                       
			                        $deductions = $this->inventory_management_model->item_deductions($product_id);
			                   


			                        if($in_service_charge_status == 1)
			                        {
			                        	 $sales = $this->inventory_management_model->get_drug_units_sold($product_id,$type);
			                        }
			                        else
			                        {
			                        	$sales =0;
			                        }
			                        $in_stock = ($quantity + $purchases) - $sales - $deductions;

									$other_items = 
										'
										 <td>'.$product_unitprice.'</td>
		                                <td>'.$markup.'</td>						         
		                                <td>'.$quantity.'</td>						         
		                                <td>'.$purchases.'</td>
		                                <td>'.$sales.'</td>						         
		                                <td>'.$deductions.'</td>

										';
									$button_two = ' <td><a href="'.site_url().'inventory/edit-product/'.$product_id.'" class="btn btn-sm btn-primary">Edit</a></td>
					                                <td><a href="'.site_url().'inventory/product-purchases/'.$product_id.'" class="btn btn-sm btn-warning">Purchases</a></td>
					                              ';
								}

								
								

								$count++;
								
								$result .= 
								'
									<tr>
										<td>'.$count.'</td>
										<td>'.$store_name.'</td>
										<td>'.$product_name.'</td>
										<td>'.$category_name.'</td>		         
		                                '.$other_items.'
		                                <td>'.$in_stock.'</td>
		                                <td>'.$status.'</td>
		                                '.$button_two.'
		                                <td>'.$button.'</td>
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
							$result .= '';
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