<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta charset="utf-8">
  <!-- Title and other stuffs -->
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="author" content="">

  <!-- Stylesheets -->
  <link href="<?php echo base_url()."assets/bluish/";?>style/bootstrap.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo base_url()."assets/bluish/";?>style/font-awesome.css">
  <link href="<?php echo base_url()."assets/bluish/";?>style/style.css" rel="stylesheet">
  
  
  <!-- HTML5 Support for IE -->
  <!--[if lt IE 9]>
  <script src="js/html5shim.js"></script>
  <![endif]-->

</head>

<body class="login">

<!-- Form area -->
<div class="admin-form">
  <div class="container">

    <div class="row">
      <div class="col-md-12">
        <!-- Widget starts -->
            <div class="logo"><a href="<?php echo site_url();?>" class="navbar-brand"><span class="bold">S</span>UMC <span class="bold">SYSTEM</span></a></div>
            <div class="widget">
              <!-- Widget head -->
              <div class="widget-head">
                  Sign In to your Account
              </div>

              <div class="widget-content">
                <div class="padd">
                	<!-- Login Errors -->
                    <?php
					if(isset($error)){
                    	echo '<div class="alert alert-danger">'.$error.'</div>';
					}
					?>
                  <!-- Login form -->
                  <?php 
				  echo form_open($this->uri->uri_string(),"class='form-horizontal'"); 
				  ?>
                    <!-- Email -->
                    <div class="form-group">
                        <i class="icon-user"></i>
                        <input type="text" class="form-control" id="inputEmail" name="username" placeholder="Username">
                    </div>
                    <!-- Password -->
                    <div class="form-group">
                        <i class="icon-lock"></i>
                        <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Password">
                    </div>
                    <!-- Remember me checkbox and sign in button -->
                      <div class="form-actions">
                          
                          <button class="submit btn btn-primary pull-right" type="submit">
                              Sign In
                              <i class="icon-angle-right"></i>
                          </button>
                      </div>
                    <br />
                  <?php echo form_close();?>
				  
				</div>
                </div>
              
                <div class="widget-foot">
                  Forgot Password? <a href="#" class="reset_password">Click here to reset</a>
                  <form class="form-horizontal hide_section" action="<?php echo site_url()."login/forgot_password/1";?>" id="forgot_password">
                    <!-- Email -->
                    <div class="form-group">
                        <i class="icon-user"></i>
                        <input type="text" class="form-control" id="inputEmail" name="email" placeholder="Email">
                    </div>
                    <!-- Remember me checkbox and sign in button -->
                      <div class="form-actions">
                          <button class="submit btn btn-primary pull-right" type="submit">
                              Reset Password
                              <i class="icon-angle-right"></i>
                          </button>
                      </div>
                    <br />
                  </form>
                </div>
            </div>
      </div>
    </div>
  </div> 
</div>
	
		

<!-- JS -->
<script src="<?php echo base_url()."assets/bluish/";?>js/jquery.js"></script>
<script src="<?php echo base_url()."assets/bluish/";?>js/bootstrap.js"></script>
<script type="text/javascript">
	$(document).on("click","a.reset_password",function(){
		
		$( "#forgot_password" ).removeClass( "hide_section" );
		$( "#forgot_password" ).addClass( "show_section" );
	});
</script>
</body>
</html>