<?php
	$item_name = set_value('item_name');
	$item_status_id = set_value('item_status_id');
	$item_description = set_value('item_description');
	$category_id = '';
	$category_name = set_value('category_name');
	$condition_id = set_value ('condition_id');
	$asset_id = set_value('asset_id');
	$asset_barcode = set_value('asset_barcode');
	$serial_number = set_value('serial_number');
	$condition_id = set_value('condition_id');
	$location_id = set_value('location_id');
	$condition = set_value('condition');
	$scrap_value = set_value('scrap_value');
	$status = set_value('status');
	$purchase_price = set_value('purchase_price');
	$inventory_description = set_value('inventory_description');
?>
<link href="<?php echo base_url()."assets/themes/jasny/css/jasny-bootstrap.css"?>" rel="stylesheet"/>

<section class="panel panel-featured panel-featured-info">

<?php
	
	if($inventory_details->num_rows() > 0)
	{
		$count = 0;
		
		$inventory_result = 
		'
		<table class="table table-bordered table-striped table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>Item Name</th>
					<th>Condition</th>
					<th>Location</th>
					<th>Barcode</th>
					<th>Serial Number</th>
					<th>Action</th>
				</tr>
			</thead>
			  <tbody>
			  
		';
		foreach ($inventory_details->result() as $row)
		{
			$inventory_data['row'] = $row;
			$item_id = $row->item_id;
			$condition_id = $row->condition_id;
			$inventory_id = $row->inventory_id;
			$location_id = $row->location_id;
			$serial_number= $row->serial_number;
			$barcode= $row->barcode_name;
			$condition = $this->items_model->get_condition_name($condition_id);
			if($condition->num_rows>0)
			{
				foreach($condition->result() as $rows)
				{
					$condition_name = $rows->condition_name;
				}
			}
			else
			{ 
				$condition_name = '-';
			}
			$location = $this->items_model->get_location_name($location_id);
			if($location->num_rows>0)
			{
				foreach($location->result() as $rows)
				{
					$location_name = $rows->location_name;
				}
			}
			else
			{ 
				$location_name = '-';
			}
			$item = $this->items_model->get_item_name($item_id);
			if($item->num_rows>0)
			{
				foreach($item->result() as $rows)
				{
					$item_name = $rows->item_name;
				}
			}
			else
			{ 
				$item_name = '-';
			}
			$count++;
			$inventory_result .= 
			'
				<tr>
					<td>'.$count.'</td>
					<td>'.$item_name.'</td>
					<td>'.$condition_name.'</td>
					<td>'.$location_name.'</td>
					<td>'.$serial_number.'</td>
					<td>'.$barcode.'</td>
					<td>
						<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit_inventory_item'.$inventory_id.'"><i class="fa fa-pencil"></i>Edit</button>
						<div class="modal fade" id="edit_inventory_item'.$inventory_id.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										<h4 class="modal-title" id="myModalLabel">Edit '.$item_name.' '.$barcode.' </h4>
									</div>
									<div class="modal-body">
										'.$this->load->view('edit/edit_inventory',$inventory_data,TRUE).'
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									</div>
								</div>
							</div>
						</div>
					</td>
					<td><a href="'.site_url().'inventory/delete-inventory-item/'.$inventory_id.'/" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you really want to delete this inventory item?\');" title="Delete"><i class="fa fa-trash"></i></a></td>
				</tr> 
			';
		}
		
		$inventory_result .= 
		'
					  </tbody>
					</table>
		';
	}
	
	else
	{
		$inventory_result = "<p>No inventory for this item has been added</p>";
	}
