<link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">
<script src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script>
 <section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
          <h4 class="panel-title pull-left"><i class="icon-reorder"></i><?php echo $title;?></h4>
          <div class="widget-icons pull-right">
                <a href="<?php echo base_url();?>inventory-setup/item-categories" class="btn btn-primary pull-right btn-sm">Back to Item categories</a>
          </div>
          <div class="clearfix"></div>
    </header>
    <div class="panel-body">
        <?php
        if(isset($error)){
            echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
        }
    	
    	//the category details
    	$item_category_id = $category[0]->item_category_id;
    	$category_name = $category[0]->category_name;
    	$category_parent = $category[0]->category_parent;
    	$category_status = $category[0]->category_status;
    	$category_preffix = $category[0]->category_preffix;
    	$image = $category[0]->category_image_name;
        
        $validation_errors = validation_errors();
        
        if(!empty($validation_errors))
        {
    		$item_category_id = set_value('item_category_id');
    		$category_name = set_value('category_name');
    		$category_parent = set_value('category_parent');
    		$category_status = set_value('category_status');
    		$category_preffix = set_value('category_preffix');
    		
            echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
        }
    	
        ?>
        
        <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
        <div class="row">
            <div class="col-md-6">
                <!-- Category Name -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">Category Name</label>
                    <div class="col-lg-4">
                    	<input type="text" class="form-control" name="category_name" placeholder="Category Name" value="<?php echo $category_name;?>" required>
                    </div>
                </div>
                <!-- Category Parent -->
                <div class="form-group">
                    <label class="col-lg-4 control-label">Category Parent</label>
                    <div class="col-lg-4">
                    	<select name="category_parent" class="form-control" required>
                        	<?php
            				echo '<option value="0">No Parent</option>';
            				if($all_categories->num_rows() > 0)
            				{
            					$result = $all_categories->result();
            					
            					foreach($result as $res)
            					{
            						if($res->category_id == $category_parent)
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
                    <label class="col-lg-4 control-label">Activate Category?</label>
                    <div class="col-lg-4">
                        <div class="radio">
                            <label>
                            	<?php
                                if($category_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="category_status">';}
            					else{echo '<input id="optionsRadios1" type="radio" value="1" name="category_status">';}
            					?>
                                Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                            	<?php
                                if($category_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="category_status">';}
            					else{echo '<input id="optionsRadios1" type="radio" value="0" name="category_status">';}
            					?>
                                No
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-actions center-align">
                    <button class="submit btn btn-primary btn-sm" type="submit">
                        Edit Item Category
                    </button>
                </div>
            </div>
        <?php echo form_close();?>
    </div>
    </section>