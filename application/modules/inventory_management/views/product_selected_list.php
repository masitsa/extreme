<?php
$current_query = $this->inventory_management_model->get_store_request($store_id);
?>
<table class='table table-striped table-hover table-condensed'>
	<tr>
		<th>No.</th>
    	<th>Product Name</th>
		<th>QTY Requested</th>
		<th colspan="2">Actions</th>
	</tr>
	<tbody>
	<?php
		if($current_query->num_rows() > 0)
		{
			$count = 0;
			foreach ($current_query->result() as $key) {
				$product_id = $key->product_id;
				$product_name = $key->product_name;
				$quantity_requested = $key->quantity_requested;
				$product_deductions_id = $key->product_deductions_id;
				$count++;
				?>
				
					<tr>
			        	<td><?php echo $count;?></td>
						<td><?php echo $product_name;?></td>
						<td><input type="text" class="form-control" id="quantity<?php echo $product_deductions_id;?>" size="2" value="<?php echo $quantity_requested;?>"></td>
						<td><a id="update_action_point_form" class='btn btn-warning btn-sm fa fa-edit' onclick="change_quantity(<?php echo $product_deductions_id;?>,<?php echo $store_id;?>)" href="#" > Update quantity</a>
						<a class='btn btn-danger btn-sm fa fa-trash' href='#' onclick=''> Remove from order</a></td>
						
					</tr>
				
				<?php
			}
		}
	?>
		
	</tbody>
</table>
<div class="row">
	<div class="col-md-12 center-align">
		<a class='btn btn-sm btn-success' onclick='finish_making_request()'> Done Making Order </a>
	</div>
</div>
