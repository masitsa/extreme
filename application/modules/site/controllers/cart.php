<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/site/controllers/site.php";

class Cart extends site {
	
	function __construct()
	{
		parent:: __construct();
	}
	
	public function add_item($product_id)
	{
		if($this->cart_model->add_item($product_id))
		{
			$cart_items = $this->cart_model->get_cart();
			
			$data['result'] = 'success';
			$data['cart_items'] = $cart_items;
			$data['cart_total'] = $this->load->view('cart/cart_total', '', TRUE);
		}
		
		else
		{
			$data['result'] = 'failure';
		}
		
		echo json_encode($data);
	}
	
	public function delete_cart_item($row_id, $page = 1)
	{
		if($this->cart_model->delete_cart_item($row_id))
		{
			$cart_items = $this->cart_model->get_cart();
			
			$data['result'] = 'success';
			$data['cart_items'] = $cart_items;
			$data['cart_total'] = $this->load->view('cart/cart_total', '', TRUE);
		}
		
		else
		{
			$data['result'] = 'failure';
		}
		
		if($page == 1)
		{
			echo json_encode($data);
		}
		
		else
		{
			redirect('cart');
		}
	}
	
	public function view_cart()
	{
		//Required general page data
		$v_data['all_children'] = $this->categories_model->all_child_categories();
		$v_data['parent_categories'] = $this->categories_model->all_parent_categories();
		$v_data['crumbs'] = $this->site_model->get_crumbs();
		
		$data['content'] = $this->load->view('cart/view_cart', $v_data, true);
		
		$data['title'] = $this->site_model->display_page_title();
		$this->load->view('templates/general_page', $data);
	}
	
	public function update_cart()
	{
		if($this->cart_model->update_cart())
		{
			
		}
		
		else
		{
			
		}
		
		redirect('cart');
	}
}
?>