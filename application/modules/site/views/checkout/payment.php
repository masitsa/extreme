<?php 

	$payment_method = $this->session->userdata('payment_option');
	
	if($payment_method == 1)
	{
		$checked1 = 'checked';
		$checked2 = '';
	}
	else
	{
		$checked1 = '';
		$checked2 = 'checked';
	}
	

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
                <h2 class="block-title-2"> Payment method </h2>
                <p>Please select the preferred payment method to use on this order.</p>
                <hr>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="paymentBox">
                <?php echo form_open('checkout/add-payment-options');?>
                  <div class="panel-group paymentMethod" id="accordion">
                    <div class="panel panel-default">
                      <div class="panel-heading panel-heading-custom">
                        <h4 class="panel-title"> <a class="cashOnDelivery" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> <span class="numberCircuil">Option 1</span> <strong> Cash on Delivery</strong> </a> </h4>
                      </div>
                      <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="panel-body">
                          <p>You can opt to pay for your order upon its delivery.</p>
                          <br>
                          <label class="radio-inline" for="radios-4">
                            <input name="radios" id="radios-4" value="1" type="radio" <?php echo $checked1?>>
                            Cash On Delivery </label>
                          <div class="pull-right"> <button type="submit" class="btn btn-primary btn-small " > Order &nbsp; <i class="fa fa-arrow-circle-right"></i> </button> </div>
                        </div>
                      </div>
                    </div>
                    <div class="panel panel-default">
                      <div class="panel-heading panel-heading-custom">
                        <h4 class="panel-title"> <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"> <span class="numberCircuil">Option 2</span><strong> MPesa</strong> </a> </h4>
                      </div>
                      <div id="collapseTwo" class="panel-collapse collapse">
                        <div class="panel-body">
                          <p>You can also choose to pay for your order via MPesa. Our number is 0713 249 320</p>
                          <br>
                          <label class="radio-inline" for="radios-3">
                            <input name="radios" id="radios-3" value="2" type="radio" <?php echo $checked2?>>
                            MPesa</label>
                          <div class="pull-right"> <button type="submit" class="btn btn-primary btn-small " > Order &nbsp; <i class="fa fa-arrow-circle-right"></i> </button> </div>
                        </div>
                      </div>
                    </div>
                    
                  </div>
                </div>
                <?php echo form_close();?>
                <!--/row--> 
                
              </div>
            </div>
          </div>
          <!--/row end-->
          
          <div class="cartFooter w100">
            <div class="box-footer">
              <div class="pull-left"> <a class="btn btn-default" href="<?php echo site_url().'checkout/delivery';?>"> <i class="fa fa-arrow-left"></i> &nbsp; Delivery </a> </div>
            </div>
          </div>
        </div>
        
        <!--/ cartFooter --> 
        
      </div>
    </div>
    <!--/row end-->
    
    <div class="col-lg-3 col-md-3 col-sm-12 rightSidebar">
      <div class="w100 cartMiniTable">
      	<?php echo $this->load->view('cart/cart_summary', '', TRUE);?>
      </div>
    </div>
    <!--/rightSidebar--> 
    
  </div>
  <!--/row-->
  
  <div style="clear:both"></div>
</div>
<!-- /.main-container -->
<div class="gap"> </div>