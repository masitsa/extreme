<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/microfinance/controllers/microfinance.php";

class Group extends microfinance 
{
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Default action is to show all the group
	*
	*/
	public function index($order = 'group_name', $order_method = 'ASC') 
	{
		$where = 'group_id > 0';
		$table = 'group';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'microfinance/'.$order.'/'.$order_method;
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
		
		$config['cur_tag_open'] = '<li class="active">';
		$config['cur_tag_close'] = '</li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $data["links"] = $this->pagination->create_links();
		$query = $this->group_model->get_all_group($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Groups';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('group/all_group', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new group
	*
	*/
	public function add_group() 
	{
		//form validation rules
		$this->form_validation->set_rules('group_name', 'Group name', 'required|xss_clean');
		$this->form_validation->set_rules('group_contact_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('group_contact_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('group_email', 'Email', 'valid_email|is_unique[group.group_email]|xss_clean');
		$this->form_validation->set_rules('group_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('group_phone2', 'Phone 2', 'xss_clean');
		$this->form_validation->set_rules('group_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('group_locality', 'Locality', 'xss_clean');
		$this->form_validation->set_rules('group_number', 'Group number', 'xss_clean');
		$this->form_validation->set_rules('group_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('group_post_code', 'Post code', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$group_id = $this->group_model->add_group();
			if($group_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Group added successfully");
				redirect('microfinance/edit-group/'.$group_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add group. Please try again");
			}
		}
		
		$data['title'] = 'Add group';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('group/add_group', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing group
	*	@param int $group_id
	*
	*/
	public function edit_group($group_id) 
	{	
		//open the add new group
		$data['title'] = 'Edit group';
		$v_data['title'] = $data['title'];
		
		$v_data['group_id'] = $group_id;
		$v_data['relationships'] = $this->individual_model->get_relationship();
		$v_data['religions'] = $this->individual_model->get_religion();
		$v_data['civil_statuses'] = $this->individual_model->get_civil_status();
		$v_data['titles'] = $this->individual_model->get_title();
		$v_data['genders'] = $this->individual_model->get_gender();
		$v_data['job_titles_query'] = $this->individual_model->get_job_titles();
		
		$v_data['group_id'] = $group_id;
		$v_data['group'] = $this->group_model->get_group($group_id);
		$v_data['group_members'] = $this->group_model->get_group_members($group_id);
		$data['content'] = $this->load->view('group/edit_group', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing group
	*	@param int $group_id
	*
	*/
	public function edit_about($group_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('group_name', 'Group name', 'required|xss_clean');
		$this->form_validation->set_rules('group_contact_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('group_contact_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('group_email', 'Email', 'valid_email|exists[group.group_email]|xss_clean');
		$this->form_validation->set_rules('group_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('group_phone2', 'Phone 2', 'xss_clean');
		$this->form_validation->set_rules('group_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('group_locality', 'Locality', 'xss_clean');
		$this->form_validation->set_rules('group_number', 'Group number', 'xss_clean');
		$this->form_validation->set_rules('group_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('group_post_code', 'Post code', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update group
			if($this->group_model->edit_group($group_id))
			{
				$this->session->set_userdata('success_message', 'Group\'s general details editted successfully');
				redirect('microfinance/edit-group/'.$group_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not edit group\'s general details. Please try again');
			}
		}
		
		$this->edit_group($group_id);
	}
    
	/*
	*
	*	Add a new member
	*
	*/
	public function add_member($group_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('individual_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('individual_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('individual_dob', 'Date of Birth', 'xss_clean');
		$this->form_validation->set_rules('individual_email', 'Email', 'valid_email|is_unique[individual.individual_email]|xss_clean');
		$this->form_validation->set_rules('individual_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('individual_phone2', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('individual_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'xss_clean');
		$this->form_validation->set_rules('individual_locality', 'Locality', 'xss_clean');
		$this->form_validation->set_rules('title_id', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'required|xss_clean');
		$this->form_validation->set_rules('individual_number', 'Individual number', 'xss_clean');
		$this->form_validation->set_rules('individual_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('individual_post_code', 'Post code', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$individual_id = $this->group_model->add_member($group_id);
			if($individual_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Member added successfully");
				redirect('microfinance/edit-group/'.$group_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message", "Could not add member. Please try again");
			}
		}
		
		$this->edit_group($group_id);
	}
    
	/*
	*
	*	Delete an existing group
	*	@param int $group_id
	*
	*/
	public function delete_group($group_id)
	{
		if($this->group_model->delete_group($group_id))
		{
			$this->session->set_userdata('success_message', 'Group has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Group could not deleted');
		}
		redirect('microfinance/group');
	}
    
	/*
	*
	*	Activate an existing group
	*	@param int $group_id
	*
	*/
	public function activate_group($group_id)
	{
		$this->group_model->activate_group($group_id);
		$this->session->set_userdata('success_message', 'Group activated successfully');
		redirect('microfinance/group');
	}
    
	/*
	*
	*	Deactivate an existing group
	*	@param int $group_id
	*
	*/
	public function deactivate_group($group_id)
	{
		$this->group_model->deactivate_group($group_id);
		$this->session->set_userdata('success_message', 'Group disabled successfully');
		redirect('microfinance/group');
	}
    
	/*
	*
	*	Add a new group
	*
	*/
	public function add_group_plan($group_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('savings_plan_id', 'Savings plan', 'required|xss_clean');
		$this->form_validation->set_rules('group_savings_status', 'Activate plan', 'required|xss_clean');
		$this->form_validation->set_rules('group_savings_opening_balance', 'Opening balance', 'xss_clean');
		$this->form_validation->set_rules('start_date', 'Start date', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->group_model->add_group_plan($group_id))
			{
				$this->session->set_userdata("success_message", "Plan added successfully");
				redirect('microfinance/edit-group/'.$group_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message", "Could not add plan. Please try again");
			}
		}
		
		$this->edit_group($group_id);
	}
    
	/*
	*
	*	Activate an existing group
	*	@param int $group_id
	*
	*/
	public function activate_group_plan($group_savings_id, $group_id)
	{
		if($this->group_model->activate_group_plan($group_savings_id))
		{
			$this->session->set_userdata('success_message', 'Plan activated successfully');
		}
		
		else
		{
			$this->session->set_userdata("error_message", "Could not activate plan. Please try again");
		}
		
		redirect('microfinance/edit-group/'.$group_id);
	}
    
	/*
	*
	*	Dectivate an existing group
	*	@param int $group_savings_id
	*
	*/
	public function deactivate_group_plan($group_savings_id, $group_id)
	{
		if($this->group_model->deactivate_group_plan($group_savings_id))
		{
			$this->session->set_userdata('success_message', 'Plan deactivated successfully');
		}
		
		else
		{
			$this->session->set_userdata("error_message", "Could not deactivate plan. Please try again");
		}
		
		redirect('microfinance/edit-group/'.$group_id);
	}
}
?>