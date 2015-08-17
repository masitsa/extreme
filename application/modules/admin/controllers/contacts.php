<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/admin/controllers/admin.php";

class Contacts extends admin 
{
	var $logo_path;
	var $logo_location;
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('users_model');
		$this->load->model('contacts_model');
		$this->load->model('file_model');
		
		$this->load->library('image_lib');
		
		//path to image directory
		$this->logo_path = realpath(APPPATH . '../assets/logo/');
		$this->logo_location = base_url().'assets/logo/';
	}
	
	public function show_contacts()
	{
		$v_data['logo_location'] = 'http://placehold.it/200x200';
		
		//get previously entered company data
		$company_query = $this->contacts_model->get_company_details();
		$company_row = $company_query->row();
		
		$v_data['email'] = $company_row->email;
		$v_data['phone'] = $company_row->phone;
		$v_data['address'] = $company_row->address;
		$v_data['city'] = $company_row->city;
		$v_data['post_code'] = $company_row->post_code;
		$v_data['building'] = $company_row->building;
		$v_data['floor'] = $company_row->floor;
		$v_data['location'] = $company_row->location;
		$v_data['working_weekend'] = $company_row->working_weekend;
		$v_data['working_weekday'] = $company_row->working_weekday;
		$v_data['company_name'] = $company_row->company_name;
		$v_data['mission'] = $company_row->mission;
		$v_data['vision'] = $company_row->vision;
		$v_data['motto'] = $company_row->motto;
		$v_data['facebook'] = $company_row->facebook;
		$v_data['twitter'] = $company_row->twitter;
		$v_data['pintrest'] = $company_row->pintrest;
		$v_data['about'] = $company_row->about;
		$v_data['objectives'] = $company_row->objectives;
		$v_data['core_values'] = $company_row->core_values;
			
		if(!empty($company_row->logo))
		{
			$v_data['logo_location'] = $this->logo_location.$company_row->logo;
		}
		
		//upload image if it has been selected
		$response = $this->contacts_model->upload_company_logo($this->logo_path, $company_row->logo);
		if($response)
		{
			$v_data['logo_location'] = $this->logo_location.$this->session->userdata('logo_file_name');
		}
		
		//case of upload error
		else
		{
			$v_data['logo_error'] = $this->session->userdata('error_message');
		}
		
		$logo_error = $this->session->userdata('error_message');
		
		$this->form_validation->set_rules('email', 'Email', 'trim|valid_email|xss_clean');
		$this->form_validation->set_rules('phone', 'Phone', 'trim|xss_clean');
		$this->form_validation->set_rules('address', 'Address', 'trim|xss_clean');
		$this->form_validation->set_rules('city', 'City', 'trim|xss_clean');
		$this->form_validation->set_rules('post_code', 'Post code', 'trim|xss_clean');
		$this->form_validation->set_rules('building', 'Building', 'trim|xss_clean');
		$this->form_validation->set_rules('floor', 'Floor', 'trim|xss_clean');
		$this->form_validation->set_rules('location', 'Location', 'trim|xss_clean');
		$this->form_validation->set_rules('working_weekend', 'Weekend working hours', 'trim|xss_clean');
		$this->form_validation->set_rules('working_weekday', 'Weekday working hours', 'trim|xss_clean');
		$this->form_validation->set_rules('company_name', 'Company Name', 'trim|xss_clean');
		$this->form_validation->set_rules('mission', 'Mission', 'trim|xss_clean');
		$this->form_validation->set_rules('vision', 'Vision', 'trim|xss_clean');
		$this->form_validation->set_rules('motto', 'Motto', 'trim|xss_clean');
		$this->form_validation->set_rules('facebook', 'Facebook', 'trim|xss_clean');
		$this->form_validation->set_rules('twitter', 'Twitter', 'trim|xss_clean');
		$this->form_validation->set_rules('pintrest', 'Pinterest', 'trim|xss_clean');
		$this->form_validation->set_rules('about', 'About', 'trim|xss_clean');
		$this->form_validation->set_rules('objectives', 'Objectives', 'trim|xss_clean');
		$this->form_validation->set_rules('core_values', 'Core values', 'trim|xss_clean');

		if ($this->form_validation->run())
		{	
			if(empty($logo_error))
			{
		
				$logo = $this->session->userdata('logo_file_name');
				
				if(empty($logo))
				{
					$logo = $company_row->logo;
					$thumb = $company_row->thumb;
				}
				else
				{
					$thumb = $this->session->userdata('logo_thumb_name');
				}
				
				if($this->contacts_model->update_company_details($logo, $thumb))
				{
					$this->session->unset_userdata('logo_file_name');
					$this->session->unset_userdata('logo_thumb_name');
					$this->session->unset_userdata('error_message');
					$this->session->set_userdata('success_message', 'Company details have been updated successfully');
					
					redirect('administration/company-profile');
				}
			}
			
			else
			{
				$v_data['email'] = $this->input->post("email");
				$v_data['phone'] = $this->input->post("phone");
				$v_data['address'] = $this->input->post("address");
				$v_data['city'] = $this->input->post("city");
				$v_data['post_code'] = $this->input->post("post_code");
				$v_data['building'] = $this->input->post("building");
				$v_data['floor'] = $this->input->post("floor");
				$v_data['location'] = $this->input->post("location");
				$v_data['working_weekend'] = $this->input->post("working_weekend");
				$v_data['working_weekday'] = $this->input->post("working_weekday");
				$v_data['company_name'] = $this->input->post("company_name");
				$v_data['mission'] = $this->input->post("mission");
				$v_data['vision'] = $this->input->post("vision");
				$v_data['motto'] = $this->input->post("motto");
				$v_data['facebook'] = $this->input->post("facebook");
				$v_data['twitter'] = $this->input->post("twitter");
				$v_data['pintrest'] = $this->input->post("pintrest");
				$v_data['about'] = $this->input->post("about");
				$v_data['objectives'] = $this->input->post("objectives");
				$v_data['core_values'] = $this->input->post("core_values");
			}
		}
		
		
		$error2 = validation_errors(); 
		if(!empty($error2))
		{
			$v_data['email'] = $this->input->post("email");
			$v_data['phone'] = $this->input->post("phone");
			$v_data['address'] = $this->input->post("address");
			$v_data['city'] = $this->input->post("city");
			$v_data['post_code'] = $this->input->post("post_code");
			$v_data['building'] = $this->input->post("building");
			$v_data['floor'] = $this->input->post("floor");
			$v_data['location'] = $this->input->post("location");
			$v_data['working_weekend'] = $this->input->post("working_weekend");
			$v_data['working_weekday'] = $this->input->post("working_weekday");
			$v_data['company_name'] = $this->input->post("company_name");
			$v_data['mission'] = $this->input->post("mission");
			$v_data['vision'] = $this->input->post("vision");
			$v_data['motto'] = $this->input->post("motto");
			$v_data['facebook'] = $this->input->post("facebook");
			$v_data['twitter'] = $this->input->post("twitter");
			$v_data['pintrest'] = $this->input->post("pintrest");
			$v_data['about'] = $this->input->post("about");
			$v_data['objectives'] = $this->input->post("objectives");
			$v_data['core_values'] = $this->input->post("core_values");
		}
		
		$logo = $this->session->userdata('logo_file_name');
		
		if(!empty($logo))
		{
			$v_data['logo_location'] = $this->logo_location.$this->session->userdata('logo_file_name');
		}
		$v_data['error'] = $logo_error;
		
		$data['title'] = 'Company details';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view("company_details", $v_data, TRUE);
		
		$this->load->view('templates/general_page', $data);
	}
}
?>