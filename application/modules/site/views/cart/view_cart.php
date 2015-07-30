<div class="container main-container headerOffset">
  
  <?php echo $this->load->view('products/breadcrumbs');?>
  
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-7">
      <h1 class="section-title-inner"><span><i class="glyphicon glyphicon-shopping-cart"></i> Shopping cart </span></h1>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">
      <h4 class="caps"><a href="<?php echo site_url().'products/all-products';?>"><i class="fa fa-chevron-left"></i> Back to shopping </a></h4>
    </div>
  </div><!--/.row-->
  
  
  <div class="row">
    <div class="col-lg-9 col-md-9 col-sm-7">
      <form action="<?php echo site_url().'cart/update-cart';?>" method="POST">
      <div class="row userInfo">
        <div class="col-xs-12 col-sm-12">
          <div class="cartContent w100">
            <table class="cartTable table-responsive" style="width:100%">
              <tbody>
              
                <tr class="CartProduct cartTableHeader">
                  <td style="width:15%"  > Product </td>
                  <td style="width:40%"  >Details</td>
                  <td style="width:10%"  class="delete">&nbsp;</td>
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
								
								<td class="delete">
									<a title="Delete" href="'.site_url().'cart/delete-item/'.$items['rowid'].'/2"> 
										<i class="glyphicon glyphicon-trash fa-2x"></i>
									</a>
								</td>
								
								<td ><input class="quanitySniper" type="text" value="'.$items['qty'].'" name="quantity'.$items['rowid'].'"></td>
								
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
          
          <div class="cartFooter w100">
            <div class="box-footer">
              <div class="pull-left"> <a href="<?php echo site_url().'products/all-products';?>" class="btn btn-default"> <i class="fa fa-arrow-left"></i> &nbsp; Continue shopping </a> </div>
              <div class="pull-right">
                <button type="submit" class="btn btn-default"> <i class="fa fa-undo"></i> &nbsp; Update cart </button>
              </div>
            </div>
          </div> <!--/ cartFooter --> 
          
        </div>
      </div>
      <!--/row end--> 
      </form>
    </div>
    
    <div class="col-lg-3 col-md-3 col-sm-5 rightSidebar">
      <div class="contentBox" >
        <div class="w100 costDetails">
          <div class="table-block" id="order-detail-content"> <a class="btn btn-primary btn-lg btn-block " title="checkout" href="<?php echo site_url().'checkout';?>" style="margin-bottom:20px"> Proceed to checkout &nbsp; <i class="fa fa-arrow-right"></i> </a>
            <div class="w100 cartMiniTable">
              	<?php echo $this->load->view('cart/cart_summary', '', TRUE);?>
            </div>
          </div>
        </div>
      </div>
      <!-- End popular --> 
      
    </div>
    <!--/rightSidebar--> 
    
  </div><!--/row-->
  
  <div style="clear:both"></div>
</div><!-- /.main-container -->

<div class="gap"> </div>
