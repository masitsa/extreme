<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Stores extends MX_Controller 
{
	var $stores_path;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/users_model');
		$this->load->model('stores_model');
		$this->load->model('admin/file_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('site/site_model');
		$this->load->model('administration/personnel_model');
		//path to image directory
	}
    
	/*
	*
	*	Default action is to show all the stores
	*
	*/

	public function index() 
	{
		//$where = 'created_by IN (0, '.$this->session->userdata('vendor_id').')';
		$where = 'branch_code = "'.$this->session->userdata('branch_code').'"';
		$table = 'store';

		$store_search = $this->session->userdata('store_search');
		
		if(!empty($store_search))
		{
			$where .= $store_search;
		}
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'inventory-stores';
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
		$query = $this->stores_model->get_all_stores($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['title'] = 'All stores';
			//$v_data['child_stores'] = $this->stores_model->all_child_stores();
			$data['content'] = $this->load->view('stores/all_stores', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'inventory-stores/add-store class="btn btn-success pull-right">Add store</a>There are no stores';
		}
		$data['title'] = 'All stores';
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new store
	*
	*/
	public function add_store() 
	{
		//form validation rules
		$this->form_validation->set_rules('store_name', 'store Name', 'required|xss_clean');
		$this->form_validation->set_rules('store_parent', 'store Parent', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			
			if($this->stores_model->add_store())
			{
				$this->session->set_userdata('success_message', 'store added successfully');
				redirect('inventory-setup/inventory-stores');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add store. Please try again');
			}
		}
		
		//open the add new store
		$data['title'] = 'Add New store';
		$v_data['title'] = 'Add New store';
		$v_data['all_stores'] = $this->stores_model->all_parent_stores();
		$data['content'] = $this->load->view('stores/add_store', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing store
	*	@param int $store_id
	*
	*/
	public function edit_store($store_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('store_name', 'store Name', 'required|xss_clean');
		$this->form_validation->set_rules('store_status', 'store Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			
			if($this->stores_model->update_store($store_id))
			{
				$this->session->set_userdata('success_message', 'store updated successfully');
				redirect('inventory-setup/inventory-stores');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update store. Please try again');
			}
		}
		
		//open the add new store
		$data['title'] = 'Edit store';
		$v_data['title'] = 'Edit store';
		
		//select the store from the database
		$query = $this->stores_model->get_store($store_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['store'] = $query->result();
			$v_data['all_stores'] = $this->stores_model->all_parent_stores();
			
			$data['content'] = $this->load->view('stores/edit_store', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'store does not exist';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing store
	*	@param int $store_id
	*
	*/
	public function delete_store($store_id)
	{
		//delete store image
		$query = $this->stores_model->get_store($store_id);
		
		if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->store_image_name;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->stores_path."/images/".$image, $this->stores_path);
			//delete thumbnail
			$this->file_model->delete_file($this->stores_path."/thumbs/".$image, $this->stores_path);
		}
		$this->stores_model->delete_store($store_id);
		$this->session->set_userdata('success_message', 'store has been deleted');
		redirect('inventory-setup/inventory-stores');
	}
    
	/*
	*
	*	Activate an existing store
	*	@param int $store_id
	*
	*/
	public function activate_store($store_id)
	{
		$this->stores_model->activate_store($store_id);
		$this->session->set_userdata('success_message', 'store activated successfully');
		redirect('inventory-setup/inventory-stores');
	}
    
	/*
	*
	*	Deactivate an existing store
	*	@param int $store_id
	*
	*/
	public function deactivate_store($store_id)
	{
		$this->stores_model->deactivate_store($store_id);
		$this->session->set_userdata('success_message', 'store disabled successfully');
		redirect('inventory-setup/inventory-stores');
	}
	public function search_stores()
	{

		$store_name = $this->input->post('store_name');


		if(!empty($store_name))
		{
			$store_name = ' AND store.store_name LIKE \'%'.mysql_real_escape_string($store_name).'%\' ';
		}
		
		
		$search = $store_name;
		$this->session->set_userdata('store_search', $search);
		
		$this->index();
		
	}
	public function close_stores_search()
	{
		$this->session->unset_userdata('store_search');
		redirect('inventory-setup/inventory-stores');
	}
}
?>