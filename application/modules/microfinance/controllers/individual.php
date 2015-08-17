<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/microfinance/controllers/microfinance.php";

class Individual extends microfinance 
{
	var $individual_path;
	var $individual_location;
	var $signature_path;
	var $signature_location;
	
	function __construct()
	{
		parent:: __construct();
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->individual_path = realpath(APPPATH . '../assets/img/individuals');
		$this->individual_location = base_url().'assets/img/individuals/';
		$this->signature_path = realpath(APPPATH . '../assets/img/signatures');
		$this->signature_location = base_url().'assets/img/signatures/';
	}
    
	/*
	*
	*	Default action is to show all the individual
	*
	*/
	public function index($order = 'individual_onames', $order_method = 'ASC') 
	{
		$where = 'individual_id > 0';
		$table = 'individual';
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
		$query = $this->individual_model->get_all_individual($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = 'Individual';
		$v_data['title'] = $data['title'];
		
		$v_data['order'] = $order;
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['all_individual'] = $this->individual_model->all_individual();
		$v_data['page'] = $page;
		$data['content'] = $this->load->view('individual/all_individual', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Add a new individual
	*
	*/
	public function add_individual() 
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
		$this->form_validation->set_rules('individual_email2', 'Email 2', 'valid_email|is_unique[individual.individual_email2]|xss_clean');
		$this->form_validation->set_rules('document_id', 'Document type', 'xss_clean');
		$this->form_validation->set_rules('document_number', 'Document number', 'xss_clean');
		$this->form_validation->set_rules('document_place', 'Place of issue', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$individual_id = $this->individual_model->add_individual();
			if($individual_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Individual added successfully");
				redirect('microfinance/edit-individual/'.$individual_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add individual. Please try again");
			}
		}
		
		$v_data['relationships'] = $this->individual_model->get_relationship();
		$v_data['religions'] = $this->individual_model->get_religion();
		$v_data['civil_statuses'] = $this->individual_model->get_civil_status();
		$v_data['titles'] = $this->individual_model->get_title();
		$v_data['genders'] = $this->individual_model->get_gender();
		$v_data['job_titles_query'] = $this->individual_model->get_job_titles();
		$data['title'] = 'Add individual';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('individual/add_individual', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing individual
	*	@param int $individual_id
	*
	*/
	public function edit_individual($individual_id, $image_location = NULL, $signature_location = NULL) 
	{
		//open the add new individual
		$data['title'] = 'Edit individual';
		$v_data['title'] = $data['title'];
		$v_data['individual'] = $this->individual_model->get_individual($individual_id);
		$row = $v_data['individual']->row();
		
		if($image_location == NULL)
		{
			$img = $row->image;
			
			if((empty($img)) || ($img == '0'))
			{
				$image_location = 'http://placehold.it/200x200?text=image';
			}
			
			else
			{
				$image_location = $this->individual_location.$img;
			}
		}
		
		else
		{
			$image_location = $this->individual_location.$image_location;
		}
		
		if($signature_location == NULL)
		{
			$img = $row->signature;
			
			if((empty($img)) || ($img == '0'))
			{
				$signature_location = 'http://placehold.it/200x100?text=signature';
			}
			
			else
			{
				$signature_location = $this->signature_location.$img;
			}
		}
		
		else
		{
			$signature_location = $this->signature_location.$signature_location;
		}
		
		$v_data['image_location'] = $image_location;
		$v_data['signature_location'] = $signature_location;
		$v_data['individual_id'] = $individual_id;
		$v_data['relationships'] = $this->individual_model->get_relationship();
		$v_data['religions'] = $this->individual_model->get_religion();
		$v_data['civil_statuses'] = $this->individual_model->get_civil_status();
		$v_data['titles'] = $this->individual_model->get_title();
		$v_data['genders'] = $this->individual_model->get_gender();
		$v_data['job_titles_query'] = $this->individual_model->get_job_titles();
		$v_data['emergency_contacts'] = $this->individual_model->get_emergency_contacts($individual_id);
		$v_data['dependants'] = $this->individual_model->get_individual_dependants($individual_id);
		$v_data['jobs'] = $this->individual_model->get_individual_jobs($individual_id);
		$v_data['individual_savings'] = $this->individual_model->get_individual_savings_plans($individual_id);
		$v_data['savings_plans'] = $this->savings_plan_model->all_savings_plan();
		$v_data['parent_sections'] = $this->sections_model->all_parent_sections('section_position');
		$data['content'] = $this->load->view('individual/edit_individual', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit an existing individual
	*	@param int $individual_id
	*
	*/
	public function edit_about($individual_id) 
	{
		//upload product's gallery images
		$resize['width'] = 400;
		$resize['height'] = 400;
		
		$resize2['width'] = 400;
		$resize2['height'] = 200;
		
		$image_location = 'http://placehold.it/200x200?text=image';
		$signature_location = 'http://placehold.it/200x100?text=signature';
		$image_error = '';
		$signature_error = '';
		
		$this->session->unset_userdata('upload_error_message');
		$image_upload_name = 'individual_image_name';
		$signature_upload_name = 'individual_signature_name';
		
		//upload image if it has been selected
		$response = $this->individual_model->upload_image($this->individual_path, $this->individual_location, $resize, $image_upload_name, 'individual_image');
		if($response)
		{
			$image_location = $this->individual_location.$this->session->userdata($image_upload_name);
		}
		
		//case of upload error
		else
		{
			$image_error = $this->session->userdata('upload_error_message');
			$this->session->unset_userdata('upload_error_message');
		}
		
		//upload image if it has been selected
		$response = $this->individual_model->upload_image($this->signature_path, $this->signature_location, $resize2, $signature_upload_name, 'individual_signature');
		if($response)
		{
			$signature_location = $this->signature_location.$this->session->userdata($signature_upload_name);
		}
		
		//case of upload error
		else
		{
			$signature_error = $this->session->userdata('upload_error_message');
			$this->session->unset_userdata('upload_error_message');
		}
		
		//form validation rules
		$this->form_validation->set_rules('individual_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('individual_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('individual_dob', 'Date of Birth', 'xss_clean');
		$this->form_validation->set_rules('individual_email', 'Email', 'valid_email|exists[individual.individual_email]|xss_clean');
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
		$this->form_validation->set_rules('individual_email2', 'Email 2', 'valid_email|exists[individual.individual_email2]|xss_clean');
		$this->form_validation->set_rules('document_id', 'Document type', 'xss_clean');
		$this->form_validation->set_rules('document_number', 'Document number', 'xss_clean');
		$this->form_validation->set_rules('document_place', 'Place of issue', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//update if no image upload errors
			if(empty($image_error) && empty($signature_error))
			{
				//update individual
				$individual = $this->individual_model->get_individual($individual_id);
				$row = $individual->row();
				
				$image = $this->session->userdata($image_upload_name);
				if(empty($image))
				{
					$image = $row->image;
				}
				
				$signature = $this->session->userdata($signature_upload_name);
				if(empty($signature))
				{
					$signature = $row->signature;
				}
				
				if($this->individual_model->edit_individual($individual_id, $image, $signature))
				{
					$this->session->set_userdata('success_message', 'Individual\'s general details updated successfully');
					$this->session->unset_userdata($image_upload_name);
					$this->session->unset_userdata($signature_upload_name);
					redirect('microfinance/edit-individual/'.$individual_id);
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Could not update individual\'s general details. Please try again');
				}
			}
			
			//else return to form to fix upload errors
			else
			{
				$error = '';
				if(!empty($signature_error))
				{
					$error .= '<strong>Signature upload error!</strong> '.$signature_error;
				}
				if(!empty($image_error))
				{
					$error .= '<br/><strong>Signature upload error!</strong> '.$image_error;
				}
				$this->session->set_userdata('error_message', $error);
			}
		}
		
		$this->edit_individual($individual_id, $this->session->userdata($image_upload_name), $this->session->userdata($signature_upload_name));
	}
    
	/*
	*
	*	Edit an existing individual
	*	@param int $individual_id
	*
	*/
	public function add_emergency($individual_id) 
	{	
		//form validation rules
		$this->form_validation->set_rules('individual_emergency_onames', 'Other Names', 'required|xss_clean');
		$this->form_validation->set_rules('individual_emergency_fname', 'First Name', 'required|xss_clean');
		$this->form_validation->set_rules('individual_emergency_dob', 'Date of Birth', 'xss_clean');
		$this->form_validation->set_rules('individual_emergency_email', 'Email', 'valid_email|is_unique[individual_emergency.individual_emergency_email]|xss_clean');
		$this->form_validation->set_rules('individual_emergency_phone', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('individual_emergency_phone2', 'Phone', 'xss_clean');
		$this->form_validation->set_rules('individual_emergency_address', 'Address', 'xss_clean');
		$this->form_validation->set_rules('individual_emergency_city', 'City', 'xss_clean');
		$this->form_validation->set_rules('individual_emergency_post_code', 'Post code', 'xss_clean');
		$this->form_validation->set_rules('individual_emergency_email2', 'Email 2', 'valid_email|is_unique[individual_emergency.individual_emergency_email2]|xss_clean');
		$this->form_validation->set_rules('document_id', 'Document type', 'xss_clean');
		$this->form_validation->set_rules('document_number', 'Document number', 'xss_clean');
		$this->form_validation->set_rules('document_place', 'Place of issue', 'xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->individual_model->add_emergency($individual_id))
			{
				$this->session->set_userdata('success_message', 'Individual\'s next of kin details updated successfully');
				redirect('microfinance/edit-individual/'.$individual_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not update individual\'s next of kin details. Please try again');
			}
		}
		
		$this->edit_individual($individual_id);
	}
    
	/*
	*
	*	Edit an existing individual
	*	@param int $individual_id
	*
	*/
	public function add_position($individual_id) 
	{	
		//form validation rules
		$this->form_validation->set_rules('employer', 'Employer', 'required|xss_clean');
		$this->form_validation->set_rules('job_title', 'Job title', 'required|xss_clean');
		$this->form_validation->set_rules('employment_date', 'Employment date', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->individual_model->add_position($individual_id))
			{
				$this->session->set_userdata('success_message', 'Individual\'s position added successfully');
				redirect('microfinance/edit-individual/'.$individual_id);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add individual\'s position details. Please try again');
			}
		}
		
		$this->edit_individual($individual_id);
	}
    
	/*
	*
	*	Delete an existing individual
	*	@param int $individual_id
	*
	*/
	public function delete_individual($individual_id)
	{
		if($this->individual_model->delete_individual($individual_id))
		{
			$this->session->set_userdata('success_message', 'Individual has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Individual could not deleted');
		}
		redirect('microfinance/individual');
	}
    
	/*
	*
	*	Activate an existing individual
	*	@param int $individual_id
	*
	*/
	public function activate_individual($individual_id)
	{
		$this->individual_model->activate_individual($individual_id);
		$this->session->set_userdata('success_message', 'Individual activated successfully');
		redirect('microfinance/individual');
	}
    
	/*
	*
	*	Deactivate an existing individual
	*	@param int $individual_id
	*
	*/
	public function deactivate_individual($individual_id)
	{
		$this->individual_model->deactivate_individual($individual_id);
		$this->session->set_userdata('success_message', 'Individual disabled successfully');
		redirect('microfinance/individual');
	}
    
	/*
	*
	*	Add a new individual
	*
	*/
	public function add_individual_plan($individual_id) 
	{
		//form validation rules
		$this->form_validation->set_rules('savings_plan_id', 'Savings plan', 'required|xss_clean');
		$this->form_validation->set_rules('individual_savings_status', 'Activate plan', 'required|xss_clean');
		$this->form_validation->set_rules('individual_savings_opening_balance', 'Opening balance', 'xss_clean');
		$this->form_validation->set_rules('start_date', 'Start date', 'xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->individual_model->add_individual_plan($individual_id))
			{
				$this->session->set_userdata("success_message", "Plan added successfully");
				redirect('microfinance/edit-individual/'.$individual_id);
			}
			
			else
			{
				$this->session->set_userdata("error_message", "Could not add plan. Please try again");
			}
		}
		
		$this->edit_individual($individual_id);
	}
    
	/*
	*
	*	Activate an existing individual
	*	@param int $individual_id
	*
	*/
	public function activate_individual_plan($individual_savings_id, $individual_id)
	{
		if($this->individual_model->activate_individual_plan($individual_savings_id))
		{
			$this->session->set_userdata('success_message', 'Plan activated successfully');
		}
		
		else
		{
			$this->session->set_userdata("error_message", "Could not activate plan. Please try again");
		}
		
		redirect('microfinance/edit-individual/'.$individual_id);
	}
    
	/*
	*
	*	Dectivate an existing individual
	*	@param int $individual_savings_id
	*
	*/
	public function deactivate_individual_plan($individual_savings_id, $individual_id)
	{
		if($this->individual_model->deactivate_individual_plan($individual_savings_id))
		{
			$this->session->set_userdata('success_message', 'Plan deactivated successfully');
		}
		
		else
		{
			$this->session->set_userdata("error_message", "Could not deactivate plan. Please try again");
		}
		
		redirect('microfinance/edit-individual/'.$individual_id);
	}
    
	/*
	*
	*	Activate an existing individual
	*	@param int $individual_id
	*
	*/
	public function activate_position($individual_job_id, $individual_id)
	{
		if($this->individual_model->activate_individual_position($individual_job_id))
		{
			$this->session->set_userdata('success_message', 'Position activated successfully');
		}
		
		else
		{
			$this->session->set_userdata("error_message", "Could not activate position. Please try again");
		}
		
		redirect('microfinance/edit-individual/'.$individual_id);
	}
    
	/*
	*
	*	Dectivate an existing individual
	*	@param int $individual_savings_id
	*
	*/
	public function deactivate_position($individual_job_id, $individual_id)
	{
		if($this->individual_model->deactivate_individual_position($individual_job_id))
		{
			$this->session->set_userdata('success_message', 'Position deactivated successfully');
		}
		
		else
		{
			$this->session->set_userdata("error_message", "Could not deactivate position. Please try again");
		}
		
		redirect('microfinance/edit-individual/'.$individual_id);
	}
    
	/*
	*
	*	Delete an existing individual
	*	@param int $individual_id
	*
	*/
	public function delete_emergency($individual_emergency_id, $individual_id)
	{
		if($this->individual_model->delete_emergency($individual_emergency_id))
		{
			$this->session->set_userdata('success_message', 'Next of kin has been deleted');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Next of kin could not deleted');
		}
		redirect('microfinance/edit-individual/'.$individual_id);
	}
}
?>