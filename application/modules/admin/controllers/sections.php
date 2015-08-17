<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Sections extends admin 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
	}
    
	/*
	*
	*	Default action is to show all the sections
	*
	*/
	public function index($order = 'section_name', $order_method = 'ASC') 
	{
		$where = 'section_id > 0';
		$table = 'section';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'administration/sections/'.$order.'/'.$order_method;
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
		$query = $this->sections_model->get_all_sections($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Sections';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_sections'] = $this->sections_model->all_sections();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('sections/all_sections', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new section
	*
	*/
	public function add_section() 
	{
		//form validation rules
		$this->form_validation->set_rules('section_name', 'Section Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('section_status', 'Status', 'required|xss_clean');
		$this->form_validation->set_rules('section_parent', 'Parent', 'required|xss_clean');
		$this->form_validation->set_rules('section_icon', 'Status', 'trim|xss_clean');
		$this->form_validation->set_rules('section_position', 'Position', 'trim|numeric|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{	
			if($this->sections_model->add_section())
			{
				$this->session->set_userdata('success_message', 'Section added successfully');
				redirect('administration/sections');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add section. Please try again');
			}
		}
		
		//open the add new section
		
		$data['title'] = 'Add section';
		$v_data['title'] = $data['title'];
		$v_data['all_sections'] = $this->sections_model->all_parent_sections();
		$data['content'] = $this->load->view('sections/add_section', $v_data, true);
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing section
	*	@param int $section_id
	*
	*/
	public function edit_section($section_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('section_name', 'Section Name', 'trim|required|xss_clean');
		$this->form_validation->set_rules('section_status', 'Status', 'required|xss_clean');
		$this->form_validation->set_rules('section_parent', 'Parent', 'required|xss_clean');
		$this->form_validation->set_rules('section_icon', 'Status', 'trim|xss_clean');
		$this->form_validation->set_rules('section_position', 'Position', 'trim|numeric|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update section
			if($this->sections_model->update_section($section_id))
			{
				$this->session->set_userdata('success_message', 'Section updated successfully');
				redirect('administration/sections');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update section. Please try again');
			}
		}
		
		//open the add new section
		$data['title'] = 'Edit section';
		$v_data['title'] = $data['title'];
		
		//select the section from the database
		$query = $this->sections_model->get_section($section_id);
		
		if ($query->num_rows() > 0)
		{
			$v_data['section'] = $query->result();
			$v_data['all_sections'] = $this->sections_model->all_parent_sections();
			
			$data['content'] = $this->load->view('sections/edit_section', $v_data, true);
		}
		
		else
		{
			$data['content'] = 'Section does not exist';
		}
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing section
	*	@param int $section_id
	*
	*/
	public function delete_section($section_id)
	{
		if($this->sections_model->delete_section($section_id))
		{
			$this->session->set_userdata('success_message', 'Section has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Section could not deleted');
		}
		redirect('administration/sections');
	}
    
	/*
	*
	*	Activate an existing section
	*	@param int $section_id
	*
	*/
	public function activate_section($section_id)
	{
		$this->sections_model->activate_section($section_id);
		$this->session->set_userdata('success_message', 'Section activated successfully');
		redirect('administration/sections');
	}
    
	/*
	*
	*	Deactivate an existing section
	*	@param int $section_id
	*
	*/
	public function deactivate_section($section_id)
	{
		$this->sections_model->deactivate_section($section_id);
		$this->session->set_userdata('success_message', 'Section disabled successfully');
		redirect('administration/sections');
	}
}
?>