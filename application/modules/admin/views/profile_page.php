<?php
$contacts = $this->site_model->get_contacts();
$logo = $contacts['logo'];
?>
<section class="panel">
	<header class="panel-heading">
    	<h2 class="panel-title">Profile Details</h2>
    </header>
	<div class="panel-body">
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
			
		?>
		<div class="row">
            <div class="col-sm-12 col-md-4 col-lg-3">
                <img src="<?php echo base_url();?>assets/logo/<?php echo $logo;?>">
            </div>
            <div class="col-sm-6 col-md-4 col-lg-3">
                <h5><strong>Profile Details</strong></h5>
                <div class="form-group">
                    <label class="col-lg-4 control-label"><strong> Personnel Name:</strong> </label>
                    
                    <div class="col-lg-8">
                        <?php echo $this->session->userdata('first_name');?>
                    </div>

                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label"> <strong>Username:</strong> </label>
                    
                    <div class="col-lg-8">
                        <?php echo $this->session->userdata('username');?>
                    </div>
                    
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label"><strong>Branch Name: </strong></label>
                    
                    <div class="col-lg-8">
                        <?php echo $this->session->userdata('branch_name');?>
                    </div>
                    
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label"><strong> Branch Code:</strong> </label>
                    
                    <div class="col-lg-8">
                        <?php echo $this->session->userdata('branch_code');?>
                    </div>
                    
                </div>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-6">
                <h5><strong>Change Password</strong></h5>
                <form enctype="multipart/form-data" action="<?php echo base_url();?>change-password"  id = "change_password" method="post">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="slideshow_name"> <strong>Current Password </strong></label>
                                    <div class="col-md-8">
                                        <input type="password" class="form-control" name="current_password" placeholder="Current Password" >
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="slideshow_name"><strong>New Password</strong></label>
                                    <div class="col-md-8">
                                        <input type="password" class="form-control" name="new_password" placeholder="New Password" >
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="form-group">
                                    <label class="col-md-4 control-label" for="slideshow_name"><strong>Confirm New Password</strong></label>
                                    <div class="col-md-8">
                                        <input type="password" class="form-control" name="confirm_new_password" placeholder="Confirm new password" >
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                    <div class="form-group center-align">
                                        <button type="submit" class="btn btn-sm btn-success" name="submit" >Change Password</button>
                                    </div>
                                
                            </div>
                                
                        </div>
                        
                    </div>
                </form>
            </div>
		</div>
	</div>
</section>
	