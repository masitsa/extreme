<?php
//shipping data

if($item_id > 0)
{
	$query = $this->items_model->get_item($item_id);
	$product = $query->result();
	
	$category_id = $product[0]->category_id;
	$product_id = $product[0]->product_id;
	$checked_out = $product[0]->checked_out;
	$location = $product[0]->location;
	$product_name = $product[0]->product_name;
	$product_status = $product[0]->product_status;
	$product_description = $product[0]->product_description;
	$category_name = $product[0]->category_name;
    $unit_of_measure = $product[0]->unit_of_measure;
	$model = $product[0]->model;
	$purchase_price= $product[0]->purchase_price;
	$scrap_value= $product[0]->scrap_value;
	$brand = $product[0]->brand;
	$manufacturer = $product[0]->manufacturer;
	$asset_id = $product[0]->asset_id;
	$serial_number = $product[0]->serial_number;
    $reorder_level = $product[0]->reorder_level;
    $brand_id = $product[0]->brand_id;
    $generic_id = $product[0]->generic_id;
    $class_id = $product[0]->class_id;
    $drug_type_id = $product[0]->drug_type_id;
	$status = $product[0]->product;
	$condition_id = $product[0]->condition_id;
	$condition = $product[0]->condition;
	$v_errors = validation_errors();
	

}

else
{
	

	$product_name = set_value('product_name');
	$product_status = set_value('product_status');
	$product_description = set_value('product_description');
	$category_id = '';
	$category_name = set_value('category_name');
	$condition_id = set_value ('condition_id');
	$asset_id = set_value('asset_id');
	$asset_barcode = set_value('asset_barcode');
	$serial_number = set_value('serial_number');
	$condition_id = set_value('condition_id');
	$condition = set_value('condition');
	$scrap_value = set_value('scrap_value');
	$status = set_value('status');
	$purchase_price = set_value('purchase_price');
	$model = set_value('model');
	$brand = set_value('brand');
	$manufacturer = set_value('manufacturer');
    $reorder_level = set_value('reorder_level');
    $brand_id = set_value('brand_id');
    $generic_id = set_value('generic_id');
    $class_id = set_value('class_id');
    $class_id = set_value('class_id');
    $drug_type_id = set_value('drug_type_id');
    $unit_of_measure = set_value('unit_of_measure');
}