//repopulate data if validation errors occur
$validation_error = validation_errors();
?>
<header class="panel-heading">
        <h2 class="panel-title">Add Item Inventory</h2>
        </header>
    <div class="panel-body">

        <div class="pull-right">
        	<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_inventory_item"><i class="fa fa-plus"></i>Add</button>
            <a href="<?php echo site_url().'inventory/item';?>" class="btn btn-sm btn-info pull-right">Back to Items</a>
         </div>
         <div class="modal fade" id="add_inventory_item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Add Item Inventory</h4>
                    </div>
                    <div class="modal-body">
                        <?php 
									
						echo form_open('inventory/inventory-add-item/'.$item_id.'', array("class" => "form-horizontal", "role" => "form"));
						?>
							<div class="row">
							
							<div class="col-md-6">
							   <div class="form-group">
									 <label class="col-lg-4 control-label">Asset Barcode:</label>
										
										<div class="col-lg-8">
										   <input type="text" class="form-control" name="asset_barcode" placeholder="Asset Barcode" value="<?php echo set_value('asset_barcode');?>">
										</div>
								 </div>
								 <div class="form-group">
									 <label class="col-lg-4 control-label">Serial Number:</label>
										
									<div class="col-lg-8">
										   <input type="text" class="form-control" name="serial_number" placeholder="Serial Number" value="<?php echo set_value('serial_number');?>">
									</div>
								  </div>
								  <div class="form-group">
									 <label class="col-lg-4 control-label">Location: </label>
										
										<div class="col-lg-8">
										   <select name="location_id" id="location_id" class="form-control">
												<?php
												echo '<option value="0">No Location </option>';
												if($all_locations->num_rows() > 0)
												{
													$result = $all_locations->result();
													
													foreach($result as $res)
													{
														if($res->location_id == $location_id)
														{
															echo '<option value="'.$res->location_id.'" selected>'.$res->location_name.' '.$res->location_id.'</option>';
														}
														else
														{
															echo '<option value="'.$res->location_id.'">'.$res->location_name.' </option>';
														}
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-md-6">
								<div class="form-group">
									 <label class="col-lg-4 control-label">Condition:</label>
										
										<div class="col-lg-8">
										 <select name="condition_id" id="condition_id" class="form-control">
												<?php
												echo '<option value="0">No Condition </option>';
												if($all_conditions->num_rows() > 0)
												{
													$result = $all_conditions->result();
													
													foreach($result as $res)
													{
														if($res->condition_id == $condition_id)
														{
															echo '<option value="'.$res->condition_id.'" selected>'.$res->condition_name.'</option>';
														}
														else
														{
															echo '<option value="'.$res->condition_id.'">'.$res->condition_name.' </option>';
														}
													}
												}
												?>
											</select>
										</div>
										</div>
									   <div class="form-group">
									 <label class="col-lg-4 control-label">Status:</label>
									 <div class="col-lg-8">
										   <select name="status_id" id="status_id" class="form-control">
												<?php
												echo '<option value="0">No Status </option>';
												if($all_status->num_rows() > 0)
												{
													$result = $all_status->result();
													
													foreach($result as $res)
													{
														if($res->item_status_id == $item_status_id)
														{
															echo '<option value="'.$res->item_status_id.'" selected>'.$res->item_status_name.'</option>';
														}
														else
														{
															echo '<option value="'.$res->item_status_id.'">'.$res->item_status_name.' </option>';
														}
													}
												}
												?>
											</select>
											</div>
									</div>
								</div>
							</div>
							<br/>
							 <div class="form-group">
									  <label class="col-lg-2 control-label">Inventory Description</label>
									  <div class="col-lg-10">
										<textarea name="inventory_description" class="form-control"><?php echo $inventory_description;?></textarea>
									  </div>
									</div>
							<br>
							 <div class="row">   
								<div class="form-actions center-align">
									<button class="submit btn btn-primary" type="submit">
										Add Item Inventory
									</button>
								</div>
							</div>
								<?php echo form_close();?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
                
                
		 <?php 
				echo $inventory_result;
			?>
    </div>    
</section>

<script type="text/javascript">
     function discount_type(id){

        var myTarget1 = document.getElementById("percentage_div");
        var myTarget2 = document.getElementById("amount_div");
        if(id == 1)
        {
          myTarget1.style.display = 'none';
          myTarget2.style.display = 'none';
        }
        else if(id == 2)
        {
          myTarget1.style.display = 'block';
          myTarget2.style.display = 'none';
        }
        else if(id == 3)
        {
          myTarget1.style.display = 'none';
          myTarget2.style.display = 'block';
        }
        else
        {
          myTarget1.style.display = 'none';
          myTarget2.style.display = 'none';
        }
        
    }
	$(document).on("change","select#category_id",function()
	{			
		value = $(this).val();
		
		var features = $.ajax(
		{
			url: '<?php echo site_url();?>vendor/items/get_category_features/'+value,
			processData: false,
			contentType: false,
			cache: true
		});
		features.done(function(code) {
			if((code == "null") || (code == null)){
				$('div#features').fadeIn('slow').html('');
			}
			else{
				$('div#features').fadeIn('slow').html(code);
			}
		});
	});
	
	//Add Feature
	$(document).on("submit","div#features_tab formz",function(e)
	{
		e.preventDefault();
		
		var formData = new FormData(this);
		
		var category_feature_id = $(this).attr('name');

		$.ajax({
			type:'POST',
			url: $(this).attr('action'),
			data:formData,
			cache:false,
			contentType: false,
			processData: false,
			dataType: 'json',
			success:function(data){
				
				if(data.result == "success")
				{
					$("#new_features"+category_feature_id).html(data.result_options);
					$("#cat_feature"+category_feature_id)[0].reset();
				}
				else
				{
					$("#new_features"+category_feature_id).html(data);
				}
			},
			error: function(xhr, status, error) {
				alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
				$("#new_features"+category_feature_id).html(error);
			}
		});
		return false;
	});
	
	//Delete Feature
	$(document).on("click","a.delete_feature",function()
	{
		var category_feature_id = $(this).attr('id');
		var delete_row = $(this).attr('href');
		var row = $.ajax(
		{
			url: '<?php echo site_url();?>vendor/items/delete_new_feature/'+category_feature_id+'/'+delete_row,
			processData: false,
			contentType: false,
			cache: true
		});
		row.done(function(data) {
			$("#new_features"+category_feature_id).html(data);
		});
		return false;
	});
</script>