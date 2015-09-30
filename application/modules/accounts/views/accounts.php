<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Accounts extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();
		
		$this->load->model('site/site_model');
		$this->load->model('admin/users_model');
		$this->load->model('admin/sections_model');
		$this->load->model('admin/admin_model');
		$this->load->model('payroll_model');
		$this->load->model('hr/personnel_model');
		$this->load->model('admin/branches_model');
		$this->load->model('accounts_model');
		$this->load->model('nurse/nurse_model');
		$this->load->model('reception/reception_model');
		$this->load->model('reception/database');
		$this->load->model('medical_admin/medical_admin_model');
		$this->load->model('pharmacy/pharmacy_model');
		//$this->load->model('administration/personnel_model');
		
		$this->load->model('auth/auth_model');
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
	public function index()
	{
		$this->session->unset_userdata('all_transactions_search');
		
		$data['content'] = $this->load->view('dashboard', '', TRUE);
		
		$data['title'] = 'Dashboard';
		$data['sidebar'] = 'accounts_sidebar';
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function accounts_queue()
	{
		$where = 'visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND (visit_department.department_id = 6 OR visit_department.accounts = 0) AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND visit.close_card = 0 AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'AND visit.visit_date = \''.date('Y-m-d').'\'';
		
		$table = 'visit_department, visit, patients, visit_type';
		
		$visit_search = $this->session->userdata('visit_search');
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
		}
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/accounts/accounts_queue';
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = 3;
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$v_data['type_links'] =1;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->reception_model->get_all_ongoing_visits($table, $where, $config["per_page"], $page);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Accounts Queue';
		$v_data['title'] = 'Accounts Queue';
		$v_data['module'] = 0;
		$v_data['close_page'] = 1;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('accounts_queue', $v_data, true);
		$data['sidebar'] = 'accounts_sidebar';
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
		
	}
	public function search_visits($pager)
	{
		$visit_type_id = $this->input->post('visit_type_id');
		$surnames = $this->input->post('surname');
		$personnel_id = $this->input->post('personnel_id');
		$visit_date = $this->input->post('visit_date');
		$othernames = $this->input->post('othernames');
		
		if(!empty($visit_type_id))
		{
			$visit_type_id = ' AND visit.visit_type = '.$visit_type_id.' ';
		}
		
		if(!empty($personnel_id))
		{
			$personnel_id = ' AND visit.personnel_id = '.$personnel_id.' ';
		}
		
		if(!empty($visit_date))
		{
			$visit_date = ' AND visit.visit_date = \''.$visit_date.'\' ';
		}
		
		//search surname
		$surnames = explode(" ",$surnames);
		$total = count($surnames);
		
		$count = 1;
		$surname = ' AND (';
		for($r = 0; $r < $total; $r++)
		{
			if($count == $total)
			{
				$surname .= ' patients.patient_surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\'';
			}
			
			else
			{
				$surname .= ' patients.patient_surname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\' AND ';
			}
			$count++;
		}
		$surname .= ') ';
		
		//search other_names
		$other_names = explode(" ",$othernames);
		$total = count($other_names);
		
		$count = 1;
		$other_name = ' AND (';
		for($r = 0; $r < $total; $r++)
		{
			if($count == $total)
			{
				$other_name .= ' patients.patient_othernames LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\'';
			}
			
			else
			{
				$other_name .= ' patients.patient_othernames LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\' AND ';
			}
			$count++;
		}
		$other_name .= ') ';
		
		$search = $visit_type_id.$surname.$other_name.$visit_date.$personnel_id;
		$this->session->set_userdata('visit_accounts_search', $search);
		if($pager == 1)
		{
			redirect('accounts/accounts-queue');
		}
		else if($pager == 2)
		{
			redirect('accounts/un-closed-visits');
		}
		else if($pager == 3)
		{
			redirect('accounts/closed-visits');
		}
		else
		{
			redirect('accounts/accounts-queue');
		}
		
		
	}
	public function close_queue_search($pager)
	{
		$this->session->unset_userdata('visit_accounts_search');
		if($pager == 1)
		{
			redirect('accounts/accounts-queue');
		}
		else if($pager == 2)
		{
			redirect('accounts/un-closed-visits');
		}
		else if($pager == 3)
		{
			redirect('accounts/closed-visits');
		}
		else
		{
			redirect('accounts/accounts-queue');
		}
	}
	public function accounts_unclosed_queue()
	{
		//$where = 'visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.department_id = 6 AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND visit.close_card = 0';
		$where = 'visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND visit.close_card = 0 AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'';
		
		$table = 'visit_department, visit, patients, visit_type';
		
		$visit_search = $this->session->userdata('visit_accounts_search');
		$segment = 3;
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
			$segment = 4;
		}
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/accounts/accounts_unclosed_queue';
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
		$config['uri_segment'] = $segment;
		$config['per_page'] = 40;
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
		$v_data['type_links'] =2;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->reception_model->get_all_ongoing_visits2($table, $where, $config["per_page"], $page);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['close_page'] = 2;
		
		$data['title'] = 'Accounts Unclosed Visits';
		$v_data['title'] = 'Accounts Unclosed Visits';
		$v_data['module'] = 0;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('accounts_queue', $v_data, true);
		$data['sidebar'] = 'accounts_sidebar';
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
		
	}
	public function accounts_closed_visits()
	{
		$where = 'visit.visit_delete = 0  AND visit.patient_id = patients.patient_id AND visit.close_card = 1 ';
		$where = 'visit.visit_delete = 0 AND visit_department.visit_id = visit.visit_id AND visit_department.visit_department_status = 1 AND visit.patient_id = patients.patient_id AND visit.close_card = 1 AND visit_type.visit_type_id = visit.visit_type AND visit.branch_code = \''.$this->session->userdata('branch_code').'\'';
		
		$table = 'visit_department, visit, patients, visit_type';
		
		$visit_search = $this->session->userdata('visit_accounts_search');
		$segment = 3;
		
		if(!empty($visit_search))
		{
			$where .= $visit_search;
			$segment = 3;
		}
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'/accounts/accounts_closed_visits';
		$config['total_rows'] = $this->reception_model->count_items($table, $where);
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
        $v_data['type_links'] =3;
		$query = $this->reception_model->get_all_ongoing_visits2($table, $where, $config["per_page"], $page);
		
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		
		$data['title'] = 'Accounts closed Visits';
		$v_data['title'] = 'Accounts closed Visits';
		$v_data['module'] = 7;
		$v_data['close_page'] = 3;
		
		$v_data['type'] = $this->reception_model->get_types();
		$v_data['doctors'] = $this->reception_model->get_doctor();
		
		$data['content'] = $this->load->view('accounts_queue', $v_data, true);
		$data['sidebar'] = 'accounts_sidebar';
		
		$this->load->view('admin/templates/general_page', $data);
		// end of it
		
	}
	public function invoice($visit_id)
	{
		?>
        	<script type="text/javascript">
        		var config_url = $('#config_url').val();
				window.open(config_url+"/accounts/print_invoice/<?php echo $visit_id;?>","Popup","height=900,width=1200,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				window.location.href="<?php echo base_url("index.php/accounts/accounts_queue")?>";
			</script>
        <?php
		
		$this->accounts_queue();
	}
	
	public function payments($visit_id, $close_page = NULL)
	{
		$v_data = array('visit_id'=>$visit_id);
		
		$v_data['going_to'] = $this->accounts_model->get_going_to($visit_id);
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$v_data['patient_type'] = $patient['patient_type'];
		$v_data['patient_othernames'] = $patient['patient_othernames'];
		$v_data['patient_surname'] = $patient['patient_surname'];
		$v_data['patient_type_id'] = $patient['visit_type_id'];
		$v_data['account_balance'] = $patient['account_balance'];
		$v_data['visit_type_name'] = $patient['visit_type_name'];
		$v_data['patient_id'] = $patient['patient_id'];
		$primary_key = $patient['patient_id'];
		$v_data['close_page'] = $close_page;
		$data['content'] = $this->load->view('payments', $v_data, true);
		
		$data['title'] = 'Payments';
		$data['sidebar'] = 'accounts_sidebar';
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function make_payments($visit_id, $close_page = NULL)
	{
		$this->form_validation->set_rules('payment_method', 'Payment Method', 'trim|required|xss_clean');
		$this->form_validation->set_rules('amount_paid', 'Amount', 'trim|required|xss_clean');
		$this->form_validation->set_rules('type_payment', 'Type of payment', 'trim|required|xss_clean');
		$payment_method = $this->input->post('payment_method');
		// normal or credit note or debit note
		$type_payment = $this->input->post('type_payment');
		
		// Normal
		if($type_payment == 1)
		{
			$this->form_validation->set_rules('service_id', 'Service', 'xss_clean');
			if(!empty($payment_method))
			{
				if($payment_method == 1)
				{
					// check for cheque number if inserted
					$this->form_validation->set_rules('cheque_number', 'Cheque Number', 'trim|required|xss_clean');
				}
				else if($payment_method == 3)
				{
					// check for insuarance number if inserted
					$this->form_validation->set_rules('insuarance_number', 'Insurance Number', 'trim|required|xss_clean');
				}
				else if($payment_method == 5)
				{
					//  check for mpesa code if inserted
					$this->form_validation->set_rules('mpesa_code', 'Amount', 'trim|required|xss_clean');
				}
			}
		}
		else if($type_payment == 2)
		{
			// debit note
			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('payment_service_id', 'Service', 'required|xss_clean');
		}
		else if($type_payment == 3)
		{
			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
			$this->form_validation->set_rules('payment_service_id', 'Service', 'required|xss_clean');
		}
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			
			if($type_payment == 3 || $type_payment == 2)
			{
				$username=$this->input->post('username');
				$password=$this->input->post('password');
				// check if the username and password is for an administrator
				$checker_response = $this->accounts_model->check_admin_person($username, $password);
				// end of checker function
				if(($checker_response > 0))
				{
					$this->accounts_model->receipt_payment($visit_id, $checker_response);
				}
				else
				{
					$this->session->set_userdata("error_message","Seems like you dont have the priviledges to effect this event. Please contact your administrator.");
				}
			}
			else
			{
				$this->accounts_model->receipt_payment($visit_id);
			}
			
			
			redirect('accounts/payments/'.$visit_id.'/'.$close_page);
		}
		else
		{
			$this->session->set_userdata("error_message", validation_errors());
			redirect('accounts/payments/'.$visit_id.'/'.$close_page);
		}
	}
	
	public function add_billing($visit_id, $close_page = NULL)
	{
		$this->form_validation->set_rules('billing_method_id', 'Billing Method', 'required|numeric');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->accounts_model->add_billing($visit_id))
			{
				$this->session->set_userdata('success_message', 'Billing method successfully added');
			}
			else
			{
				$this->session->set_userdata("error_message","Unable to add billing method. Please try again");
			}
		}
		else
		{
			$this->session->set_userdata("error_message","Fill in the fields");
		}
		
		redirect('accounts/payments/'.$visit_id.'/'.$close_page);
	}
	
	public function print_invoice($visit_id)
	{
		$this->accounts_model->receipt($visit_id);
	}
	
	public function print_invoice_old($visit_id)
	{
		$this->accounts_model->receipt($visit_id, TRUE);
	}
	
	public function print_invoice_new($visit_id,$department_name = NULL)
	{
		$data = array('visit_id'=>$visit_id);
		$data['contacts'] = $this->site_model->get_contacts();
		
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$data['patient'] = $patient;
		$this->load->view('invoice', $data);
		
	}
	public function print_receipt_new($visit_id)
	{
		$data = array('visit_id'=>$visit_id);
		$data['contacts'] = $this->site_model->get_contacts();
		
		$patient = $this->reception_model->patient_names2(NULL, $visit_id);
		$data['patient'] = $patient;
		$this->load->view('receipt', $data);
	}
	public function print_single_receipt($payment_id)
	{
		$data = array('payment_id' => $payment_id);
		$data['contacts'] = $this->site_model->get_contacts();
		$data['receipt_payment_id'] = $payment_id;
		
		$patient = $this->reception_model->patient_names3($payment_id);
		$data['patient'] = $patient;
		$this->load->view('single_receipt', $data);
	}
	public function bulk_close_visits($page)
	{
		$total_visits = sizeof($_POST['visit']);
		
		//check if any checkboxes have been ticked
		if($total_visits > 0)
		{	
			for($r = 0; $r < $total_visits; $r++)
			{	
				$visit = $_POST['visit'];
				$visit_id = $visit[$r]; 
				
				if($this->accounts_model->end_visit($visit_id))
				{
					$this->session->set_userdata('success_message', 'Visits ended successfully');
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Unable to end visits');
				}
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Please select visits to terminate first');
		}
		
		redirect('accounts/accounts_unclosed_queue/'.$page);
	}
	
	public function send_to_department($visit_id)
	{
		$data['accounts'] = 1;
		$this->db->where('visit_department.visit_department_status = 1 AND visit_department.visit_id = '.$visit_id);
		if($this->db->update('visit_department', $data))
		{
			$this->session->set_userdata('success_message', 'Patient has been sent');
			redirect('accounts/accounts-queue');
		}
		
		else
		{
			$this->session->set_userdata('error_message', 'Patient could not be sent');
			redirect('accounts/payments/'.$visit_id.'/1');
		}
	}
}
?>