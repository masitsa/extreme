<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MX_Controller {
	
	function __construct()
	{
		parent:: __construct();
		$this->load->model('login_model');
	}
    
	/*
	*
	*	Login a user
	*
	*/
	public function login_user() 
	{
		//form validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|xss_clean|exists[personnel.personnel_username]');
		$this->form_validation->set_rules('password', 'Password', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			//check if user has valid login credentials
			$login_status = $this->login_model->validate_user();
				// $newdata = array(
				   //                 'login_status'     	=> TRUE,
				   //                 'personnel_fname'  	=> "Alvaro",
				   //                 'first_name'     	=> "Masitsa",
				   //                 'personnel_email'	=> "amasitsa@gmail.com",
				   //                 'personnel_id'  		=> 85
			 //               );

				// 		$this->session->set_userdata($newdata);
			// $login_status = 1;
			if($login_status == 1)
			{
				redirect('control-panel/'.$this->session->userdata('personnel_id'));
			}
			else if($login_status== 0)
			{
				// user should just try again
				redirect('change-password/'.$this->session->userdata('personnel_id').'/user');
			}
			else if($login_status =="password_strength")
			{
				// user password strength does not conform with the strength standards

				redirect('change-password/'.$this->session->userdata('personnel_id').'/user');
			}
			else if($login_status =="limit")
			{
				// user password has exceeded the limits
				redirect('change-password/'.$this->session->userdata('personnel_id').'/user');
			}
			
			else
			{
				$data['error'] = 'The email or password provided is incorrect. Please try again';
				$this->load->view('admin_login', $data);
			}
		}
		
		else
		{
			$this->load->view('admin_login');
		}
	}
	
	public function logout_user()
	{
		$this->session->sess_destroy();
		$this->session->set_userdata('front_success_message', 'Your have been signed out of your account');
		redirect('login');
	}
}
?>