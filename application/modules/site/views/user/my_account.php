<?php
	$user = $user_details->row();
	$fname = $user->first_name;
?>
<div class="container main-container headerOffset">
  
  <?php echo $this->load->view('products/breadcrumbs');?>
  
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-7">
      <h1 class="section-title-inner"><span><i class="fa fa-unlock-alt"></i> My account </span></h1>
      <div class="row userInfo">
        <div class="col-xs-12 col-sm-12">
        	<p>Hi <?php echo $fname;?></p>
          <h2 class="block-title-2"><span>Welcome to your account. Here you can manage all of your personal information and orders.</span></h2>
          <ul class="myAccountList row">
            <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
              <div class="thumbnail equalheight"> <a title="Orders" href="<?php echo site_url().'account/orders-list';?>"><i class="fa fa-calendar"></i> Order history </a> </div>
            </li>
            </li>
            <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
              <div class="thumbnail equalheight"> <a title="Personal information" href="<?php echo site_url().'account/my-details';?>"><i class="fa fa-cog"></i> Personal information</a> </div>
            </li>
            <!--<li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
              <div class="thumbnail equalheight"> <a title="My wishlists" href="<?php echo site_url().'account/wishlist';?>"><i class="fa fa-heart"></i> My wishlists </a> </div>
            </li>-->
          </ul>
          <div class="clear clearfix"> </div>
        </div>
      </div>
      <!--/row end--> 
      
    </div>
    <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">
      <h4 class="caps"><a href="<?php echo site_url().'account/sign-out';?>" class="btn btn-default"><i class="fa fa-sign-out"></i> Sign Out </a></h4>
    </div>
  </div>
  <!--/row-->
  
  <div style="clear:both"></div>
</div>
<!-- /wrapper -->
<div class="gap"> </div>