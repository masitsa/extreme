<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class clients extends MX_Controller {
	var $clients_path;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('admin/users_model');
		$this->load->model('clients_model');
		$this->load->model('admin/file_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('site/site_model');
		$this->load->model('administration/personnel_model');
		//path to image directory
	}
    
	/*
	*
	*	Default action is to show all the clients
	*
	*/

	public function index() 
	{
		//$where = 'created_by IN (0, '.$this->session->userdata('vendor_id').')';
		$where = 'branch_code = "'.$this->session->userdata('branch_code').'"';
		$table = 'client';

		$clients_search = $this->session->userdata('clients_search');
		
		if(!empty($clients_search))
		{
			$where .= $clients_search;
		}
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'clients';
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
		$query = $this->clients_model->get_all_clients($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$v_data['title'] = 'All clients';
			//$v_data['child_clients'] = $this->clients_model->all_child_clients();
			$data['content'] = $this->load->view('clients/all_clients', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'inventory-setup/add-clients" class="btn btn-success pull-right">Add clients</a><br>There are no clients';
		}
		$data['title'] = 'All clients';
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new clients
	*
	*/
	public function add_clients() 
	{
		//form validation rules
		$this->form_validation->set_rules('clients_name', 'clients Name', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//upload product's gallery images
			
			if($this->clients_model->add_clients())
			{
				$this->session->set_userdata('success_message', 'clients added successfully');
				redirect('inventory-setup/clients');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add clients. Please try again');
			}
		}
		
		//open the add new clients
		$data['title'] = 'Add New clients';
		$v_data['title'] = 'Add New clients';
		$data['content'] = $this->load->view('clients/add_clients', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing clients
	*	@param int $clients_id
	*
	*/
	public function edit_clients($clients_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('clients_name', 'clients Name', 'required|xss_clean');
		$this->form_validation->set_rules('clients_status', 'clients Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			
			if($this->clients_model->update_clients($clients_id))
			{
				$this->session->set_userdata('success_message', 'clients updated successfully');
				redirect('inventory-setup/clients');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update clients. Please try again');
			}
		}
		
		//open the add new client
		$data['title'] = 'Edit Client';
		$v_data['title'] = 'Edit clients';
		
		//select the clients from the database
		$query = $this->clients_model->get_clients($clients_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['clients'] = $query->result();
			
			$data['content'] = $this->load->view('clients/edit_clients', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'clients does not exist';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing clients
	*	@param int $clients_id
	*
	*/
	public function delete_clients($clients_id)
	{
		//delete clients image
		$query = $this->clients_model->get_clients($clients_id);
		
		/*if ($query->num_rows() > 0)
		{
			$result = $query->result();
			$image = $result[0]->clients_image_name;
			
			$this->load->model('file_model');
			//delete image
			$this->file_model->delete_file($this->clients_path."/images/".$image, $this->clients_path);
			//delete thumbnail
			$this->file_model->delete_file($this->clients_path."/thumbs/".$image, $this->clients_path);
		}*/
		$this->clients_model->delete_clients($clients_id);
		$this->session->set_userdata('success_message', 'clients has been deleted');
		redirect('inventory-setup/clients');
	}
    
	/*
	*
	*	Activate an existing clients
	*	@param int $clients_id
	*
	*/
	public function activate_clients($clients_id)
	{
		$this->clients_model->activate_clients($clients_id);
		$this->session->set_userdata('success_message', 'clients activated successfully');
		redirect('inventory-setup/clients');
	}
    
	/*
	*
	*	Deactivate an existing clients
	*	@param int $clients_id
	*
	*/
	public function deactivate_clients($clients_id)
	{
		$this->clients_model->deactivate_clients($clients_id);
		$this->session->set_userdata('success_message', 'clients disabled successfully');
		redirect('inventory-setup/clients');
	}
	public function search_clients()
	{

		$clients_name = $this->input->post('clients_name');


		if(!empty($clients_name))
		{
			$clients_name = ' AND clients.clients_name LIKE \'%'.mysql_real_escape_string($clients_name).'%\' ';
		}
		
		
		$search = $clients_name;
		$this->session->set_userdata('clients_search', $search);
		
		$this->index();
		
	}
	public function close_clients_search()
	{
		$this->session->unset_userdata('clients_search');
		redirect('inventory-setup/clients');
	}
}
?>