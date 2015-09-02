          
          <section class="panel">
                <header class="panel-heading">
                    <div class="panel-actions">
                        <a href="#" class="panel-action panel-action-toggle" data-panel-toggle></a>
                    </div>
            
                    <h2 class="panel-title"><?php echo $title;?></h2>
                </header>
                <div class="panel-body">
                	<div class="row" style="margin-bottom:20px;">
                        <div class="col-lg-12">
                            <a href="<?php echo site_url();?>admin/branches" class="btn btn-info pull-right">Back to branches</a>
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
                    <div class="row">
                    	<div class="col-md-6">
                            <!-- Branch Name -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Branch name</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="branch_name" placeholder="Branch Name" value="<?php echo set_value('branch_name');?>">
                                </div>
                            </div>
                            <!-- Email -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Email</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="branch_email" placeholder="Email" value="<?php echo set_value('branch_email');?>">
                                </div>
                            </div>
                            <!-- Phone -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Phone</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="branch_phone" placeholder="Phone" value="<?php echo set_value('branch_phone');?>">
                                </div>
                            </div>
                            <!-- Branch Address -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Address</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="branch_address" placeholder="Address" value="<?php echo set_value('branch_address');?>">
                                </div>
                            </div>
                            <!-- Branch City -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">City</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="branch_city" placeholder="City" value="<?php echo set_value('branch_city');?>">
                                </div>
                            </div>
                            <!-- Branch Post code -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Post code</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="branch_post_code" placeholder="Post code" value="<?php echo set_value('branch_post_code');?>">
                                </div>
                            </div>
                            <!-- Branch Location -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Location</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="branch_location" placeholder="Location" value="<?php echo set_value('branch_location');?>">
                                </div>
                            </div>
                            <!-- Branch Building -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Building</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="branch_building" placeholder="Building" value="<?php echo set_value('branch_building');?>">
                                </div>
                            </div>
                            <!-- Branch Floor -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Floor</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" name="branch_floor" placeholder="Floor" value="<?php echo set_value('branch_floor');?>">
                                </div>
                            </div>
                    	</div>
                    	<div class="col-md-6">
                            <!-- Image -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Branch Image</label>
                                <div class="col-lg-6">
                                    
                                    <div class="row">
                                    
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width:200px; height:200px;">
                                                    <img src="http://placehold.it/200x200">
                                                </div>
                                                <div>
                                                    <span class="btn btn-file btn_pink"><span class="fileinput-new">Select Image</span><span class="fileinput-exists">Change</span><input type="file" name="branch_image"></span>
                                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            <!-- Activate checkbox -->
                            <div class="form-group">
                                <label class="col-lg-4 control-label">Activate Branch?</label>
                                <div class="col-lg-6">
                                    <div class="radio">
                                        <label>
                                            <input id="optionsRadios1" type="radio" checked value="1" name="branch_status">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label>
                                            <input id="optionsRadios2" type="radio" value="0" name="branch_status">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions center-align">
                        <button class="submit btn btn-primary" type="submit">
                            Add Branch
                        </button>
                    </div>
                    <br />
                    <?php echo form_close();?>
                </div>
            </section>