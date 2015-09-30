<?php
//personnel data
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
$personnel_type_id2 = set_value('personnel_type_id');
?>          
          <section class="panel">
                <header class="panel-heading">
                    <h2 class="panel-title"><?php echo $title;?></h2>
                </header>
                <div class="panel-body">
                	<div class="row" style="margin-bottom:20px;">
                        <div class="col-lg-12">
                            <a href="<?php echo site_url();?>human-resource/personnel" class="btn btn-info pull-right">Back to personnel</a>
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
        
	</div>
    
    <div class="col-md-6">
        
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
        
        
    </div>
</div>
<div class="row" style="margin-top:10px;">
	<div class="col-md-12">
        <div class="form-actions center-align">
            <button class="submit btn btn-primary" type="submit">
                Add personnel
            </button>
        </div>
    </div>
</div>
                    <?php echo form_close();?>
                </div>
            </section>