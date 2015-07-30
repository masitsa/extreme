          <link href="<?php echo base_url()."assets/themes/jasny/css/jasny-bootstrap.css"?>" rel="stylesheet"/>
          <div class="padd">
            <!-- Adding Errors -->
            <?php
            if(isset($error)){
                echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
            }
			
			//the blog_category details
			$blog_category_id = $category[0]->blog_category_id;
			$blog_category_name = $category[0]->blog_category_name;
			$blog_category_status = $category[0]->blog_category_status;
            
            $validation_errors = validation_errors();
            
            if(!empty($validation_errors))
            {
				$blog_category_name = set_value('blog_category_name');
				$blog_category_status = set_value('blog_category_status');
				
                echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
            }
			
            ?>
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <!-- post category -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Category Parent</label>
                <div class="col-lg-4">
                	<?php echo $categories;?>
                </div>
            </div>
            <!-- blog_category Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Category Name</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="blog_category_name" placeholder="Category Name" value="<?php echo $blog_category_name;?>" required>
                </div>
            </div>
            <!-- Activate checkbox -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Activate Category?</label>
                <div class="col-lg-4">
                    <div class="radio">
                        <label>
                        	<?php
                            if($blog_category_status == 1){echo '<input id="optionsRadios1" type="radio" checked value="1" name="blog_category_status">';}
							else{echo '<input id="optionsRadios1" type="radio" value="1" name="blog_category_status">';}
							?>
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                        	<?php
                            if($blog_category_status == 0){echo '<input id="optionsRadios1" type="radio" checked value="0" name="blog_category_status">';}
							else{echo '<input id="optionsRadios1" type="radio" value="0" name="blog_category_status">';}
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