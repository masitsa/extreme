<?php
$contacts = $this->site_model->get_contacts();
 $logo = $contacts['logo'];
?>
<section class="panel">
	<header class="panel-heading">
            <h5 class="pull-left"><i class="icon-reorder"></i>Profile Details</h5>
          <div class="widget-icons pull-right">

          </div>
          <div class="clearfix"></div>
    </header>
	<div class="panel-body">
		<div class="row">
			<div class="col-md-6 col-lg-12 col-xl-6">
				<div class="col-lg-3">
						<div class="form-group">
								 <div class="col-lg-12">
								 		<img src="<?php echo base_url();?>assets/logo/<?php echo $logo;?>">
								 </div>
						</div>
				</div>
				<div class="col-lg-3">
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
				<div class="col-lg-6">
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
	</div>
</section>
	