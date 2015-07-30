<?php
	$user = $user_details->row();
?>
<div class="container main-container headerOffset">

	<?php echo $this->load->view('products/breadcrumbs');

      	$error = $this->session->userdata('front_error_message');
      	$success = $this->session->userdata('front_success_message');
		if(!empty($error))
		{
			?>
				<div class="alert alert-danger">
                    <p>
						<?php 
							echo $error;
							$this->session->unset_userdata('front_error_message');
                        ?>
                    </p>
				</div>
			<?php
		}
		
		if(!empty($success))
		{
			?>
                <div class="alert alert-success">
                    <p>
						<?php 
							echo $success;
							$this->session->unset_userdata('front_success_message');
                        ?>
                    </p>
                </div>
			<?php
		}
	?>

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 class="section-title-inner"><span><i class="glyphicon glyphicon-user"></i> My personal information </span></h1>
            <div class="row userInfo">
				<div class="col-lg-12">
					<h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2>
					<p class="required"><sup>*</sup> Required field</p>
				</div>
				
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<form action="<?php echo site_url().'account/update-details';?>" method="POST" role="form">
						<div class="form-group required">
							<label for="first_name">First Name <sup>*</sup> </label>
							<input required  type="text" class="form-control" name="first_name" value="<?php echo $user->first_name;?>" required>
						</div>
						<div class="form-group required">
							<label for="last_name">Last Name <sup>*</sup> </label>
							<input required type="text" class="form-control" name="last_name" value="<?php echo $user->other_names;?>" required>
						</div>
						<div class="form-group">
							<label for="email"> Email </label>
							<input type="email" class="form-control" name="email" value="<?php echo $user->email;?>" readonly>
						</div>
						<div class="form-group required">
							<label for="phone"> Mobile Phone <sup>*</sup></label>
							<input type="tel" class="form-control" name="phone" value="<?php echo $user->phone;?>" required>
						</div>
						<div class="col-lg-12">
							<button type="submit" class="btn   btn-primary"><i class="fa fa-save"></i> &nbsp; Update Account </button>
						</div>
					</form>
				</div>
                
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
					<form action="<?php echo site_url().'account/update-password';?>" method="POST" role="form">
						<div class="form-group required">
							<label for="current_password"> Password <sup> * </sup> </label>
							<input type="password" value="123456" name="current_password" class="form-control" required>
							<input type="hidden" value="<?php echo $user->password;?>" name="slug">
						</div>
						<div class="form-group required">
							<label for="new_password"> New Password <sup> * </sup></label>
							<input type="password"  name="new_password" class="form-control" required>
						</div>
						<div class="form-group required">
							<label for="confirm_password"> Confirm Password <sup> * </sup></label>
							<input type="password"  name="confirm_password" class="form-control" required>
						</div>
						<div class="col-lg-12">
							<button type="submit" class="btn   btn-primary"><i class="fa fa-save"></i> &nbsp; Update Password </button>
						</div>
					</form>
                </div>
			</div>
			<div class="col-lg-12 clearfix">
				<ul class="pager">
				<li class="previous pull-right"><a href="<?php echo site_url().'products/all-products';?>"> <i class="fa fa-home"></i> Go to Shop </a></li>
				<li class="next pull-left"><a href="<?php echo site_url().'account';?>"> &larr; Back to My Account</a></li>
				</ul>
			</div>
		</div>
		<!--/row end--> 
    </div>
    <!--/row-->
    
    <div style="clear:both"></div>
</div>
<!-- /main-container -->

<div class="gap"> </div>