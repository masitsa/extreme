<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/inventory/controllers/inventory.php";

class Unit extends inventory
{
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Default action is to show all the unit
	*
	*/
	public function index($order = 'unit_name', $order_method = 'ASC') 
	{
		$where = 'unit_id > 0';
		$table = 'unit';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'inventory/units-of-measurement/'.$order.'/'.$order_method;
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
		$query = $this->unit_model->get_all_unit($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'unit';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_unit'] = $this->unit_model->all_unit();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('unit/all_unit', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new unit
	*
	*/
	public function add_unit() 
	{
		//form validation rules
		$this->form_validation->set_rules('unit_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('unit_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('unit_dob', 'Date of Birth', 'xss_clean');
		$this->form_validation->set_rules('unit_email', 'Email', 'valid_email|is_unique[unit.unit_email]|xss_clean');
		$this->form_validation->set_rules('unit_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('unit_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'xss_clean');
		$this->form_validation->set_rules('unit_locality', 'Locality', 'xss_clean');
		$this->form_validation->set_rules('title_id', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'required|xss_clean');
		$this->form_validation->set_rules('unit_number', 'unit number', 'xss_clean');
		$this->form_validation->set_rules('unit_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('unit_post_code', 'Post code', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$unit_id = $this->unit_model->add_unit();
			if($unit_id != FALSE)
			{
				$this->session->set_userdata("success_message", "unit added successfully");
				redirect('inventory/edit-unit/'.$unit_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add unit. Please try again");
			}
		}
		
		$v_data['relationships'] = $this->unit_model->get_relationship();
		$v_data['religions'] = $this->unit_model->get_religion();
		$v_data['civil_statuses'] = $this->unit_model->get_civil_status();
		$v_data['titles'] = $this->unit_model->get_title();
		$v_data['genders'] = $this->unit_model->get_gender();
		$v_data['job_titles_query'] = $this->unit_model->get_job_titles();
		$data['title'] = 'Add unit';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('unit/add_unit', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing unit
	*	@param int $unit_id
	*
	*/
	public function edit_unit($unit_id) 
	{	
		//open the add new unit
		$data['title'] = 'Edit unit';
		$v_data['title'] = $data['title'];
		
		$v_data['unit_id'] = $unit_id;
		$v_data['relationships'] = $this->unit_model->get_relationship();
		$v_data['religions'] = $this->unit_model->get_religion();
		$v_data['civil_statuses'] = $this->unit_model->get_civil_status();
		$v_data['titles'] = $this->unit_model->get_title();
		$v_data['genders'] = $this->unit_model->get_gender();
		$v_data['job_titles_query'] = $this->unit_model->get_job_titles();
		$v_data['unit'] = $this->unit_model->get_unit($unit_id);
		$v_data['emergency_contacts'] = $this->unit_model->get_emergency_contacts($unit_id);
		$v_data['dependants'] = $this->unit_model->get_unit_dependants($unit_id);
		$v_data['jobs'] = $this->unit_model->get_unit_jobs($unit_id);
		$v_data['leave'] = $this->unit_model->get_unit_jobs($unit_id);
		$v_data['roles'] = $this->unit_model->get_unit_roles($unit_id);
		$v_data['leave_types'] = $this->unit_model->get_leave_types();
		$v_data['parent_sections'] = $this->sections_model->all_parent_sections('section_position');
		$data['content'] = $this->load->view('unit/edit_unit', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing unit
	*	@param int $unit_id
	*
	*/
	public function edit_about($unit_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('unit_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('unit_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('unit_dob', 'Date of Birth', 'xss_clean');
		$this->form_validation->set_rules('unit_email', 'Email', 'valid_email|is_unique[unit.unit_email]|xss_clean');
		$this->form_validation->set_rules('unit_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('unit_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'xss_clean');
		$this->form_validation->set_rules('unit_locality', 'Locality', 'xss_clean');
		$this->form_validation->set_rules('title_id', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'required|xss_clean');
		$this->form_validation->set_rules('unit_username', 'Username', 'required|xss_clean|is_unique[unit.unit_username]');
		$this->form_validation->set_rules('unit_kin_fname', 'Next of Kin First Name', 'xss_clean');
		$this->form_validation->set_rules('unit_kin_onames', 'Next of Kin Other Names', 'xss_clean');
		$this->form_validation->set_rules('unit_kin_contact', 'Next of Kin Phone', 'xss_clean');
		$this->form_validation->set_rules('unit_kin_address', 'Next of Kin Address', 'xss_clean');
		$this->form_validation->set_rules('kin_relationship_id', 'Relationship With Kin', 'xss_clean');
		$this->form_validation->set_rules('job_title_id', 'Job Title', 'xss_clean');
		$this->form_validation->set_rules('staff_id', 'Staff ID', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update unit
			if($this->unit_model->update_unit($unit_id))
			{
				$this->session->set_userdata('success_message', 'unit updated successfully');
				redirect('inventory/unit');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update unit. Please try again');
			}
		}
		
		//open the add new unit
		$data['title'] = 'Edit unit';
		$v_data['title'] = $data['title'];
		
		$v_data['unit_id'] = $unit_id;
		$v_data['relationships'] = $this->unit_model->get_relationship();
		$v_data['religions'] = $this->unit_model->get_religion();
		$v_data['civil_statuses'] = $this->unit_model->get_civil_status();
		$v_data['titles'] = $this->unit_model->get_title();
		$v_data['genders'] = $this->unit_model->get_gender();
		$v_data['unit'] = $this->unit_model->get_unit($unit_id);
		$v_data['job_titles_query'] = $this->unit_model->get_job_titles();
		$data['content'] = $this->load->view('unit/edit_unit', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Delete an existing unit
	*	@param int $unit_id
	*
	*/
	public function delete_unit($unit_id)
	{
		if($this->unit_model->delete_unit($unit_id))
		{
			$this->session->set_userdata('success_message', 'unit has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'unit could not deleted');
		}
		redirect('inventory/unit');
	}
    
	/*
	*
	*	Activate an existing unit
	*	@param int $unit_id
	*
	*/
	public function activate_unit($unit_id)
	{
		$this->unit_model->activate_unit($unit_id);
		$this->session->set_userdata('success_message', 'unit activated successfully');
		redirect('inventory/unit');
	}
    
	/*
	*
	*	Deactivate an existing unit
	*	@param int $unit_id
	*
	*/
	public function deactivate_unit($unit_id)
	{
		$this->unit_model->deactivate_unit($unit_id);
		$this->session->set_userdata('success_message', 'unit disabled successfully');
		redirect('inventory/unit');
	}
	
	function add_leave()
	{
		$this->form_validation->set_rules('unit_id', 'unit', 'trim|numeric|required|xss_clean');
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('leave_type_id', 'Leave Type', 'trim|numeric|required|xss_clean');

		if ($this->form_validation->run() == FALSE)//if there is an invalid item
		{
			$this->calender($_SESSION['navigation_id'], $_SESSION['sub_navigation_id']);
		}
		
		else//if the input is valid
		{
			$items = array(
						'unit_id' => $this->input->post("unit_id"),
						'start_date' => $this->input->post("start_date"),
						'end_date' => $this->input->post("end_date"),
						'leave_type_id' => $this->input->post("leave_type_id")
					);
			$result = $this->db->insert("leave_duration", $items);
			
			redirect("administration/calender/".$_SESSION['navigation_id']."/".$_SESSION['sub_navigation_id']);
		}
	}
	
	public function get_section_children($section_id)
	{
		$sub_sections = $this->sections_model->get_sub_sections($section_id);
		
		$children = '';
		
		if($sub_sections->num_rows() > 0)
		{
			foreach($sub_sections->result() as $res)
			{
				$section_id = $res->section_id;
				$section_name = $res->section_name;
				
				$children .= '<option value="'.$section_id.'" >'.$section_name.'</option>';
			}
		}
		
		else
		{
			$children = '<option value="" >--No sub sections--</option>';
		}
		
		echo $children;
	}
}
?>