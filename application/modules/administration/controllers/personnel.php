<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personnel extends MX_Controller
{	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('reception/reception_model');
		$this->load->model('personnel_model');
		$this->load->model('database');
		
		$this->load->model('auth/auth_model');
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
	
	public function index()
	{
		// this is it
		$where = 'personnel_id > 0';
		$personnel_search = $this->session->userdata('personnel_search');
		
		if(!empty($personnel_search))
		{
			$where .= $personnel_search;
		}
		
		$segment = 4;
		$table = 'personnel';
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/administration/personnel/index';
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
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
		$query = $this->personnel_model->list_all_personnel($table, $where, $config["per_page"], $page);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['search'] = $personnel_search;
		
		$data['title'] = 'Personnel';
		$v_data['title'] = 'Personnel';
		
		$data['content'] = $this->load->view('personnel/personnel_list', $v_data, true);
		
		$data['sidebar'] = 'admin_sidebar';
		
		$this->load->view('auth/template_sidebar', $data);
		// end of it
	}
	
	public function reset_password($personne_id)
	{
		$table = "personnel";
		$key = "personnel_id";
		$key_value = $personne_id;
		$items = array(
        	"personnel_password" => md5(123456)
    	);
		$this->database->update_entry2($table, $key, $key_value, $items); 
		$this->session->set_userdata('success_message', 'Password updated successfully');
		redirect('administration/personnel');
	}	
	
	public function delete_personnel_department($personnel_department_id, $personnel_id = NULL)
	{
		if($this->personnel_model->delete_personnel_department($personnel_department_id))
		{
			$this->session->set_userdata('success_message', 'Role deleted successfully');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Could not delete role. Please try again');
		}
		
		if($personnel_id > 0)
		{
			redirect('administration/personnel/edit_personnel/'.$personnel_id);
		}
		
		else
		{
			redirect('administration/personnel');
		}
	}
	
	/*
	*	Edit personnel
	*
	*/
	public function edit_personnel($personnel_id)
	{
		//form validation rules
		$this->form_validation->set_rules('personnel_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_dob', 'Date of Birth', 'xss_clean');
		$this->form_validation->set_rules('personnel_email', 'Email', 'valid_email|xss_clean');
		$this->form_validation->set_rules('personnel_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('personnel_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'xss_clean');
		$this->form_validation->set_rules('personnel_locality', 'Locality', 'xss_clean');
		$this->form_validation->set_rules('title_id', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_username', 'Username', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_kin_fname', 'Next of Kin First Name', 'xss_clean');
		$this->form_validation->set_rules('personnel_kin_onames', 'Next of Kin Other Names', 'xss_clean');
		$this->form_validation->set_rules('personnel_kin_contact', 'Next of Kin Phone', 'xss_clean');
		$this->form_validation->set_rules('personnel_kin_address', 'Next of Kin Address', 'xss_clean');
		$this->form_validation->set_rules('kin_relationship_id', 'Relationship With Kin', 'xss_clean');
		$this->form_validation->set_rules('job_title_id', 'Job Title', 'xss_clean');
		$this->form_validation->set_rules('staff_id', 'Staff ID', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->personnel_model->edit_personnel($personnel_id))
			{
				$this->session->set_userdata("success_message", "Personnel edited successfully");
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit personnel. Please try again");
			}
		}
		
		$v_data['personnel_id'] = $personnel_id;
		$v_data['relationships'] = $this->reception_model->get_relationship();
		$v_data['religions'] = $this->reception_model->get_religion();
		$v_data['civil_statuses'] = $this->reception_model->get_civil_status();
		$v_data['titles'] = $this->reception_model->get_title();
		$v_data['genders'] = $this->reception_model->get_gender();
		$v_data['personnel'] = $this->personnel_model->get_single_personnel($personnel_id);
		$v_data['job_titles_query'] = $this->personnel_model->get_job_titles();
		$v_data['departments'] = $this->personnel_model->get_departments();
		$v_data['personnel_departments'] = $this->personnel_model->get_personnel_departments($personnel_id);
		$data['content'] = $this->load->view('personnel/edit_personnel', $v_data, true);
		
		$data['title'] = 'Edit Personnel';
		$data['sidebar'] = 'admin_sidebar';
		$this->load->view('auth/template_sidebar', $data);	
	}
	
	/*
	*	Add personnel
	*
	*/
	public function add_personnel()
	{
		//form validation rules
		$this->form_validation->set_rules('personnel_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_dob', 'Date of Birth', 'xss_clean');
		$this->form_validation->set_rules('personnel_email', 'Email', 'valid_email|is_unique[personnel.personnel_email]|xss_clean');
		$this->form_validation->set_rules('personnel_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('personnel_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'xss_clean');
		$this->form_validation->set_rules('personnel_locality', 'Locality', 'xss_clean');
		$this->form_validation->set_rules('title_id', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_username', 'Username', 'required|xss_clean|is_unique[personnel.personnel_username]');
		$this->form_validation->set_rules('personnel_kin_fname', 'Next of Kin First Name', 'xss_clean');
		$this->form_validation->set_rules('personnel_kin_onames', 'Next of Kin Other Names', 'xss_clean');
		$this->form_validation->set_rules('personnel_kin_contact', 'Next of Kin Phone', 'xss_clean');
		$this->form_validation->set_rules('personnel_kin_address', 'Next of Kin Address', 'xss_clean');
		$this->form_validation->set_rules('kin_relationship_id', 'Relationship With Kin', 'xss_clean');
		$this->form_validation->set_rules('job_title_id', 'Job Title', 'xss_clean');
		$this->form_validation->set_rules('staff_id', 'Staff ID', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$personnel_id = $this->personnel_model->add_personnel();
			if($personnel_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Personnel added successfully");
				redirect('administration/personnel/edit_personnel/'.$personnel_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add personnel. Please try again");
			}
		}
		
		$v_data['relationships'] = $this->reception_model->get_relationship();
		$v_data['religions'] = $this->reception_model->get_religion();
		$v_data['civil_statuses'] = $this->reception_model->get_civil_status();
		$v_data['titles'] = $this->reception_model->get_title();
		$v_data['genders'] = $this->reception_model->get_gender();
		$v_data['job_titles_query'] = $this->personnel_model->get_job_titles();
		$data['content'] = $this->load->view('personnel/add_personnel', $v_data, true);
		
		$data['title'] = 'Add Personnel';
		$data['sidebar'] = 'admin_sidebar';
		$this->load->view('auth/template_sidebar', $data);	
	}
	
	public function add_personnel_department($department_id, $personnel_id)
	{
		if($this->personnel_model->add_personnel_department($department_id, $personnel_id))
		{
			$this->session->set_userdata("success_message", "Role added successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not add role. Please try again");
		}
		redirect('administration/personnel/edit_personnel/'.$personnel_id);
	}

	public function deactivate_personnel($personnel_id)
	{
		
		$visit_data = array('authorise'=>1);
		$this->db->where('personnel_id',$personnel_id);
		if($this->db->update('personnel', $visit_data))
		{
				redirect('administration/personnel');
		}
		else
		{
				redirect('administration/personnel');
		}
		
	
		
	}
	public function activate_personnel($personnel_id)
	{
		
		$visit_data = array('authorise'=>0);
		$this->db->where('personnel_id',$personnel_id);
		if($this->db->update('personnel', $visit_data))
		{
				redirect('administration/personnel');
		}
		else
		{
				redirect('administration/personnel');
		}
		
	}
	
}
?>