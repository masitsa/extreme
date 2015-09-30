<?php 
$v_data['all_categories'] = $all_categories;
echo $this->load->view('inventory/search/search_products', $v_data, TRUE); ?>

<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title pull-left"><?php echo $title;?></h2>
         <div class="widget-icons pull-right">
            	<a href="<?php echo base_url();?>inventory/import-product" class="btn btn-success btn-sm" style="margin-left:10px;">Import Product</a>
				<a href="<?php echo base_url();?>inventory/export-product" class="btn btn-success btn-sm" style="margin-left:10px;">Export Product</a>
				<a href="<?php echo base_url();?>inventory/add-product" class="btn btn-success btn-sm">Add Product</a>
			
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
						
				$search = $this->session->userdata('product_search');
				
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
					$result .= 
					'
					<div class="row">
					<div class="col-md-12">
						<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
						  <thead>
							<tr>
							  <th class="table-sortable:default table-sortable" title="Click to sort">#</th>
							  <th class="table-sortable:default table-sortable" title="Click to sort">Product Name</th>
							  <th class="table-sortable:default table-sortable" title="Click to sort">Date Created</th>
							  <th class="table-sortable:default table-sortable" title="Click to sort">Last Modified</th>
							  <th>Status</th>
							  <th colspan="6">Actions</th>
							</tr>
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
						$category_id = $row->category_id;
						$created = $row->created;
						$created_by = $row->created_by;
						$last_modified = $row->last_modified;
						$modified_by = $row->modified_by;
						$category_name = $row->category_name;
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
								<td>'.$product_name.'</td>
								<td>'.date('jS M Y H:i a',strtotime($row->created)).'</td>
								<td>'.date('jS M Y H:i a',strtotime($row->last_modified)).'</td>
								<td>'.$status.'</td>
								<td><a href="'.site_url().'inventory/edit-product/'.$product_id.'" class="btn btn-sm btn-success">Edit Product</a></td>
								<td>'.$button.'</td>
								<td><a href="'.site_url().'inventory/delete-product/'.$product_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete '.$product_name.'?\');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
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
	</div>
</section>