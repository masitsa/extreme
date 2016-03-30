<?php
//shipping data

if($item_id > 0)
{
	$query = $this->items_model->get_item($item_id);
	$product = $query->result();
	
	$category_id = $product[0]->category_id;
	$product_id = $product[0]->product_id;
	$product_name = $product[0]->product_name;
	$product_status = $product[0]->product_status;
	$product_description = $product[0]->product_description;
	$category_name = $product[0]->category_name;
    $unit_of_measure = $product[0]->unit_of_measure;
    $reorder_level = $product[0]->reorder_level;
    $brand_id = $product[0]->brand_id;
    $generic_id = $product[0]->generic_id;
    $class_id = $product[0]->class_id;
    $drug_type_id = $product[0]->drug_type_id;

	$v_errors = validation_errors();
	

}

else
{
	

	$product_name = set_value('product_name');
	$product_status = set_value('product_status');
	$product_description = set_value('product_description');
	$category_id = '';
	$category_name = set_value('category_name');

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
        <h2 class="panel-title">Add new Item</h2>
    </header>
    <div class="panel-body">
          	<a href="<?php echo site_url().'inventory/item';?>" class="btn btn-sm btn-info pull-right">Back to items</a>
            
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
                                                 <label class="col-lg-4 control-label">Unit of measure: </label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="unit_of_measure" placeholder="Unit of Measure" value="<?php echo set_value('unit_of_measure');?>">
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
                                                    <label class="col-lg-4 control-label">Quantity: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="quantity" placeholder="quantity" value="<?php echo set_value('quantity');?>">
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