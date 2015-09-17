<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hospital_administration/controllers/hospital_administration.php";

class Visit_types extends Hospital_administration 
{
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Default action is to show all the visit_types
	*
	*/
	public function index($order = 'visit_type_name', $order_method = 'ASC') 
	{
		$where = 'visit_type_id > 0';
		$table = 'visit_type';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'hospital-administration/visit_types/'.$order.'/'.$order_method;
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
		$query = $this->visit_types_model->get_all_visit_types($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Visit types';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_visit_types'] = $this->visit_types_model->all_visit_types();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('visit_types/all_visit_types', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new visit_type
	*
	*/
	public function add_visit_type() 
	{
		//form validation rules
		$this->form_validation->set_rules('visit_type_name', 'Visit type Name', 'required|xss_clean');
		$this->form_validation->set_rules('visit_type_status', 'Visit type Status', 'required|xss_clean');
		$this->form_validation->set_rules('insurance_company_id', 'Insurance company', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->visit_types_model->add_visit_type())
			{
				$this->session->set_userdata('success_message', 'Visit type added successfully');
				redirect('hospital-administration/visit-types');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add visit type. Please try again');
			}
		}
		$v_data['insurance_companies'] = $this->companies_model->all_companies();
		$data['title'] = 'Add visit type';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('visit_types/add_visit_type', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing visit_type
	*	@param int $visit_type_id
	*
	*/
	public function edit_visit_type($visit_type_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('visit_type_name', 'Visit type Name', 'required|xss_clean');
		$this->form_validation->set_rules('visit_type_status', 'Visit type Status', 'required|xss_clean');
		$this->form_validation->set_rules('insurance_company_id', 'Insurance company', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update visit_type
			if($this->visit_types_model->update_visit_type($visit_type_id))
			{
				$this->session->set_userdata('success_message', 'Visit type updated successfully');
				redirect('hospital-administration/visit-types');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update visit type. Please try again');
			}
		}
		
		$v_data['insurance_companies'] = $this->companies_model->all_companies();
		//open the add new visit_type
		$data['title'] = 'Edit visit type';
		$v_data['title'] = $data['title'];
		
		//select the visit_type from the database
		$query = $this->visit_types_model->get_visit_type($visit_type_id);
		
		$v_data['visit_type'] = $query->result();
		
		$data['content'] = $this->load->view('visit_types/edit_visit_type', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing visit_type
	*	@param int $visit_type_id
	*
	*/
	public function delete_visit_type($visit_type_id)
	{
		$this->visit_types_model->delete_visit_type($visit_type_id);
		$this->session->set_userdata('success_message', 'Visit type has been deleted');
		
		redirect('hospital-administration/visit-types');
	}
    
	/*
	*
	*	Activate an existing visit_type
	*	@param int $visit_type_id
	*
	*/
	public function activate_visit_type($visit_type_id)
	{
		$this->visit_types_model->activate_visit_type($visit_type_id);
		$this->session->set_userdata('success_message', 'Visit type activated successfully');
		
		redirect('hospital-administration/visit-types');
	}
    
	/*
	*
	*	Deactivate an existing visit_type
	*	@param int $visit_type_id
	*
	*/
	public function deactivate_visit_type($visit_type_id)
	{
		$this->visit_types_model->deactivate_visit_type($visit_type_id);
		$this->session->set_userdata('success_message', 'Visit type disabled successfully');
		
		redirect('hospital-administration/visit-types');
	}
}
?>