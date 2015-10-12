<?php
$relationships = $this->reception_model->get_relationship();
$religions = $this->reception_model->get_religion();
$civil_statuses = $this->reception_model->get_civil_status();
$titles = $this->reception_model->get_title();
$genders = $this->reception_model->get_gender();
$patient = $this->reception_model->get_patient_data($patient_id);
$insurance = $this->reception_model->get_insurance();

$row = $patient->row();
$patient_surname = $row->patient_surname;
$patient_othernames = $row->patient_othernames;
$title_id = $row->title_id;
$patient_date_of_birth = $row->patient_date_of_birth;
$gender_id = $row->gender_id;
$religion_id = $row->religion_id;
$civil_status_id = $row->civil_status_id;
$patient_email = $row->patient_email;
$patient_address = $row->patient_address;
$patient_postalcode = $row->patient_postalcode;
$patient_town = $row->patient_town;
$patient_phone1 = $row->patient_phone1;
$patient_phone2 = $row->patient_phone2;
$patient_kin_sname = $row->patient_kin_sname;
$patient_kin_othernames = $row->patient_kin_othernames;
$relationship_id = $row->relationship_id;
$patient_national_id = $row->patient_national_id;
$insurance_company_id = $row->insurance_company_id;
$next_of_kin_contact = $row->patient_kin_phonenumber1;

//patient room details
$visit_room_id = '';
$visit_bed_id = '';

if($visit_bed->num_rows() > 0)
{
	$res = $visit_bed->row();
	
	$visit_room_id = $res->room_id;
	$visit_bed_id = $res->bed_id;
}

$room_beds = $this->nurse_model->get_room_beds($visit_room_id);

?>
<div class="row">
	<div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Room details</h2>
            </header>
            <div class="panel-body">
            	<?php echo form_open('nurse/set_patient_room/'.$visit_id);?>
            	<div class="row">
                	<div class="col-md-4">
						<div class="form-group">
                            <label class="col-md-4 control-label">Ward: </label>
                            
                            <div class="col-md-8">
                                <select class="form-control" name="ward_id" id="ward_id">
								<?php
                                    if($wards->num_rows() > 0)
                                    {
                                        foreach($wards->result() as $res)
                                        {
                                            $ward_id = $res->ward_id;
                                            $ward_name = $res->ward_name;
                                            
                                            if($visit_ward_id == $ward_id)
                                            {
                                                echo '<option value="'.$ward_id.'" selected>'.$ward_name.'</option>';
                                            }
											else
											{
                                                echo '<option value="'.$ward_id.'">'.$ward_name.'</option>';
											}
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                	<div class="col-md-4">
						<div class="form-group">
                            <label class="col-md-4 control-label">Room: </label>
                            
                            <div class="col-md-8">
                                <select class="form-control" name="room_id" id="room_id">
                                	<option value="">-- Select a room --</option>
								<?php
                                    if($ward_rooms->num_rows() > 0)
                                    {
                                        foreach($ward_rooms->result() as $res)
                                        {
                                            $room_id = $res->room_id;
                                            $room_name = $res->room_name;
                                            
                                            if($visit_room_id == $room_id)
                                            {
                                                echo '<option value="'.$room_id.'" selected>'.$room_name.'</option>';
                                            }
											else
											{
                                                echo '<option value="'.$room_id.'">'.$room_name.'</option>';
											}
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                	<div class="col-md-4">
						<div class="form-group">
                            <label class="col-md-4 control-label">Bed: </label>
                            
                            <div class="col-md-8">
                                <select class="form-control" name="bed_id" id="bed_id">
                                	<option value="">-- Select a bed --</option>
								<?php
                                    if($room_beds->num_rows() > 0)
                                    {
                                        foreach($room_beds->result() as $res)
                                        {
                                            $bed_id = $res->bed_id;
                                            $bed_number = $res->bed_number;
                                            
                                            if($visit_bed_id == $bed_id)
                                            {
                                                echo '<option value="'.$bed_id.'" selected>'.$bed_number.'</option>';
                                            }
											else
											{
                                                echo '<option value="'.$bed_id.'">'.$bed_number.'</option>';
											}
                                        }
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="center-align" style="margin-top:10px;">
                	<button type="submit" class="btn btn-primary">Update room details</button>
                </div>
                <?php echo form_close();?>
            </div>
            
            <div class="row">
            	<div class="col-md-12">
            		<div id="bed_charges"></div>
            	</div>
            </div>
         </section>
    </div>
    
	<div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">Consultant fee</h2>
            </header>
            <div class="panel-body">
            	<div id="consultation_charges"></div>
            </div>
        </section>
    </div>
    
	<div class="col-md-12">
        <section class="panel panel-featured panel-featured-info">
            <header class="panel-heading">
                <h2 class="panel-title">About patient</h2>
            </header>
            <div class="panel-body">
            	<div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="col-md-4 control-label">Title: </label>
                            
                            <div class="col-md-8">
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
                                                echo $title_name;
                                                break;
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Surname: </label>
                            
                            <div class="col-md-8">
                                <?php echo $patient_surname;?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Other Names: </label>
                            
                            <div class="col-md-8">
                                <?php echo $patient_othernames;?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Date of Birth: </label>
                            
                            <div class="col-md-8">
                                <?php echo $patient_date_of_birth;?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Gender: </label>
                            
                            <div class="col-md-8">
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
                                                echo $gender_name;
                                                break;
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        
                        
                       
                        <div class="form-group">
                            <label class="col-md-4 control-label">Email Address: </label>
                            
                            <div class="col-md-8">
                                <?php echo $patient_email;?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">National ID: </label>
                            
                            <div class="col-md-8">
                                <?php echo $patient_national_id;?>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="col-md-6">
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Primary Phone: </label>
                            
                            <div class="col-md-8">
                                <?php echo $patient_phone1;?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Other Phone: </label>
                            
                            <div class="col-md-8">
                                <?php echo $patient_phone2;?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Next of Kin Surname: </label>
                            
                            <div class="col-md-8">
                                <?php echo $patient_kin_sname;?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Next of Kin Other Names: </label>
                            
                            <div class="col-md-8">
                                <?php echo $patient_kin_sname;?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Next of Kin Contact: </label>
                            
                            <div class="col-md-8">
                                <?php echo $next_of_kin_contact;?>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-md-4 control-label">Relationship To Kin: </label>
                            
                            <div class="col-md-8">
								<?php
                                    if($relationships->num_rows() > 0)
                                    {
                                        $relationship = $relationships->result();
                                        
                                        foreach($relationship as $res)
                                        {
                                            $db_relationship_id = $res->relationship_id;
                                            $relationship_name = $res->relationship_name;
                                            
                                            if($db_relationship_id == $relationship_id)
                                            {
                                                echo $relationship_name;
                                                break;
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </section>
    </div>
</div>

<script type="text/javascript">
	
	$(document).on("change","select#ward_id",function(e)
	{
		var ward_id = $(this).val();
		
		//get rooms
		$.get( "<?php echo site_url();?>nurse/get_ward_rooms/"+ward_id, function( data ) 
		{
			$( "#room_id" ).html( data );
			
			$.get( "<?php echo site_url();?>nurse/get_room_beds/0", function( data ) 
			{
				$( "#bed_id" ).html( data );
			});
		});
	});
	
	$(document).on("change","select#room_id",function(e)
	{
		var room_id = $(this).val();
		
		//get beds
		$.get( "<?php echo site_url();?>nurse/get_room_beds/"+room_id, function( data ) 
		{
			$( "#bed_id" ).html( data );
		});
	});
</script>