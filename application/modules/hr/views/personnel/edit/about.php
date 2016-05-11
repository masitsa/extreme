<?php
//personnel data
$row = $personnel->row();

$personnel_onames = $row->personnel_onames;
$personnel_fname = $row->personnel_fname;
$personnel_dob = $row->personnel_dob;
$personnel_email = $row->personnel_email;
$personnel_phone = $row->personnel_phone;
$personnel_address = $row->personnel_address;
$civil_status_id = $row->civilstatus_id;
$personnel_locality = $row->personnel_locality;
$title_id = $row->title_id;
$gender_id = $row->gender_id;
$personnel_city = $row->personnel_city;
$personnel_number = $row->personnel_number;
$personnel_post_code = $row->personnel_post_code;
$branch_id = $row->branch_id;
$personnel_account_number = $row->personnel_account_number;
$personnel_nssf_number = $row->personnel_nssf_number;
$personnel_kra_pin = $row->personnel_kra_pin;
$personnel_national_id_number = $row->personnel_national_id_number;
$personnel_nhif_number = $row->personnel_nhif_number;
$personnel_type_id2 = $row->personnel_type_id;
$image = $row->image;

//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$personnel_onames = set_value('personnel_onames');
	$personnel_fname = set_value('personnel_fname');
	$personnel_dob = set_value('personnel_dob');
	$personnel_email = set_value('personnel_email');
	$personnel_phone = set_value('personnel_phone');
	$personnel_address = set_value('personnel_address');
	$civil_status_id = set_value('civil_status_id');
	$personnel_locality = set_value('personnel_locality');
	$title_id = set_value('title_id');
	$gender_id = set_value('gender_id');
	$personnel_city = set_value('personnel_city');
	$personnel_number = set_value('personnel_number');
	$personnel_post_code = set_value('personnel_post_code');
	$branch_id = set_value('branch_id');
	$personnel_account_number = set_value('personnel_account_number');
	$personnel_nssf_number = set_value('personnel_nssf_number');
	$personnel_kra_pin = set_value('personnel_kra_pin');
	$personnel_national_id_number = set_value('personnel_national_id_number');
	$personnel_nhif_number = set_value('personnel_nhif_number');
	$personnel_type_id2 = set_value('personnel_type_id');
}
?>
<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title">About <?php echo $personnel_onames.' '.$personnel_fname;?></h2>
    </header>
    <div class="panel-body">
    <!-- Adding Errors -->
    <?php
    if(isset($error)){
        echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
    }
    if(!empty($validation_errors))
    {
        echo '<div class="alert alert-danger"> Oh snap! '.$validation_errors.' </div>';
    }

    ?>
            
<?php echo form_open_multipart(''.site_url().'human-resource/edit-personnel-about/'.$personnel_id.'', array("class" => "form-horizontal", "role" => "form"));?>
<input type="hidden" name="previous_image" value="<?php echo $image;?>" />

