
          <section class="panel">
                <header class="panel-heading">
                    <div class="panel-actions">
                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                        <a href="#" class="panel-action panel-action-dismiss" data-panel-dismiss></a>
                    </div>
            
                    <h2 class="panel-title"><?php echo $title;?></h2>
                </header>
                <div class="panel-body">
                	<div class="row" style="margin-bottom:20px;">
                        <div class="col-lg-12">
                            <a href="<?php echo site_url();?>admin/categories" class="btn btn-info pull-right">Back to categories</a>
                        </div>
                    </div>
                <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
			
			//the category details
			$category_id = $category[0]->category_id;
			$category_name = $category[0]->category_name;
			$category_parent = $category[0]->category_parent;
			$category_status = $category[0]->category_status;
			$category_preffix = $category[0]->category_preffix;
			$image = $category[0]->category_image_name;
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
				$category_id = set_value('category_id');
				$category_name = set_value('category_name');
				$category_parent = set_value('category_parent');
				$category_status = set_value('category_status');
				$category_preffix = set_value('category_preffix');
				
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
			
            ?>
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
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
            <!-- Category Preffix -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Category Preffix</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="category_preffix" placeholder="Category Preffix" value="<?php echo $category_preffix;?>" required>
                </div>
            </div>
            <!-- Image -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Category Image</label>
                <input type="hidden" value="<?php echo $image;?>" name="current_image"/>
                <div class="col-lg-4">
                    
                    <div class="row">
                    
                    	<div class="col-md-4 col-sm-4 col-xs-4">
                        	<div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:200px; height:200px;">
                                    <img src="<?php echo base_url()."assets/images/categories/".$image;?>">
                                </div>
                                <div>
                                    <span class="btn btn-file btn_pink"><span class="fileinput-new">Select Image</span><span class="fileinput-exists">Change</span><input type="file" name="category_image"></span>
                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
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
                <button class="submit btn btn-primary" type="submit">
                    Edit Category
                </button>
            </div>
            <br />
            <?php echo form_close();?>
                </div>
            </section>