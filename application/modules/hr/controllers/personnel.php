<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hr/controllers/hr.php";

class Personnel extends hr 
{
	function __construct()
	{
		parent:: __construct();
	}
    
	/*
	*
	*	Default action is to show all the personnel
	*
	*/
	public function index($order = 'branch_id', $order_method = 'ASC') 
	{
		$where = 'personnel.personnel_type_id = personnel_type.personnel_type_id';
		$table = 'personnel, personnel_type';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'human-resource/personnel/'.$order.'/'.$order_method;
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
		$query = $this->personnel_model->get_all_personnel($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Personnel';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['branches'] = $this->branches_model->all_branches();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('personnel/all_personnel', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new personnel
	*
	*/
	public function add_personnel() 
	{
		//form validation rules
		$this->form_validation->set_rules('branch_id', 'Branch', 'xss_clean');
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
		$this->form_validation->set_rules('personnel_number', 'Personnel number', 'xss_clean');
		$this->form_validation->set_rules('personnel_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('personnel_post_code', 'Post code', 'xss_clean');
		$this->form_validation->set_rules('personnel_type_id', 'Personnel_type', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$personnel_id = $this->personnel_model->add_personnel();
			if($personnel_id > 0)
			{
				$this->session->set_userdata("success_message", "Personnel added successfully");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add personnel. Please try again ".$personnel_id);
			}
		}
		
		$v_data['branches'] = $this->branches_model->all_branches();
		$v_data['relationships'] = $this->personnel_model->get_relationship();
		$v_data['religions'] = $this->personnel_model->get_religion();
		$v_data['civil_statuses'] = $this->personnel_model->get_civil_status();
		$v_data['titles'] = $this->personnel_model->get_title();
		$v_data['genders'] = $this->personnel_model->get_gender();
		$v_data['job_titles_query'] = $this->personnel_model->get_job_titles();
		$v_data['personnel_types'] = $this->personnel_model->get_personnel_types();
		$data['title'] = 'Add personnel';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('personnel/add_personnel', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}

	/*
	*
	*	Edit an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function edit_personnel($personnel_id) 
	{
		//open the add new personnel
		$data['title'] = 'Edit ';
		$v_data['title'] = $data['title'];
		
		$v_data['personnel_id'] = $personnel_id;
		$v_data['branches'] = $this->branches_model->all_branches();
		$v_data['relationships'] = $this->personnel_model->get_relationship();
		$v_data['religions'] = $this->personnel_model->get_religion();
		$v_data['civil_statuses'] = $this->personnel_model->get_civil_status();
		$v_data['titles'] = $this->personnel_model->get_title();
		$v_data['genders'] = $this->personnel_model->get_gender();
		$v_data['job_titles_query'] = $this->personnel_model->get_job_titles();
		$v_data['personnel'] = $this->personnel_model->get_personnel($personnel_id);
		$v_data['emergency_contacts'] = $this->personnel_model->get_emergency_contacts($personnel_id);
		$v_data['dependants'] = $this->personnel_model->get_personnel_dependants($personnel_id);
		$v_data['jobs'] = $this->personnel_model->get_personnel_jobs($personnel_id);
		$v_data['leave'] = $this->personnel_model->get_personnel_leave($personnel_id);
		$v_data['roles'] = $this->personnel_model->get_personnel_roles($personnel_id);
		$v_data['leave_types'] = $this->personnel_model->get_leave_types();
		$v_data['departments'] = $this->personnel_model->get_departments();
		$v_data['personnel_types'] = $this->personnel_model->get_personnel_types();
		$v_data['parent_sections'] = $this->sections_model->all_parent_sections('section_position');
		$data['content'] = $this->load->view('personnel/edit_personnel', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}

	public function add_emergency_contact($personnel_id)
    {
    	$this->form_validation->set_rules('personnel_emergency_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_emergency_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('relationship_id', 'Date of Birth', 'xss_clean');
		$this->form_validation->set_rules('title_id', 'Title', 'required|xss_clean');
		
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			
			if($this->personnel_model->add_emergency_contact($personnel_id))
			{
				$this->session->set_userdata("success_message", "Personnel emergency contact added successfully");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add personnel emergency contact. Please try again");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
		}
		else
		{
			$this->session->set_userdata("error_message","Could not add personnel emergency contact. Please try again");
			redirect('human-resource/edit-personnel/'.$personnel_id);

		}
		
    }
	
	public function update_personnel_roles($personnel_id)
	{
		$this->form_validation->set_rules('section_id', 'Post code', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->personnel_model->update_personnel_roles($personnel_id))
			{
				$this->session->set_userdata("success_message", "Personnel roles successfully updated");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not update personnel roles. Please try again");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
		}
		else
		{
			$this->session->set_userdata("error_message","Could not update personnel roles. Please try again");
			redirect('human-resource/edit-personnel/'.$personnel_id);	
		}
	}
    
    public function update_personnel_about_details($personnel_id)
    {
		$this->form_validation->set_rules('branch_id', 'Branch', 'xss_clean');
    	$this->form_validation->set_rules('personnel_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_dob', 'Date of Birth', 'xss_clean');
		// $this->form_validation->set_rules('personnel_email', 'Email', 'valid_email|is_unique[personnel.personnel_email]|xss_clean');
		$this->form_validation->set_rules('personnel_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('personnel_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('civil_status_id', 'Civil Status', 'xss_clean');
		$this->form_validation->set_rules('personnel_locality', 'Locality', 'xss_clean');
		$this->form_validation->set_rules('title_id', 'Title', 'required|xss_clean');
		$this->form_validation->set_rules('gender_id', 'Gender', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_number', 'Personnel number', 'xss_clean');
		$this->form_validation->set_rules('personnel_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('personnel_post_code', 'Post code', 'xss_clean');
		$this->form_validation->set_rules('personnel_account_number', 'Account number', 'xss_clean');
		$this->form_validation->set_rules('personnel_nssf_number', 'NSSF number', 'xss_clean');
		$this->form_validation->set_rules('personnel_nhif_number', 'NHIF number', 'xss_clean');
		$this->form_validation->set_rules('personnel_kra_pin', 'KRA pin', 'xss_clean');
		$this->form_validation->set_rules('personnel_national_id_number', 'ID number', 'xss_clean');
		$this->form_validation->set_rules('personnel_type_id', 'Personnel_type', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			
			if($this->personnel_model->edit_personnel($personnel_id))
			{
				$this->session->set_userdata("success_message", "Personnel updated successfully updated");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add personnel. Please try again");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
		}
		else
		{
			$this->session->set_userdata("error_message","Could not add personnel. Please try again");
			redirect('human-resource/edit-personnel/'.$personnel_id);
		}
    }
	/*
	*
	*	Edit an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function edit_about($personnel_id) 
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
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update personnel
			if($this->personnel_model->update_personnel($personnel_id))
			{
				$this->session->set_userdata('success_message', 'Personnel updated successfully');
				redirect('human-resource/personnel');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update personnel. Please try again');
			}
		}
		
		//open the add new personnel
		$data['title'] = 'Edit personnel';
		$v_data['title'] = $data['title'];
		
		$v_data['personnel_id'] = $personnel_id;
		$v_data['relationships'] = $this->personnel_model->get_relationship();
		$v_data['religions'] = $this->personnel_model->get_religion();
		$v_data['civil_statuses'] = $this->personnel_model->get_civil_status();
		$v_data['titles'] = $this->personnel_model->get_title();
		$v_data['genders'] = $this->personnel_model->get_gender();
		$v_data['personnel'] = $this->personnel_model->get_personnel($personnel_id);
		$v_data['job_titles_query'] = $this->personnel_model->get_job_titles();
		$data['content'] = $this->load->view('personnel/edit_personnel', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}

	public function reset_password($personnel_id)
	{
		
    	$data = array(
			"personnel_password" => md5(123456)
		);
		
		$this->db->where('personnel_id', $personnel_id);
		if($this->db->update('personnel', $data))
		{
			$this->session->set_userdata('success_message', 'Password updated successfully');
			redirect('human-resource/edit-personnel/'.$personnel_id);
		}
		else{
			$this->session->set_userdata('success_message', 'Password updated successfully');
			redirect('human-resource/edit-personnel/'.$personnel_id);
		}
		
	}
	public function update_personnel_account_details($personnel_id)
	{
		
		$this->form_validation->set_rules('personnel_username', 'Personnel Username', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_account_status', 'Login status', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			
			if($this->personnel_model->update_personnel_account_details($personnel_id))
			{
				$this->session->set_userdata("success_message", "Account details has been updated successfully");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not update personnel account details. Please try again");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
		}
		else
		{
			$this->session->set_userdata("error_message",validation_errors());
			redirect('human-resource/edit-personnel/'.$personnel_id);
		}
		
	}
    
	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function delete_personnel($personnel_id)
	{
		if($this->personnel_model->delete_personnel($personnel_id))
		{
			$this->session->set_userdata('success_message', 'Personnel has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Personnel could not deleted');
		}
		redirect('human-resource/personnel');
	}
    
	/*
	*
	*	Activate an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function activate_personnel($personnel_id)
	{
		$this->personnel_model->activate_personnel($personnel_id);
		$this->session->set_userdata('success_message', 'Personnel activated successfully');
		redirect('human-resource/personnel');
	}
    
	/*
	*
	*	Deactivate an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function deactivate_personnel($personnel_id)
	{
		$this->personnel_model->deactivate_personnel($personnel_id);
		$this->session->set_userdata('success_message', 'Personnel disabled successfully');
		redirect('human-resource/personnel');
	}
	
	
	
	public function get_section_children($section_id)
	{
		$sub_sections = $this->sections_model->get_sub_sections($section_id);
		
		$children = '';
		
		if($sub_sections->num_rows() > 0)
		{
			$children = '<option value="0" >--Select a sub section--</option>';
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

	/*
	*
	*	Activate an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function activate_emergency_contact($emergency_contact_id,$personnel_id)
	{
		$this->personnel_model->activate_emergency_contact($emergency_contact_id);
		$this->session->set_userdata('success_message', 'Personnel emergency activated successfully');
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}
    
	/*
	*
	*	Deactivate an existing personnel
	*	@param int $emergency_contact_id
	*
	*/
	public function deactivate_emergency_contact($emergency_contact_id,$personnel_id)
	{
		$this->personnel_model->deactivate_emergency_contact($emergency_contact_id);
		$this->session->set_userdata('success_message', 'Personnel emergnecy disabled successfully');
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}

	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function delete_emergency_contact($personnel_emergency_contact_id,$personnel_id)
	{
		if($this->personnel_model->delete_personnel_emergency_contact($personnel_emergency_contact_id))
		{
			$this->session->set_userdata('success_message', 'Personnel emergency contact has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Personnel emergency contact could not deleted');
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}
    
	public function add_dependant_contact($personnel_id)
    {
    	$this->form_validation->set_rules('personnel_dependant_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_dependant_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('title_id', 'Title', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->personnel_model->add_dependant_contact($personnel_id))
			{
				$this->session->set_userdata("success_message", "Personnel dependant contact added successfully");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add personnel dependant contact. Please try again");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
		}
		else
		{
			$this->session->set_userdata("error_message","Could not add personnel dependant contact. Please try again");
			redirect('human-resource/edit-personnel/'.$personnel_id);

		}
		
    }
    public function activate_dependant_contact($dependant_contact_id,$personnel_id)
	{
		$this->personnel_model->activate_dependant_contact($dependant_contact_id);
		$this->session->set_userdata('success_message', 'Personnel dependant activated successfully');
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}
    
	/*
	*
	*	Deactivate an existing personnel
	*	@param int $dependant_contact_id
	*
	*/
	public function deactivate_dependant_contact($dependant_contact_id,$personnel_id)
	{
		$this->personnel_model->deactivate_dependant_contact($dependant_contact_id);
		$this->session->set_userdata('success_message', 'Personnel emergnecy disabled successfully');
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}

	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function delete_dependant_contact($personnel_dependant_contact_id,$personnel_id)
	{
		if($this->personnel_model->delete_personnel_dependant_contact($personnel_dependant_contact_id))

		{
			$this->session->set_userdata('success_message', 'Personnel dependant has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Personnel dependant could not deleted');
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}
	
	public function add_personnel_job($personnel_id)
    {
    	$this->form_validation->set_rules('department_id', 'Department', 'required|xss_clean');
    	$this->form_validation->set_rules('job_title_id', 'Job title', 'required|xss_clean');
    	$this->form_validation->set_rules('department_id', 'Department', 'required|xss_clean');
    	$this->form_validation->set_rules('job_commencement_date', 'Start date', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->personnel_model->add_personnel_job($personnel_id))
			{
				$this->session->set_userdata("success_message", "Position added successfully");
			}
			
			else
			{
				$this->session->set_userdata("error_message", "Could not add position. Please try again");
			}
		}
		
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
		}
		
		redirect('human-resource/edit-personnel/'.$personnel_id);
    }
	
    public function activate_personnel_job($personnel_job_id,$personnel_id)
	{
		if($this->personnel_model->activate_personnel_job($personnel_job_id))
		{
			$this->session->set_userdata('success_message', 'Position activated successfully');
		}
		
		else
		{
			$this->session->set_userdata("error_message", "Could not activate position. Please try again");
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}
    
	/*
	*
	*	Deactivate an existing personnel
	*	@param int $personnel_job_id
	*
	*/
	public function deactivate_personnel_job($personnel_job_id,$personnel_id)
	{
		if($this->personnel_model->deactivate_personnel_job($personnel_job_id))
		{
			$this->session->set_userdata('success_message', 'Position deactivated successfully');
		}
		
		else
		{
			$this->session->set_userdata("error_message", "Could not deactivate position. Please try again");
		}
		$this->personnel_model->deactivate_personnel_job($personnel_job_id);
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}

	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function delete_personnel_job($personnel_personnel_job_id,$personnel_id)
	{
		if($this->personnel_model->delete_personnel_job($personnel_personnel_job_id))
		{
			$this->session->set_userdata('success_message', 'Personnel dependant contact has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Personnel dependant contact could not deleted');
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}

	public function add_personnel_leave($personnel_id)
	{
		$this->form_validation->set_rules('start_date', 'Start Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('end_date', 'End Date', 'trim|required|xss_clean');
		$this->form_validation->set_rules('leave_type_id', 'Leave Type', 'trim|numeric|required|xss_clean');

		if ($this->form_validation->run())//if there is an invalid item
		{
			if($this->personnel_model->add_personnel_leave($personnel_id))
			{
				$this->session->set_userdata("success_message", "Personnel leave  added successfully");
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add personnel leave. Please try again");
			}
		}
		
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}
	public function activate_personnel_leave($personnel_leave_id,$personnel_id)
	{
		if($this->personnel_model->activate_personnel_leave($personnel_leave_id))
		{
			$this->session->set_userdata('success_message', 'Personnel leave has been activated');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Personnel leave could not activated');
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}
    
	/*
	*
	*	Deactivate an existing personnel
	*	@param int $personnel_leave_id
	*
	*/
	public function deactivate_personnel_leave($personnel_leave_id,$personnel_id)
	{
		if($this->personnel_model->deactivate_personnel_leave($personnel_leave_id))
		{
			$this->session->set_userdata('success_message', 'Personnel leave has been disabled');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Personnel leave could not disabled');
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}

	/*
	*
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function delete_personnel_leave($personnel_personnel_leave_id, $personnel_id)
	{
		if($this->personnel_model->delete_personnel_leave($personnel_personnel_leave_id))
		{
			$this->session->set_userdata('success_message', 'Personnel leave has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Personnel leave could not deleted');
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}

	public function delete_personnel_role($personnel_section_id, $personnel_id)
    {
		if($this->personnel_model->delete_personnel_role($personnel_section_id))
		{
			$this->session->set_userdata("success_message", "Role deleted successfully");
			redirect('human-resource/edit-personnel/'.$personnel_id);
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete role. Please try again");
			redirect('human-resource/edit-personnel/'.$personnel_id);
		}
    }

	public function edit_invoice_authorize($personnel_id)
    {
		if($this->personnel_model->edit_invoice_authorize($personnel_id))
		{
			$this->session->set_userdata("success_message", "Authorize updated successfully");
			redirect('human-resource/edit-personnel/'.$personnel_id);
		}
		
		else
		{
			$this->session->set_userdata("error_message","Unable to update. Please try again");
			redirect('human-resource/edit-personnel/'.$personnel_id);
		}
    }
    public function edit_order_authorize($personnel_id)
    {

    	$this->form_validation->set_rules('approval_role_id', 'Status Role', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			
			if($this->personnel_model->edit_order_authorize($personnel_id))
			{
				$this->session->set_userdata("success_message", "Authorize updated successfully");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Unable to update. Please try again");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
		}
		else
		{

		}
    }
    public function edit_store_authorize($personnel_id)
    {

    	$this->form_validation->set_rules('store_id', 'Store Name', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			
			if($this->personnel_model->edit_store_authorize($personnel_id))
			{
				$this->session->set_userdata("success_message", "Authorize updated successfully");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Unable to update. Please try again");
				redirect('human-resource/edit-personnel/'.$personnel_id);
			}
		}
		else
		{

		}
    }
}
?>