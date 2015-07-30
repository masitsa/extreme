
<div class="container main-container headerOffset">
  
  <?php echo $this->load->view('products/breadcrumbs');?>
  
  <?php echo $this->load->view('checkout/checkout_header');?>
  
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <div class="row userInfo">
        
        <div class="col-xs-12 col-sm-12">
        
       <div class="w100 clearfix"> 
            <?php echo $this->load->view('checkout/order_steps', '', TRUE);?>
        </div>
        
        
        <div class="w100 clearfix">
        
        
        	<div class="row userInfo">
                
                <div class="col-lg-12">
                    <h2 class="block-title-2"> Review Order </h2>
                </div>
                
            	<div class="col-xs-12 col-sm-12">
                      <div class="cartContent w100 checkoutReview ">
                        
            <table class="cartTable table-responsive" style="width:100%">
              <tbody>
              
                <tr class="CartProduct cartTableHeader">
                  <td style="width:15%"  > Product </td>
                  <td style="width:40%"  >Details</td>
                  <td style="width:10%" >QNT</td>
                  <td style="width:10%" >Discount</td>
                  <td style="width:15%" >Total</td>
                </tr>
                
                <?php
                foreach ($this->cart->contents() as $items): 

					$cart_product_id = $items['id'];
					
					//get product details
					$product_details = $this->products_model->get_product($cart_product_id);
					
					if($product_details->num_rows() > 0)
					{
						$product = $product_details->row();
						
						$product_thumb = $product->product_thumb_name;
						$sale_price = $product->sale_price;
						$product_selling_price = $product->product_selling_price;
						$discount = 0;
						
						if($sale_price > 0)
						{
							$discount = $product_selling_price - ($product_selling_price * ($sale_price/100));
						}
						
						$total = number_format($items['qty']*$items['price'], 0, '.', ',');
					
						echo '
						
							<tr class="CartProduct">
								<td  class="CartProductThumb">
									<div> 
										<a href="'.site_url().'products/view-product/'.$items['id'].'">
											<img src="'.base_url().'assets/images/products/images/'.$product_thumb.'" alt="'.$items['name'].'">
										</a> 
									</div>
								</td>
								
								<td >
									<div class="CartDescription">
										<h4> <a href="'.site_url().'products/view-product/'.$items['id'].'">'.$items['name'].'</a> </h4>
										<div class="price"> <span>KES '.number_format($items['price'], 0, '.', ',').'</span></div>
									</div>
								</td>
								
								<td >'.$items['qty'].'</td>
								
								<td >'.$discount.'</td>
								<td class="price">KES '.$total.'</td>
							</tr>
						';
					}
		
				endforeach; 
				?>
              </tbody>
            </table>
          </div>
          <!--cartContent-->
          
          <div class="w100 costDetails">
            <div class="table-block" id="order-detail-content">
      			<?php echo $this->load->view('cart/cart_summary', '', TRUE);?>
            </div>
          </div>
          <!--/costDetails-->
          <!--/row-->
        </div>
       </div>
     </div><!--/row end-->
    <div class="cartFooter w100">
    <div class="box-footer">
  <div class="pull-left"> <a class="btn btn-default" href="<?php echo site_url().'checkout/payment';?>">
        <i class="fa fa-arrow-left"></i> &nbsp; Payment method </a> 
   </div>
   
   
   <div class="pull-right">
    <a href="<?php echo site_url().'checkout/confirm-order';?>" class="btn btn-primary btn-small " > 
        Confirm Order &nbsp; <i class="fa fa-check"></i>
     </a>
  </div>
</div>
</div>
<!--/ cartFooter --> 
        
        </div>
        </div>
      </div>
      <!--/row end--> 
  </div>
  <!--/row-->
  
  <div style="clear:both"></div>
</div>
<!-- /wrapper --> 
<div class="gap"> </div>