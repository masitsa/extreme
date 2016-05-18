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
		$this->load->model('users_model');
		$this->load->model('branches_model');
		$this->load->model('sections_model');
		$this->load->model('hr/personnel_model');
		$this->load->model('accounts/payroll_model');
		
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
		$branch_id = $this->session->userdata('branch_id');
		$branch_name = $this->session->userdata('branch_name');
		$branches = $this->branches_model->all_branches();
		$where = 'month.month_id = payroll.month_id AND payroll_status = 1 ';
		$title = $branch_name.' Payroll history';
		
		if(($branch_id == FALSE) || (empty($branch_id)))
		{
			if($branches->num_rows() > 0)
			{
				$row = $branches->result();
				$branch_id = $row[0]->branch_id;
				$branch_name = $row[0]->branch_name;
				$where .= ' AND payroll.branch_id = '.$branch_id;
				$this->session->set_userdata('branch_id', $branch_id);
				$this->session->set_userdata('branch_name', $branch_name);
			}
		}
		
		else
		{
			$where .= ' AND payroll.branch_id = '.$branch_id;
		}
		
		//search items
		$search = $this->session->userdata('payroll_search');
		
		if(!empty($search))
		{
			$where .= $search;
			$title = $branch_name.' '.$this->session->userdata('payroll_search_title');
		}
		$table = 'payroll, month';
		//pagination
		$segment = 2;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'dashboard';
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
		$query = $this->payroll_model->get_all_payrolls($table, $where, $config["per_page"], $page, $order='payroll.month_id', $order_method = 'DESC');
		
		$data['title'] = $v_data['title'] = $title;
		$personnel_id = $this->session->userdata('personnel_id');
		$v_data['leave'] = $this->personnel_model->get_personnel_leave($personnel_id);
		$v_data['leave_types'] = $this->personnel_model->get_leave_types();
		$v_data['personnel_query'] = $this->personnel_model->get_personnel($personnel_id);
		$v_data['month'] = $this->payroll_model->get_months();
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['branches'] = $branches;
		$v_data['payments'] = $this->payroll_model->get_all_payments();
		$v_data['benefits'] = $this->payroll_model->get_all_benefits();
		$v_data['allowances'] = $this->payroll_model->get_all_allowances();
		$v_data['deductions'] = $this->payroll_model->get_all_deductions();
		$v_data['savings'] = $this->payroll_model->get_all_savings();
		$v_data['loan_schemes'] = $this->payroll_model->get_all_loan_schemes();
		$v_data['other_deductions'] = $this->payroll_model->get_all_other_deductions();
		$v_data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		
		$data['content'] = $this->load->view('profile_page', $v_data, true);
		
		$this->load->view('templates/general_page', $data);
	}
    
	
	
	//print individual payslip
	public function payslip_details($payroll_id)
	{
		$where = 'personnel_status = 1 AND personnel_type_id = 1';
		
		$this->db->where('payroll.branch_id = branch.branch_id AND payroll.payroll_id = '.$payroll_id);
		$branches = $this->db->get('payroll, branch');
		
		if($branches->num_rows() > 0)
		{
			$row = $branches->result();
			$branch_id = $row[0]->branch_id;
			$branch_name = $row[0]->branch_name;
			$branch_image_name = $row[0]->branch_image_name;
			$branch_address = $row[0]->branch_address;
			$branch_post_code = $row[0]->branch_post_code;
			$branch_city = $row[0]->branch_city;
			$branch_phone = $row[0]->branch_phone;
			$branch_email = $row[0]->branch_email;
			$branch_location = $row[0]->branch_location;
			$where .= ' AND branch_id = '.$branch_id;
		}
		
		$data['branch_name'] = $branch_name;
		$data['branch_image_name'] = $branch_image_name;
		$data['branch_id'] = $branch_id;
		$data['branch_address'] = $branch_address;
		$data['branch_post_code'] = $branch_post_code;
		$data['branch_city'] = $branch_city;
		$data['branch_phone'] = $branch_phone;
		$data['branch_email'] = $branch_email;
		$data['branch_location'] = $branch_location;
		
		$data['payroll_id'] = $payroll_id;
		$data['payroll'] = $this->payroll_model->get_payroll($payroll_id);
		$data['query'] = $this->personnel_model->retrieve_payroll_personnel($where);
			
		$data['payments'] = $this->payroll_model->get_all_payments();
		$data['benefits'] = $this->payroll_model->get_all_benefits();
		$data['allowances'] = $this->payroll_model->get_all_allowances();
		$data['deductions'] = $this->payroll_model->get_all_deductions();
		$data['savings'] = $this->payroll_model->get_all_savings();
		$data['loan_schemes'] = $this->payroll_model->get_all_loan_schemes();
		$data['other_deductions'] = $this->payroll_model->get_all_other_deductions();

		$this->load->view('accounts/payroll/individual_payslip', $data);
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