<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/hr/controllers/hr.php";

class Personnel extends hr 
{
	var $document_upload_path;
	var $document_upload_location;
	var $personnel_path;
	var $personnel_location;
	var $csv_path;
	
	function __construct()
	{
		parent:: __construct();
		
		$this->load->library('image_lib');

		$this->document_upload_path = realpath(APPPATH . '../assets/document_uploads');
		$this->document_upload_location = base_url().'assets/document_uploads/';
		$this->personnel_path = realpath(APPPATH . '../assets/personnel');
		$this->personnel_location = base_url().'assets/personnel/';
		$this->csv_path = realpath(APPPATH . '../assets/csv');
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
		$personnel_search = $this->session->userdata('personnel_search2');
		
		if(!empty($personnel_search))
		{
			$where .= $personnel_search;
		}
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
	public function edit_personnel($personnel_id, $image_location = NULL) 
	{
		//open the add new personnel
		$data['title'] = 'Edit ';
		$v_data['title'] = $data['title'];
		$v_data['personnel'] = $this->personnel_model->get_personnel($personnel_id);
		$row = $v_data['personnel']->row();
		
		if($image_location == NULL)
		{
			$img = $row->image;
			
			if((empty($img)) || ($img == '0'))
			{
				$image_location = 'http://placehold.it/300x300?text=image';
			}
			
			else
			{
				$image_location = $this->personnel_location.$img;
			}
		}
		
		else
		{
			$image_location = $this->personnel_location.$image_location;
		}
		
		$v_data['image_location'] = $image_location;
		$v_data['personnel_id'] = $personnel_id;
		$v_data['branches'] = $this->branches_model->all_branches();
		$v_data['relationships'] = $this->personnel_model->get_relationship();
		$v_data['religions'] = $this->personnel_model->get_religion();
		$v_data['civil_statuses'] = $this->personnel_model->get_civil_status();
		$v_data['titles'] = $this->personnel_model->get_title();
		$v_data['genders'] = $this->personnel_model->get_gender();
		$v_data['job_titles_query'] = $this->personnel_model->get_job_titles();
		$v_data['emergency_contacts'] = $this->personnel_model->get_emergency_contacts($personnel_id);
		$v_data['dependants'] = $this->personnel_model->get_personnel_dependants($personnel_id);
		$v_data['jobs'] = $this->personnel_model->get_personnel_jobs($personnel_id);
		$v_data['roles'] = $this->personnel_model->get_personnel_roles($personnel_id);
		$v_data['leave'] = $this->personnel_model->get_personnel_leave($personnel_id);
		$v_data['personnel_other_documents'] = $this->personnel_model->get_document_uploads($personnel_id);
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
		$resize['width'] = 400;
		$resize['height'] = 400;
		$image_location = 'http://placehold.it/300x300?text=image';
		$image_error = '';
		$this->session->unset_userdata('upload_error_message');
		$image_upload_name = 'personnel_document_name';
		$previous_image = $this->input->post('previous_image');
		
		//upload image if it has been selected
		$response = $this->personnel_model->upload_image($this->personnel_path, $this->personnel_location, $resize, $image_upload_name, 'personnel_image', $previous_image);
		
		if($response)
		{
			$image_location = $this->personnel_location.$this->session->userdata($image_upload_name);
		}
		
		else
		{
			$image_error = $this->session->userdata('upload_error_message');
		}
		
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
			//update if no image upload errors
			if(empty($image_error))
			{
				//update personnel
				$personnel = $this->personnel_model->get_personnel($personnel_id);
				$row = $personnel->row();
				
				$image = $this->session->userdata($image_upload_name);
				if(empty($image))
				{
					$image = $row->image;
				}
				
				if($this->personnel_model->edit_personnel($personnel_id, $image))
				{
					$this->session->set_userdata('success_message', 'personnel\'s general details updated successfully');
					$this->session->unset_userdata($image_upload_name);
					redirect('human-resource/edit-personnel/'.$personnel_id);
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Could not update personnel\'s general details. Please try again');
				}
			}
			
			//else return to form to fix upload errors
			else
			{
				$error = '';
				if(!empty($image_error))
				{
					$error .= '<br/><strong>Signature upload error!</strong> '.$image_error;
				}
				$this->session->set_userdata('error_message', $error);
			}
		}
		else
		{
			$this->session->set_userdata("error_message","Could not add personnel. Please try again");
		}
		
