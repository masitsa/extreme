<?php
class Auth extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('auth_model');
		$this->load->model('site/site_model');
	}
	
	public function index()
	{
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
		
		else
		{
			redirect('dashboard');
		}
	}
    
	/*
	*
	*	Login a user
	*
	*/
	public function login_user() 
	{
		$data['personnel_password_error'] = '';
		$data['personnel_username_error'] = '';
		
		//form validation rules
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('personnel_username', 'Username', 'required|xss_clean|exists[personnel.personnel_username]');
		$this->form_validation->set_rules('personnel_password', 'Password', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//check if personnel has valid login credentials
			if($this->auth_model->validate_personnel())
			{
				redirect('dashboard');
			}
			
			else
			{
				$this->session->set_userdata('login_error', 'The username or password provided is incorrect. Please try again');
				$data['personnel_username'] = set_value('personnel_username');
				$data['personnel_password'] = set_value('personnel_password');
			}
		}
		else
		{
			$validation_errors = validation_errors();
			//echo $validation_errors; die();
			//repopulate form data if validation errors are present
			if(!empty($validation_errors))
			{
				//create errors
				$data['personnel_password_error'] = form_error('personnel_password');
				$data['personnel_username_error'] = form_error('personnel_username');
				
				//repopulate fields
				$data['personnel_password'] = set_value('personnel_password');
				$data['personnel_username'] = set_value('personnel_username');
			}
			
			//populate form data on initial load of page
			else
			{
				$data['personnel_password'] = "";
				$data['personnel_username'] = "";
			}
		}
		$data['title'] = $this->site_model->display_page_title();
		
		$this->load->view('templates/login', $data);
	}
}
?>