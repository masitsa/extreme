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
		$this->load->model('hr/personnel_model');
		
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
		
		// $data['content'] = $this->load->view('dashboard', $v_data, true);
		$data['content'] = $this->load->view('profile_page', $v_data, true);
		
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
    	$this->form_validation->set_rules('sms_key', 'SMS key', 'xss_clean');
    	$this->form_validation->set_rules('sms_user', 'SMS User', 'xss_clean');
		
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
	
	public function clickatel_sms()
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
	
	public function sms()
	{
        // This will override any configuration parameters set on the config file
		// max of 160 characters
		// to get a unique name make payment of 8700 to Africastalking/SMSLeopard
		// unique name should have a maximum of 11 characters
        $params = array('username' => 'alviem', 'apiKey' => '1f61510514421213f9566191a15caa94f3d930305c99dae2624dfb06ef54b703');  
        $this->load->library('africastalkinggateway', $params);
		
        // Send the message
		try 
		{
        	$results = $this->africastalkinggateway->sendMessage('+254770827872', 'Halo Martin. I am sending this message from the ERP');
			
			//var_dump($results);die();
			foreach($results as $result) {
				// status is either "Success" or "error message"
				echo " Number: " .$result->number;
				echo " Status: " .$result->status;
				echo " MessageId: " .$result->messageId;
				echo " Cost: "   .$result->cost."\n";
			}
		}
		
		catch(AfricasTalkingGatewayException $e)
		{
			echo "Encountered an error while sending: ".$e->getMessage();
		}
    }
}
?>