		$this->edit_personnel($personnel_id, $this->session->userdata($image_upload_name));
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
	*	Delete an existing personnel
	*	@param int $personnel_id
	*
	*/
	public function delete_document_scan($document_upload_id, $personnel_id)
	{
		if($this->personnel_model->delete_document_scan($document_upload_id))
		{
			$this->session->set_userdata('success_message', 'Document has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Document could not deleted');
		}
		redirect('human-resource/edit-personnel/'.$personnel_id);
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

	/*
	*
	*	Add documents 
	*	@param int $personnel_id
	*
	*/
	public function upload_documents($personnel_id) 
	{
		$image_error = '';
		$this->session->unset_userdata('upload_error_message');
		$document_name = 'document_scan';
		
		//upload image if it has been selected
		$response = $this->personnel_model->upload_any_file($this->document_upload_path, $this->document_upload_location, $document_name, 'document_scan');
		if($response)
		{
			$document_upload_location = $this->document_upload_location.$this->session->userdata($document_name);
		}
		
		//case of upload error
		else
		{
			$image_error = $this->session->userdata('upload_error_message');
			$this->session->unset_userdata('upload_error_message');
		}

		$document = $this->session->userdata($document_name);
		$this->form_validation->set_rules('document_place', 'Place of issue', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{

			if($this->personnel_model->upload_personnel_documents($personnel_id, $document))
			{
				$this->session->set_userdata('success_message', 'Document uploaded successfully');
				$this->session->unset_userdata($document_name);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not upload document. Please try again');
			}
		}
		else
		{
			$this->session->set_userdata('error_message', 'Could not upload document. Please try again');
		}
		
		redirect('human-resource/edit-personnel/'.$personnel_id);
	}
	
	//import personnel
	function import_personnel()
	{
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		
		$data['content'] = $this->load->view('import/import_personnel', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	function import_personnel_template()
	{
		//export products template in excel 
		$this->personnel_model->import_personnel_template();
	}
	//do the personnel import
	function do_personnel_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import products from excel 
				$response = $this->personnel_model->import_csv_personnel($this->csv_path);
				
				if($response == FALSE)
				{
					$v_data['import_response_error'] = 'Something went wrong. Please try again.';
				}
				
				else
				{
					if($response['check'])
					{
						$v_data['import_response'] = $response['response'];
					}
					
					else
					{
						$v_data['import_response_error'] = $response['response'];
					}
				}
			}
			
			else
			{
				$v_data['import_response_error'] = 'Please select a file to import.';
			}
		}
		
		else
		{
			$v_data['import_response_error'] = 'Please select a file to import.';
		}
		
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		
		$data['content'] = $this->load->view('import/import_personnel', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function search_personnel()
	{
		$personnel_number = $this->input->post('personnel_number');
		$branch_id = $this->input->post('branch_id');
		$search_title = '';
		
		/*if(!empty($personnel_number))
		{
			$search_title .= ' member number <strong>'.$personnel_number.'</strong>';
			$personnel_number = ' AND personnel.personnel_number LIKE \'%'.$personnel_number.'%\'';
		}*/
		if(!empty($personnel_number))
		{
			$search_title .= ' personnel number <strong>'.$personnel_number.'</strong>';
			$personnel_number = ' AND personnel.personnel_number = \''.$personnel_number.'\'';
		}
		
		if(!empty($branch_id))
		{
			$search_title .= ' member type <strong>'.$branch_id.'</strong>';
			$branch_id = ' AND personnel.branch_id = \''.$branch_id.'\' ';
		}
		
		//search surname
		if(!empty($_POST['personnel_fname']))
		{
			$search_title .= ' first name <strong>'.$_POST['personnel_fname'].'</strong>';
			$surnames = explode(" ",$_POST['personnel_fname']);
			$total = count($surnames);
			
			$count = 1;
			$surname = ' AND (';
			for($r = 0; $r < $total; $r++)
			{
				if($count == $total)
				{
					$surname .= ' personnel.personnel_fname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\'';
				}
				
				else
				{
					$surname .= ' personnel.personnel_fname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\' AND ';
				}
				$count++;
			}
			$surname .= ') ';
		}
		
		else
		{
			$surname = '';
		}
		
		//search other_names
		if(!empty($_POST['personnel_onames']))
		{
			$search_title .= ' other names <strong>'.$_POST['personnel_onames'].'</strong>';
			$other_names = explode(" ",$_POST['personnel_onames']);
			$total = count($other_names);
			
			$count = 1;
			$other_name = ' AND (';
			for($r = 0; $r < $total; $r++)
			{
				if($count == $total)
				{
					$other_name .= ' personnel.personnel_onames LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\'';
				}
				
				else
				{
					$other_name .= ' personnel.personnel_onames LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\' AND ';
				}
				$count++;
			}
			$other_name .= ') ';
		}
		
		else
		{
			$other_name = '';
		}
		
		$search = $personnel_number.$branch_id.$surname.$other_name;
		$this->session->set_userdata('personnel_search2', $search);
		$this->session->set_userdata('personnel_search_title2', $search_title);
		
		$this->index();
	}
	
	public function close_search()
	{
		$this->session->unset_userdata('personnel_search2', $search);
		$this->session->unset_userdata('personnel_search_title2', $search_title);
		
		redirect('human-resource/personnel');
	}
}
?>