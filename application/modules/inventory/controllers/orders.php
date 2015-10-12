<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Orders extends MX_Controller
{ 
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/users_model');
		$this->load->model('inventory_management/products_model');
		$this->load->model('orders_model');
		$this->load->model('suppliers_model');
		$this->load->model('categories_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('administration/personnel_model');
		$this->load->model('hr/personnel_model');
	}
    
	/*
	*
	*	Default action is to show all the orders
	*
	*/
	public function index() 
	{
		// get my approval roles

		$where = 'orders.order_status_id = order_status.order_status_id';
		$table = 'orders, order_status';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'inventory/orders';
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = 4;
		$config['per_page'] = 20;
		$config['num_links'] = 5;
		
		$config['full_tag_open'] = '<ul class="pagination pull-right">';
		$config['full_tag_close'] = '</ul>';
		
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$config['next_tag_open'] = '<li>';
		$config['next_link'] = 'Next';
		$config['next_tag_close'] = '</span>';
		
		$config['prev_tag_open'] = '<li>';
		$config['prev_link'] = 'Prev';
		$config['prev_tag_close'] = '</li>';
		
		$config['cur_tag_open'] = '<li class="active">';
		$config['cur_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->orders_model->get_all_orders($table, $where, $config["per_page"], $page);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['order_status_query'] = $this->orders_model->get_order_status();
		// $v_data['level_status'] = $this->orders_model->order_level_status();
		$v_data['title'] = "All Orders";
		$data['content'] = $this->load->view('orders/all_orders', $v_data, true);
		
		$data['title'] = 'All orders';
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new order
	*
	*/
	public function add_order() 
	{
		//form validation rules
		$this->form_validation->set_rules('order_instructions', 'Order Instructions', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$order_id = $this->orders_model->add_order();
			//update order
			if($order_id > 0)
			{
				redirect('inventory/orders/'.$order_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update order. Please try again');
			}
		}
		
		//open the add new order
		$data['title'] = 'Add Order';
		$v_data['title'] = 'Add Order';
		$v_data['order_status_query'] = $this->orders_model->get_order_status();

		$data['content'] = $this->load->view('orders/add_order', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    

    public function add_order_item($order_id,$order_number)
    {

		$this->form_validation->set_rules('product_id', 'Product', 'required|xss_clean');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->orders_model->add_order_item($order_id))
			{
				$this->session->set_userdata('success_message', 'Order created successfully');
			}	
			else
			{
				$this->session->set_userdata('error_message', 'Something went wrong, please try again');
			}
		}
		else
		{

		}
		$v_data['title'] = 'Add Order Item to '.$order_number;
		$v_data['order_status_query'] = $this->orders_model->get_order_status();
		$v_data['products_query'] = $this->products_model->all_products();
		$v_data['order_number'] = $order_number;
		$v_data['order_id'] = $order_id;
		$v_data['order_item_query'] = $this->orders_model->get_order_items($order_id);
		$v_data['order_suppliers'] = $this->orders_model->get_order_suppliers($order_id);

		$v_data['suppliers_query'] = $this->suppliers_model->all_suppliers();
		$data['content'] = $this->load->view('orders/order_item', $v_data, true);

		$this->load->view('admin/templates/general_page', $data);
    }


    public function print_lpo_new($supplier_order_id)
	{
		$data = array('supplier_order_id'=>$supplier_order_id);

		$data['contacts'] = $this->site_model->get_contacts();
		
		$this->load->view('orders/views/lpo_print', $data);
		
	}
	public function print_rfq_new($order_id,$supplier_id,$order_number)
	{
		$data = array('order_id'=>$order_id,'supplier_id'=>$supplier_id,'order_number'=>$order_number);

		$data['contacts'] = $this->site_model->get_contacts();
		
		$this->load->view('orders/views/request_for_quotation', $data);
		
	}

    public function update_order_item($order_id,$order_number,$order_item_id)
    {
    	$this->form_validation->set_rules('quantity', 'Quantity', 'numeric|required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
	    	if($this->orders_model->update_order_item($order_id,$order_item_id))
			{
				$this->session->set_userdata('success_message', 'Order Item updated successfully');
			}	
			else
			{
				$this->session->set_userdata('error_message', 'Order Item was not updated');
			}
		}
		else
		{
			$this->session->set_userdata('success_message', 'Sorry, Please enter a number in the field');
		}
		redirect('inventory/add-order-item/'.$order_id.'/'.$order_number.'');

    }
    public function update_supplier_prices($order_id,$order_number,$order_item_id)
    {
    	$this->form_validation->set_rules('unit_price', 'Unit Price', 'numeric|required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
	    	if($this->orders_model->update_order_item_price($order_id,$order_item_id))
			{
				$this->session->set_userdata('success_message', 'Order Item updated successfully');
			}	
			else
			{
				$this->session->set_userdata('error_message', 'Order Item was not updated');
			}
		}
		else
		{
			$this->session->set_userdata('success_message', 'Sorry, Please enter a number in the field');
		}
		redirect('inventory/add-order-item/'.$order_id.'/'.$order_number.'');

    }
    public function submit_supplier($order_id,$order_number)
    {
    	$this->form_validation->set_rules('supplier_id', 'Quantity', 'numeric|required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->orders_model->add_supplier_to_order($order_id))
			{
				$this->session->set_userdata('success_message', 'Order Item updated successfully');
			}
			else
			{
				$this->session->set_userdata('success_message', 'Order Item updated successfully');
			}
		}
		else
		{
			$this->session->set_userdata('success_message', 'Order Item updated successfully');
		}
		redirect('inventory/add-order-item/'.$order_id.'/'.$order_number.'');
    }
	/*
	*
	*	Edit an existing order
	*	@param int $order_id
	*
	*/
	public function edit_order($order_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('order_instructions', 'Order Instructions', 'required|xss_clean');
		$this->form_validation->set_rules('user_id', 'Customer', 'required|xss_clean');
		$this->form_validation->set_rules('payment_method', 'Payment Method', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update order
			if($this->orders_model->update_order($order_id))
			{
				$this->session->set_userdata('success_message', 'Order updated successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update order. Please try again');
			}
		}
		
		//open the add new order
		$data['title'] = 'Edit Order';
		
		//select the order from the database
		$query = $this->orders_model->get_order($order_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['order'] = $query->row();
			$query = $this->products_model->all_products();
			$v_data['products'] = $query->result();#
			$v_data['payment_methods'] = $this->orders_model->get_payment_methods();
			
			$data['content'] = $this->load->view('orders/edit_order', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Order does not exist';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add products to an order
	*	@param int $order_id
	*	@param int $product_id
	*	@param int $quantity
	*
	*/
	public function add_product($order_id, $product_id, $quantity, $price)
	{
		if($this->orders_model->add_product($order_id, $product_id, $quantity, $price))
		{
			redirect('edit-order/'.$order_id);
		}
	}
    
	/*
	*
	*	Add products to an order
	*	@param int $order_id
	*	@param int $order_item_id
	*	@param int $quantity
	*
	*/
	public function update_cart($order_id, $order_item_id, $quantity)
	{
		if($this->orders_model->update_cart($order_item_id, $quantity))
		{
			redirect('edit-order/'.$order_id);
		}
	}
    
	/*
	*
	*	Delete an existing order
	*	@param int $order_id
	*
	*/
	public function delete_order($order_id)
	{
		//delete order
		$this->db->delete('orders', array('order_id' => $order_id));
		$this->db->delete('order_item', array('order_item_id' => $order_id));
		redirect('all-orders');
	}
    
	/*
	*
	*	Add products to an order
	*	@param int $order_item_id
	*
	*/
	public function delete_order_item($order_id, $order_item_id)
	{
		if($this->orders_model->delete_order_item($order_item_id))
		{
			redirect('edit-order/'.$order_id);
		}
	}
    
	/*
	*
	*	Complete an order
	*	@param int $order_id
	*
	*/
	public function finish_order($order_id)
	{
		$data = array(
					'order_status'=>2
				);
				
		$this->db->where('order_id = '.$order_id);
		$this->db->update('orders', $data);
		
		redirect('all-orders');
	}
	public function send_order_for_correction($order_id)
	{

    	$data = array(
					'order_approval_status'=>0,
					'order_status_id'=>1
				);
				
		$this->db->where('order_id = '.$order_id);
		$this->db->update('orders', $data);
		
		redirect('inventory/orders');
	}
    public function send_order_for_approval($order_id,$order_status= NULL)
    {
    	if($order_status == NULL)
    	{
    		$order_status = 1;
    	}
    	else
    	{
    		$order_status = $order_status;
    	}
    	
		$this->orders_model->update_order_status($order_id,$order_status);


		redirect('inventory/orders');
    }
	/*
	*
	*	Cancel an order
	*	@param int $order_id
	*
	*/
	public function cancel_order($order_id)
	{
		$data = array(
					'order_status'=>3
				);
				
		$this->db->where('order_id = '.$order_id);
		$this->db->update('orders', $data);
		
		redirect('all-orders');
	}
    
	/*
	*
	*	Deactivate an order
	*	@param int $order_id
	*
	*/
	public function deactivate_order($order_id)
	{
		$data = array(
					'order_status'=>1
				);
				
		$this->db->where('order_id = '.$order_id);
		$this->db->update('orders', $data);
		
		redirect('all-orders');
	}
}
?>