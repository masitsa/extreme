   
        <!-- Jasny -->
        <link href="<?php echo base_url();?>assets/jasny/jasny-bootstrap.css" rel="stylesheet">		
        <script type="text/javascript" src="<?php echo base_url();?>assets/jasny/jasny-bootstrap.js"></script> 
          <div class="padd">
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
			
				if(isset($_SESSION['error'])){?>
					<div class="row">
						<div class="col-md-6 col-md-offset-2">
							<div class="alert alert-danger">
								<strong>Error!</strong> <?php echo $_SESSION['error']; $_SESSION['error'] = NULL;?>
							</div>
						</div>
					</div>
				<?php }?>
			
				<?php
				$attributes = array('role' => 'form', 'class'=>'form-horizontal');
		
				echo form_open_multipart($this->uri->uri_string(), $attributes);
				
				if(!empty($error))
				{
					?>
					<div class="alert alert-danger">
						<?php echo $error;?>
					</div>
					<?php
				}
				?>
                <div class="row">
                	<div class="col-md-4">
                        <div class="form-group">
                            <label class="col-lg-4 control-label" for="gallery_name">Title</label>
                            <div class="col-lg-8">
                            	<input type="text" class="form-control" name="gallery_name" placeholder="Title" value="<?php echo $gallery_row->gallery_name;?>">
                            </div>
                        </div>
                        <!-- department type -->
                        <div class="form-group">
                            <label class="col-lg-4 control-label">Department</label>
                            <div class="col-lg-8">
                                <select name="department_id" id="department_id" class="form-control" required>
                                    <?php
                                    if($active_departments->num_rows() > 0)
                                    {
                                        $result = $active_departments->result();
                                        
                                        foreach($result as $res)
                                        {
                                            if($res->department_id == $gallery_row->department_id)
                                            {
                                                echo '<option value="'.$res->department_id.'" selected="selected">'.$res->department_name.'</option>';
                                            }
                                            else
                                            {
                                                echo '<option value="'.$res->department_id.'">'.$res->department_name.'</option>';
                                            }
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        <!-- end: department type -->
                        </div> 
					</div>
                	<div class="col-md-8">
                        <label class="control-label" for="image">Gallery Image</label>
                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="max-height: 400px;">
                                	<img src="<?php echo $gallery_location;?>" class="img-responsive"/>
                                </div>
                                    <div>
                                        <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="gallery_image"></span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                                    </div>
                                </div>
                            </div>
                	</div>
                </div>
				
				<div class="form-group center-align">
					<input type="submit" value="Edit Image" class="login_btn btn btn-success btn-lg">
				</div>
				<?php
					echo form_close();
				?>
		</div>