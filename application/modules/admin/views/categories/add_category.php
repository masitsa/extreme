          
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
                    
                    $validation_errors = validation_errors();
                    
                    if(!empty($validation_errors))
                    {
                        echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
                    }
                    ?>
                    
                    <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
                    <!-- Category Name -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Category Name</label>
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="category_name" placeholder="Category Name" value="<?php echo set_value('category_name');?>" required>
                        </div>
                    </div>
                    <!-- Category Parent -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Category Parent</label>
                        <div class="col-lg-6">
                            <select name="category_parent" class="form-control" required>
                                <?php
                                echo '<option value="0">No Parent</option>';
                                if($all_categories->num_rows() > 0)
                                {
                                    $result = $all_categories->result();
                                    
                                    foreach($result as $res)
                                    {
                                        if($res->category_id == set_value('category_parent'))
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
                        <div class="col-lg-6">
                            <input type="text" class="form-control" name="category_preffix" placeholder="Category Preffix" value="<?php echo set_value('category_preffix');?>" required>
                        </div>
                    </div>
                    <!-- Image -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Category Image</label>
                        <div class="col-lg-6">
                            
                            <div class="row">
                            
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:200px; height:200px;">
                                            <img src="http://placehold.it/200x200">
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
                        <div class="col-lg-6">
                            <div class="radio">
                                <label>
                                    <input id="optionsRadios1" type="radio" checked value="1" name="category_status">
                                    Yes
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input id="optionsRadios2" type="radio" value="0" name="category_status">
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions center-align">
                        <button class="submit btn btn-primary" type="submit">
                            Add Category
                        </button>
                    </div>
                    <br />
                    <?php echo form_close();?>
                </div>
            </section>