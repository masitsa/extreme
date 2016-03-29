<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class requests extends MX_Controller
{ 
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/users_model');
		$this->load->model('inventory_management/items_model');
		$this->load->model('inventory_management/products_model');
		$this->load->model('requests_model');
		$this->load->model('clients_model');
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
	*	Default action is to show all the requests
	*
	*/
	public function index() 
	{
		// get my approval roles
		$this->db->where('personnel_id ='.$this->session->userdata('personnel_id'));
		$query = $this->db->get('personnel_approval');
		$v_data['personnel_approval_query']=$query;
		
		//retrieve requests, clients
		$where='requests.request_id = request_level_status.request_id AND client.client_id = requests.client_id AND inventory_level_status.inventory_level_status_id = request_level_status.inventory_level_status_id AND request_level_status.request_level_status_status = 1';

		$table = 'requests, request_level_status, client, inventory_level_status';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'inventory/requests';
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = 3;
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->requests_model->get_all_requests($table, $where, $config["per_page"], $page);
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		//$v_data['request_status_query'] = $this->requests_model->get_request_status();
		// $v_data['level_status'] = $this->requests_model->request_level_status();
		$v_data['title'] = "All requests";
		$data['content'] = $this->load->view('requests/all_requests', $v_data, true);
		
		$data['title'] = 'All requests';
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new request
	*
	*/
	public function add_request() 
	{
		//form validation rules
		$this->form_validation->set_rules('request_instructions', 'request Instructions', 'required|xss_clean');
		$this->form_validation->set_rules('client_id', 'Client name', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			$request_id = $this->requests_model->add_request();
			//update request
			if($request_id > 0)
			{
				redirect('inventory/requests/'.$request_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update request. Please try again');
			}
		}
		
		//open the add new request
		$data['title'] = 'Add request';
		$v_data['title'] = 'Add request';
//		$v_data['request_status_query'] = $this->requests_model->get_request_status();
		$v_data['clients_query'] = $this->clients_model->all_clients();
		$data['content'] = $this->load->view('requests/add_request', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    

    public function add_request_item($request_id,$request_number)
    {

		$this->form_validation->set_rules('item_id', 'Item', 'required|xss_clean');
		$this->form_validation->set_rules('quantity', 'Quantity', 'required|xss_clean');
		 
		if ($this->form_validation->run())
		{
			if($this->requests_model->add_request_item($request_id))
			{
				$this->session->set_userdata('success_message', 'request created successfully');
			}	
			else
			{
				$this->session->set_userdata('error_message', 'Something went wrong, please try again');
			}
		}
		else
		{
			$this->session->set_userdata('error_message', 'Something went wrong, please try again');
		}
		$v_data['title'] = 'Add request Item to '.$request_number;
		//$v_data['request_status_query'] = $this->requests_model->get_request_status();
		$v_data['items_query'] = $this->items_model->all_items();
		$v_data['request_number'] = $request_number;
		$v_data['request_id'] = $request_id;
		$v_data['request_item_query'] = $this->requests_model->get_request_items($request_id);
		$v_data['request_suppliers'] = $this->requests_model->get_request_suppliers($request_id);

		$v_data['suppliers_query'] = $this->suppliers_model->all_suppliers();
		$data['content'] = $this->load->view('requests/request_item', $v_data, true);

		$this->load->view('admin/templates/general_page', $data);
    }


    public function print_lpo_new($supplier_request_id)
	{
		$data = array('supplier_request_id'=>$supplier_request_id);

		$data['contacts'] = $this->site_model->get_contacts();
		
		$this->load->view('requests/views/lpo_print', $data);
		
	}
	public function print_rfq_new($request_id,$supplier_id,$request_number)
	{
		$data = array('request_id'=>$request_id,'supplier_id'=>$supplier_id,'request_number'=>$request_number);

		$data['contacts'] = $this->site_model->get_contacts();
		
		$this->load->view('requests/views/request_for_quotation', $data);
		
	}

    public function update_request_item($request_id,$request_number,$request_item_id)
    {
    	$this->form_validation->set_rules('quantity', 'Quantity', 'numeric|required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
	    	if($this->requests_model->update_request_item($request_id,$request_item_id))
			{
				$this->session->set_userdata('success_message', 'request Item updated successfully');
			}	
			else
			{
				$this->session->set_userdata('error_message', 'request Item was not updated');
			}
		}
		else
		{
			$this->session->set_userdata('success_message', 'Sorry, Please enter a number in the field');
		}
		redirect('inventory/add-request-item/'.$request_id.'/'.$request_number.'');

    }
    public function update_supplier_prices($request_id,$request_number,$request_item_id)
    {
    	$this->form_validation->set_rules('unit_price', 'Unit Price', 'numeric|required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
	    	if($this->requests_model->update_request_item_price($request_id,$request_item_id))
			{
				$this->session->set_userdata('success_message', 'request Item updated successfully');
			}	
			else
			{
				$this->session->set_userdata('error_message', 'request Item was not updated');
			}
		}
		else
		{
			$this->session->set_userdata('success_message', 'Sorry, Please enter a number in the field');
		}
		redirect('inventory/add-request-item/'.$request_id.'/'.$request_number.'');

    }
    public function submit_supplier($request_id,$request_number)
    {
    	$this->form_validation->set_rules('supplier_id', 'Quantity', 'numeric|required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->requests_model->add_supplier_to_request($request_id))
			{
				$this->session->set_userdata('success_message', 'request Item updated successfully');
			}
			else
			{
				$this->session->set_userdata('success_message', 'request Item updated successfully');
			}
		}
		else
		{
			$this->session->set_userdata('success_message', 'request Item updated successfully');
		}
		redirect('inventory/add-request-item/'.$request_id.'/'.$request_number.'');
    }
	/*
	*
	*	Edit an existing request
	*	@param int $request_id
	*
	*/
	public function edit_request($request_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('request_instructions', 'request Instructions', 'required|xss_clean');
		$this->form_validation->set_rules('user_id', 'Customer', 'required|xss_clean');
		$this->form_validation->set_rules('payment_method', 'Payment Method', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update request
			if($this->requests_model->update_request($request_id))
			{
				$this->session->set_userdata('success_message', 'request updated successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update request. Please try again');
			}
		}
		
		//open the add new request
		$data['title'] = 'Edit request';
		
		//select the request from the database
		$query = $this->requests_model->get_request($request_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['request'] = $query->row();
			$query = $this->products_model->all_products();
			$v_data['products'] = $query->result();#
			$v_data['payment_methods'] = $this->requests_model->get_payment_methods();
			
			$data['content'] = $this->load->view('requests/edit_request', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'request does not exist';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	public function send_request_for_correction($request_id)
	{

    	$data = array(
					'request_approval_status'=>0,
					'request_status_id'=>1
				);
				
		$this->db->where('request_id = '.$order_id);
		$this->db->update('requests', $data);
		
		redirect('inventory/requests');
	}

	
	/*
	*
	*	Add products to an request
	*	@param int $request_id
	*	@param int $product_id
	*	@param int $quantity
	*
	*/
	public function add_product($request_id, $product_id, $quantity, $price)
	{
		if($this->requests_model->add_product($request_id, $product_id, $quantity, $price))
		{
			redirect('edit-request/'.$request_id);
		}
	}
    
	/*
	*
	*	Add products to an request
	*	@param int $request_id
	*	@param int $request_item_id
	*	@param int $quantity
	*
	*/
	public function update_cart($request_id, $request_item_id, $quantity)
	{
		if($this->requests_model->update_cart($request_item_id, $quantity))
		{
			redirect('edit-request/'.$request_id);
		}
	}
    
	/*
	*
	*	Delete an existing request
	*	@param int $request_id
	*
	*/
	public function delete_request($request_id)
	{
		//delete request
		$this->db->delete('requests', array('request_id' => $request_id));
		$this->db->delete('request_item', array('request_item_id' => $request_id));
		redirect('all-requests');
	}
    
	/*
	*
	*	Add products to an request
	*	@param int $request_item_id
	*
	*/
	public function delete_request_item($request_item_id, $request_id, $request_number)
	{
		if($this->requests_model->delete_request_item($request_item_id))
		{
			redirect('inventory/add-request-item/'.$request_id.'/'.$request_number);
		}
	}
    
	/*
	*
	*	Complete an request
	*	@param int $request_id
	*
	*/
	public function finish_request($request_id)
	{
		$data = array(
					'request_approval_status'=>1
				);
				
		$this->db->where('request_id = '.$request_id);
		$this->db->update('requests', $data);
		
		redirect('all-requests');
	}

    public function send_request_for_approval($request_id,$request_status= NULL)
    {
    	if($request_status == NULL)
    	{
    		$request_status = 1;
    	}
    	else
    	{
    		$request_status = $request_status;
    	}
    	
		$this->requests_model->update_request_status($request_id,$request_status);


		redirect('inventory/requests');
    }
	/*
	*
	*	Cancel an request
	*	@param int $request_id
	*
	*/
	public function cancel_request($request_id)
	{
		$data = array(
					'request_status'=>3
				);
				
		$this->db->where('request_id = '.$request_id);
		$this->db->update('requests', $data);
		
		redirect('all-requests');
	}
    
	/*
	*
	*	Deactivate an request
	*	@param int $request_id
	*
	*/
	public function deactivate_request($request_id)
	{
		$data = array(
					'request_status'=>1
				);
				
		$this->db->where('request_id = '.$request_id);
		$this->db->update('requests', $data);
		
		redirect('all-requests');
	}
}
?>