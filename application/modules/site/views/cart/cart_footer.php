<h3 class="text-right subtotal" id="cart_sub_total"> 
   	Subtotal: KES <?php echo $this->load->view('cart/cart_total', '', TRUE);?> 
</h3>
<a class="btn btn-sm btn-danger" href="<?php echo site_url().'cart';?>"> <i class="fa fa-shopping-cart"> </i> VIEW CART </a> 
<a class="btn btn-sm btn-primary" href="<?php echo site_url().'checkout';?>"> CHECKOUT </a> 
