<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sync_tables extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('sync_tables_model');
		$this->load->model('admin/users_model');
		$this->load->model('hr/personnel_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		
		$this->load->model('auth/auth_model');
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
    
	/*
	*
	*	Default action is to show all the sync_tables
	*
	*/
	public function index($order = 'sync_table_name', $order_method = 'ASC') 
	{
		$where = 'sync_table_id > 0';
		$table = 'sync_table';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'cloud/sync-tables/'.$order.'/'.$order_method;
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
		$query = $this->sync_tables_model->get_all_sync_tables($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'sync tables';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('sync_tables/all_sync_tables', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new sync_table
	*
	*/
	public function add_sync_table() 
	{
		//form validation rules
		$this->form_validation->set_rules('branch_code', 'Branch code', 'required|xss_clean');
		$this->form_validation->set_rules('sync_table_name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('table_key_name', 'Table key name', 'required|xss_clean');
		$this->form_validation->set_rules('sync_table_status', 'Sync table Status', 'required|xss_clean');
		$this->form_validation->set_rules('sync_table_cloud_save_function', 'Cloud save function', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->sync_tables_model->add_sync_table())
			{
				$this->session->set_userdata('success_message', 'sync table added successfully');
				redirect('cloud/sync-tables');
			}
		}
		
		$data['title'] = 'Add sync table';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('sync_tables/add_sync_table', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing sync_table
	*	@param int $sync_table_id
	*
	*/
	public function edit_sync_table($sync_table_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('branch_code', 'Branch code', 'required|xss_clean');
		$this->form_validation->set_rules('sync_table_name', 'Name', 'required|xss_clean');
		$this->form_validation->set_rules('table_key_name', 'Table key name', 'required|xss_clean');
		$this->form_validation->set_rules('sync_table_status', 'sync_table Status', 'required|xss_clean');
		$this->form_validation->set_rules('sync_table_cloud_save_function', 'Cloud save function', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update sync_table
			if($this->sync_tables_model->update_sync_table($sync_table_id))
			{
				$this->session->set_userdata('success_message', 'sync table updated successfully');
				redirect('cloud/sync-tables');
			}
		}
		
		//open the add new sync_table
		$data['title'] = 'Edit sync table';
		$v_data['title'] = $data['title'];
		
		//select the sync_table from the database
		$query = $this->sync_tables_model->get_sync_table($sync_table_id);
		
		$v_data['sync_table'] = $query->result();
		
		$data['content'] = $this->load->view('sync_tables/edit_sync_table', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing sync_table
	*	@param int $sync_table_id
	*
	*/
	public function delete_sync_table($sync_table_id)
	{
		$this->sync_tables_model->delete_sync_table($sync_table_id);
		$this->session->set_userdata('success_message', 'sync table has been deleted');
		
		redirect('cloud/sync-tables');
	}
    
	/*
	*
	*	Activate an existing sync_table
	*	@param int $sync_table_id
	*
	*/
	public function activate_sync_table($sync_table_id)
	{
		if($this->sync_tables_model->activate_sync_table($sync_table_id))
		{
			$this->session->set_userdata('success_message', 'sync table activated successfully');
		}
		
		redirect('cloud/sync-tables');
	}
    
	/*
	*
	*	Deactivate an existing sync_table
	*	@param int $sync_table_id
	*
	*/
	public function deactivate_sync_table($sync_table_id)
	{
		$this->sync_tables_model->deactivate_sync_table($sync_table_id);
		$this->session->set_userdata('success_message', 'sync table disabled successfully');
		
		redirect('cloud/sync-tables');
	}
}
?>