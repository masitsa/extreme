<?php

class Cart_model extends CI_Model 
{
    
	/*
	*
	*	Add an item to the cart
	*	@param int product_id
	*
	*/
	public function add_item($product_id)
	{
		//check if the item exists in the cart
		$in_cart = $this->item_in_cart($product_id);
		//If item is already in cart update its quantity
		if($in_cart['result'])
		{
			$row_id = $in_cart['row_id'];
			$quantity = $in_cart['quantity'] + 1;
			
			$data = array(
               'rowid' => $row_id,
               'qty'   => $quantity
            );

			$this->cart->update($data); 
		}
		
		//otherwise add a new product to cart
		else
		{
			//get product details
			$product_details = $this->products_model->get_product($product_id);
			
			if($product_details->num_rows() > 0)
			{
				$product = $product_details->row();
				
				//calculate selling price
				$selling_price = $product->product_selling_price;
				$sale_price = $product->sale_price;
				if($sale_price > 0)
				{
					$selling_price = $selling_price - ($selling_price * ($sale_price/100));
				}
				
				//add product to cart
				$data = array(
					   'id'      => $product_id,
					   'qty'     => 1,
					   'price'   => $selling_price,
					   'name'    => $product->product_name
					   //'options' => array('Size' => 'L', 'Color' => 'Red')
					);
		
				$this->cart->insert($data); 
			}
		}
		
		return TRUE;
	}
    
	/*
	*
	*	Check if cart contains a particular product
	*	@param int product_id
	*
	*/
	public function item_in_cart($product_id)
	{
		$data['result'] = FALSE;
		foreach ($this->cart->contents() as $items): 

			$cart_product_id = $items['id'];
			
			if($cart_product_id == $product_id)
			{
				$data['result'] = TRUE;
				$data['row_id'] = $items['rowid'];
				$data['quantity'] = $items['qty'];
				
				break;
			}
		
		endforeach; 
		
		return $data;
	}
    
	/*
	*
	*	Get the items in cart
	*
	*/
	public function get_cart()
	{
		$cart = '
					<table  >
						<tbody>';
		
		foreach ($this->cart->contents() as $items): 

			$cart_product_id = $items['id'];
			
			//get product details
			$product_details = $this->products_model->get_product($cart_product_id);
			
			if($product_details->num_rows() > 0)
			{
				$product = $product_details->row();
				
				$product_thumb = $product->product_thumb_name;
				$total = number_format($items['qty']*$items['price'], 0, '.', ',');
			
				$cart .= '
							<tr class="miniCartProduct">
								<td style="20%" class="miniCartProductThumb">
									<div> 
										<a href="#"> 
											<img src="'.base_url().'assets/images/products/images/'.$product_thumb.'" alt="'.$items['name'].'"> 
										</a> 
									</div>
								</td>
								<td style="40%">
									<div class="miniCartDescription">
										<h4> <a href="#"> '.$items['name'].' </a> </h4>
										<div class="price"> <span> KES '.number_format($items['price'], 0, '.', ',').' </span> </div>
									</div>
								</td>
								<td  style="10%" class="miniCartQuantity"><a > X '.$items['qty'].' </a></td>
								<td  style="15%" class="miniCartSubtotal"><span> KES '.$total.' </span></td>
								<td  style="5%" class="delete"><a style="color:red;" href='.$items['rowid'].' class="delete_cart_item"> <i class="glyphicon glyphicon-trash"></i> </a></td>
							</tr>
				';
			}
		
		endforeach; 
		
		$cart .= '
						</tbody>
					</table>';
		
		return $cart;
	}
    
	/*
	*
	*	Delete an item from the cart
	*	@param int row_id
	*
	*/
	public function delete_cart_item($row_id)
	{
		$data = array(
		   'rowid' => $row_id,
		   'qty'   => 0
		);

		$this->cart->update($data); 
		
		return TRUE;
	}
    
	/*
	*
	*	Update the cart
	*	@param int product_id
	*
	*/
	public function update_cart()
	{
		foreach ($this->cart->contents() as $items): 

			$row_id = $items['rowid'];
			$current_quantity = $items['qty'];
			
			$update_quantity = $this->input->post('quantity'.$row_id);
			
			if($update_quantity != $current_quantity)
			{
				$data = array(
				   'rowid' => $row_id,
				   'qty'   => $update_quantity
				);
	
				$this->cart->update($data); 
			}
		
		endforeach; 
		
		return TRUE;
	}
    
	/*
	*
	*	Save the cart items to the db
	*
	*/
	public function save_order()
	{
		//get order number
		$order_number = $this->orders_model->create_order_number();
		
		//create order
		$data = array(
					'user_id'=>$this->session->userdata('user_id'),
					'created'=>date('Y-m-d H:i:s'),
					'order_instructions'=>$this->session->userdata('delivery_instructions'),
					'payment_method'=>$this->session->userdata('payment_option'),
					'order_number'=>$order_number,
					'created_by'=>$this->session->userdata('user_id')
				);
				
		if($this->db->insert('orders', $data))
		{
			$order_id = $this->db->insert_id();
			
			//save order items
			foreach ($this->cart->contents() as $items): 
	
				$cart_product_id = $items['id'];
				$quantity = $items['qty'];
				$price = $items['price'];
				
				$data = array(
						'product_id'=>$cart_product_id,
						'order_id'=>$order_id,
						'quantity'=>$quantity,
						'price'=>$price
					);
					
				if($this->db->insert('order_item', $data))
				{
					
				}
			
			endforeach; 
			
			//remove session data
			$array_items = array('delivery_instructions' => '', 'payment_option' => '');
			$this->session->unset_userdata($array_items);
			
			//clear the shopping cart
			$this->cart->destroy();
		}
		
		return TRUE;
	}
}

