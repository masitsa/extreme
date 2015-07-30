
<div class="container main-container headerOffset">
  
  <?php echo $this->load->view('products/breadcrumbs');?>
  
  <?php
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
  
    <div class="col-lg-12 col-md-12  col-sm-12">
    
      <h1 class="section-title-inner"><span><i class="fa fa-lock"></i> Authentication</span></h1>
      
      <div class="row userInfo">
        <div class="col-xs-12 col-sm-6">
          <h2 class="block-title-2"> Create an account </h2>
          <form role="form" action="<?php echo site_url().'checkout/register';?>" method="POST">
            <div class="form-group required">
              <label for="first_name">First Name <sup>*</sup></label>
              <input type="text" class="form-control" name="first_name" placeholder="Enter name" value="<?php echo set_value('first_name');?>" required>
            </div>
            <div class="form-group required">
              <label for="other_names">Other Names <sup>*</sup></label>
              <input type="text" class="form-control" name="other_names" placeholder="Enter last name" value="<?php echo set_value('other_names');?>" required>
            </div>
            <div class="form-group required">
              <label for="email">Email address <sup>*</sup></label>
              <input type="email" class="form-control" name="email" placeholder="Enter email" value="<?php echo set_value('email');?>" required>
            </div>
            <div class="form-group required">
              <label for="password">Password <sup>*</sup></label>
              <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group required">
            <label for="phone">Mobile phone <sup>*</sup></label>
            <input  required type="tel"  name="phone" class="form-control" placeholder="Enter phone" value="<?php echo set_value('phone');?>">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-user"></i> Create an account</button>
          </form>
        </div>
        
        
        <div class="col-xs-12 col-sm-6">
          <h2 class="block-title-2"><span>Already registered?</span></h2>
          <form role="form" action="<?php echo site_url().'checkout/login';?>" method="POST">
            <div class="form-group required">
              <label for="InputEmail2">Email address <sup>*</sup></label>
              <input type="email" class="form-control" name="email" placeholder="Enter email" value="<?php echo set_value('email');?>" required>
            </div>
            <div class="form-group required">
              <label for="InputPassword2">Password <sup>*</sup></label>
              <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="checkbox">
                Remember me </label>
            </div>
            <div class="form-group">
              <p><a title="Recover your forgotten password" href="<?php echo site_url().'forgot-password';?>">Forgot your password? </a></p>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-sign-in"></i> Sign In</button>
          </form>
        </div>
        <!-- <div class="col-xs-12 col-sm-4">
          <h2 class="block-title-2"><span>Checkout as Guest</span></h2>
          <p>Don't have an account and you don't want to register? Checkout as a guest instead!</p>
          <a href=" action="<?php echo site_url().'checkout/my-details';?>"" class="btn btn-primary"><i class="fa fa-sign-in"></i> Checkout as Guest</a> </div> -->
      </div> <!--/row end--> 
      
    </div>
  </div> <!--/row-->
  
  <div style="clear:both"></div>
</div>
<!-- /.main-container -->

<div class="gap"> </div>