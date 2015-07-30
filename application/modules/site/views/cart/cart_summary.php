<table id="cart-summary" class="std table">
    <tbody>
        <tr >
            <td>Total products</td>
            <td class="price" ><?php echo $this->cart->total_items();?> items</td>
        </tr>
        <tr  style="">
            <td>Delivery</td>
            <td class="price" ><span class="success">Free delivery!*</span></td>
        </tr>
        <tr >
            <td > Total </td>
            <td class=" site-color" id="total-price">KES <?php echo $this->load->view('cart/cart_total');?></td>
        </tr>
    </tbody>
</table>