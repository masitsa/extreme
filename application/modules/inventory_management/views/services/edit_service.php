<?php
//shipping data

if($service_id > 0)
{
	$query = $this->services_model->get_service($service_id);
	$product = $query->result();
	$service_status_id = $product[0]->service_status_id;
	$service_id =$product[0]->service_id;
	$service_price = $product[0]->service_price;
	$reorder_point = $product[0]->reorder_point;
	$service_name = $product[0]->service_name;
	$service_description = $product[0]->service_description;
    $quantity_on_hand = $product[0]->quantity_on_hand;
	$service_cost = $product[0]->service_cost;
	$quantity_on_purchase_order = $product[0]->quantity_on_purchase_order;
	
	$v_errors = validation_errors();
	

}

else
{
	

	$product_name = set_value('product_name');
	$product_status = set_value('product_status');
	$product_description = set_value('product_description');
	$service_status_id = set_value('service_status_id');
	$category_id = '';
	$category_name = set_value('category_name');
	$condition_id = set_value ('condition_id');
	$service_cost = set_value('service_cost');
	$service_name = set_value('service_name');
	$service_description = set_value('service_description');
	$quantity_on_sales_order = set_value('quantity_on_sales_order');
	$quantity_on_purchase_order = set_value('quantity_on_purchase_order');
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
    $quantity_on_hand = set_value('quantity_on_hand');
}

?>
<link href="<?php echo base_url()."assets/themes/jasny/css/jasny-bootstrap.css"?>" rel="stylesheet"/>
<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title">Edit Service</h2>
    </header>
    <div class="panel-body">
          	<a href="<?php echo site_url().'inventory/services';?>" class="btn btn-sm btn-info pull-right">Back to Services</a>
            
            <div class="row">
                <div class="col-md-12">
                    
                    <!-- Adding Errors -->
                    <?php
					
					if($service_id > 0)
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
                                    Service details </a>
                                </li>
                               
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="tab_default_1">
                                  
                                    <?php 
									
									echo form_open('inventory/edit-service/'.$service_id, array("class" => "form-horizontal", "role" => "form"));
									?>
                                     	<div class="row">
                                        	<div class="col-md-6">
                                                
                                                <!-- product Name -->
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Service Name <span class="required">*</span></label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="service_name" placeholder="Service Name" value="<?php echo $service_name;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Preffered Vendor <span class="required">*</span></label>
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
                                         
                                                 <div class="form-group">
                                                 <label class="col-lg-4 control-label">Quantity on Hand: </label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="quantity_on_hand" placeholder="Quantity on Hand" value="<?php echo set_value('quantity_on_hand');?>">
                                                    </div>
                                                </div>
                                                 <div class="form-group">
                                                 <label class="col-lg-4 control-label">Service Cost: </label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="service_cost" placeholder="Service Cost" value="<?php echo set_value('service_cost');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Activate service?</label>
                                                    <div class="col-lg-8">
                                                        <div class="radio">
                                                            <label>
                                                                <?php
                                                                if($service_status_id == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="product_status">';}
                                                                else{echo '<input id="optionsRadios1" type="radio" value="1" name="product_status">';}
                                                                ?>
                                                                Yes
                                                            </label>
                                                        </div>
                                                        <div class="radio">
                                                            <label>
                                                                <?php
                                                                if($service_status_id == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="product_status">';}
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
                                                 <label class="col-lg-4 control-label">Service Price: </label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="service_price" placeholder="Service Price" value="<?php echo set_value('service_price');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                 <label class="col-lg-4 control-label">Reorder Point: </label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="reorder_point" placeholder="Reorder Point" value="<?php echo set_value('reorder_point');?>">
                                                    </div>
                                                </div>
                                                 
                                                <div class="form-group">
                                                 <label class="col-lg-4 control-label">Quantity On Sales Order:</label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="quantity_on_sales_order" placeholder="Quantity On Sales Order" value="<?php echo set_value('quantity_on_sales_order');?>">
                                                    </div>
                                                </div>
                                                    <div class="form-group">
                                                 <label class="col-lg-4 control-label">Quantity on Purchase Order:</label>
                                                    
                                                    <div class="col-lg-8">
                                                       <input type="text" class="form-control" name="quantity_on_purchase_order" placeholder="Quantity on Purchase Order" value="<?php echo set_value('quantity_on_purchase_order');?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-lg-2 control-label">Service Description</label>
                                                  <div class="col-lg-10">
                                                    <textarea name="service_description" class="form-control"><?php echo $service_description;?></textarea>
                                                  </div>
                                                </div>
                                        	</div>
                                        </div>
                                        <br>
                                         <div class="row">   
                                            <div class="form-actions center-align">
                                                <button class="submit btn btn-primary" type="submit">
                                                   Edit Service
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