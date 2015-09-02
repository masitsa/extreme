<div class="control_panel_form">
        <div class="container">
        	
            <div class="center-align">
            	<div class="row">
                    <h3>Hi <?php echo $this->session->userdata('personnel_fname');?>. Please select a fill in all the fields to change your password</h3>
                </div>
                <div class="row">
                <div class="center-align">
                <?php
                    $error = $this->session->userdata('error_message');
                    $validation_error = validation_errors();
                    $success = $this->session->userdata('success_message');
                    
                    if(!empty($error))
                    {
                        echo '<div class="alert alert-danger">'.$error.'</div>';
                        $this->session->unset_userdata('error_message');
                    }
                    
                    if(!empty($validation_error))
                    {
                        echo '<div class="alert alert-danger">'.$validation_error.'</div>';
                    }
                    
                    if(!empty($success))
                    {
                        echo '<div class="alert alert-success">'.$success.'</div>';
                        $this->session->unset_userdata('success_message');
                    }
                ?>
              </div>
                <?php  echo form_open("auth/change_user_password/".$personnel_id, array("class" => "form-horizontal"));?>
                	 <div class="form-group">
                        <label class="col-lg-4 control-label">Current Password: </label>
                        
                        <div class="col-lg-8">
                            <input class="form-control" type="password" name="current_password" placeholder="Current password">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-4 control-label">New Password: </label>
                        
                        <div class="col-lg-8">
                            <input  class="form-control" type="password"  name="new_password" placeholder="New password">
                        </div>
                    </div>
                     <div class="form-group">
                        <label class="col-lg-4 control-label">Confirm New Password: </label>
                        
                        <div class="col-lg-8">
                            <input class="form-control" type="password"  name="confirm_new_password" placeholder="Confirm New password">
                        </div>
                    </div>
        
                <div class="center-align">
                    <button class="btn btn-success btn-lg" type="submit">Change Password</button>
                    <?php 
                    if($module == 'user')
                    {

                    }
                    else
                    {
                    ?>
                     <a href='<?php echo site_url();?>/control-panel/<?php echo $personnel_id;?>' class="btn btn-warning btn-lg" >Back to Control Panel</a>
                    <?php
                    }
                    ?> 
                </div>
                <?php echo form_close();?>
                
        
                </div>
            </div>
        </div> 
    </div>