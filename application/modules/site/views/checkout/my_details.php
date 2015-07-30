<?php
	$user = $user_details->row();
?>
<div class="container main-container headerOffset">
  
  <?php echo $this->load->view('products/breadcrumbs');?>
  
  <?php echo $this->load->view('checkout/checkout_header');?>
  
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-12">
      <div class="row userInfo">
        <div class="col-xs-12 col-sm-12">
          <div class="w100 clearfix">
            <?php echo $this->load->view('checkout/order_steps', '', TRUE);?>
          </div>
          
          
          <div class="w100 clearfix">
            <div class="row userInfo">
              <div class="col-lg-12">
                <h2 class="block-title-2"> To add a new address, please fill out the form below. </h2>
              </div>
              
              <form>
                <div class="col-xs-12 col-sm-6">
                  <div class="form-group required">
                    <label for="InputName">First Name <sup>*</sup> </label>
                    <input required type="text" class="form-control" id="InputName" placeholder="First Name" value="<?php echo $user->first_name;?>">
                  </div>
                  <div class="form-group required">
                    <label for="InputLastName">Last Name <sup>*</sup> </label>
                    <input required type="text" class="form-control" id="InputLastName" placeholder="Last Name" value="<?php echo $user->other_names;?>">
                  </div>
                  <div class="form-group">
                    <label for="InputEmail">Email </label>
                    <input type="text" class="form-control" id="InputEmail" placeholder="Email" readonly value="<?php echo $user->email;?>">
                  </div>
                  <div class="form-group required">
                    <label for="InputMobile">Mobile phone <sup>*</sup></label>
                    <input  required type="tel"  name="InputMobile" class="form-control" id="InputMobile" value="<?php echo $user->phone;?>">
                  </div>
                </div>
              </form>
            </div>
            <!--/row end--> 
            
          </div>
          <div class="cartFooter w100">
            <div class="box-footer">
              <div class="pull-left"> <a class="btn btn-default" href="<?php echo site_url().'products/all-products';?>"> <i class="fa fa-arrow-left"></i> &nbsp; Back to Shop </a> </div>
              <div class="pull-right"> <a class="btn btn-primary btn-small "  href="<?php echo site_url().'checkout/delivery';?>"> Delivery &nbsp; <i class="fa fa-arrow-circle-right"></i> </a> </div>
            </div>
          </div>
          <!--/ cartFooter --> 
          
        </div>
      </div>
      <!--/row end--> 
      
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12 rightSidebar">
      <div class="w100 cartMiniTable">
      	<?php echo $this->load->view('cart/cart_summary', '', TRUE);?>
      </div>
      <!--  /cartMiniTable--> 
      
    </div>
    <!--/rightSidebar--> 
    
  </div> <!--/row-->
  
  <div style="clear:both"></div>
</div>
<!-- /.main-container-->
<div class="gap"> </div>
