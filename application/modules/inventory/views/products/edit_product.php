<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title pull-left">Edit Product</h2>
        <div class="widget-icons pull-right">
             <a href="<?php echo site_url().'inventory/products';?>" class="btn btn-sm btn-info">Back to products</a>
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
		
        
        $validation_errors = validation_errors();
        
        if(!empty($validation_errors))
		{
			
			$product_name = set_value('product_name');
			$product_status = set_value('product_status');
			$product_description = set_value('product_description');
			$category_name = set_value('category_name');
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
            </div>
            <div class="col-md-6">
            
               
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