<div class="row">
	<div class="col-md-2">
    	<!-- Image -->
        <div class="form-group">
            <div class="col-lg-12">
                
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="max-width:200px; max-height:200px;">
                        <img src="<?php echo $image_location;?>" class="img-responsive">
                    </div>
                    <div>
                        <span class="btn btn-file btn-success"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="personnel_image"></span>
                        <a href="#" class="btn btn-info fileinput-exists" data-dismiss="fileinput">Remove</a>
                    </div>
                </div>
                
            </div>
        </div>

	</div>
    
    <div class="col-md-5">
        <div class="form-group">
            <label class="col-lg-5 control-label">Branch: </label>
            
            <div class="col-lg-7">
                <select class="form-control" name="branch_id">
                	<?php
                    	if($branches->num_rows() > 0)
						{
							foreach($branches->result() as $res)
							{
								$branch_id2 = $res->branch_id;
								$branch_name = $res->branch_name;
								
								if($branch_id2 == $branch_id)
								{
									echo '<option value="'.$branch_id2.'" selected>'.$branch_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$branch_id2.'">'.$branch_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Type: </label>
            
            <div class="col-lg-7">
                <select class="form-control" name="personnel_type_id">
                	<?php
                    	if($personnel_types->num_rows() > 0)
						{
							$status = $personnel_types->result();
							
							foreach($status as $res)
							{
								$personnel_type_id = $res->personnel_type_id;
								$personnel_type_name = $res->personnel_type_name;
								
								if($personnel_type_id == $personnel_type_id2)
								{
									echo '<option value="'.$personnel_type_id.'" selected>'.$personnel_type_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$personnel_type_id.'">'.$personnel_type_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Title: </label>
            
            <div class="col-lg-7">
            	<select class="form-control" name="title_id">
                	<?php
                    	if($titles->num_rows() > 0)
						{
							$title = $titles->result();
							
							foreach($title as $res)
							{
								$db_title_id = $res->title_id;
								$title_name = $res->title_name;
								
								if($db_title_id == $title_id)
								{
									echo '<option value="'.$db_title_id.'" selected>'.$title_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$db_title_id.'">'.$title_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Other Names: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_onames" placeholder="Other Names" value="<?php echo $personnel_onames;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">First Name: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_fname" placeholder="First Name" value="<?php echo $personnel_fname;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Personnel number: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_number" placeholder="Personnel number" value="<?php echo $personnel_number;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Date of Birth: </label>
            
            <div class="col-lg-7">
            	<div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="personnel_dob" placeholder="Date of Birth" value="<?php echo $personnel_dob;?>">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">ID number: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_national_id_number" placeholder="ID number" value="<?php echo $personnel_national_id_number;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Gender: </label>
            
            <div class="col-lg-7">
            	<select class="form-control" name="gender_id">
                	<?php
                    	if($genders->num_rows() > 0)
						{
							$gender = $genders->result();
							
							foreach($gender as $res)
							{
								$db_gender_id = $res->gender_id;
								$gender_name = $res->gender_name;
								
								if($db_gender_id == $gender_id)
								{
									echo '<option value="'.$db_gender_id.'" selected>'.$gender_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$db_gender_id.'">'.$gender_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Civil Status: </label>
            
            <div class="col-lg-7">
            	<select class="form-control" name="civil_status_id">
                	<?php
                    	if($civil_statuses->num_rows() > 0)
						{
							$status = $civil_statuses->result();
							
							foreach($status as $res)
							{
								$status_id = $res->civil_status_id;
								$status_name = $res->civil_status_name;
								
								if($status_id == $civil_status_id)
								{
									echo '<option value="'.$status_id.'" selected>'.$status_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$status_id.'">'.$status_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
	</div>
    
    <div class="col-md-5">
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Email Address: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_email" placeholder="Email Address" value="<?php echo $personnel_email;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Phone: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_phone" placeholder="Phone" value="<?php echo $personnel_phone;?>">
            </div>
        </div>
        
         <div class="form-group">
            <label class="col-lg-5 control-label">Residence: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_locality" placeholder="Residence" value="<?php echo $personnel_locality;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Address: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_address" placeholder="Address" value="<?php echo $personnel_address;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">City: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_city" placeholder="City" value="<?php echo $personnel_locality;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Post code: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_post_code" placeholder="Post code" value="<?php echo $personnel_post_code;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Account number: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_account_number" placeholder="Account number" value="<?php echo $personnel_account_number;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">NSSF number: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_nssf_number" placeholder="NSSF number" value="<?php echo $personnel_nssf_number;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">NHIF number: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_nhif_number" placeholder="NHIF number" value="<?php echo $personnel_nhif_number;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">KRA pin: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_kra_pin" placeholder="KRA pin" value="<?php echo $personnel_kra_pin;?>">
            </div>
        </div>
        
    </div>
</div>
<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
        <div class="form-actions center-align">
            <button class="submit btn btn-primary" type="submit">
                Edit personnel
            </button>
        </div>
    </div>
</div>
            <?php echo form_close();?>
                </div>
            </section>