

<div class="container main-container headerOffset">
  
  <?php echo $this->load->view('products/breadcrumbs');?>
  
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-7">
      <h1 class="section-title-inner"> <span> <i class="fa fa-unlock-alt"> </i> Forgot your password? </span> </h1>
      <div class="row userInfo">
        <div class="col-xs-12 col-sm-12">
          <p> To reset your password, enter the email address you use to sign in to site. We will then send you a new password. </p>
		  <?php
            $error = $this->session->userdata('front_error_message');
            
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
          ?>
          <?php echo form_open($this->uri->uri_string(), array("role" => "form"));?>
            <div class="form-group">
              <label for="email"> Email address </label>
              <input  type="email" class="form-control" name="email" placeholder="Enter email" required>
            </div>
            <button type="submit" class="btn   btn-primary"> <i class="fa fa-unlock"> </i> Reset Password </button>
          <?php echo form_close();?>
          <div class="clear clearfix">
            <ul class="pager">
              <li class="previous pull-right"> <a href="<?php echo site_url().'checkout';?>"> &larr; Back to Login </a> </li>
            </ul>
          </div>
        </div>
      </div>
      <!--/row end--> 
      
    </div>
    <div class="col-lg-3 col-md-3 col-sm-5"> </div>
  </div>
  <!--/row-->
  
  <div style="clear:both"> </div>
</div>
<!-- /wrapper -->

<div class="gap"> </div>