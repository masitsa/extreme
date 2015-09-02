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
$patient_phone1 = $row->patient_phone1;
$patient_phone2 = $row->patient_phone2;
$Surname = $row->Surname;
$Other_names = $row->Other_names;
$Staff_Number = $row->Staff_Number;
$gender = $row->gender;
$DOB = $row->DOB;
if($gender == 'F')
{
	$gender_name = 'Female';
}
else
{
	$gender_name = 'Male';
}
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
<div class="row">
	<div class="col-md-6">
		<a href="<?php echo site_url();?>/reception/staff" class="btn btn-sm btn-primary ">Back to staff list</a>
	</div>
</div>

        <!-- Widget -->
<div class="widget boxed">
    <!-- Widget head -->
    <div class="widget-head">
        <h4 class="pull-left"><i class="icon-reorder"></i>Edit <?php echo $Surname;?> <?php echo $Other_names;?></h4>
        <div class="widget-icons pull-right">
            <a href="#" class="wminimize"><i class="icon-chevron-up"></i></a> 
            <a href="#" class="wclose"><i class="icon-remove"></i></a>
        </div>
    
    	<div class="clearfix"></div>
    
    </div>             
    
    <!-- Widget content -->
    <div class="widget-content">

    	<div class="padd">
    	

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
            	<input type="text" class="form-control" name="staff_number" value='<?php echo $Staff_Number;?>' placeholder="Staff ID" readonly>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Title: </label>
            
            <div class="col-lg-8">
            	<input type="text" class="form-control" name="title" placeholder="title" value="<?php echo $gender_name;?>" readonly>

            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Surname: </label>
            
            <div class="col-lg-8">
            	<input type="text" class="form-control" name="patient_surname" placeholder="Surname" value="<?php echo $Surname;?>" readonly>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Other Names: </label>
            
            <div class="col-lg-8">
            	<input type="text" class="form-control" name="patient_othernames" placeholder="Other Names" value="<?php echo $Other_names?>" readonly>
            </div>
        </div>
        
        
	
    </div>
    <div class="col-md-6">
    	<div class="form-group">
            <label class="col-lg-4 control-label">Date of Birth: </label>
            
            <div class="col-lg-8">
                <div id="datetimepicker1" class="input-append">
                    <input data-format="yyyy-MM-dd" class="form-control" type="text" name="patient_dob" placeholder="Date of Birth" value="<?php echo $DOB;?>" readonly>
                    <span class="add-on">
                        &nbsp;<i data-time-icon="icon-time" data-date-icon="icon-calendar">
                        </i>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-4 control-label">Phone Number : </label>
            
            <div class="col-lg-8">
            	<input type="text" class="form-control" name="phone_number" placeholder="Phone number" value="<?php echo $patient_phone1?>" >

            </div>
        </div>

        <div class="form-group">
            <label class="col-lg-4 control-label">Alternate phone : </label>
            
            <div class="col-lg-8">
            	<input type="text" class="form-control" name="phone_number2" placeholder="Phone number" value="<?php echo $patient_phone2?>" >

            </div>
        </div>
        
       
        
	</div>
</div>

<div class="center-align">
	<button class="btn btn-info btn-lg" type="submit">Edit Staff Details</button>
</div>
<?php echo form_close();?>
    	</div>
    </div>
</div>
