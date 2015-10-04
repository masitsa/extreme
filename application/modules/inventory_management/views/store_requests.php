<?php


$segment = 4;
$addition = '';
if($store_priviledges->num_rows() > 0)
{
	$count = 0;
	 $number_rows = $store_priviledges->num_rows();

	foreach ($store_priviledges->result() as $key) {
		# code...
		$store_parent = $key->store_parent;
		$store_id = $key->store_id;
		$count++;

		if($count == 1 AND $number_rows > $count)
		{
			$delimeter = '(';
		}
		else
		{
			$delimeter = '';
		}
		if($number_rows > 1 AND $number_rows == $count)
		{
			$back_delimeter = ')';
			
		}
		else
		{
			$back_delimeter = '';
		}

		if($count > 0 AND $number_rows != $count)
		{
			$or_addition = 'OR';
		}
		else
		{
			$or_addition = '';
		}
		
		$addition .= ' '.$delimeter.' product_deductions.store_id = '.$store_id.' '.$or_addition.' '.$back_delimeter.'';
		
	}
	


}
else
{
	$addition = '';
}

$where = 'product_deductions.store_id = store.store_id AND '.$addition.' ';
$table = 'product_deductions, store';

//pagination
$this->load->library('pagination');
$config['base_url'] = site_url().'inventory/manage-store';
$config['total_rows'] = $this->reception_model->count_items($table, $where);
$config['uri_segment'] = $segment;
$config['per_page'] = 20;
$config['num_links'] = 5;


$config['full_tag_open'] = '<ul class="pagination pull-right">';
$config['full_tag_close'] = '</ul>';

$config['first_tag_open'] = '<li>';
$config['first_tag_close'] = '</li>';

$config['last_tag_open'] = '<li>';
$config['last_tag_close'] = '</li>';

$config['next_tag_open'] = '<li>';
$config['next_link'] = 'Next';
$config['next_tag_close'] = '</span>';

$config['prev_tag_open'] = '<li>';
$config['prev_link'] = 'Prev';
$config['prev_tag_close'] = '</li>';

$config['cur_tag_open'] = '<li class="active"><a href="#">';
$config['cur_tag_close'] = '</a></li>';

$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
$this->pagination->initialize($config);

$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
$v_data["links"] = $this->pagination->create_links();
$query = $this->inventory_management_model->get_all_requests_made($table, $where, $config["per_page"], $page,'search_date');


?>
<div class="row">
	<div class="col-md-12">
		<table class="table table-hover table-bordered ">
	         <tbody>
	         	<?php
	         	$personnel_query = $this->personnel_model->get_all_personnel();
	         	if($query->num_rows() > 0)
	         	{
					foreach ($query->result() as $row)
					{
						$search_date = $row->search_date;
						// $store_name = $row->store_name;
						// $pieces = explode(" ", $search_date);
						// $date = $pieces[0];

						// get requests for this day
						$query_request = $this->inventory_management_model->get_orders_on_days_requests($search_date)
		         	?>
			         	<tr ><strong><?php echo $search_date;?></strong></tr>
			         	<tr>
						<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
							<thead> 
					            <th>#</th>
					             <th class="table-sortable:default table-sortable" title="Click to sort">Store Name</th>
					            <th class="table-sortable:default table-sortable" title="Click to sort">Item Name</th>
					            <th class="table-sortable:default table-sortable" title="Click to sort">QTY Ordered</th>
					            <th class="table-sortable:default table-sortable" title="Click to sort">QTY Given</th>
					            <th>Status</th>
					            <th class="table-sortable:default table-sortable" title="Click to sort">QTY Received</th>
					            <th>Action</th>
					        </thead>
							<tbody>
								<?php
								$counter = 0;
								if($query_request->num_rows() > 0)
								{

									foreach ($query_request->result() as $key) {
										# code...
										$product_name = $key->product_name;
										$product_id = $key->product_id;
										$quantity_requested = $key->quantity_requested;
										$quantity_received = $key->quantity_received;
										$quantity_given = $key->quantity_given;
										$store_name = $key->store_name;
										$product_deductions_id = $key->product_deductions_id;
										$product_deductions_status = $key->product_deductions_status;
										if($product_deductions_status == 0)
	                                    {
	                                        $status = '<span class="label label-warning">Not Awarded</span>';
	                                    }
	                                    //create activated status db2_field_display_size(stmt, column)
	                                    else if($product_deductions_status == 1)
	                                    {
	                                        $status = '<span class="label label-info">Awarded</span>';
	                                    }
	                                    else if($product_deductions_status == 2)
	                                    {
	                                        $status = '<span class="label label-success">Received</span>';
	                                    }
										$counter++;
									?>
										<tr>
											<td><?php echo $counter;?></td>
											<td><?php echo $store_name?></td>
											<td><?php echo $product_name?></td>
											<td><?php echo $quantity_requested;?></td>
											<td><?php echo $quantity_given?></td>
											<td><?php echo $status;?></td>
											<td><input type="text" class="form-control" id="quantity_received<?php echo $product_deductions_id;?>" name="quantity_received<?php echo $product_deductions_id;?>" size="1" value="<?php echo $quantity_received;?>"></td>
                                    		<td><a id="update_action_point_form"  onclick="receive_quantity(<?php echo $product_deductions_id;?>,<?php echo $store_id;?>,<?php echo $product_id;?>)" class="btn btn-sm btn-warning fa fa-pencil"> Received</a></td>
											<td></td>
										</tr>
									<?php
									}
								}
								?>
							</tbody>
						</table>
						</tr>
					<?php
					}
				}
					?>

			</tbody>
		</table>
	</div>
</div>
<div class="widget-foot">
                                
	<?php if(isset($links)){echo $links;}?>

    <div class="clearfix"></div> 

</div>
        
