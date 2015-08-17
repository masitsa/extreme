<?php
//individual data
$row = $individual->row();

$individual_onames = $row->individual_onames;
$individual_fname = $row->individual_fname;
$individual_dob = $row->individual_dob;
$individual_email = $row->individual_email;
$individual_phone = $row->individual_phone;
$individual_phone2 = $row->individual_phone2;
$individual_address = $row->individual_address;
$civil_status_id = $row->civilstatus_id;
$individual_locality = $row->individual_locality;
$title_id = $row->title_id;
$gender_id = $row->gender_id;
$individual_city = $row->individual_city;
$individual_number = $row->individual_number;
$individual_post_code = $row->individual_post_code;
$document_id = $row->document_id;
$document_number = $row->document_number;
$document_place = $row->document_place;
$individual_email2 = $row->individual_email2;

//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$individual_onames = set_value('individual_onames');
	$individual_fname = set_value('individual_fname');
	$individual_dob = set_value('individual_dob');
	$individual_email = set_value('individual_email');
	$individual_phone = set_value('individual_phone');
	$individual_phone2 = set_value('individual_phone2');
	$civil_status_id = set_value('civil_status_id');
	$individual_locality = set_value('individual_locality');
	$title_id = set_value('title_id');
	$gender_id = set_value('gender_id');
	$individual_number = set_value('individual_number');
	$individual_address = set_value('individual_address');
	$individual_city = set_value('individual_city');
	$individual_post_code = set_value('individual_post_code');
	$document_id = set_value('document_id');
	$document_number = set_value('document_number');
	$document_place = set_value('document_place');
	$individual_email2 = set_value('individual_email2');
}
?>
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title">About <?php echo $individual_onames.' '.$individual_fname;?></h2>
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
            
            <?php echo form_open_multipart('microfinance/update-individual/'.$individual_id, array("class" => "form-horizontal", "role" => "form"));?>
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
                        <span class="btn btn-file btn-success"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="individual_image"></span>
                        <a href="#" class="btn btn-info fileinput-exists" data-dismiss="fileinput">Remove</a>
                    </div>
                </div>
                
            </div>
        </div>
        
        <!-- Signature -->
        <div class="form-group">
            <div class="col-lg-12">
                
                <div class="fileinput fileinput-new" data-provides="fileinput">
                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="max-width:200px; max-height:200px;">
                        <img src="<?php echo $signature_location;?>" class="img-responsive">
                    </div>
                    <div>
                        <span class="btn btn-file btn-success"><span class="fileinput-new">Select signature</span><span class="fileinput-exists">Change</span><input type="file" name="individual_signature"></span>
                        <a href="#" class="btn btn-info fileinput-exists" data-dismiss="fileinput">Remove</a>
                    </div>
                </div>
                
            </div>
        </div>
	</div>
    
    <div class="col-md-5">
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
            	<input type="text" class="form-control" name="individual_onames" placeholder="Other Names" value="<?php echo $individual_onames;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">First Name: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_fname" placeholder="First Name" value="<?php echo $individual_fname;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Personnel number: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_number" placeholder="Personnel number" value="<?php echo $individual_number;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Date of Birth: </label>
            
            <div class="col-lg-7">
            	<div class="input-group">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </span>
                    <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="individual_dob" placeholder="Date of Birth" value="<?php echo $individual_dob;?>">
                </div>
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
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Email Address: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_email" placeholder="Email Address" value="<?php echo $individual_email;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Email Address 2: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_email2" placeholder="Email Address 2" value="<?php echo $individual_email2;?>">
            </div>
        </div>
	</div>
    
    <div class="col-md-5">
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Phone: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_phone" placeholder="Phone" value="<?php echo $individual_phone;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Phone 2: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_phone2" placeholder="Phone 2" value="<?php echo $individual_phone2;?>">
            </div>
        </div>
        
         <div class="form-group">
            <label class="col-lg-5 control-label">Residence: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_locality" placeholder="Residence" value="<?php echo $individual_locality;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Address: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_address" placeholder="Address" value="<?php echo $individual_address;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">City: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_city" placeholder="City" value="<?php echo $individual_locality;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Post code: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="individual_post_code" placeholder="Post code" value="<?php echo $individual_post_code;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Document type: </label>
            
            <?php
            	if($document_id == 2)
				{
			?>
            <div class="col-sm-3">
                <div class="radio">
                    <label>
                        <input type="radio" name="document_id" value="1" id="document_id1">
                        National ID
                    </label>
                </div>
            </div>
            
            <div class="col-sm-3">
                <div class="radio">
                    <label>
                        <input type="radio" name="document_id" value="2" checked="checked" id="document_id2">
                        Passport
                    </label>
                </div>
            </div>
            <?php } 
			
            	else
				{
			?>
            <div class="col-sm-3">
                <div class="radio">
                    <label>
                        <input type="radio" name="document_id" value="1" checked="checked" id="document_id1">
                        National ID
                    </label>
                </div>
            </div>
            
            <div class="col-sm-3">
                <div class="radio">
                    <label>
                        <input type="radio" name="document_id" value="2" id="document_id2">
                        Passport
                    </label>
                </div>
            </div>
            <?php } ?>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Document number: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="document_number" placeholder="Document number" value="<?php echo $document_number;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Place of issue: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="document_place" placeholder="Place of issue" value="<?php echo $document_place;?>">
            </div>
        </div>
        
    </div>
</div>
<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
        <div class="form-actions center-align">
            <button class="submit btn btn-primary" type="submit">
                Edit individual
            </button>
        </div>
    </div>
</div>
            <?php echo form_close();?>
                </div>
            </section>