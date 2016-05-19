<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Hr extends MX_Controller 
{
	var $csv_path;
	function __construct()
	{
		parent:: __construct();
		
		$this->load->model('auth/auth_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/users_model');
		$this->load->model('admin/file_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('personnel_model');
		$this->load->model('leave_model');
		$this->load->model('hr_model');
		$this->load->model('schedules_model');
		$this->load->model('admin/branches_model');
		$this->load->model('tutorial_model');
		$this->csv_path = realpath(APPPATH . '../assets/csv');
		
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
	}
    
	/*
	*
	*	Dashboard
	*
	*/
	public function dashboard() 
	{
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		
		$data['content'] = $this->load->view('dashboard', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
    
	/*
	*
	*	Edit hr configuration
	*
	*/
	public function configuration()
	{
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		$v_data['job_titles_query'] = $this->personnel_model->get_job_titles();
		
		$data['content'] = $this->load->view('configuration', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}

	public function add_job_title()
    {
    	$this->form_validation->set_rules('job_title_name', 'Job title', 'required|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{
			if($this->hr_model->add_job_title())
			{
				$this->session->set_userdata("success_message", "Job title added successfully");
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add job title. Please try again");
			}
		}
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
		}
		
		redirect('human-resource/configuration');
    }

	public function edit_job_title($job_title_id)
    {
    	$this->form_validation->set_rules('job_title_name', 'Job title', 'required|xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{
			if($this->hr_model->edit_job_title($job_title_id))
			{
				$this->session->set_userdata("success_message", "Job title editted successfully");
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit job title. Please try again");
			}
		}
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
		}
		
		redirect('human-resource/configuration');
    }

	public function delete_job_title($job_title_id)
    {
		if($this->hr_model->delete_job_title($job_title_id))
		{
			$this->session->set_userdata("success_message", "Job title deleted successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete job title. Please try again");
		}
		
		redirect('human-resource/configuration');
    }
	
	//payroll data import
	public function import_payroll()
	{
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		
		$data['content'] = $this->load->view('import/import_payroll', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	//template for the payroll data
	function import_payroll_template()
	{
		//export products template in excel 
		$this->hr_model->import_payroll_template();
	}
	//import the payroll data
	function do_payroll_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import products from excel 
				$response = $this->hr_model->import_csv_payroll($this->csv_path);
				
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
		
		$data['content'] = $this->load->view('import/import_payroll', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
}
?>