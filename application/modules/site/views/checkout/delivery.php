
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
                <h2 class="block-title-2"> Please enter your any delivery instructions </h2>
                <h3> This may include time, location or any other details you may have </h3>
              </div>
              <div class="col-xs-12 col-sm-12">
                <div class="w100 row">
                	<?php echo form_open('checkout/add-delivery-instructions');?>
                  <div class="form-group col-lg-12 col-sm-12 col-md-12 -col-xs-12">
                    <label for="delivery_instructions">Delivery Instructions</label>
                    <textarea name="delivery_instructions" class="form-control" required><?php echo $this->session->userdata('delivery_instructions');?></textarea>
                  </div>
                  
                  <div class="form-group col-lg-12 col-sm-12 col-md-12 -col-xs-12">
            		<button type="submit" class="btn btn-primary"> Add instructions</button>
                  </div>
                  <?php echo form_close();?>
                </div>
                
                <!--/row-->
                
                <div class="cartFooter w100">
                  <div class="box-footer">
                    <div class="pull-left"> <a class="btn btn-default" href="<?php echo site_url().'checkout/my-details';?>"> <i class="fa fa-arrow-left"></i> &nbsp; Customer </a> </div>
                    <div class="pull-right"> <a href="<?php echo site_url().'checkout/payment';?>" class="btn btn-primary btn-small " > Payment Method &nbsp; <i class="fa fa-arrow-circle-right"></i> </a> </div>
                  </div>
                </div>
                <!--/ cartFooter --> 
                
              </div>
            </div>
          </div>
          <!--/row end--> 
          
        </div>
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
<!-- /main-container -->


<div class="gap"> </div>