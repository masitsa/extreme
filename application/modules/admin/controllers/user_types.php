<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class User_types extends admin {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('user_types_model');
	}
    
	/*
	*
	*	Default action is to show all the user_types
	*
	*/
	public function index() 
	{
		$where = 'user_type.user_type_status  = user_status.user_status_id';
		$table = 'user_type, user_status';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = base_url().'all-user_types';
		$config['total_rows'] = $this->users_model->count_items($table, $where);
		$config['uri_segment'] = 2;
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
		
		$page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->user_types_model->get_all_user_types($table, $where, $config["per_page"], $page);
		
		if ($query->num_rows() > 0)
		{
			$v_data['query'] = $query;
			$v_data['page'] = $page;
			$data['content'] = $this->load->view('user_types/all_user_types', $v_data, true);
		}
		
		else
		{
			$data['content'] = '<a href="'.site_url().'add-user_type" class="btn btn-success pull-right">Add User Type</a>There are no user type';
		}
		$data['title'] = 'All User Type';
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Add a new user_type
	*
	*/
	public function add_user_type() 
	{
		//form validation rules
		$this->form_validation->set_rules('user_type_cost', 'Monthly Cost', 'required|xss_clean');
		$this->form_validation->set_rules('user_type_name', 'User_type Name', 'required|xss_clean');
		$this->form_validation->set_rules('user_type_status', 'User_type Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{	
			if($this->user_types_model->add_user_type())
			{
				$this->session->set_userdata('success_message', 'User Type added successfully');
				redirect('all-user_types');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add user type. Please try again');
			}
		}
		
		//open the add new user_type
		$data['title'] = 'Add New User Type';
		$data['content'] = $this->load->view('user_types/add_user_type', '', true);
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Edit an existing user_type
	*	@param int $user_type_id
	*
	*/
	public function edit_user_type($user_type_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('user_type_cost', 'Monthly Cost', 'required|xss_clean');
		$this->form_validation->set_rules('user_type_name', 'User_type Name', 'required|xss_clean');
		$this->form_validation->set_rules('user_type_status', 'User_type Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update user_type
			if($this->user_types_model->update_user_type($user_type_id))
			{
				$this->session->set_userdata('success_message', 'User type updated successfully');
				redirect('all-user_types');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update user type. Please try again');
			}
		}
		
		//open the add new user_type
		$data['title'] = 'Edit User Type';
		
		//select the user_type from the database
		$query = $this->user_types_model->get_user_type($user_type_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['user_type'] = $query->result();
			
			$data['content'] = $this->load->view('user_types/edit_user_type', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'User type does not exist';
		}
		
		$this->load->view('templates/general_admin', $data);
	}
    
	/*
	*
	*	Delete an existing user_type
	*	@param int $user_type_id
	*
	*/
	public function delete_user_type($user_type_id)
	{
		$this->user_types_model->delete_user_type($user_type_id);
		$this->session->set_userdata('success_message', 'User type has been deleted');
		redirect('all-user_types');
	}
    
	/*
	*
	*	Activate an existing user_type
	*	@param int $user_type_id
	*
	*/
	public function activate_user_type($user_type_id)
	{
		$this->user_types_model->activate_user_type($user_type_id);
		$this->session->set_userdata('success_message', 'User type activated successfully');
		redirect('all-user_types');
	}
    
	/*
	*
	*	Deactivate an existing user_type
	*	@param int $user_type_id
	*
	*/
	public function deactivate_user_type($user_type_id)
	{
		$this->user_types_model->deactivate_user_type($user_type_id);
		$this->session->set_userdata('success_message', 'User type disabled successfully');
		redirect('all-user_types');
	}
}
?>