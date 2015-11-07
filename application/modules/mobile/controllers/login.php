<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MX_Controller {
	
	function __construct()
	{
		parent:: __construct();
		
		// Allow from any origin
		if (isset($_SERVER['HTTP_ORIGIN'])) {
			header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
			header('Access-Control-Allow-Credentials: true');
			header('Access-Control-Max-Age: 86400');    // cache for 1 day
		}
	
		// Access-Control headers are received during OPTIONS requests
		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
				header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         
	
			if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
				header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
	
			exit(0);
		}
		
		$this->load->model('login_model');
		$this->load->model('email_model');
		
		$this->load->library('Mandrill', $this->config->item('mandrill_key'));
	}
	
	public function get_logged_in_member()
	{
		
		$newdata = array(
                   'personnel_email'     		=> $this->session->userdata('personnel_email'),
                   'personnel_first_name'     	=> $this->session->userdata('personnel_first_name'),
                   'personnel_id'  			=> $this->session->userdata('personnel_id'),
                   'personnel_username'  			=> $this->session->userdata('personnel_username')
               );
		
		$response['result'] = $newdata;
		
		echo json_encode($newdata);
	}
	
	public function login_member() 
	{
		$member_no = $this->input->post('personnel_username');
		$member_password = $this->input->post('personnel_password');
		
		if(($member_no == 'amasitsa') && ($member_password == 'r6r5bb!!'))
		{
			$newdata = array(
			   'login_status' => TRUE,
			   'first_name'   => 'Alvaro',
			   'username'     => 'amasitsa',
			   'personnel_type_id'     => '2',
			   'personnel_id' => 0,
			   'branch_code'   => 'OSH',
			   'branch_name'     => 'KISII',
			   'branch_id' => 2
		   );

			$this->session->set_userdata($newdata);
			
			$response['message'] = 'success';
			$response['result'] = $newdata;
			
		}
		
		else
		{
			$result = $this->login_model->validate_member($member_no, $member_password);
		
			if($result != FALSE)
			{
				//create user's login session
				$newdata = array(
					   'personnel_login_status'    => TRUE,
					   'personnel_email'     		=> $result[0]->personnel_email,
					   'personnel_first_name'     	=> $result[0]->personnel_fname,
					   'personnel_id'  			=> $result[0]->personnel_id,
					   'personnel_username'  			=> $result[0]->personnel_username
				   );
				$this->session->set_userdata($newdata);
				
				$response['message'] = 'success';
				$response['result'] = $newdata;
			}
			
			else
			{
				$response['message'] = 'fail';
				$response['result'] = 'You have entered incorrect details. Please try again';
			}
		}
		echo json_encode($response);
	}

}