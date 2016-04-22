<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title pull-left">Edit Item </h2>
        <div class="widget-icons pull-right">
          	<a href="<?php echo site_url().'inventory/item';?>" class="btn btn-sm btn-info pull-right">Back to Items</a>
            
                 </div>
        <div class="clearfix"></div>
    </header>
    <div class="panel-body">
     <link href="<?php echo base_url()."assets/themes/jasny/css/jasny-bootstrap.css"?>" rel="stylesheet"/>
                    
                  <!--   Adding Errors -->
                    
					      <?php
		
		//the item details
		$item_category_id = $item[0]->item_category_id;
		$item_id = $item[0]->item_id;
		$item_name = $item[0]->item_name;
		$item_status_id = $item[0]->item_status_id;
		$item_description = $item[0]->item_description;
		$quantity=$item[0]->quantity;
		$category_name = $item[0]->category_name;
        $item_hiring_price = $item[0]->item_hiring_price;
        $minimum_hiring_price = $item[0]->minimum_hiring_price;
       
        $validation_errors = validation_errors();
        
        if(!empty($validation_errors))
		{
			
			$item_name = set_value('item_name');
			$item_status = set_value('item_status');
			$item_description = set_value('item_description');
			$category_name = set_value('category_name');
            $quantity = set_value('quantity');
            $item_unit_price = set_value('item_unit_price');
            $item_hiring_price = set_value('item_hiring_price');
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
                                                
                                                <!-- item Name -->
                                                <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Item Name <span class="required"></span></label>
                                                    <div class="col-lg-8">
                                                        <input type="text" class="form-control" name="item_name" placeholder="Item Name" value="<?php echo $item_name;?>">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-lg-4 control-label">Supplier <span class="required"></span></label>
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
                                                                if($item_status_id == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="item_status">';}
                                                                else{echo '<input id="optionsRadios1" type="radio" value="1" name="item_status">';}
                                                                ?>
                                                                Yes
                                                            </label>
                                                        </div>
                                                        <div class="radio">
                                                            <label>
                                                                <?php
                                                                if($item_status_id == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="item_status">';}
                                                                else{echo '<input id="optionsRadios1" type="radio" value="0" name="item_status">';}
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
                                                  <label class="col-lg-4 control-label">Item Description</label>
                                                  <div class="col-lg-8">
                                                    <textarea name="item_description" class="form-control"><?php echo $item_description;?></textarea>
                                                  </div>
                                                </div>
                                        	</div>
                                        </div>
                                        <br>
                                         <div class="row">   
                                            <div class="form-actions center-align">
                                                <button class="submit btn btn-primary" type="submit">
                                                    Edit Item
                                                </button>
                                            </div>
                                        </div>
                                        
                                            <?php echo form_close();?>
                                    
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