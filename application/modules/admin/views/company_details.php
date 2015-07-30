
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
                        
            <?php
				$error2 = validation_errors(); 
				if(!empty($error2)){?>
					<div class="row">
						<div class="col-md-6 col-md-offset-2">
							<div class="alert alert-danger">
								<strong>Error!</strong> <?php echo validation_errors(); ?>
							</div>
						</div>
					</div>
				<?php }
				$attributes = array('role' => 'form', 'class' => 'form-horizontal');
		
				echo form_open_multipart($this->uri->uri_string(), $attributes);
				?>
                <div class="row">
                	<h2>About company</h2>
                	<div class="col-md-5">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="image">Company logo</label>
                            <div class="col-md-8">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;">
                                        <img src="<?php echo $logo_location;?>" class="img-responsive"/>
                                    </div>
									
									<div>
										<span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="logo"></span>
										<a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
									</div>
                                </div>
                            </div>
                        </div>
                    </div>
                	<div class="col-md-7">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="company_name">Company Name</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="company_name" placeholder="Company Name" value="<?php echo $company_name;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="working_weekday">Working hours weekday</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="working_weekday" placeholder="Working hours weekday" value="<?php echo $working_weekday;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="working_weekend">Working hours weekend</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="working_weekend" placeholder="Working hours weekend" value="<?php echo $working_weekend;?>">
                            </div>
                        </div>
					</div>
                </div>
                <div class="row">
                	<div class="col-md-12">
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="about">Executive summary</label>
                            <div class="col-md-10">
                                <textarea class="cleditor" name="about" placeholder="About"><?php echo $about;?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="mission">Mission</label>
                            <div class="col-md-10">
                                <textarea class="cleditor" name="mission" placeholder="Mission"><?php echo $mission;?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="vision">Vision</label>
                            <div class="col-md-10">
                                <textarea class="cleditor" name="vision" placeholder="Vision"><?php echo $vision;?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="objectives">Objectives</label>
                            <div class="col-md-10">
                                <textarea class="cleditor" name="objectives" placeholder="Objectives"><?php echo $objectives;?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-2 control-label" for="core_values">Core values</label>
                            <div class="col-md-10">
                                <textarea class="cleditor" name="core_values" placeholder="Core values"><?php echo $core_values;?></textarea>
                            </div>
                        </div>
					</div>
                </div>
                <div class="row">
                	<h2>Contact details</h2>
                	<div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="email">Email</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="email" placeholder="Email" value="<?php echo $email;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="phone">Phone</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="phone" placeholder="Phone" value="<?php echo $phone;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="building">Building</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="building" placeholder="Building" value="<?php echo $building;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="floor">Floor/ room</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="floor" placeholder="Floor/ room" value="<?php echo $floor;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="location">Location</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="location" placeholder="Location" value="<?php echo $location;?>">
                            </div>
                        </div>
                	</div>
                	<div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="address">Address</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="address" placeholder="Address" value="<?php echo $address;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="city">City</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="city" placeholder="City" value="<?php echo $city;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="post_code">Post code</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="post_code" placeholder="Post code" value="<?php echo $post_code;?>">
                            </div>
                        </div>
                	</div>
                </div>
                <div class="row">
                	<h2>Social media</h2>
                	<div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="facebook">Facebook</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="facebook" placeholder="Facebook" value="<?php echo $facebook;?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="twitter">Twitter</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="twitter" placeholder="Twitter" value="<?php echo $twitter;?>">
                            </div>
                        </div>
                	</div>
                	<div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="pintrest">Linkedin</label>
                            <div class="col-md-8">
                            	<input type="text" class="form-control" name="pintrest" placeholder="Linkedin" value="<?php echo $pintrest;?>">
                            </div>
                        </div>
                	</div>
                </div>
				
				<div class="form-group center-align">
					<input type="submit" value="Edit company" class="login_btn btn btn-success btn-lg">
				</div>
				<?php
					form_close();
				?>
                </div>
            </section>