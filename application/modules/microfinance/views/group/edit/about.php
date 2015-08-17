<?php
//group data
$row = $group->row();

$group_name = $row->group_name;
$group_onames = $row->group_contact_onames;
$group_fname = $row->group_contact_fname;
$group_email = $row->group_email;
$group_phone = $row->group_phone;
$group_phone2 = $row->group_phone2;
$group_address = $row->group_address;
$group_locality = $row->group_locality;
$group_city = $row->group_city;
$group_number = $row->group_number;
$group_post_code = $row->group_post_code;

//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$group_name = set_value('group_name');
	$group_onames = set_value('group_contact_onames');
	$group_fname = set_value('group_contact_fname');
	$group_dob = set_value('group_dob');
	$group_email = set_value('group_email');
	$group_phone = set_value('group_phone');
	$group_address = set_value('group_address');
	$civil_status_id = set_value('civil_status_id');
	$group_locality = set_value('group_locality');
	$title_id = set_value('title_id');
	$gender_id = set_value('gender_id');
	$group_city = set_value('group_city');
	$group_number = set_value('group_number');
	$group_post_code = set_value('group_post_code');
}
?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">About <?php echo $group_name;?></h2>
                </header>
                <div class="panel-body">
            
            <?php echo form_open('microfinance/edit-about/'.$group_id, array("class" => "form-horizontal", "role" => "form"));?>
<div class="row">
	<div class="col-md-6">
        <div class="form-group">
            <label class="col-lg-5 control-label">Group name: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_name" placeholder="Group name" value="<?php echo $group_name;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Contact other names: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_contact_onames" placeholder="Other Names" value="<?php echo $group_onames;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Contact first name: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_contact_fname" placeholder="First Name" value="<?php echo $group_fname;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Group number: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_number" placeholder="Group number" value="<?php echo $group_number;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Email Address: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_email" placeholder="Email Address" value="<?php echo $group_email;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Location: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_locality" placeholder="Location" value="<?php echo $group_locality;?>">
            </div>
        </div>
	</div>
    
    <div class="col-md-6">
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Phone: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_phone" placeholder="Phone" value="<?php echo $group_phone;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Phone 2: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_phone2" placeholder="Phone 2" value="<?php echo $group_phone2;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Address: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_address" placeholder="Address" value="<?php echo $group_address;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">City: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_city" placeholder="City" value="<?php echo $group_locality;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Post code: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="group_post_code" placeholder="Post code" value="<?php echo $group_post_code;?>">
            </div>
        </div>
        
        
    </div>
</div>
<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
        <div class="form-actions center-align">
            <button class="submit btn btn-primary" type="submit">
                Edit group
            </button>
        </div>
    </div>
</div>
            <?php echo form_close();?>
                </div>
            </section>