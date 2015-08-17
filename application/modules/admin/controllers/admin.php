<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();
		
		$this->load->model('admin_model');
		$this->load->model('auth/auth_model');
		$this->load->model('site/site_model');
		$this->load->model('reports_model');
		$this->load->model('sections_model');
		
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
		
		$this->load->view('templates/general_page', $data);
	}
    
	/*
	*
	*	Edit admin configuration
	*
	*/
	public function configuration()
	{
		$data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		$v_data['configuration'] = $this->admin_model->get_configuration();
		
		$data['content'] = $this->load->view('configuration', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}

	public function edit_configuration($configuration_id)
    {
    	$this->form_validation->set_rules('mandrill', 'Email API key', 'xss_clean');
		
		//if form conatins valid data
		if ($this->form_validation->run())
		{
			if($this->admin_model->edit_configuration($configuration_id))
			{
				$this->session->set_userdata("success_message", "Configuration updated successfully");
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not update configuration. Please try again");
			}
		}
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
		}
		
		redirect('administration/configuration');
    }
	
	public function sms()
	{
        // This will override any configuration parameters set on the config file
        $params = array('user' => 'amasitsa', 'password' => 'GRICWfQAfOEAHK', 'api_id' => '3557139');  
        $this->load->library('clickatel', $params);

        // Send the message
        $this->clickatel->send_sms('+254726149351', 'This is a test message');

        // Get the reply
        echo $this->clickatel->last_reply();

        // Send message to multiple numbers
        /*$numbers = array('351965555555', '351936666666', '351925555555');
        $this->clickatel->send_sms($numbers, 'This is a test message');*/
    }
}
?>