?>
<link href="<?php echo base_url()."assets/themes/jasny/css/jasny-bootstrap.css"?>" rel="stylesheet"/>
<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title">Add New Item</h2>
    </header>
    <div class="panel-body">
          	<a href="<?php echo site_url().'inventory/item';?>" class="btn btn-sm btn-info pull-right">Back to Items</a>
            
            <div class="row">
                <div class="col-md-12">
                    
                    <!-- Adding Errors -->
                    <?php
					
					if($item_id > 0)
					{
						echo '';
					}
					
					else
					{
						echo '';
					}
                    if(isset($error)){
                        echo '<div class="alert alert-danger center-align alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Oh snap! '.$error.' </div>';
                    }
                    
                    $validation_errors = validation_errors();
                    
                    if(!empty($validation_errors))
                    {
                        echo '<div class="alert alert-danger center-align alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Oh snap! '.$validation_errors.' </div>';
                    }
                    ?>
        
                    <div class="tabbable-panel">
                        <div class="tabbable-line">
                            <ul class="nav nav-tabs ">
                                <li class="active">
                                    <a href="#tab_default_1" data-toggle="tab">
                                    Item details </a>
                                </li>
                               
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">
                                  
                                    <?php 
									
									echo form_open('inventory/add-item', array("class" => "form-horizontal", "role" => "form"));
									?>
                                     	<div class="row">
                                        	<div class="col-md-6">
                                                
                                                <!-- product Name -->
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Item Name <span class="required">*</span></label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="item_name" placeholder="Item Name" value="<?php echo $product_name;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Supplier <span class="required">*</span></label>
                                                    <div class="col-lg-8">
                                                        <select name="store_id" id="store_id" class="form-control">
                                                            <?php
                                                            echo '<option value="0">No Supplier</option>';
                                                            if($all_suppliers->num_rows() > 0)
                                                            {
                                                                $result = $all_suppliers->result();
                                                                
                                                                foreach($result as $res_store)
                                                                {
                                                                    if($res_store->supplier_id == $supplier_id)
                                                                    {
                                                                        echo '<option value="'.$res_store->supplier_id.'" selected>'.$res_store->supplier_name.'</option>';
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '<option value="'.$res_store->supplier_id.'">'.$res_store->supplier_name.'</option>';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div> 
                                                <!-- Product Category -->
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Item Category <span class="required">*</span></label>
                                                    <div class="col-lg-8">
                                                        <select name="item_category_id" id="category_id" class="form-control">
                                                            <?php
                                                            echo '<option value="0">No Category </option>';
                                                            if($all_categories->num_rows() > 0)
                                                            {
                                                                $result = $all_categories->result();
                                                                
                                                                foreach($result as $res)
                                                                {
                                                                    if($res->item_category_id == $item_category_id)
                                                                    {
                                                                        echo '<option value="'.$res->item_category_id.'" selected>'.$res->category_name.' '.$res->item_category_id.'</option>';
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '<option value="'.$res->item_category_id.'">'.$res->category_name.' </option>';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div> 
                                             <div class="form-group">
                                                 <label class="col-lg-4 control-label">Condition ID:</label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="condition_id" placeholder="Condition ID" value="<?php echo set_value('condition_id');?>">
                                                    </div>
                                                    </div>
                                                 <div class="form-group">
                                                 <label class="col-lg-4 control-label">Unit of measure: </label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="unit_of_measure" placeholder="Unit of Measure" value="<?php echo set_value('unit_of_measure');?>">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                 <label class="col-lg-4 control-label">Asset Id: </label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="asset_id" placeholder="Asset Id" value="<?php echo set_value('asset_id');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                 <label class="col-lg-4 control-label">Checked Out: </label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="checked_out" placeholder="Checked Out" value="<?php echo set_value('checked_out');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                 <label class="col-lg-4 control-label">Location: </label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="location" placeholder="Location" value="<?php echo set_value('location');?>">
                                                    </div>
                                                </div>
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
                                                 <label class="col-lg-4 control-label">Status:</label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="status" placeholder="Status" value="<?php echo set_value('status');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Activate item?</label>
                                                    <div class="col-lg-8">
                                                        <div class="radio">
                                                            <label>
                                                                <?php
                                                                if($product_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="product_status">';}
                                                                else{echo '<input id="optionsRadios1" type="radio" value="1" name="product_status">';}
                                                                ?>
                                                                Yes
                                                            </label>
                                                        </div>
                                                        <div class="radio">
                                                            <label>
                                                                <?php
                                                                if($product_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="product_status">';}
                                                                else{echo '<input id="optionsRadios1" type="radio" value="0" name="product_status">';}
                                                                ?>
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div> 

                                              
                                        	</div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Hiring Price: </label>
                                                    
                                                    <div class="col-lg-8">
                                                 
                                                      <input type="text" class="form-control" name="item_hiring_price" placeholder="Hiring Price" value="<?php echo set_value('item_hiring_price');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Minimum Hiring Price: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="minimum_hiring_price" placeholder="Minimum Price" value="<?php echo set_value('minimum_hiring_price');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Item Price: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="item_unit_price" placeholder="Price:" value="<?php echo set_value('item_unit_price');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Purchase Price: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="purchase_price" placeholder="Purchase Price:" value="<?php echo set_value('purchase_price');?>">
                                                    </div>
                                                    </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label
                                                </div>                                                    
">Scrap Value: </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="scrap_value" placeholder="Scrap Value:" value="<?php echo set_value('scrap_value');?>">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                    <label class="col-lg-4 control-label
                                                </div>                                                    
">Condition: </label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="condition" placeholder="Condition" value="<?php echo set_value('condition');?>">
                                                    </div>
                                                </div>
               	   <div class="form-group">
                                                    <label class="col-lg-4 control-label">Quantity: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="quantity" placeholder="quantity" value="<?php echo set_value('quantity');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                 <label class="col-lg-4 control-label">Model:</label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="model" placeholder="Model" value="<?php echo set_value('model');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                 <label class="col-lg-4 control-label">Brand:</label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="brand" placeholder="Brand" value="<?php echo set_value('brand');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                 <label class="col-lg-4 control-label">Manufacturer:</label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="manufacturer" placeholder="Manufacturer" value="<?php echo set_value('manufacturer');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-lg-2 control-label">Item Description</label>
                                                  <div class="col-lg-10">
                                                    <textarea name="item_description" class="form-control"><?php echo $product_description;?></textarea>
                                                  </div>
                                                </div>
                                        	</div>
                                        </div>
                                        <br>
                                         <div class="row">   
                                            <div class="form-actions center-align">
                                                <button class="submit btn btn-primary" type="submit">
                                                    Add Item
                                                </button>
                                            </div>
                                        </div>
                                            <?php echo form_close();?>
                                    
                                </div>
                              
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
    </section>
<script src="<?php echo base_url().'assets/themes/tinymce/js/';?>tinymce.min.js"></script>
<script>tinymce.init({selector:'textarea'});</script>
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
			url: '<?php echo site_url();?>vendor/products/get_category_features/'+value,
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
			url: '<?php echo site_url();?>vendor/products/delete_new_feature/'+category_feature_id+'/'+delete_row,
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