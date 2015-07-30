          <link href="<?php echo base_url()."assets/themes/jasny/css/jasny-bootstrap.css"?>" rel="stylesheet"/>
          <div class="padd">
			<h3>Send Mail</h3>
			<?php echo form_open_multipart($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
         	<div class="row">
            	<div class="col-md-12">
                    <!-- Adding Errors -->
                    <?php
                    if(isset($error)){
                        echo '<div class="alert alert-danger">'.$error.'</div>';
                    }
                    if(isset($success)){
                        echo '<div class="alert alert-success">'.$success.'</div>';
                    }
                    
                    $validation_errors = validation_errors();
                    
                    if(!empty($validation_errors))
                    {
                        echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
                    }
                    ?>
                    
                    
                    <!-- product Name -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Email Addresses</label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" name="emails" placeholder="Email Addresses" value="<?php echo set_value('emails');?>" required>
                        </div>
                    </div>
                    
                    <!-- product Name -->
                    <div class="form-group">
                        <label class="col-lg-4 control-label">Subject</label>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" name="subject" placeholder="Subject" value="<?php echo set_value('subject');?>" required>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="row">
            	<div class="col-md-12">
                    <!-- Product Description -->
                    <div class="form-group">
                      <label class="col-lg-2 control-label">Message</label>
                      <div class="col-lg-10">
                        <textarea class="cleditor" name="message"><?php echo set_value('message');?></textarea>
                      </div>
                    </div>
            	</div>
            </div>
            
            <div class="form-actions center-align">
                <button class="submit btn btn-primary" type="submit">
                    Send to Many
                </button>
            </div>
            <?php echo form_close();?>
		</div>