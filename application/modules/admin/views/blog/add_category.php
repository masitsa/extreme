          <div class="padd">
            <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
            ?>
            
            <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <!-- post category -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Category Parent</label>
                <div class="col-lg-4">
                	<?php echo $categories;?>
                </div>
            </div>
            <!-- post Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Category Name</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="blog_category_name" placeholder="Category Name" value="<?php echo set_value('blog_category_name');?>" required>
                </div>
            </div>
            <!-- Activate checkbox -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Activate Category?</label>
                <div class="col-lg-4">
                    <div class="radio">
                        <label>
                            <input id="optionsRadios1" type="radio" checked value="1" name="blog_category_status">
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input id="optionsRadios2" type="radio" value="0" name="blog_category_status">
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