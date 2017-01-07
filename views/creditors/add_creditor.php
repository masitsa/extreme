<?php
//creditor data
$creditor_name = set_value('creditor_name');
$creditor_email = set_value('creditor_email');
$creditor_phone = set_value('creditor_phone');
$creditor_location = set_value('creditor_location');
$creditor_building = set_value('creditor_building');
$creditor_floor = set_value('creditor_floor');
$creditor_address = set_value('creditor_address');
$creditor_post_code = set_value('creditor_post_code');
$creditor_city = set_value('creditor_city');
$creditor_contact_person_name = set_value('creditor_contact_person_name');
$creditor_contact_person_onames = set_value('creditor_contact_person_onames');
$creditor_contact_person_phone1 = set_value('creditor_contact_person_phone1');
$creditor_contact_person_phone2 = set_value('creditor_contact_person_phone2');
$creditor_contact_person_email = set_value('creditor_contact_person_email');
$creditor_description = set_value('creditor_description');
?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"><?php echo $title;?></h2>
                </header>
                <div class="panel-body">
                	<div class="row" style="margin-bottom:20px;">
                        <div class="col-lg-12">
                            <a href="<?php echo site_url();?>accounts/creditors" class="btn btn-info pull-right">Back to creditor</a>
                        </div>
                    </div>
                        
                    <!-- Adding Errors -->
                    <?php
						$success = $this->session->userdata('success_message');
						$error = $this->session->userdata('error_message');
						
						if(!empty($success))
						{
							echo '
								<div class="alert alert-success">'.$success.'</div>
							';
							
							$this->session->unset_userdata('success_message');
						}
						
						if(!empty($error))
						{
							echo '
								<div class="alert alert-danger">'.$error.'</div>
							';
							
							$this->session->unset_userdata('error_message');
						}
			
						$validation_errors = validation_errors();
						
						if(!empty($validation_errors))
						{
							echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
						}
                    
						$validation_errors = validation_errors();
						
						if(!empty($validation_errors))
						{
							echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
						}
                    ?>
                    
                    <?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
<div class="row">
	<div class="col-md-6">
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Creditor Name: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_name" placeholder="Creditor Name" value="<?php echo $creditor_name;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Email: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_email" placeholder="Email" value="<?php echo $creditor_email;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Phone: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_phone" placeholder="Phone" value="<?php echo $creditor_phone;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Location: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_location" placeholder="Location" value="<?php echo $creditor_location;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Building: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_building" placeholder="Building" value="<?php echo $creditor_building;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Floor: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_floor" placeholder="Floor" value="<?php echo $creditor_floor;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Address: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_address" placeholder="Address" value="<?php echo $creditor_address;?>">
            </div>
        </div>
        
	</div>
    
    <div class="col-md-6">
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Post code: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_post_code" placeholder="Post code" value="<?php echo $creditor_post_code;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">City: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_city" placeholder="City" value="<?php echo $creditor_city;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Contact First Name: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_contact_person_name" placeholder="Contact First Name" value="<?php echo $creditor_contact_person_name;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Contact Other Names: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_contact_person_onames" placeholder="Contact Other Names" value="<?php echo $creditor_contact_person_onames;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Contact Phone 1: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_phone" placeholder="Contact Phone 1" value="<?php echo $creditor_phone;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Contact Phone 2: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_phone" placeholder="Contact Phone 2" value="<?php echo $creditor_phone;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Contact Email: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="creditor_contact_person_email" placeholder="Contact Email" value="<?php echo $creditor_contact_person_email;?>">
            </div>
        </div>
        
    </div>
</div>

<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
        
        <div class="form-group">
            <label class="col-lg-2 control-label">Description: </label>
            
            <div class="col-lg-9">
            	<textarea class="form-control" name="creditor_description" rows="5"><?php echo $creditor_phone;?></textarea>
            </div>
        </div>
    </div>
</div>

<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
        <div class="form-actions center-align">
            <button class="submit btn btn-primary" type="submit">
                Add creditor
            </button>
        </div>
    </div>
</div>
                    <?php echo form_close();?>
                </div>
            </section>