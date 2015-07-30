<!-- this part is duplicate from cartMenu  keep it for mobile -->
<div class="navbar-cart  collapse">
    <div class="cartMenu  col-lg-4 col-xs-12 col-md-4 ">
        
        <div class="w100 miniCartTable scroll-pane" id="menu_cart">
        	<?php echo $this->cart_model->get_cart();?>
        </div>
        <!--/.miniCartTable-->
        
        <div class="miniCartFooter  miniCartFooterInMobile text-right">
        	<?php
				echo $this->load->view('cart/cart_footer', '', TRUE);
			?>
        </div>
        <!--/.miniCartFooter--> 
    
    </div>
    <!--/.cartMenu--> 
</div>
<!--/.navbar-cart-->