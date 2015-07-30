
  
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-7">
      <h1 class="section-title-inner"><span><i class="glyphicon glyphicon-shopping-cart"></i> Checkout</span></h1>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">
      <h4 class="caps"><a href="<?php echo site_url().'products/all-products';?>"><i class="fa fa-chevron-left"></i> Back to shopping </a></h4>
    </div>
  </div> <!--/.row-->
  
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