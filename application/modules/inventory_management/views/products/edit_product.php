<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title pull-left">Edit Product</h2>
        <div class="widget-icons pull-right">
        	<?php if($module == 'a'){?>
             <a href="<?php echo site_url().'pharmacy-setup/inventory';?>" class="btn btn-sm btn-info">Back to inventory</a>
        	<?php }else{?>
             <a href="<?php echo site_url().'inventory/products';?>" class="btn btn-sm btn-info">Back to products</a>
        	<?php }?>
        </div>
        <div class="clearfix"></div>
    </header>
    <div class="panel-body">
     <link href="<?php echo base_url()."assets/themes/jasny/css/jasny-bootstrap.css"?>" rel="stylesheet"/>

     
        <!-- Adding Errors -->
        <?php
		
		//the product details
		$category_id = $product[0]->category_id;
		$product_id = $product[0]->product_id;
		$product_name = $product[0]->product_name;
		$product_status = $product[0]->product_status;
		$product_description = $product[0]->product_description;
		$category_name = $product[0]->category_name;

        $store_id = $product[0]->store_id;
        $batch_no = $product[0]->batch_no;
        $quantity = $product[0]->quantity;
        $product_pack_size = $product[0]->product_packsize;
        $product_unitprice = $product[0]->product_unitprice;

        $unit_of_measure = $product[0]->unit_of_measure;
        $reorder_level = $product[0]->reorder_level;
        $brand_id = $product[0]->brand_id;
        $generic_id = $product[0]->generic_id;
        $class_id = $product[0]->class_id;
        $drug_type_id = $product[0]->drug_type_id;
		
        
        $validation_errors = validation_errors();
        
        if(!empty($validation_errors))
		{
			
			$product_name = set_value('product_name');
			$product_status = set_value('product_status');
			$product_description = set_value('product_description');
			$category_name = set_value('category_name');
            $store_id = set_value('store_id');
            $batch_no = set_value('batch_no');
            $quantity = set_value('quantity');
            $product_pack_size = set_value('product_pack_size');
            $product_unitprice = set_value('product_unitprice');
            $reorder_level = set_value('reorder_level');
            $brand_id = set_value('brand_id');
            $generic_id = set_value('generic_id');
            $class_id = set_value('class_id');
            $class_id = set_value('class_id');
            $drug_type_id = set_value('drug_type_id');
            $unit_of_measure = set_value('unit_of_measure');
        }
		
		
		
        ?>
        
        <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
        <!-- Adding Errors -->
        <?php
        if(!empty($validation_errors))
        {
            echo '<div class="alert alert-danger center-align"> Oh snap! '.$validation_errors.' </div>';
        }
		$error = $this->session->userdata('error_message');
		$success = $this->session->userdata('success_message');
		if(!empty($error))
		{
			echo '<div class="alert alert-danger">'.$error.'</div>';
			$this->session->unset_userdata('error_message');
		}
		
		if(!empty($success))
		{
			echo '<div class="alert alert-success">'.$success.'</div>';
			$this->session->unset_userdata('success_message');
		}
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
                                    foreach($drug_types as $res_drug_type)
                                    {
                                        $drug_type_name = $res_drug_type->drug_type_name;
                                        
                                        if($res_drug_type->drug_type_id == $drug_type_id)
                                        {
                                            echo '<option value="'.$res_drug_type->drug_type_id.'" selected>'.$drug_type_name.'</option>';
                                        }
                                        
                                        else
                                        {
                                            echo '<option value="'.$res_drug_type->drug_type_id.'">'.$drug_type_name.'</option>';
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

                <!-- Activate checkbox -->
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
                        <input type="text" class="form-control" name="product_unitprice" placeholder="Unit Price" value="<?php echo $product_unitprice;?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-4 control-label">Pack Size: </label>
                    
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="product_pack_size" placeholder="Pack Size" value="<?php echo $product_pack_size;?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">Opening Quantity: </label>
                    
                    <div class="col-lg-8">
                        <input type="text" class="form-control" name="quantity" placeholder="Opening Quantity:" value="<?php echo $quantity;?>">
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
                                    foreach($drug_brands as $res_brand)
                                    {
                                        $brand_name = $res_brand->brand_name;
                                        
                                        if($res_brand->brand_id == $brand_id)
                                        {
                                            echo '<option value="'.$res_brand->brand_id.'" selected>'.$brand_name.'</option>';
                                        }
                                        
                                        else
                                        {
                                            echo '<option value="'.$res_brand->brand_id.'">'.$brand_name.'</option>';
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
                                    foreach($drug_classes as $res_class)
                                    {
                                        $class_name = $res_class->class_name;
                                        
                                        if($res_class->class_id == $class_id)
                                        {
                                            echo '<option value="'.$res_class->class_id.'" selected>'.$class_name.'</option>';
                                        }
                                        
                                        else
                                        {
                                            echo '<option value="'.$res_class->class_id.'">'.$class_name.'</option>';
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
                                    foreach($drug_generics as $res_generic)
                                    {
                                        $generic_name = $res_generic->generic_name;
                                        
                                        if($res_generic->generic_id == $generic_id)
                                        {
                                            echo '<option value="'.$res_generic->generic_id.'" selected>'.$generic_name.'</option>';
                                        }
                                        
                                        else
                                        {
                                            echo '<option value="'.$res_generic->generic_id.'">'.$generic_name.'</option>';
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
                    Edit product
                </button>
            </div>
        </div>
        <?php echo form_close();?>
        
      
	</div>
</section>
<script src="<?php echo base_url().'assets/themes/tinymce/js/';?>tinymce.min.js"></script>
<script>tinymce.init({selector:'textarea'});</script>
