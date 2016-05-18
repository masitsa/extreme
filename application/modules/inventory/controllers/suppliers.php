<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Suppliers extends MX_Controller {
	
	var $csv_path;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('auth/auth_model');
		$this->load->model('admin/users_model');
		$this->load->model('suppliers_model');
		$this->load->model('admin/file_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('site/site_model');
		$this->load->model('administration/personnel_model');
		
		//path to image directory
		$this->csv_path = realpath(APPPATH . '../assets/csv');
		
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
		
	}
    
	/*
	*
	*	Default action is to show all the suppliers
	*
	*/

	public function index($order = 'supplier_name', $order_method = 'ASC') 
	{
		//$where = 'created_by IN (0, '.$this->session->userdata('vendor_id').')';
		$where = 'branch_code = "'.$this->session->userdata('branch_code').'" AND deleted = 0';
		$table = 'supplier';

		$supplier_search = $this->session->userdata('supplier_search');
		
		if(!empty($supplier_search))
		{
			$where .= $supplier_search;
		}
		$segment = 5;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'suppliers'.$order.'/'.$order_method;
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
        $data["links"] = $this->pagination->create_links();
		$query = $this->suppliers_model->get_all_suppliers($table, $where, $config["per_page"], $page, $order,$order_method);
		
		if ($query->num_rows() > 0)
		{
				//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['title'] = 'All Suppliers';
			//$v_data['child_suppliers'] = $this->suppliers_model->all_child_suppliers();
			$data['content'] = $this->load->view('suppliers/all_suppliers', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'inventory-setup/add-supplier" class="btn btn-success pull-right">Add supplier</a><br>There are no suppliers';
		}
		$data['title'] = 'All Suppliers';
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new supplier
	*
	*/
	public function add_supplier() 
	{
		//form validation rules
		$this->form_validation->set_rules('supplier_name', 'supplier Name', 'required|xss_clean');
		$this->form_validation->set_rules('supplier_email', 'Supplier Email', 'valid_email','xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			
			if($this->suppliers_model->add_supplier())
			{
				$this->session->set_userdata('success_message', 'supplier added successfully');
				redirect('inventory-setup/suppliers');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add supplier. Please try again');
			}
		}
		
		//open the add new supplier
		$data['title'] = 'Add New Supplier';
		$v_data['title'] = 'Add New Supplier';
		$data['content'] = $this->load->view('suppliers/add_supplier', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing supplier
	*	@param int $supplier_id
	*
	*/
	public function edit_supplier($supplier_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('supplier_name', 'supplier Name', 'required|xss_clean');
		$this->form_validation->set_rules('supplier_status', 'supplier Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			
			if($this->suppliers_model->update_supplier($supplier_id))
			{
				$this->session->set_userdata('success_message', 'supplier updated successfully');
				redirect('inventory-setup/suppliers');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update supplier. Please try again');
			}
		}
		
		//open the add new supplier
		$data['title'] = 'Edit Supplier';
		$v_data['title'] = 'Edit Supplier';
		
		//select the supplier from the database
		$query = $this->suppliers_model->get_supplier($supplier_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['supplier'] = $query->result();
			
			$data['content'] = $this->load->view('suppliers/edit_supplier', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'supplier does not exist';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing supplier
	*	@param int $supplier_id
	*
	*/
	public function delete_supplier($supplier_id)
	{
		//delete supplier image
		$query = $this->suppliers_model->get_supplier($supplier_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->supplier_image_name;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->suppliers_path."/images/".$image, $this->suppliers_path);
			//delete thumbnail
			$this->file_model->delete_file($this->suppliers_path."/thumbs/".$image, $this->suppliers_path);
		}
		$this->suppliers_model->delete_supplier($supplier_id);
		$this->session->set_userdata('success_message', 'supplier has been deleted');
		redirect('inventory-setup/suppliers');
	}
    
	/*
	*
	*	Activate an existing supplier
	*	@param int $supplier_id
	*
	*/
	public function activate_supplier($supplier_id)
	{
		$this->suppliers_model->activate_supplier($supplier_id);
		$this->session->set_userdata('success_message', 'supplier activated successfully');
		redirect('inventory-setup/suppliers');
	}
    
	/*
	*
	*	Deactivate an existing supplier
	*	@param int $supplier_id
	*
	*/
	public function deactivate_supplier($supplier_id)
	{
		$this->suppliers_model->deactivate_supplier($supplier_id);
		$this->session->set_userdata('success_message', 'supplier disabled successfully');
		redirect('inventory-setup/suppliers');
	}
	public function search_suppliers()
	{

		$supplier_name = $this->input->post('suppliers_name');


		if(!empty($supplier_name))
		{
			$supplier_name = ' AND supplier.supplier_name LIKE \'%'.mysql_real_escape_string($supplier_name).'%\' ';
		}
		
		
		$search = $supplier_name;
		$this->session->set_userdata('supplier_search', $search);
		
		$this->index();
		
	}
	public function close_suppliers_search()
	{
		$this->session->unset_userdata('supplier_search');
		redirect('inventory-setup/suppliers');
	}
	function import_suppliers()
	{
		//open the add new product
		$v_data['title'] = 'Import Suppliers';
		$data['title'] = 'Import suppliers';
		$data['content'] = $this->load->view('suppliers/import_suppliers', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	function import_template()
	{
		//export products template in excel 
		 $this->suppliers_model->import_template();
	}
	function do_suppliers_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import clients from excel 
				$response = $this->suppliers_model->import_csv_suppliers($this->csv_path);
				
				if($response == FALSE)
				{
				}
				
				else
				{
					if($response['check'])
					{
						$v_data['import_response'] = $response['response'];
					}
					
					else
					{
						$v_data['import_response_error'] = $response['response'];
					}
				}
			}
			
			else
			{
				$v_data['import_response_error'] = 'Please select a file to import.';
			}
		}
		
		else
		{
			$v_data['import_response_error'] = 'Please select a file to import.';
		}
		
		//open the add new item
		$v_data['title'] = 'Import suppliers';
		$data['title'] = 'Import suppliers';
		$data['content'] = $this->load->view('suppliers/import_suppliers', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
}
?>