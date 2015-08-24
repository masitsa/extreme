<?php
//patient data
$row = $patient->row();
$patient_surname = $row->patient_surname;
$patient_othernames = $row->patient_othernames;
$title_idd = $row->title_id;
$patient_date_of_birth = $row->patient_date_of_birth;
$gender_idd = $row->gender_id;
$staff_id = $row->dependant_id;
$religion_idd = $row->religion_id;
$civil_status_idd = $row->civil_status_id;
$relationship_idd = $row->relationship_id;
$patient_national_id = $row->patient_national_id;
$next_of_kin_contact = $row->patient_kin_phonenumber1;
//echo $gender_id;
//repopulate data if validation errors occur
$validation_error = validation_errors();
				
if(!empty($validation_error))
{
	$patient_surname = set_value('patient_surname');
	$patient_othernames = set_value('patient_othernames');
	$title_idd = set_value('title_id');
	$patient_date_of_birth = set_value('patient_dob');
	$gender_id = set_value('gender_id');
	$religion_id = set_value('religion_id');
	$civil_status_id = set_value('civil_status_id');
	$patient_email = set_value('patient_email');
	$patient_address = set_value('patient_address');
	$patient_postalcode = set_value('patient_postalcode');
	$patient_town = set_value('patient_town');
	$patient_phone1 = set_value('patient_phone1');
	$patient_phone2 = set_value('patient_phone2');
	$patient_kin_sname = set_value('patient_kin_sname');
	$patient_kin_othernames = set_value('patient_kin_othernames');
	$relationship_id = set_value('relationship_id');
	$patient_national_id = set_value('patient_national_id');
	$next_of_kin_contact = set_value('next_of_kin_contact');
}
 
?>
        <!-- Widget -->
<div class="widget boxed">
    <!-- Widget head -->
    <div class="widget-head">
        <h4 class="pull-left"><i class="icon-reorder"></i>Edit <?php echo $patient_surname;?> <?php echo $patient_othernames;?></h4>
        <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
        </div>
    
    	<div class="clearfix"></div>
    
    </div>             
    
    <!-- Widget content -->
    <div class="widget-content">
    	<div class="padd">
        <a href="<?php echo site_url().'/reception/staff_dependants'?>">Back to Staff Dependants</a>
        <?php
		$error = $this->session->userdata('error_message');
		$success = $this->session->userdata('success_message');
		$validation_error = validation_errors();
		
		if(!empty($validation_error))
		{
			echo '<div class="alert alert-danger">'.$validation_error.'</div>';
			$this->session->unset_userdata('error_message');
		}
		
		if(!empty($error))
		{
			echo '<div class="alert alert-danger">'.$error.'</div>';
			$this->session->unset_userdata('error_message');
		}
		if(!empty($success))
		{
			echo '<div class="alert alert-success">'.$success.'</div>';
			$this->session->unset_userdata('success_message');
		}
		echo form_open($this->uri->uri_string(), array("class" => "form-horizontal"));
		?>

<div class="row">
	<div class="col-md-6">
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Staff ID: </label>
            
            <div class="col-lg-8">
            	<input type="text" class="form-control" name="staff_number" value='<?php echo $staff_id;?>' placeholder="Staff ID" readonly>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Title: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name="title_id">
                	<?php
                    	if($titles->num_rows() > 0)
						{
							$title = $titles->result();
							
							foreach($title as $res)
							{
								$title_id = $res->title_id;
								$title_name = $res->title_name;
								
								if($title_id == $title_idd)
								{
									echo '<option value="'.$title_id.'" selected>'.$title_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$title_id.'">'.$title_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Surname: </label>
            
            <div class="col-lg-8">
            	<input type="text" class="form-control" name="patient_surname" placeholder="Surname" value="<?php echo $patient_surname;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Other Names: </label>
            
            <div class="col-lg-8">
            	<input type="text" class="form-control" name="patient_othernames" placeholder="Other Names" value="<?php echo $patient_othernames?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Date of Birth: </label>
            
            <div class="col-lg-8">
                <div id="datetimepicker1" class="input-append">
                    <input data-format="yyyy-MM-dd" class="form-control" type="text" name="patient_dob" placeholder="Date of Birth" value="<?php echo $patient_date_of_birth;?>">
                    <span class="add-on">
                        &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                        </i>
                    </span>
                </div>
            </div>
        </div>
	
    </div>
    <div class="col-md-6">
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Gender: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name="gender_id">
                	<?php
                    	if($genders->num_rows() > 0)
						{
							$gender = $genders->result();
							
							foreach($gender as $res)
							{
								$gender_id = $res->gender_id;
								$gender_name = $res->gender_name;
								
								if($gender_id == $gender_idd)
								{
									echo '<option value="'.$gender_id.'" selected>'.$gender_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$gender_id.'">'.$gender_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Religion: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name="religion_id">
                	<?php
                    	if($religions->num_rows() > 0)
						{
							$religion = $religions->result();
							
							foreach($religion as $res)
							{
								$religion_id = $res->religion_id;
								$religion_name = $res->religion_name;
								
								if($religion_id == $religion_idd)
								{
									echo '<option value="'.$religion_id.'" selected>'.$religion_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$religion_id.'">'.$religion_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Civil Status: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name="civil_status_id">
                	<?php
                    	if($civil_statuses->num_rows() > 0)
						{
							$status = $civil_statuses->result();
							
							foreach($status as $res)
							{
								$status_id = $res->civil_status_id;
								$status_name = $res->civil_status_name;
								
								if($status_id == $civil_status_idd)
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
            <label class="col-lg-4 control-label">Relationship To Staff: </label>
            
            <div class="col-lg-8">
            	<select class="form-control" name="relationship_id">
                	<?php
                    	if($relationships->num_rows() > 0)
						{
							$relationship = $relationships->result();
							
							foreach($relationship as $res)
							{
								$relationship_id = $res->relationship_id;
								$relationship_name = $res->relationship_name;
								
								if($relationship_id == $relationship_idd)
								{
									echo '<option value="'.$relationship_id.'" selected>'.$relationship_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$relationship_id.'">'.$relationship_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
	</div>
</div>

<div class="center-align">
	<button class="btn btn-info btn-lg" type="submit">Edit Dependant</button>
</div>
<?php echo form_close();?>
    	</div>
    </div>
</div>
