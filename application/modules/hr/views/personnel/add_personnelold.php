<?php
//personnel data
$personnel_onames =set_value('personnel_onames');
$personnel_fname =set_value('personnel_fname');
$personnel_dob =set_value('personnel_dob');
$personnel_email =set_value('personnel_email');
$personnel_phone =set_value('personnel_phone');
$personnel_address =set_value('personnel_address');
$civil_status_id =set_value('civil_status_id');
$personnel_locality =set_value('personnel_locality');
$title_id =set_value('title_id');
$gender_id =set_value('gender_id');
$personnel_username =set_value('personnel_username');
$personnel_kin_fname =set_value('personnel_kin_fname');
$personnel_kin_onames =set_value('personnel_kin_onames');
$personnel_kin_contact =set_value('personnel_kin_contact');
$personnel_kin_address =set_value('personnel_kin_address');
$kin_relationship_id =set_value('kin_relationship_id');
$job_title_id =set_value('job_title_id');
$staff_id =set_value('staff_id');
?>          
          <personnel class="panel">
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
                            <a href="<?php echo site_url();?>administration/personnel" class="btn btn-info pull-right">Back to personnel</a>
                        </div>
                    </div>
                        
                    <!-- Adding Errors -->
                    <?php
                    if(isset($error)){
                        echo '<div class="alert alert-danger"> Oh snap! Change a few things up and try submitting again. </div>';
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
            <label class="col-lg-5 control-label">Username: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_username" placeholder="First Name" value="<?php echo $personnel_username;?>">
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
            <label class="col-lg-5 control-label">Job Title: </label>
            
            <div class="col-lg-7">
                <select class="form-control" name="job_title_id">
                	<?php
                    	if($job_titles_query->num_rows() > 0)
						{
							$job_titles = $job_titles_query->result();
							
							foreach($job_titles as $res)
							{
								$db_job_title_id = $res->job_title_id;
								$job_title_name = $res->job_title_name;
								
								if($db_job_title_id == $job_title_id)
								{
									echo '<option value="'.$db_job_title_id.'" selected>'.$job_title_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$db_job_title_id.'">'.$job_title_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
	</div>
    
    <div class="col-md-6">
        
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
            <label class="col-lg-5 control-label">Address: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_address" placeholder="Address" value="<?php echo $personnel_address;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Locality: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_locality" placeholder="Locality" value="<?php echo $personnel_locality;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Next of Kin First Name: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_kin_fname" placeholder="Kin Surname" value="<?php echo $personnel_kin_fname;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Next of Kin Other Names: </label>
            
            <div class="col-lg-7">
            	<input type="text" class="form-control" name="personnel_kin_onames" placeholder="Kin Other Names" value="<?php echo $personnel_kin_onames;?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-5 control-label">Next of Kin Contact: </label>
            
            <div class="col-lg-7">
                <input type="text" class="form-control" name="personnel_kin_contact" placeholder="Kin Contact" value="<?php echo $personnel_kin_contact;?>">
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-5 control-label">Next of Kin Address: </label>
            
            <div class="col-lg-7">
                <input type="text" class="form-control" name="personnel_kin_address" placeholder="Kin Address" value="<?php echo $personnel_kin_address;?>">
            </div>
        </div>
        
        <div class="form-group">
            <label class="col-lg-5 control-label">Relationship To Kin: </label>
            
            <div class="col-lg-7">
                <select class="form-control" name="relationship_id">
                	<?php
                    	if($relationships->num_rows() > 0)
						{
							$relationship = $relationships->result();
							
							foreach($relationship as $res)
							{
								$db_relationship_id = $res->relationship_id;
								$relationship_name = $res->relationship_name;
								
								if($db_relationship_id == $kin_relationship_id)
								{
									echo '<option value="'.$db_relationship_id.'" selected>'.$relationship_name.'</option>';
								}
								
								else
								{
									echo '<option value="'.$db_relationship_id.'">'.$relationship_name.'</option>';
								}
							}
						}
					?>
                </select>
            </div>
        </div>
        
    </div>
</div>
                    <div class="form-group">
                        <div class="col-lg-6 col-lg-offset-4">
                            <div class="form-actions center-align">
                                <button class="submit btn btn-primary" type="submit">
                                    Add personnel
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <br />
                    <?php echo form_close();?>
                </div>
            </personnel>