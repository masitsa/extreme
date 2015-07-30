          <style type="text/css">
		  	.add-on{cursor:pointer;}
		  </style>
          <link href="<?php echo base_url()."assets/themes/jasny/css/jasny-bootstrap.css"?>" rel="stylesheet"/>
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
            
            <?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
            <!-- post category -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Post Category</label>
                <div class="col-lg-4">
                	<?php echo $categories;?>
                </div>
            </div>
            <!-- post Name -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Post Title</label>
                <div class="col-lg-4">
                	<input type="text" class="form-control" name="post_title" placeholder="Post Title" value="<?php echo set_value('post_title');?>" required>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">Post Date</label>
                
                <div class="col-lg-4">
                    <div id="datetimepicker1" class="input-append">
                        <input data-format="yyyy-MM-dd" class="form-control" type="text" name="created" placeholder="Post Date" value="<?php echo set_value('created');?>">
                        <span class="add-on">
                            &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                            </i>
                        </span>
                    </div>
                </div>
            </div>
            <!-- Image -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Post Image</label>
                <div class="col-lg-4">
                    
                    <div class="row">
                    
                    	<div class="col-md-4 col-sm-4 col-xs-4">
                        	<div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:200px; height:200px;">
                                    <img src="http://placehold.it/200x200">
                                </div>
                                <div>
                                    <span class="btn btn-file btn-info"><span class="fileinput-new">Select Image</span><span class="fileinput-exists">Change</span><input type="file" name="post_image"></span>
                                    <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">Remove</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <!-- Activate checkbox -->
            <div class="form-group">
                <label class="col-lg-4 control-label">Activate Post?</label>
                <div class="col-lg-4">
                    <div class="radio">
                        <label>
                            <input id="optionsRadios1" type="radio" checked value="1" name="post_status">
                            Yes
                        </label>
                    </div>
                    <div class="radio">
                        <label>
                            <input id="optionsRadios2" type="radio" value="0" name="post_status">
                            No
                        </label>
                    </div>
                </div>
            </div>
            <!-- post content -->
            <div class="form-group">
                <label class="col-lg-12 control-label">Post Content</label>
                <div class="col-lg-12" style="height:500px;">
                    <textarea class="cleditor" name="post_content" placeholder="Post Content"><?php echo set_value('post_content');?></textarea>
                </div>
            </div>
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Add post
                </button>
            </div>
            <br />
            <?php echo form_close();?>
		</div>