<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_management  extends MX_Controller
{	
	var $csv_path;
	function __construct()
	{
		parent:: __construct();
		$this->load->model('inventory_management_model');
		$this->load->model('products_model');
		$this->load->model('reception/reception_model');
		$this->load->model('pharmacy/pharmacy_model');
		$this->load->model('nurse/nurse_model');
		$this->load->model('accounts/accounts_model');
		$this->load->model('admin/users_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('reception/database');
		$this->load->model('administration/personnel_model');
		$this->load->model('inventory/categories_model');
		$this->load->model('inventory/stores_model');
		

		$this->csv_path = realpath(APPPATH . '../assets/csv');
		
		$this->load->model('auth/auth_model');
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}

	public function index()
	{
		$store_priviledges = $this->inventory_management_model->get_assigned_stores();
		$v_data['store_priviledges'] =  $store_priviledges;
		$addition = '';
		if($store_priviledges->num_rows() > 0)
		{
			$count = 0;
			$number_rows = $store_priviledges->num_rows();

			foreach ($store_priviledges->result() as $key) {
				# code...
				$store_parent = $key->store_parent;
				$store_id = $key->store_id;
				$count++;

				if($count == 1 AND $number_rows > $count)
				{
					$delimeter = '(';
				}
				else
				{
					$delimeter = '';
				}
				if($number_rows > 1 AND $number_rows == $count)
				{
					$back_delimeter = ')';
					
				}
				else
				{
					$back_delimeter = '';
				}

				if($count > 0 AND $number_rows != $count)
				{
					$or_addition = 'OR';
				}
				else
				{
					$or_addition = '';
				}
				if($store_parent > 0)
				{
					
					$addition .= ' '.$delimeter.' store_product.store_id = '.$store_id.' '.$or_addition.' '.$back_delimeter.'';
				}
				else
				{
					$addition .= ''.$delimeter.'store.store_id = '.$store_id.'';
				}
			}
			
			if($store_parent > 0)
			{
				$v_data['type'] = 1;
				$table = ',store_product';
				$constant = ' AND store_product.store_id = store.store_id AND product.product_id = store_product.product_id AND ';
			}
			else
			{

				$v_data['type'] = 2;
				$table = '';
				$constant  = ' AND product.store_id = store.store_id AND ';
			}
		}
		else
		{
			$v_data['type'] = 3;
			$table = '';
			$constant = '';
			$addition = '';
		}

		$where = 'product.category_id = category.category_id '.$constant.' '.$addition.' ';
		$table = 'product, category, store'.$table;
		
		$product_inventory_search = $this->session->userdata('product_inventory_search');
		
		if(!empty($product_inventory_search))
		{
			$where .= $product_inventory_search;
		}
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'inventory/products';
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
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
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->products_model->get_all_products($table, $where, $config["per_page"], $page);
		$v_data['all_generics'] = $this->inventory_management_model->get_all_generics();
		$v_data['all_brands'] = $this->inventory_management_model->get_all_brands();
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['title'] = 'All Products';
		
			$v_data['all_categories'] = $this->categories_model->all_categories();

			$data['content'] = $this->load->view('products/all_products', $v_data, true);
		}
		
		else
		{
			$search = $this->session->userdata('product_search');
			$search_result = '';
			if(!empty($search))
			{
				$search_result = '<a href="'.site_url().'inventory/close-product-search" class="btn btn-success">Close Search</a>';
			}
			$v_data['title'] = 'All Products';
			$v_data['query'] = $query;
			$v_data['all_categories'] = $this->categories_model->all_categories();
			$data['content'] = $this->load->view('products/all_products', $v_data, true);
		}
		$data['title'] = 'All Products';
		
		$this->load->view('admin/templates/general_page', $data);


	}

	/*
	*	Add product
	*
	*/
	public function add_product($product_id = NULL)
	{
		//form validation rules
		$this->form_validation->set_rules('product_name', 'product Name', 'required|xss_clean');
		$this->form_validation->set_rules('products_pack_size', 'Pack Size', 'numeric|xss_clean');
		$this->form_validation->set_rules('quantity', 'Opening Quantity', 'numeric|xss_clean');
		$this->form_validation->set_rules('products_unitprice', 'Unit Price', 'numeric|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->inventory_management_model->save_product())
			{
				$this->session->userdata('success_message', 'Product has been added successfully');
				redirect('inventory/products');
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to add product. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Add product';
		$v_data['product_id'] = $product_id;
		$v_data['title'] = 'Add product';
		$v_data['all_stores'] = $this->stores_model->all_stores();
		$v_data['all_categories'] = $this->categories_model->all_categories();
		$v_data['drug_types'] = $this->pharmacy_model->get_drug_forms();
		$v_data['drug_brands'] = $this->pharmacy_model->get_drug_brands();
		$v_data['drug_classes'] = $this->pharmacy_model->get_drug_classes();
		$v_data['drug_generics'] = $this->pharmacy_model->get_drug_generics();
		$v_data['drug_dose_units'] = $this->pharmacy_model->get_drug_dose_units();
		$v_data['admin_routes'] = $this->pharmacy_model->get_admin_route();
		$v_data['consumption'] = $this->pharmacy_model->get_consumption();
		$data['content'] = $this->load->view('products/add_product', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	/*
	*	Edit product
	*
	*/
	public function edit_product($products_id, $module = NULL)
	{
		//form validation rules
		$this->form_validation->set_rules('product_name', 'product Name', 'required|xss_clean');
		$this->form_validation->set_rules('product_pack_size', 'Pack Size', 'numeric|xss_clean');
		$this->form_validation->set_rules('quantity', 'Opening Quantity', 'numeric|xss_clean');
		$this->form_validation->set_rules('product_unitprice', 'Unit Price', 'numeric|xss_clean');
		$this->form_validation->set_rules('batch_no', 'Batch Number', 'numeric|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->inventory_management_model->edit_product($products_id))
			{
				$this->session->userdata('success_message', 'product has been editted successfully');
				
				//back to pharmacy
				if($module == 'a')
				{
					redirect('pharmacy-setup/inventory');
				}
				else
				{
					redirect('inventory/products');
				}
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to edit product. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Edit product';
		$v_data['title'] = 'Edit product';
		$v_data['module'] = $module;
		$product_details = $this->inventory_management_model->get_product_details($products_id);
		
		$v_data['product'] = $product_details;
		$v_data['products_id'] = $products_id;
		$v_data['all_stores'] = $this->stores_model->all_stores();
		$v_data['all_categories'] = $this->categories_model->all_categories();
		$v_data['drug_types'] = $this->pharmacy_model->get_drug_forms();
		$v_data['drug_brands'] = $this->pharmacy_model->get_drug_brands();
		$v_data['drug_classes'] = $this->pharmacy_model->get_drug_classes();
		$v_data['drug_generics'] = $this->pharmacy_model->get_drug_generics();
		$v_data['drug_dose_units'] = $this->pharmacy_model->get_drug_dose_units();
		$v_data['admin_routes'] = $this->pharmacy_model->get_admin_route();
		$v_data['consumption'] = $this->pharmacy_model->get_consumption();

		$data['content'] = $this->load->view('products/edit_product', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function manage_product($product_id)
	{
		$store_priviledges = $this->inventory_management_model->get_assigned_stores();
		$v_data['store_priviledges'] =  $store_priviledges;

		$data['content'] = $this->load->view('store_management', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);	
	}
	public function product_purchases($product_id)
	{
		//form validation rules
		$this->form_validation->set_rules('purchase_quantity', 'Purchase Quantity', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('purchase_pack_size', 'Pack Size', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->inventory_management_model->purchase_product($product_id))
			{
				$this->session->userdata('success_message', 'product has been purchased successfully');
				redirect('inventory/product-purchases/'.$product_id);
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to purchase product. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Buy product';
		$product_details = $this->inventory_management_model->get_product_details($product_id);
		

		$v_data['title'] = 'Buy '.$product_details[0]->product_name;
		$v_data['product_id'] = $product_id;
		$data['content'] = $this->load->view('products/buy_product', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function edit_product_purchase($purchase_id, $product_id)
	{
		//form validation rules
		$this->form_validation->set_rules('purchase_quantity', 'Purchase Quantity', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('purchase_pack_size', 'Pack Size', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('expiry_date', 'Expiry Date', 'xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->inventory_management_model->edit_product_purchase($purchase_id))
			{
				$this->session->userdata('success_message', 'product has been purchased successfully');
				redirect('pharmacy/product_purchases/'.$product_id);
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to purchase product. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Edit Purchase';
		$data['sidebar'] = 'pharmacy_sidebar';
		$product_details = $this->inventory_management_model->get_product_details($product_id);
		$purchase_details = $this->inventory_management_model->get_purchase_details($purchase_id);

		$v_data['title'] = 'Edit '.$product_details[0]->product_name.' Purchase';
		$v_data['product_id'] = $product_id;
		$v_data['purchase_details'] = $purchase_details->row();
		$data['content'] = $this->load->view('products/edit_product_purchase', $v_data, true);

		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function all_product_purchases($product_id)
	{
		$segment = 4;
		$order = 'product_purchase.purchase_date';
		$where = 'product_purchase.product_id = '.$product_id;
		
		$product_search = $this->session->userdata('product_purchases_search');
		
		if(!empty($product_search))
		{
			$where .= $product_search;
		}
		
		$table = 'product_purchase';
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'inventory/product-purchases/'.$product_id;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
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
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->inventory_management_model->get_product_purchases($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['product_id'] = $product_id;
		
		$data['title'] = 'product List';
		$v_data['title'] = 'product List';
		$data['sidebar'] = 'pharmacy_sidebar';
		$product_details = $this->inventory_management_model->get_product_details($product_id);
		
		
			$v_data['title'] = $product_details[0]->product_name.' Purchases';
			$data['content'] = $this->load->view('products/product_purchases', $v_data, true);
		
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function deduct_product($product_id)
	{
		//form validation rules
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('product_deduction_quantity', 'deduct Quantity', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('product_deduction_pack_size', 'Pack Size', 'required|numeric|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->inventory_management_model->deduct_product($product_id))
			{
				$this->session->userdata('success_message', 'product has been deducted successfully');
				redirect('pharmacy/product_deductions/'.$product_id);
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to deduct product. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Deduct product';
		$data['sidebar'] = 'pharmacy_sidebar';
		$product_details = $this->inventory_management_model->get_product_details($product_id);
		
		if($product_details->num_rows() > 0)
		{
			$row = $product_details->row();
			$v_data['title'] = 'Deduct '.$row->product_name;
			$v_data['product_id'] = $product_id;
			$v_data['container_types'] = $this->inventory_management_model->get_container_types();
			$data['content'] = $this->load->view('deduct_product', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find product';
		}
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function edit_product_deduction($product_deduction_id, $product_id)
	{
		//form validation rules
		$this->form_validation->set_rules('container_type_id', 'Container Type', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('product_deduction_quantity', 'deduct Quantity', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('product_deduction_pack_size', 'Pack Size', 'required|numeric|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{

			if($this->inventory_management_model->edit_product_deduction($product_deduction_id))
			{
				$this->session->userdata('success_message', 'product has been deductd successfully');
				redirect('pharmacy/product_deductions/'.$product_id);
			}
			
			else
			{
				$this->session->userdata('error_message', 'Unable to deduct product. Please try again');
			}
		}
		
		else
		{
			$v_data['validation_errors'] = validation_errors();
		}
		
		//load the interface
		$data['title'] = 'Edit Deduction';
		$data['sidebar'] = 'pharmacy_sidebar';
		$product_details = $this->inventory_management_model->get_product_details($product_id);
		$deduction_details = $this->inventory_management_model->get_deduction_details($product_deduction_id);
		
		if($product_details->num_rows() > 0)
		{
			$row = $product_details->row();
			$v_data['title'] = 'Edit '.$row->product_name.' Purchase';
			$v_data['product_id'] = $product_id;
			$v_data['container_types'] = $this->inventory_management_model->get_container_types();
			$v_data['deduction_details'] = $deduction_details->row();
			$data['content'] = $this->load->view('edit_product_deduction', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Could not find deduction details';
		}
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function all_product_deductions()
	{
		$segment = 4;

		$store_priviledges = $this->inventory_management_model->get_assigned_stores();
		$v_data['store_priviledges'] =  $store_priviledges;
		$addition = '';
		if($store_priviledges->num_rows() > 0)
		{
			$count = 0;
			 $number_rows = $store_priviledges->num_rows();

			foreach ($store_priviledges->result() as $key) {
				# code...
				$store_parent = $key->store_parent;
				$store_id = $key->store_id;
				$count++;

				if($count == 1 AND $number_rows > $count)
				{
					$delimeter = '(';
				}
				else
				{
					$delimeter = '';
				}
				if($number_rows > 1 AND $number_rows == $count)
				{
					$back_delimeter = ')';
					
				}
				else
				{
					$back_delimeter = '';
				}

				if($count > 0 AND $number_rows != $count)
				{
					$or_addition = 'OR';
				}
				else
				{
					$or_addition = '';
				}
				if($store_parent > 0)
				{
					
					$addition .= ' '.$delimeter.' store_product.store_id = '.$store_id.' '.$or_addition.' '.$back_delimeter.'';
				}
				else
				{
					$addition .= ''.$delimeter.' product.store_id = '.$store_id.' '.$or_addition.' '.$back_delimeter.'';
				}
			}
			if($store_parent > 0)
			{
				$v_data['type'] = 1;
				$table = ',store_product';
				$constant = ' AND store_product.store_id = store.store_id AND product.product_id = store_product.product_id AND ';
			}
			else
			{

				$v_data['type'] = 2;
				$table = '';
				$constant  = ' AND product_deductions.store_id = store.store_id AND ';
			}


		}
		else
		{
			$table = '';
			$constant = '';
			$addition = '';
		}

		$where = 'product_deductions.product_id = product.product_id AND product.category_id = category.category_id '.$constant.' '.$addition.' ';
		$table = 'product,product_deductions, category, store'.$table;

		$order = 'product_deductions.product_deductions_date';
		// $where = 'store_product.store_id = product_deductions.store_id AND store_product.store_id = store.store_id AND product_deductions.product_id = product.product_id AND store_product.product_id = product.product_id ';
		
		$product_search = $this->session->userdata('product_deductions_search');
		
		if(!empty($product_search))
		{
			$where .= $product_search;
		}
		
		// $table = 'product_deductions,store,store_product,product';
		
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'inventory/product-deductions';
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
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
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->inventory_management_model->get_product_deductions($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		// $v_data['product_id'] = $product_id;
		
		$data['title'] = 'Requested Orders';
		$v_data['title'] = 'Requested Orders';
		// $product_details = $this->inventory_management_model->get_product_details($product_id);
		
		// $v_data['title'] = $product_details[0]->product_name.' Deductions';
		$data['content'] = $this->load->view('products/product_deductions', $v_data, true);
		
		
		$this->load->view('admin/templates/general_page', $data);
				
    }
    public function manage_store()
    {
    	$data['title'] = 'Store Management';
		$store_priviledges = $this->inventory_management_model->get_assigned_stores();
		$v_data['store_priviledges'] =  $store_priviledges;
		
		$v_data['title'] =  'Store Management';
		
		$data['content'] = $this->load->view('store_management', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);		
    }
    public function store_requests()
    {
    	$store_priviledges = $this->inventory_management_model->get_assigned_stores();
		$v_data['store_priviledges'] =  $store_priviledges;

		$v_data['title'] =  'Store Management';
		
		$this->load->view('store_requests', $v_data);

    }

   public function close_inventory_search()
	{
		$this->session->unset_userdata('product_inventory_search');
		$this->index();
	}

	public function search_inventory_product()
	{
		$product_name = $this->input->post('product_name');
		$brand_id = $this->input->post('brand_id');
		$generic_id = $this->input->post('generic_id');
		
		if(!empty($product_name))
		{
			$product_name = ' AND product.product_name LIKE \'%'.$product_name.'%\' ';
		}
		else
		{
			$product_name = '';
		}
		if(!empty($generic_id))
		{
			$generic_id = ' AND product.generic_id = '.$generic_id;
		}
		else
		{
			$generic_id = '';
		}
		
		if(!empty($brand_id))
		{
			$brand_id = ' AND product.brand_id = '.$brand_id;
		}
		else
		{
			$brand_id = '';
		}
	
		
		
		$search = $product_name.$generic_id.$brand_id;
		$this->session->set_userdata('product_inventory_search', $search);
		
		$this->index();
	}	

	public function make_order($store_id)
	{
		//check patient visit type
		// get parent store
		$parent_id = $this->inventory_management_model->get_parent_store($store_id);
		
		
		$order = 'product_name';
		
		$where = 'category.category_id = product.category_id AND product.store_id ='.$parent_id;
		$order_search = $this->session->userdata('make_order_search');
		
		if(!empty($order_search))
		{
			$where .= $order_search;
		}
		
		$table = 'product,category';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'inventory/make-order/'.$store_id;
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = 5;
		$config['per_page'] = 15;
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
		
		$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->inventory_management_model->get_parent_products($table, $where, $config["per_page"], $page, $order);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Product List';
		$v_data['title'] = 'Product List';
		
		$v_data['store_id'] = $store_id;
		$data['content'] = $this->load->view('product_list', $v_data, true);
		
		$data['title'] = 'Product List';
		$this->load->view('admin/templates/no_sidebar', $data);	
	}

	public function save_order_products($product_id, $store_id){
		$data = array('store_id' => $store_id, 'product_id'=> $product_id,'date_requested'=>date('Y-m-d H:i:s'),'search_date'=>date('Y-m-d'),'requested_by'=>$this->session->userdata('personnel_id'));

		$this->db->insert('product_deductions',$data);
		//$this->load->view('product_tests', $data);
	}
	
	 public function now_store_requests($store_id)
    {

		$v_data['store_id'] =  $store_id;
		
		$this->load->view('product_selected_list', $v_data);

    }
    public function update_order_products($product_deductions_id, $quantity)
    {
    	$data = array('quantity_requested' => $quantity,'requested_by'=>$this->session->userdata('personnel_id'));
    	$this->db->where('product_deductions_id ='.$product_deductions_id);
		$this->db->update('product_deductions',$data);
			$data['result']="success";
		echo json_encode($data);
    }
    public function award_order_products($product_deductions_id,$quantity)
    {
    	$data = array('quantity_given' => $quantity,'given_by'=>$this->session->userdata('personnel_id'),'product_deductions_status'=>1);
    	$this->db->where('product_deductions_id ='.$product_deductions_id);
		$this->db->update('product_deductions',$data);
		$data['result']="You've successfully awarded the store ".$quantity."";
		echo json_encode($data);
    }
    public function receive_order_products($product_deductions_id,$quantity,$product_id,$store_id)
    {
    	// update the products table
    	
		$this->db->select('quantity');
		$this->db->where("product_id = ".$product_id."");
		$query = $this->db->get('product');

		if($query->num_rows())
		{
			$data_query = $query->result();

			$old_quantity = $data_query[0]->quantity;
		}

		$new_quantity = $old_quantity - $quantity;


		$data = array('quantity' => $new_quantity);
    	$this->db->where('product_id  ='.$product_id);
		$this->db->update('product',$data);

		// dinished updating the products table 


		$this->db->select('quantity');
		$this->db->where("product_id = ".$product_id." AND store_id = ".$store_id."");
		$store_query = $this->db->get('store_product');
		$store_quantity = 0;
		
		if($store_query->num_rows())
		{
			$data_store_query = $store_query->result();

			$store_quantity = $data_store_query[0]->quantity;
		}
		
		$new_store_quantity = $store_quantity + $quantity;
		$where = 'product_id  ='.$product_id.' AND store_id = '.$store_id;
		
		//check if product exists in store product
		$this->db->where($where);
		$query = $this->db->get('store_product');
		
		//if exists
		if($query->num_rows() > 0)
		{
			$data2 = array('quantity' => $new_store_quantity);
			$this->db->where($where);
			$this->db->update('store_product',$data2);
		}
		else
		{
			$data2 = array('quantity' => $new_store_quantity, 'product_id' => $product_id, 'store_id' => $store_id);
			$this->db->insert('store_product',$data2);
		}

    	// update child store 
    	$data3 = array('quantity_received' => $quantity,'received_by'=>$this->session->userdata('personnel_id'),'product_deductions_status'=>2);
    	$this->db->where('product_deductions_id ='.$product_deductions_id);
		$this->db->update('product_deductions',$data3);

		$data['result']="You've successfully received ".$quantity."";
		

		echo json_encode($data);
    }
    
}