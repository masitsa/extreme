<?php
//shipping data

if($product_id > 0)
{
	$query = $this->products_model->get_product($product_id);
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
        <h2 class="panel-title">Add new Product</h2>
    </header>
    <div class="panel-body">
          	<a href="<?php echo site_url().'inventory/products';?>" class="btn btn-sm btn-info pull-right">Back to products</a>
            
            <div class="row">
                <div class="col-md-12">
                    
                    <!-- Adding Errors -->
                    <?php
					
					if($product_id > 0)
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
                                    Product details </a>
                                </li>
                               
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">
                                  
                                    <?php 
									
									echo form_open('inventory/add-product', array("class" => "form-horizontal", "role" => "form"));
									?>
                                     	<div class="row">
                                        	<div class="col-md-6">
                                                
                                                <!-- product Name -->
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Product Name <span class="required">*</span></label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="product_name" placeholder="Product Name" value="<?php echo $product_name;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Store <span class="required">*</span></label>
                                                    <div class="col-lg-8">
                                                        <select name="store_id" id="store_id" class="form-control">
                                                            <?php
                                                            echo '<option value="0">No Category</option>';
                                                            if($all_stores->num_rows() > 0)
                                                            {
                                                                $result = $all_stores->result();
                                                                
                                                                foreach($result as $res_store)
                                                                {
                                                                    if($res_store->store_id == $store_id)
                                                                    {
                                                                        echo '<option value="'.$res_store->store_id.'" selected>'.$res_store->store_name.'</option>';
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '<option value="'.$res_store->store_id.'">'.$res_store->store_name.'</option>';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div> 
                                                <!-- Product Category -->
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Product Category <span class="required">*</span></label>
                                                    <div class="col-lg-8">
                                                        <select name="category_id" id="category_id" class="form-control">
                                                            <?php
                                                            echo '<option value="0">No Category</option>';
                                                            if($all_categories->num_rows() > 0)
                                                            {
                                                                $result = $all_categories->result();
                                                                
                                                                foreach($result as $res)
                                                                {
                                                                    if($res->category_id == $category_id)
                                                                    {
                                                                        echo '<option value="'.$res->category_id.'" selected>'.$res->category_name.'</option>';
                                                                    }
                                                                    else
                                                                    {
                                                                        echo '<option value="'.$res->category_id.'">'.$res->category_name.'</option>';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div> 
                                                 <div class="form-group">
                                                        <label class="col-lg-4 control-label">Drug Type: </label>
                                                        
                                                        <div class="col-lg-8">
                                                            <select class="form-control" name="drug_type_id">
                                                                <?php
                                                                    if(count($drug_types) > 0)
                                                                    {
                                                                        foreach($drug_types as $res)
                                                                        {
                                                                            $drug_type_id = $res->drug_type_id;
                                                                            $drug_type_name = $res->drug_type_name;
                                                                            
                                                                            if($drug_type_id == set_value("drug_type_id"))
                                                                            {
                                                                                echo '<option value="'.$drug_type_id.'" selected>'.$drug_type_name.'</option>';
                                                                            }
                                                                            
                                                                            else
                                                                            {
                                                                                echo '<option value="'.$drug_type_id.'">'.$drug_type_name.'</option>';
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <!-- Activate checkbox -->
                                                 <div class="form-group">
                                                    <label class="col-lg-4 control-label">Unit of measure: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="unit_of_measure" placeholder="Units of Measure" value="<?php echo $unit_of_measure;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Activate product?</label>
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
                                                    <label class="col-lg-4 control-label">Unit Price: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="product_unitprice" placeholder="Pack Size" value="<?php echo set_value('product_unit_price');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Pack Size: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="product_pack_size" placeholder="Pack Size" value="<?php echo set_value('product_pack_size');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Opening Quantity: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="quantity" placeholder="Opening Quantity:" value="<?php echo set_value('quantity');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Re order Level: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="reorder_level" placeholder="Reorder Level" value="<?php echo $reorder_level;?>">
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Brand: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <select class="form-control" name="brand_id">
                                                            <?php
                                                                if(count($drug_brands) > 0)
                                                                {
                                                                    foreach($drug_brands as $res)
                                                                    {
                                                                        $brand_id = $res->brand_id;
                                                                        $brand_name = $res->brand_name;
                                                                        
                                                                        if($brand_id == set_value("brand_id"))
                                                                        {
                                                                            echo '<option value="'.$brand_id.'" selected>'.$brand_name.'</option>';
                                                                        }
                                                                        
                                                                        else
                                                                        {
                                                                            echo '<option value="'.$brand_id.'">'.$brand_name.'</option>';
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Class: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <select class="form-control" name="class_id">
                                                            <?php
                                                                if(count($drug_classes) > 0)
                                                                {
                                                                    foreach($drug_classes as $res)
                                                                    {
                                                                        $class_id = $res->class_id;
                                                                        $class_name = $res->class_name;
                                                                        
                                                                        if($class_id == set_value("class_id"))
                                                                        {
                                                                            echo '<option value="'.$class_id.'" selected>'.$class_name.'</option>';
                                                                        }
                                                                        
                                                                        else
                                                                        {
                                                                            echo '<option value="'.$class_id.'">'.$class_name.'</option>';
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Generic: </label>
                                                    
                                                    <div class="col-lg-8">
                                                        <select class="form-control" name="generic_id">
                                                            <?php
                                                                if(count($drug_generics) > 0)
                                                                {
                                                                    foreach($drug_generics as $res)
                                                                    {
                                                                        $generic_id = $res->generic_id;
                                                                        $generic_name = $res->generic_name;
                                                                        
                                                                        if($generic_id == set_value("generic_id"))
                                                                        {
                                                                            echo '<option value="'.$generic_id.'" selected>'.$generic_name.'</option>';
                                                                        }
                                                                        
                                                                        else
                                                                        {
                                                                            echo '<option value="'.$generic_id.'">'.$generic_name.'</option>';
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                               
                                            </div>
                                        </div>
                                        <br>
                                            
                                        <div class="row">
                                        	<div class="col-md-12">
                                                <!-- Product Description -->
                                                <div class="form-group">
                                                  <label class="col-lg-2 control-label">Product Description <span class="required">*</span></label>
                                                  <div class="col-lg-10">
                                                    <textarea name="product_description" class="form-control"><?php echo $product_description;?></textarea>
                                                  </div>
                                                </div>
                                        	</div>
                                        </div>
                                        <br>
                                         <div class="row">   
                                            <div class="form-actions center-align">
                                                <button class="submit btn btn-primary" type="submit">
                                                    Update product
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