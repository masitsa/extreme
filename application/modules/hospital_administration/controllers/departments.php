<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hospital_administration/controllers/hospital_administration.php";

class Departments extends Hospital_administration 
{
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Default action is to show all the departments
	*
	*/
	public function index($order = 'department_name', $order_method = 'ASC') 
	{
		$where = 'departments.branch_code = \''.$this->session->userdata('branch_code').'\'';
		$table = 'departments';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'hospital-administration/departments/'.$order.'/'.$order_method;
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
		$query = $this->departments_model->get_all_departments($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Departments';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('departments/all_departments', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new department
	*
	*/
	public function add_department() 
	{
		//form validation rules
		$this->form_validation->set_rules('department_name', 'Department Name', 'required|xss_clean');
		$this->form_validation->set_rules('department_status', 'Department Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->departments_model->add_department())
			{
				$this->session->set_userdata('success_message', 'Department added successfully');
				redirect('hospital-administration/departments');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add department. Please try again');
			}
		}
		
		$data['title'] = 'Add department';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('departments/add_department', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing department
	*	@param int $department_id
	*
	*/
	public function edit_department($department_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('department_name', 'Department Name', 'required|xss_clean');
		$this->form_validation->set_rules('department_status', 'Department Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update department
			if($this->departments_model->update_department($department_id))
			{
				$this->session->set_userdata('success_message', 'Department updated successfully');
				redirect('hospital-administration/departments');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update department. Please try again');
			}
		}
		
		//open the add new department
		$data['title'] = 'Edit department';
		$v_data['title'] = $data['title'];
		
		//select the department from the database
		$query = $this->departments_model->get_department($department_id);
		
		$v_data['department'] = $query->result();
		
		$data['content'] = $this->load->view('departments/edit_department', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing department
	*	@param int $department_id
	*
	*/
	public function delete_department($department_id)
	{
		$this->departments_model->delete_department($department_id);
		$this->session->set_userdata('success_message', 'Department has been deleted');
		
		redirect('hospital-administration/departments');
	}
    
	/*
	*
	*	Activate an existing department
	*	@param int $department_id
	*
	*/
	public function activate_department($department_id)
	{
		$this->departments_model->activate_department($department_id);
		$this->session->set_userdata('success_message', 'Department activated successfully');
		
		redirect('hospital-administration/departments');
	}
    
	/*
	*
	*	Deactivate an existing department
	*	@param int $department_id
	*
	*/
	public function deactivate_department($department_id)
	{
		$this->departments_model->deactivate_department($department_id);
		$this->session->set_userdata('success_message', 'Department disabled successfully');
		
		redirect('hospital-administration/departments');
	}
}
?>