<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cc_payment extends MX_Controller 
{
	function __construct()
	{
		parent:: __construct();

		$this->load->model('admin/branches_model');
		$this->load->model('cc_payment_model');
		$this->load->model('admin/users_model');
		$this->load->model('site/site_model');
		$this->load->model('admin/admin_model');
		$this->load->model('admin/sections_model');
		$this->load->model('hr/personnel_model');

	}
    
	/*
	*
	*	Default action is to show all the personnel
	*
	*/
	public function index($order = 'created', $order_method = 'DESC') 
	{
		$branch_id = $this->session->userdata('branch_id');
		$branch_name = $this->session->userdata('branch_name');
		$branches = $this->branches_model->all_branches();
		$where = '';
		$title = $branch_name.' Consaltants And Casuals Payment history';
		
		if(($branch_id == FALSE) || (empty($branch_id)))
		{
			if($branches->num_rows() > 0)
			{
				$row = $branches->result();
				$branch_id = $row[0]->branch_id;
				$branch_name = $row[0]->branch_name;
				$where .= ' AND cc_payment.branch_id = '.$branch_id;
				$this->session->set_userdata('branch_id', $branch_id);
				$this->session->set_userdata('branch_name', $branch_name);
			}
		}
		
		else
		{
			$where .= 'cc_payment.month_id  = month.month_id AND cc_payment.branch_id = '.$branch_id;
		}
		
		//search items
		$search = $this->session->userdata('cc_payment_search');
		
		if(!empty($search))
		{
			$where .= $search;
			$title = $branch_name.' '.$this->session->userdata('cc_payment_search_title');
		}
		$table = 'cc_payment, month';
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'accounts/cc-payment'.$order.'/'.$order_method;
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
		$query = $this->cc_payment_model->get_all_cc_payment($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = $v_data['title'] = $title;
		
		$v_data['order'] = $order;
		$v_data['month'] = $this->cc_payment_model->get_months();
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['branches'] = $branches;
		$data['content'] = $this->load->view('cc_payment/cc_payment_list', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
		
	}

    
	/*
	*
	*	Default action is to show all the personnel
	*
	*/
	public function salaries($order = 'personnel_onames', $order_method = 'ASC') 
	{
		$personnel_search = $this->session->userdata('personnel_search');
		$where = 'personnel_type_id = 3 OR personnel_type_id= 5 AND personnel_status != 0';
		$table = 'personnel';
		
		if(!empty($personnel_search))
		{
			$where .= $personnel_search;
		}
		$branch_id = $this->session->userdata('branch_id');
		$branch_name = $this->session->userdata('branch_name');
		$branches = $this->branches_model->all_branches();
		
		//pagination
		$segment = 5;
		$this->load->library('pagination');
		$config['base_url'] = site_url().'accounts/salary-data/'.$order.'/'.$order_method;
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
		
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$this->pagination->initialize($config);
		
		$page = ($this->uri->segment($segment)) ? $this->uri->segment($segment) : 0;
        $v_data["links"] = $this->pagination->create_links();
		$query = $this->personnel_model->get_all_personnel($table, $where, $config["per_page"], $page, $order, $order_method);
		
		//change of order method 
		if($order_method == 'DESC')
		{
			$order_method = 'ASC';
		}
		
		else
		{
			$order_method = 'DESC';
		}
		
		$data['title'] = $v_data['title'] = $branch_name.' personnel';
		
		$v_data['order'] = $order;
		$v_data['month'] = $this->cc_payment_model->get_months();
		$v_data['order_method'] = $order_method;
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['branches'] = $branches;
		$data['content'] = $this->load->view('cc_payment/salaries', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function change_branch()
	{
		$branch_id = $this->input->post('branch_id');
		
		if(($branch_id != FALSE) && (!empty($branch_id)))
		{
			$query = $this->branches_model->get_branch($branch_id);
			
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$branch_name = $row->branch_name;
				$this->session->set_userdata('branch_id', $branch_id);
				$this->session->set_userdata('branch_name', $branch_name);
			}
		}
		
		redirect('accounts/cc_payment');
	}
	
	public function create_cc_payment()
	{
		$this->form_validation->set_rules('year', 'Year', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('month', 'Month', 'required|numeric|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$year = $this->input->post("year");
			$month = $this->input->post("month");
			$branch_id = $this->session->userdata('branch_id');
			
			if(($branch_id != FALSE) && (!empty($branch_id)))
			{
				//create cc_payment
				$cc_payment_id = $this->cc_payment_model->create_cc_payment($year, $month, $branch_id);
				if($cc_payment_id > 0)
				{
					if($this->cc_payment_model->save_salary($cc_payment_id, $branch_id))
					{
						$this->session->set_userdata('success_message', 'Payroll for '.$month.'/'.$year.' has been created successfully');
					}
					
					else
					{
						$this->session->set_userdata('error_message', 'Unable to save salary details for '.$month.'/'.$year);
					}
				}
				
				else
				{
					$this->session->set_userdata('error_message', 'Unable to create cc_payment for '.$month.'/'.$year);
				}
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Branch not selected. Please try again');
			}
		}
			
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('accounts/cc-payment');
	}
	
	public function payment_details($personnel_id)
	{
		$result = $this->personnel_model->get_personnel($personnel_id);
		
		if($result->num_rows() > 0)
		{
			$row2 = $result->row();
			
			$onames = $row2->personnel_onames;
			$fname = $row2->personnel_fname;
			$personnel_account_number = $row2->personnel_account_number;
			$personnel_nssf_number = $row2->personnel_nssf_number;
			$personnel_kra_pin = $row2->personnel_kra_pin;
			$personnel_nhif_number = $row2->personnel_nhif_number;
			$personnel_national_id_number = $row2->personnel_national_id_number;
			
			$v_data['personnel_name'] = $fname." ".$onames;
			$v_data['personnel_id'] = $personnel_id;
			$v_data['personnel_account_number'] = $personnel_account_number;
			$v_data['personnel_nssf_number'] = $personnel_nssf_number;
			$v_data['personnel_kra_pin'] = $personnel_kra_pin;
			$v_data['personnel_national_id_number'] = $personnel_national_id_number;
			$v_data['personnel_nhif_number'] = $personnel_nhif_number;
			
			$v_data['payments'] = $this->cc_payment_model->get_all_payments();
			$v_data['benefits'] = $this->cc_payment_model->get_all_benefits();
			$v_data['allowances'] = $this->cc_payment_model->get_all_allowances();
			$v_data['deductions'] = $this->cc_payment_model->get_all_deductions();
			$v_data['savings'] = $this->cc_payment_model->get_all_savings();
			$v_data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
			$v_data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
			$v_data['relief'] = $this->cc_payment_model->get_relief();
			
			$v_data['personel_payments'] = $this->cc_payment_model->get_personnel_payments($personnel_id);
			$v_data['personnel_benefits'] = $this->cc_payment_model->get_personnel_benefits($personnel_id);
			$v_data['personnel_allowances'] = $this->cc_payment_model->get_personnel_allowances($personnel_id);
			$v_data['personnel_deductions'] = $this->cc_payment_model->get_personnel_deductions($personnel_id);
			$v_data['personnel_other_deductions'] = $this->cc_payment_model->get_personnel_other_deductions($personnel_id);
			$v_data['personnel_savings'] = $this->cc_payment_model->get_personnel_savings($personnel_id);
			$v_data['personnel_loan_schemes'] = $this->cc_payment_model->get_personnel_scheme($personnel_id);
			$v_data['personnel_relief'] = $this->cc_payment_model->get_personnel_relief($personnel_id);
			
			$data['title'] = $v_data['title'] = 'Payment details for '.$v_data['personnel_name'];
			
			$data['content'] = $this->load->view("cc_payment/payment_details", $v_data, TRUE);
		}
		
		else
		{
			$data['title'] = 'Error';
			$data['content'] = '<h3 class="center-align">Unable to find personnel.</h3>';
		}
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function cc_payment_configuration()
	{
		$v_data['paye'] = $this->cc_payment_model->get_paye();
		$v_data['nhif'] = $this->cc_payment_model->get_nhif();
		$v_data['nssf'] = $this->cc_payment_model->get_nssf();
		$v_data['payments'] = $this->cc_payment_model->get_all_payments();
		$v_data['benefits'] = $this->cc_payment_model->get_all_benefits();
		$v_data['allowances'] = $this->cc_payment_model->get_all_allowances();
		$v_data['deductions'] = $this->cc_payment_model->get_all_deductions();
		$v_data['savings'] = $this->cc_payment_model->get_all_savings();
		$v_data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
		$v_data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
		$v_data['relief'] = $this->cc_payment_model->get_relief();
		$data['title'] = $v_data['title'] = 'cc_payment configuration';
		
		$data['content'] = $this->load->view("cc_payment/configuration", $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function cc_payment_configuration_old()
	{
		$v_data['allowances'] = $this->cc_payment_model->get_all_allowances();
		$v_data['deductions'] = $this->cc_payment_model->get_all_deductions();
		$v_data['savings'] = $this->cc_payment_model->get_all_savings();
		$v_data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
		$data['title'] = $v_data['title'] = 'Payroll configuration';
		
		$data['content'] = $this->load->view("cc_payment/configuration", $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function print_payslips()
	{
		$this->cc_payment_model->save_salary();
		$year = $this->input->post("year");
		$month = $this->input->post("month");
		?>
			<script type="text/javascript">
				window.open("<?php echo base_url()."data/cc_payment_list.php?year=".$year."&month=".$month?>","Popup","height=500,width=1000,,scrollbars=yes,"+"directories=yes,location=yes,menubar=yes,"+"resizable=no status=no,history=no top = 50 left = 100");
				
			</script>
        <?php
		$this->payslips($_SESSION['navigation_id'], $_SESSION['sub_navigation_id']);
	}

	public function print_cc_payment($cc_payment_id)
	{
		$where = 'personnel.personnel_id = cc_payment_item.personnel_id AND cc_payment_item.cc_payment_id = '.$cc_payment_id;		
		
		$this->db->where('cc_payment.branch_id = branch.branch_id AND cc_payment.cc_payment_id = '.$cc_payment_id);
		$branches = $this->db->get('cc_payment, branch');
		
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
		
		$data['cc_payment_id'] = $cc_payment_id;
		$data['cc_payment'] = $this->cc_payment_model->get_cc_payment($cc_payment_id);
		$data['query'] = $this->personnel_model->retrieve_cc_payment_ccpersonnel($where);
			
		$data['payments'] = $this->cc_payment_model->get_all_payments();
		$data['benefits'] = $this->cc_payment_model->get_all_benefits();
		$data['allowances'] = $this->cc_payment_model->get_all_allowances();
		$data['deductions'] = $this->cc_payment_model->get_all_deductions();
		$data['savings'] = $this->cc_payment_model->get_all_savings();
		$data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
		$data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
	
		$this->load->view('cc_payment/cc_payment', $data);
		/*$this->load->library('pdf');
		$this->pdf->load_view('cc_payment/cc_payment', $data);
		$this->pdf->render();
		$this->pdf->stream("Payroll for ".$data['month']." ".$data['year'].".pdf");*/
	}
	
	public function print_paye_report($cc_payment_id)
	{
		$where = 'personnel_status = 1 AND personnel_type_id = 3 OR personnel_type_id= 5';
		
		$this->db->where('cc_payment.branch_id = branch.branch_id AND cc_payment.cc_payment_id = '.$cc_payment_id);
		$branches = $this->db->get('cc_payment, branch');
		
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
		
		$data['payments'] = $this->cc_payment_model->get_all_payments();
		$data['benefits'] = $this->cc_payment_model->get_all_benefits();
		$data['allowances'] = $this->cc_payment_model->get_all_allowances();
		
		$data['cc_payment_id'] = $cc_payment_id;
		$data['cc_payment'] = $this->cc_payment_model->get_cc_payment($cc_payment_id);
		$data['query'] = $this->personnel_model->retrieve_cc_payment_personnel($where);
	
		$this->load->view('cc_payment/paye_report', $data);
	}
	
	public function print_nssf_report($cc_payment_id)
	{
		$where = 'personnel_status = 1 AND personnel_type_id = 3 OR personnel_type_id= 5';
		
		$this->db->where('cc_payment.branch_id = branch.branch_id AND cc_payment.cc_payment_id = '.$cc_payment_id);
		$branches = $this->db->get('cc_payment, branch');
		
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
		
		$data['cc_payment_id'] = $cc_payment_id;
		$data['cc_payment'] = $this->cc_payment_model->get_cc_payment($cc_payment_id);
		$data['query'] = $this->personnel_model->retrieve_cc_payment_personnel($where);
	
		$this->load->view('cc_payment/nssf_report', $data);
	}
	
	public function print_nhif_report($cc_payment_id)
	{
		$where = 'personnel_status = 1 AND personnel_type_id = 3 OR personnel_type_id= 5';
		
		$this->db->where('cc_payment.branch_id = branch.branch_id AND cc_payment.cc_payment_id = '.$cc_payment_id);
		$branches = $this->db->get('cc_payment, branch');
		
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
		
		$data['cc_payment_id'] = $cc_payment_id;
		$data['cc_payment'] = $this->cc_payment_model->get_cc_payment($cc_payment_id);
		$data['query'] = $this->personnel_model->retrieve_cc_payment_personnel($where);
	
		$this->load->view('cc_payment/nhif_report', $data);
	}
	
	public function print_monthly_payslips($cc_payment_id)
	{
		$where = 'personnel_status = 1 AND personnel_type_id = 3 OR personnel_type_id= 5';
		
		$this->db->where('cc_payment.branch_id = branch.branch_id AND cc_payment.cc_payment_id = '.$cc_payment_id);
		$branches = $this->db->get('cc_payment, branch');
		
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
		
		$data['cc_payment_id'] = $cc_payment_id;
		$data['cc_payment'] = $this->cc_payment_model->get_cc_payment($cc_payment_id);
		$data['query'] = $this->personnel_model->retrieve_cc_payment_personnel($where);
			
		$data['payments'] = $this->cc_payment_model->get_all_payments();
		$data['benefits'] = $this->cc_payment_model->get_all_benefits();
		$data['allowances'] = $this->cc_payment_model->get_all_allowances();
		$data['deductions'] = $this->cc_payment_model->get_all_deductions();
		$data['savings'] = $this->cc_payment_model->get_all_savings();
		$data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
		$data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
	
		$this->load->view('cc_payment/monthly_payslips', $data);
	}
	public function add_new_nhif()
	{
		$this->form_validation->set_rules('nhif_from', 'From', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('nhif_to', 'To', 'numeric|xss_clean');
		$this->form_validation->set_rules('nhif_amount', 'Amount', 'required|numeric|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$nhif_id = $this->cc_payment_model->add_new_nhif();
			if($nhif_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Amount added successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add amount. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	public function edit_nhif($nhif_id)
	{
		$this->form_validation->set_rules('nhif_from'.$nhif_id, 'From', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('nhif_to'.$nhif_id, 'To', 'numeric|xss_clean');
		$this->form_validation->set_rules('nhif_amount'.$nhif_id, 'Amount', 'required|numeric|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_nhif($nhif_id))
			{
				$this->session->set_userdata("success_message", "Amount edited successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit amount. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	function delete_nhif($nhif_id)
	{
		$table = "nhif";
		$where = array(
			"nhif_id" => $nhif_id
		);
		$this->db->where($where);
		
		if($this->db->delete($table))
		{
			$this->session->set_userdata("success_message", "Amount deleted successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete amount. Please try again");
		}
		
		redirect('accounts/cc_payment-configuration');
	}
	
	public function add_new_paye()
	{
		$this->form_validation->set_rules('paye_from', 'From', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('paye_to', 'To', 'numeric|xss_clean');
		$this->form_validation->set_rules('paye_amount', 'Amount', 'required|numeric|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$paye_id = $this->cc_payment_model->add_new_paye();
			if($paye_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Amount added successfully");
				redirect('accounts/cc_payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add amount. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	public function edit_paye($paye_id)
	{
		$this->form_validation->set_rules('paye_from'.$paye_id, 'From', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('paye_to'.$paye_id, 'To', 'numeric|xss_clean');
		$this->form_validation->set_rules('paye_amount'.$paye_id, 'Amount', 'required|numeric|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_paye($paye_id))
			{
				$this->session->set_userdata("success_message", "Amount edited successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit amount. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	function delete_paye($paye_id)
	{
		$table = "paye";
		$where = array(
			"paye_id" => $paye_id
		);
		$this->db->where($where);
		
		if($this->db->delete($table))
		{
			$this->session->set_userdata("success_message", "Amount deleted successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete amount. Please try again");
		}
		
		redirect('accounts/cc-payment-configuration');
	}
	
	public function edit_nssf($nssf_id)
	{
		$this->form_validation->set_rules('amount', 'Amount', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('percentage', 'NSSF type', 'required|numeric|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_nssf($nssf_id))
			{
				$this->session->set_userdata("success_message", "NSSF edited successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit NSSF. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	//generate p9
	public function generate_p9_form()
	{
		$personnel_id = $this->session->userdata('personnel_id');
		$result = $this->personnel_model->get_personnel($personnel_id);
		$title = 'Generate P9 Form';
		$v_data['title']= $title;
		$data['content'] = $this->load->view('cc_payment/generate_p9', $v_data,true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	//p9 forms for each individual
	public function p9_form()
	{
		$personnel_id = $this->session->userdata('personnel_id');
		$year = $this->input->post("year");
		$from_month_id = $this->input->post("from_month");
		$to_month_id = $this->input->post("to_month");
		//var_dump($_POST);die();
		$this->form_validation->set_rules('year', 'Year', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('from_month', 'Month', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('to_month', 'Month', 'required|numeric|xss_clean');
		
		if ($this->form_validation->run())
		{
			$v_data['p9_data'] = $this->cc_payment_model->get_p9_form_data($personnel_id, $from_month_id,$to_month_id,$year);
		
			$result = $this->personnel_model->get_personnel($personnel_id);
			if($result->num_rows() > 0)
			{
				$row2 = $result->row();
				
				$onames = $row2->personnel_onames;
				$fname = $row2->personnel_fname;
				$kra_pin = $row2->personnel_kra_pin;
				
				$v_data['personnel_name'] = $fname." ".$onames;
				$v_data['kra_pin'] = $kra_pin;
			}
			
			else
			{
				$v_data['content'] = '<h3 class="center-align">Unable to find personnel.</h3>';
			}
			
			$v_data['contacts'] = $this->site_model->get_contacts();
			$v_data['payments'] = $this->cc_payment_model->get_all_payments();
			$v_data['benefits'] = $this->cc_payment_model->get_all_benefits();
			$v_data['allowances'] = $this->cc_payment_model->get_all_allowances();
			$v_data['deductions'] = $this->cc_payment_model->get_all_deductions();
			$v_data['insurance'] = $this->cc_payment_model->get_insurance_relief($personnel_id);
			$v_data['savings'] = $this->cc_payment_model->get_all_savings();
			$v_data['nssf'] = $this->cc_payment_model->get_nssf();
			$v_data['paye'] = $this->cc_payment_model->get_paye();
			$v_data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
			$v_data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
			$from_month = $this->input->post('from_month');
			$to_month_id = $this->input->post('to_month');
			$year = $this->input->post('year');	
			$v_data['from_month_id'] = $from_month;
			$v_data['to_month_id'] = $to_month_id;
			$v_data['year'] = $year;
			$this->load->view('cc_payment/p9', $v_data);
		}
		
		else
		{
			$validation_errors = validation_errors();
			
			if(!empty($validation_errors))
			{
				$this->session->set_userdata('error_message', $validation_errors);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Please select range to continue');
			}
			redirect('my-account/p9');
		}
	}
	
	//p10
	public function generate_p10_form()
	{
		$personnel_id = $this->session->userdata('personnel_id');
		$result = $this->personnel_model->get_personnel($personnel_id);
		$title = 'Generate P10 Form';
		$v_data['title']= $title;
		$data['content'] = $this->load->view('cc_payment/generate_p10', $v_data,true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function p10_form()
	{
		$personnel_id = $this->session->userdata('personnel_id');
		$year = $this->input->post("year");
		$from_month_id = $this->input->post("from_month");
		$to_month_id = $this->input->post("to_month");
		//var_dump($_POST);die();
		$this->form_validation->set_rules('year', 'Year', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('from_month', 'Month', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('to_month', 'Month', 'required|numeric|xss_clean');
		
		if ($this->form_validation->run())
		{
			$v_data['contacts'] = $this->site_model->get_contacts();
			$v_data['payments'] = $this->cc_payment_model->get_all_payments();
			$v_data['benefits'] = $this->cc_payment_model->get_all_benefits();
			$v_data['allowances'] = $this->cc_payment_model->get_all_allowances();
			$v_data['deductions'] = $this->cc_payment_model->get_all_deductions();
			$v_data['insurance'] = $this->cc_payment_model->get_insurance_relief($personnel_id);
			$v_data['savings'] = $this->cc_payment_model->get_all_savings();
			$v_data['nssf'] = $this->cc_payment_model->get_nssf();
			$v_data['paye'] = $this->cc_payment_model->get_paye();
			$v_data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
			$v_data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
			$year = $this->input->post("year");
			$from_month_id = $this->input->post("from_month");
			$to_month_id = $this->input->post("to_month");	
			$v_data['from_month_id'] = $from_month;
			$v_data['to_month_id'] = $to_month_id;
			$v_data['year'] = $year;
			$this->load->view('cc_payment/p10', $v_data);
		}
		
		else
		{
			$validation_errors = validation_errors();
			
			if(!empty($validation_errors))
			{
				$this->session->set_userdata('error_message', $validation_errors);
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Please select range to continue');
			}
			redirect('accounts/p10');
		}
	}
	//bank report selection
	public function bank()
	{
		$personnel_id = $this->session->userdata('personnel_id');
		$title = 'Generate Bank Report';
		$v_data['title']= $title;
		$data['content'] = $this->load->view('cc_payment/generate_bank_report', $v_data,TRUE);
		$this->load->view('admin/templates/general_page', $data);
	}
		public function generate_bank_report($cc_payment_id)
	{
		$where = 'cc_payment_item.personnel_id = personnel.personnel_id AND cc_payment_item.cc_payment_id = '.$cc_payment_id;
		
		$this->db->where('cc_payment.branch_id = branch.branch_id AND cc_payment.cc_payment_id = '.$cc_payment_id);
		$branches = $this->db->get('cc_payment, branch');
		
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
			$file_data = $row[0]->file_data;
			if(empty($file_data))
			{
				echo 'Please generate the payroll again to view the bank report';
				die();
			}
			$where .= ' AND branch_id = '.$branch_id;
			$this->load->helper('file');
			$cc_payment_path = realpath(APPPATH . '../assets/cc_payment/');
			$file = $cc_payment_path.'\\'.$file_data.'.txt';
			$data['cc_payment_data'] = json_decode(read_file($file));
			
			$data['child_branch_name'] = $branch_name;
			$data['branch_image_name'] = $branch_image_name;
			$data['branch_id'] = $branch_id;
			$data['branch_address'] = $branch_address;
			$data['branch_post_code'] = $branch_post_code;
			$data['branch_city'] = $branch_city;
			$data['branch_phone'] = $branch_phone;
			$data['branch_email'] = $branch_email;
			$data['branch_location'] = $branch_location;
		}
		$data['cc_payment_id'] = $cc_payment_id;
		$data['cc_payment'] = $this->cc_payment_model->get_cc_payment($cc_payment_id);
		$data['query'] = $this->personnel_model->retrieve_cc_payment_personnel($where);
		$data['branches'] = $branches;	
		$data['payments'] = $this->cc_payment_model->get_all_payments();
		$data['benefits'] = $this->cc_payment_model->get_all_benefits();
		$data['allowances'] = $this->cc_payment_model->get_all_allowances();
		$data['deductions'] = $this->cc_payment_model->get_all_deductions();
		$data['savings'] = $this->cc_payment_model->get_all_savings();
		$data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
		$data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
		
		$this->load->view('cc_payment/bank_report', $data);
	}
	
	public function add_new_payment()
	{
		$this->form_validation->set_rules('payment_name', 'Payment name', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$payment_id = $this->cc_payment_model->add_new_payment();
			if($payment_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Payment added successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add payment. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	public function edit_payment($payment_id)
	{
		$this->form_validation->set_rules('payment_name'.$payment_id, 'Payment name', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_payment($payment_id))
			{
				$this->session->set_userdata("success_message", "Payment edited successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit payment. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	function delete_payment($payment_id)
	{
		$table = "payment";
		$where = array(
			"payment_id" => $payment_id
		);
		$this->db->where($where);
		
		if($this->db->delete($table))
		{
			$this->session->set_userdata("success_message", "Payment deleted successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete payment. Please try again");
		}
		
		redirect('accounts/cc-payment-configuration');
	}
	
	public function add_new_benefit()
	{
		$this->form_validation->set_rules('benefit_name', 'Benefit name', 'required|xss_clean');
		$this->form_validation->set_rules('benefit_taxable', 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$benefit_id = $this->cc_payment_model->add_new_benefit();
			if($benefit_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Benefit added successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add benefit. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	public function edit_benefit($benefit_id)
	{
		$this->form_validation->set_rules('benefit_name'.$benefit_id, 'Benefit name', 'required|xss_clean');
		$this->form_validation->set_rules('benefit_taxable'.$benefit_id, 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_benefit($benefit_id))
			{
				$this->session->set_userdata("success_message", "Benefit edited successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit benefit. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	function delete_benefit($benefit_id)
	{
		$table = "benefit";
		$where = array(
			"benefit_id" => $benefit_id
		);
		$this->db->where($where);
		
		if($this->db->delete($table))
		{
			$this->session->set_userdata("success_message", "Benefit deleted successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete benefit. Please try again");
		}
		
		redirect('accounts/cc-payment-configuration');
	}
	
	public function add_new_allowance()
	{
		$this->form_validation->set_rules('allowance_name', 'Allowance name', 'required|xss_clean');
		$this->form_validation->set_rules('allowance_taxable', 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$allowance_id = $this->cc_payment_model->add_new_allowance();
			if($allowance_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Allowance added successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add allowance. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	public function edit_allowance($allowance_id)
	{
		$this->form_validation->set_rules('allowance_name'.$allowance_id, 'Allowance name', 'required|xss_clean');
		$this->form_validation->set_rules('allowance_taxable'.$allowance_id, 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_allowance($allowance_id))
			{
				$this->session->set_userdata("success_message", "Allowance edited successfully");
				redirect('accounts/cc-paymentt-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit allowance. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	function delete_allowance($allowance_id)
	{
		$table = "allowance";
		$where = array(
			"allowance_id" => $allowance_id
		);
		$this->db->where($where);
		
		if($this->db->delete($table))
		{
			$this->session->set_userdata("success_message", "Allowance deleted successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete allowance. Please try again");
		}
		
		redirect('accounts/cc-payment-configuration');
	}
	
	public function add_new_deduction()
	{
		$this->form_validation->set_rules('deduction_name', 'Deduction name', 'required|xss_clean');
		$this->form_validation->set_rules('deduction_taxable', 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$deduction_id = $this->cc_payment_model->add_new_deduction();
			if($deduction_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Deduction added successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add deduction. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	public function edit_deduction($deduction_id)
	{
		$this->form_validation->set_rules('deduction_name'.$deduction_id, 'Deduction name', 'required|xss_clean');
		$this->form_validation->set_rules('deduction_taxable'.$deduction_id, 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_deduction($deduction_id))
			{
				$this->session->set_userdata("success_message", "Deduction edited successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit deduction. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	function delete_deduction($deduction_id)
	{
		$table = "deduction";
		$where = array(
			"deduction_id" => $deduction_id
		);
		$this->db->where($where);
		
		if($this->db->delete($table))
		{
			$this->session->set_userdata("success_message", "Deduction deleted successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete deduction. Please try again");
		}
		
		redirect('accounts/cc-payment-configuration');
	}
	
	public function add_new_other_deduction()
	{
		$this->form_validation->set_rules('other_deduction_name', 'Deduction name', 'required|xss_clean');
		$this->form_validation->set_rules('other_deduction_taxable', 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$other_deduction_id = $this->cc_payment_model->add_new_other_deduction();
			if($other_deduction_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Deduction added successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add other_deduction. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	public function edit_other_deduction($other_deduction_id)
	{
		$this->form_validation->set_rules('other_deduction_name'.$other_deduction_id, 'Deduction name', 'required|xss_clean');
		$this->form_validation->set_rules('other_deduction_taxable'.$other_deduction_id, 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_other_deduction($other_deduction_id))
			{
				$this->session->set_userdata("success_message", "Deduction edited successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit other_deduction. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	function delete_other_deduction($other_deduction_id)
	{
		$table = "other_deduction";
		$where = array(
			"other_deduction_id" => $other_deduction_id
		);
		$this->db->where($where);
		
		if($this->db->delete($table))
		{
			$this->session->set_userdata("success_message", "Deduction deleted successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete other_deduction. Please try again");
		}
		
		redirect('accounts/cc-payment-configuration');
	}
	
	public function add_new_saving()
	{
		$this->form_validation->set_rules('saving_name', 'Saving name', 'required|xss_clean');
		//$this->form_validation->set_rules('saving_taxable', 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$saving_id = $this->cc_payment_model->add_new_saving();
			if($saving_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Saving added successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add saving. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	public function edit_saving($saving_id)
	{
		$this->form_validation->set_rules('saving_name'.$saving_id, 'Saving name', 'required|xss_clean');
		//$this->form_validation->set_rules('saving_taxable'.$saving_id, 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_saving($saving_id))
			{
				$this->session->set_userdata("success_message", "Saving edited successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit saving. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	function delete_saving($saving_id)
	{
		$table = "savings";
		$where = array(
			"savings_id" => $saving_id
		);
		$this->db->where($where);
		
		if($this->db->delete($table))
		{
			$this->session->set_userdata("success_message", "Saving deleted successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete saving. Please try again");
		}
		
		redirect('accounts/cc-payment-configuration');
	}
	
	public function add_new_loan_scheme()
	{
		$this->form_validation->set_rules('loan_scheme_name', 'Loan scheme name', 'required|xss_clean');
		//$this->form_validation->set_rules('loan_scheme_taxable', 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			$loan_scheme_id = $this->cc_payment_model->add_new_loan_scheme();
			if($loan_scheme_id != FALSE)
			{
				$this->session->set_userdata("success_message", "Loan scheme added successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not add loan_scheme. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	public function edit_loan_scheme($loan_scheme_id)
	{
		$this->form_validation->set_rules('loan_scheme_name'.$loan_scheme_id, 'Loan scheme name', 'required|xss_clean');
		//$this->form_validation->set_rules('loan_scheme_taxable'.$loan_scheme_id, 'Taxable', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_loan_scheme($loan_scheme_id))
			{
				$this->session->set_userdata("success_message", "Loan scheme edited successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit loan_scheme. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	function delete_loan_scheme($loan_scheme_id)
	{
		$table = "loan_scheme";
		$where = array(
			"loan_scheme_id" => $loan_scheme_id
		);
		$this->db->where($where);
		
		if($this->db->delete($table))
		{
			$this->session->set_userdata("success_message", "Loan scheme deleted successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not delete loan_scheme. Please try again");
		}
		
		redirect('accounts/cc-payment-configuration');
	}
	
	function edit_personnel_payments($personnel_id)
	{
		$table = "personnel_payment";
		
		//Update payments
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete($table);
		
		//Add payments
		$payments = $this->cc_payment_model->get_all_payments();
		if($payments->num_rows() > 0)
		{
			foreach($payments->result() as $row2)
			{
				$payment_id = $row2->payment_id;
				$amount = $this->input->post("personnel_payment_amount".$payment_id);
				
				if(($amount >= 0) || ($amount < 0))
				{
					$items = array(
						"payment_id" => $payment_id,
						"personnel_id" => $personnel_id,
						"personnel_payment_amount" => $amount
					);
					$this->db->insert($table, $items);
				}
			}
		}
		$this->session->set_userdata("success_message", "Payment details updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	function edit_personnel_benefits($personnel_id)
	{
		$table = "personnel_benefit";
		
		//Update benefits
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete($table);
		
		//Add benefits
		$benefits = $this->cc_payment_model->get_all_benefits();
		if($benefits->num_rows() > 0)
		{
			foreach($benefits->result() as $allow)
			{
				$benefit_id = $allow->benefit_id;
				$amount = $this->input->post("personnel_benefit_amount".$benefit_id);
				
				if($amount > 0)
				{
					$items = array(
						"benefit_id" => $benefit_id,
						"personnel_id" => $personnel_id,
						"personnel_benefit_amount" => $amount
					);
					$this->db->insert($table, $items);
				}
			}
		}
		
		$this->session->set_userdata("success_message", "Payment details updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	function edit_personnel_allowances($personnel_id)
	{
		$table = "personnel_allowance";
		
		//Update allowances
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete($table);
		
		//Add allowances
		$allowances = $this->cc_payment_model->get_all_allowances();
		if($allowances->num_rows() > 0)
		{
			foreach($allowances->result() as $allow)
			{
				$allowance_id = $allow->allowance_id;
				$amount = $this->input->post("personnel_allowance_amount".$allowance_id);
		
				if($amount > 0)
				{
					$items = array(
						"allowance_id" => $allowance_id,
						"personnel_id" => $personnel_id,
						"personnel_allowance_amount" => $amount
					);
					$this->db->insert($table, $items);
				}
			}
		}
		
		$this->session->set_userdata("success_message", "Payment details updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	public function edit_personnel_deductions($personnel_id)
	{
		$table = "personnel_deduction";
		
		//Update allowances
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete($table);
		
		//Add deductions
		$deductions = $this->cc_payment_model->get_all_deductions();
		if($deductions->num_rows() > 0)
		{
			foreach($deductions->result() as $allow)
			{
				$deduction_id = $allow->deduction_id;
				$amount = $this->input->post("personnel_deduction_amount".$deduction_id);
		
				if($amount > 0)
				{
					$items = array(
						"deduction_id" => $deduction_id,
						"personnel_id" => $personnel_id,
						"personnel_deduction_amount" => $amount
					);
					$this->db->insert($table, $items);
				}
			}
		}
		
		$this->session->set_userdata("success_message", "Deductions updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	public function edit_personnel_other_deductions($personnel_id)
	{
		$table = "personnel_other_deduction";
		
		//Update allowances
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete($table);
		
		//Add other_deductions
		$other_deductions = $this->cc_payment_model->get_all_other_deductions();
		if($other_deductions->num_rows() > 0)
		{
			foreach($other_deductions->result() as $allow)
			{
				$other_deduction_id = $allow->other_deduction_id;
				$amount = $this->input->post("personnel_other_deduction_amount".$other_deduction_id);
		
				if($amount > 0)
				{
					$items = array(
						"other_deduction_id" => $other_deduction_id,
						"personnel_id" => $personnel_id,
						"personnel_other_deduction_amount" => $amount
					);
					$this->db->insert($table, $items);
				}
			}
		}
		
		$this->session->set_userdata("success_message", "Deductions updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	function edit_personnel_savings($personnel_id)
	{
		//delete savings
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete("personnel_savings");
		
		$savings = $this->cc_payment_model->get_all_savings();
		
		if($savings->num_rows() > 0)
		{
			
			foreach ($savings->result() as $row2)
			{
				//save savings opening
				$savings_id = $row2->savings_id;
				$savings_opening = $this->input->post("personnel_savings_opening".$savings_id);
				$savings = $this->input->post("personnel_savings_amount".$savings_id);
				
				$items = array(
					"personnel_savings_opening" => $savings_opening,
					"personnel_savings_amount" => $savings,
					"savings_id" => $savings_id,
					"personnel_id" => $personnel_id
				);
				
				$this->db->insert("personnel_savings", $items);
			}
		}
		$this->session->set_userdata("success_message", "Savings updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	function edit_personnel_loan_schemes($personnel_id)
	{
		//delete savings
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete("personnel_scheme");
		
		$schemes = $this->cc_payment_model->get_all_loan_schemes();
		
		if($schemes->num_rows() > 0){
			
			foreach ($schemes->result() as $row2)
			{
				//save savings opening
				$scheme_id = $row2->loan_scheme_id;
				$amount = $this->input->post("borrowings".$scheme_id);
				$monthly = $this->input->post("payments".$scheme_id);
				$interest = $this->input->post("interest".$scheme_id);
				$int = $this->input->post("interest2".$scheme_id);
				$sdate = $this->input->post("start_date".$scheme_id);
				$edate = $this->input->post("end_date".$scheme_id);
				
				$items = array(
					"loan_scheme_id" => $scheme_id,
					"personnel_scheme_repayment_sdate" => $sdate,
					"personnel_scheme_repayment_edate" => $edate,
					"personnel_scheme_monthly" => $monthly,
					"personnel_scheme_interest" => $interest,
					"personnel_scheme_amount" => $amount,
					"personnel_scheme_int" => $int,
					"personnel_id" => $personnel_id
				);
				
				$this->db->insert("personnel_scheme", $items);
			}
		}
		$this->session->set_userdata("success_message", "Loan schemes updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	public function view_payslip($personnel_id)
	{
		$result = $this->personnel_model->get_personnel($personnel_id);
		
		if($result->num_rows() > 0)
		{
			$row2 = $result->row();
			
			$onames = $row2->personnel_onames;
			$fname = $row2->personnel_fname;
			
			$v_data['personnel_name'] = $fname." ".$onames;
			$v_data['personnel_id'] = $personnel_id;
			$v_data['personnel_number'] = $row2->personnel_number;
			
			$v_data['payments'] = $this->cc_payment_model->get_all_payments();
			$v_data['benefits'] = $this->cc_payment_model->get_all_benefits();
			$v_data['allowances'] = $this->cc_payment_model->get_all_allowances();
			$v_data['deductions'] = $this->cc_payment_model->get_all_deductions();
			$v_data['savings'] = $this->cc_payment_model->get_all_savings();
			$v_data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
			$v_data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
			
			$v_data['personel_payments'] = $this->cc_payment_model->get_personnel_payments($personnel_id);
			$v_data['personnel_benefits'] = $this->cc_payment_model->get_personnel_benefits($personnel_id);
			$v_data['personnel_allowances'] = $this->cc_payment_model->get_personnel_allowances($personnel_id);
			$v_data['personnel_deductions'] = $this->cc_payment_model->get_personnel_deductions($personnel_id);
			$v_data['personnel_other_deductions'] = $this->cc_payment_model->get_personnel_other_deductions($personnel_id);
			$v_data['personnel_savings'] = $this->cc_payment_model->get_personnel_savings($personnel_id);
			$v_data['personnel_loan_schemes'] = $this->cc_payment_model->get_personnel_scheme($personnel_id);
		}
		
		else
		{
			$v_data['content'] = '<h3 class="center-align">Unable to find personnel.</h3>';
		}
		$v_data['contacts'] = $this->site_model->get_contacts();
		
		$this->load->view('cc-payment/payslips', $v_data);
	}

	public function search_cc_payment()
	{
		$year = $this->input->post("year");
		$month = $this->input->post("month");
		
		if(!empty($month) && !empty($year))
		{
			$search = " AND cc_payment.cc_payment_year = '".$year."' AND cc_payment.month_id = ".$month;
			$title = " Payroll for ".$month."/".$year;
			
			$this->session->set_userdata('cc_payment_search', $search);
			$this->session->set_userdata('cc_payment_search_title', $title);
		}
		
		redirect('accounts/cc-payment');
	}
	
	public function close_cc_payment_search()
	{
		$this->session->unset_userdata('cc_payment_search');
		$this->session->unset_userdata('cc_payment_search_title');
		
		redirect('accounts/cc_payment');
	}
	
	function deactivate_cc_payment($cc_payment_id)
	{
		$table = "cc_payment";
		$where = array(
			"cc_payment_id" => $cc_payment_id
		);
		$update_data['cc_payment_status'] = 0;
		
		$this->db->where($where);
		if($this->db->update($table, $update_data))
		{
			$this->session->set_userdata("success_message", "Payroll deactivated successfully");
		}
		
		else
		{
			$this->session->set_userdata("error_message","Could not deactivate cc_payment. Please try again");
		}
		
		redirect('accounts/cc-payment');
	}
	
	public function edit_relief($relief_id)
	{
		$this->form_validation->set_rules('relief_name'.$relief_id, 'Relief name', 'required|xss_clean');
		$this->form_validation->set_rules('relief_type'.$relief_id, 'Relief type', 'required|xss_clean');
		$this->form_validation->set_rules('relief_amount'.$relief_id, 'Amount', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_relief($relief_id))
			{
				$this->session->set_userdata("success_message", "Relief edited successfully");
				redirect('accounts/cc-payment-configuration');
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit relief. Please try again");
			}
		}
		
		$this->cc_payment_configuration();
	}
	
	public function edit_personnel_relief($personnel_id)
	{
		$table = "personnel_relief";
		
		//Update allowances
		$this->db->where("personnel_id", $personnel_id);
		$this->db->delete($table);
		
		//Add reliefs
		$relief = $this->cc_payment_model->get_relief();
		if($relief->num_rows() > 0)
		{
			foreach($relief->result() as $allow)
			{
				$relief_id = $allow->relief_id;
				$amount = $this->input->post("personnel_relief_amount".$relief_id);
		
				if($amount > 0)
				{
					$items = array(
						"relief_id" => $relief_id,
						"personnel_id" => $personnel_id,
						"personnel_relief_amount" => $amount
					);
					$this->db->insert($table, $items);
				}
			}
		}
		
		$this->session->set_userdata("success_message", "Relief updated successfully");
		redirect("accounts/payment-details/".$personnel_id);
	}
	
	public function edit_payment_details($personnel_id)
	{
		$this->form_validation->set_rules('personnel_account_number', 'Account number', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_nssf_number', 'NSSF number', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_nhif_number', 'NHIF number', 'required|xss_clean');
		$this->form_validation->set_rules('personnel_kra_pin', 'KRA pin', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->edit_payment_details($personnel_id))
			{
				$this->session->set_userdata("success_message", "Payment details edited successfully");
			}
			
			else
			{
				$this->session->set_userdata("error_message","Could not edit payment details. Please try again");
			}
		}
		
		redirect('accounts/payment-details/'.$personnel_id);
	}
	
	public function search_personnel()
	{
		$personnel_number = $this->input->post('personnel_number');
		$branch_id = $this->input->post('branch_id');
		$search_title = '';
		
		/*if(!empty($personnel_number))
		{
			$search_title .= ' member number <strong>'.$personnel_number.'</strong>';
			$personnel_number = ' AND personnel.personnel_number LIKE \'%'.$personnel_number.'%\'';
		}*/
		if(!empty($personnel_number))
		{
			$search_title .= ' personnel number <strong>'.$personnel_number.'</strong>';
			$personnel_number = ' AND personnel.personnel_number = \''.$personnel_number.'\'';
		}
		
		if(!empty($branch_id))
		{
			$search_title .= ' member type <strong>'.$branch_id.'</strong>';
			$branch_id = ' AND personnel.branch_id = \''.$branch_id.'\' ';
		}
		
		//search surname
		if(!empty($_POST['personnel_fname']))
		{
			$search_title .= ' first name <strong>'.$_POST['personnel_fname'].'</strong>';
			$surnames = explode(" ",$_POST['personnel_fname']);
			$total = count($surnames);
			
			$count = 1;
			$surname = ' AND (';
			for($r = 0; $r < $total; $r++)
			{
				if($count == $total)
				{
					$surname .= ' personnel.personnel_fname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\'';
				}
				
				else
				{
					$surname .= ' personnel.personnel_fname LIKE \'%'.mysql_real_escape_string($surnames[$r]).'%\' AND ';
				}
				$count++;
			}
			$surname .= ') ';
		}
		
		else
		{
			$surname = '';
		}
		
		//search other_names
		if(!empty($_POST['personnel_onames']))
		{
			$search_title .= ' other names <strong>'.$_POST['personnel_onames'].'</strong>';
			$other_names = explode(" ",$_POST['personnel_onames']);
			$total = count($other_names);
			
			$count = 1;
			$other_name = ' AND (';
			for($r = 0; $r < $total; $r++)
			{
				if($count == $total)
				{
					$other_name .= ' personnel.personnel_onames LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\'';
				}
				
				else
				{
					$other_name .= ' personnel.personnel_onames LIKE \'%'.mysql_real_escape_string($other_names[$r]).'%\' AND ';
				}
				$count++;
			}
			$other_name .= ') ';
		}
		
		else
		{
			$other_name = '';
		}
		
		$search = $personnel_number.$branch_id.$surname.$other_name;
		$this->session->set_userdata('personnel_search', $search);
		$this->session->set_userdata('personnel_search_title', $search_title);
		
		$this->salaries();
	}
	
	public function close_search()
	{
		$this->session->unset_userdata('personnel_search', $search);
		$this->session->unset_userdata('personnel_search_title', $search_title);
		
		redirect('accounts/salary-data');
	}
	
	public function calculate_personnel_paye($basic_pay)
	{
		echo $basic_pay.'<br/>';
		$paye = $this->cc_payment_model->calculate_paye($basic_pay);
		echo $paye;
	}
	public function all_cc_payment()
	{
		$this->session->unset_userdata('branch_id2');
		$this->session->unset_userdata('branch_name2');

		redirect('accounts/cc_payment');
	}
	
	public function cc_payment_report()
	{
		//var_dump($this->session->userdata('login_status'));die();
		
		$branch_id = $this->session->userdata('branch_id3');
		$branch_name = $this->session->userdata('branch_name3');
		$branches = $this->branches_model->all_branches();
		$where = 'month.month_id = cc_payment.month_id AND cc_payment_status = 1 AND personnel.personnel_id = cc_payment_item.personnel_id AND cc_payment.month_id ='.date('m');
		$title = $branch_name.' Payroll history';
		
		if(($branch_id == FALSE) || (empty($branch_id)))
		{
			if($branches->num_rows() > 0)
			{
				$row = $branches->result();
				$branch_id = $row[0]->branch_id;
				$branch_name = $row[0]->branch_name;
				//$where .= ' AND cc_payment.branch_id = '.$branch_id;
				$this->session->set_userdata('branch_id', $branch_id);
				$this->session->set_userdata('branch_name', $branch_name);
			}
		}
		
		else
		{
			
		}
		
		//search items
		$search = $this->session->userdata('cc_payment_report_search');
		
		if(!empty($search))
		{
			$where .= $search;
			$title = $branch_name.' '.$this->session->userdata('cc_payment_search_title');
		}
		$table = 'cc_payment, month, personnel, cc_payment_item';
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
		$query = $this->cc_payment_model->get_cc_payment_summary($where);
		//var_dump($where); die();
		$data['title'] = $v_data['title'] = $title;
		$v_data['month'] = $this->cc_payment_model->get_months();
		$v_data['query'] = $query;
		$v_data['page'] = $page;
		$v_data['branches'] = $branches;
		$v_data['payments'] = $this->cc_payment_model->get_all_payments();
		$v_data['benefits'] = $this->cc_payment_model->get_all_benefits();
		$v_data['allowances'] = $this->cc_payment_model->get_all_allowances();
		$v_data['deductions'] = $this->cc_payment_model->get_all_deductions();
		$v_data['savings'] = $this->cc_payment_model->get_all_savings();
		$v_data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
		$v_data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
		$v_data['title'] = $this->site_model->display_page_title();
		$v_data['title'] = $data['title'];
		
		$data['content'] = $this->load->view('cc_payment/cc_payment_reports', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	//display report for the branch that has been searched for
	public function search_cc_payment_reports()
	{
		$branch_id3 = $this->input->post('branch_id');
		$month2 = $this->input->post('month');
		$year = $this->input->post('year');
		$branch_name3 = $this->salary_advance_model->get_branch_name($branch_id3);
		$m_name = $this->salary_advance_model->get_month_name($month2);
		
		//var_dump($branch_id2); die();
		if(!empty($month2) && !empty($branch_id2) && !empty($year))
		{
			$search = " AND cc_payment.branch_id= '".$branch_id3."' AND cc_payment.month_id = ".$month2."'AND cc_payment.cc_payment_year ='".$year;
			$title = " Payroll Report Summary for ".$branch_name3."/".$m_name;
			
			$this->session->set_userdata('branch_id3', $branch_id3);
			$this->session->set_userdata('branch_name3', $m_name);
			$this->session->set_userdata('cc_payment_report_search', $search);
			$this->session->set_userdata('cc_payment_report_title', $title);
		}
		
		redirect('accounts/cc_payment_reports');
	}
	
	public function add_overtime_hours($personnel_id)
	{
		$this->form_validation->set_rules('personnel_overtime_hours', 'Overtime', 'required|numeric|xss_clean');
		$this->form_validation->set_rules('overtime_type', 'Type', 'required|xss_clean');
		$this->form_validation->set_rules('overtime_type_rate', 'Type', 'required|xss_clean');
		
		//if form conatins invalid data
		if ($this->form_validation->run())
		{
			if($this->cc_payment_model->update_overtime_hours($personnel_id))
			{
				$this->session->set_userdata('success_message', 'Overtime hours updated successfully');
			}
			else
			{
				$this->session->set_userdata('error_message', 'Unable to update overtime hours. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		$v_data['personnel_overtime'] = $this->cc_payment_model->calculate_overtime($personnel_id);
		$v_data['personnel'] = $this->cc_payment_model->get_personnel_details($personnel_id);
		$v_data['overtime_hours'] = $this->cc_payment_model->get_overtime_hours($personnel_id);
		$data['title'] = 'Overtime Hours';
		$v_data['title'] = $data['title'];
		$data['content'] = $this->load->view('cc_payment/add_overtime_hours', $v_data, true);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	//function for monthly branch summary
	public function month_summary($cc_payment_id,$branch_id)
	{
		$where = 'cc_payment_item.personnel_id = personnel.personnel_id AND cc_payment_item.cc_payment_id = '.$cc_payment_id.' AND personnel.branch_id = '.$branch_id;
		
		$this->db->where('cc_payment.branch_id = branch.branch_id AND cc_payment.cc_payment_id = '.$cc_payment_id);
		$branches = $this->db->get('cc_payment, branch');
		
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
			$file_data = $row[0]->file_data;
			if(empty($file_data))
			{
				echo 'Please generate the payroll again to view the bank report';
				die();
			}
			$where .= ' AND branch_id = '.$branch_id;
			$this->load->helper('file');
			$cc_payment_path = realpath(APPPATH . '../assets/cc_payment/');
			$file = $cc_payment_path.'\\'.$file_data.'.txt';
			$data['cc_payment_data'] = json_decode(read_file($file));
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
		
		$data['query'] = $this->personnel_model->retrieve_cc_payment_personnel($where);
		
		$data['payments'] = $this->cc_payment_model->get_all_payments();
		$data['benefits'] = $this->cc_payment_model->get_all_benefits();
		$data['allowances'] = $this->cc_payment_model->get_all_allowances();
		$data['deductions'] = $this->cc_payment_model->get_all_deductions();
		$data['savings'] = $this->cc_payment_model->get_all_savings();
		$data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
		$data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
		$data['relief'] = $this->cc_payment_model->get_relief();
		
		$data['basic_pay_table']  = $this->cc_payment_model->get_table_id("basic_pay");
		$data['payment_table'] = $this->cc_payment_model->get_table_id("payment");
		$data['benefit_table'] =  $this->cc_payment_model->get_table_id("benefit");
		$data['payment_table'] = $this->cc_payment_model->get_table_id("payment");
		$data['other_deduction_table'] = $this->cc_payment_model->get_table_id("other_deduction");
		$data['deduction_table'] = $this->cc_payment_model->get_table_id("deduction");
		$data['allowance_table'] = $this->cc_payment_model->get_table_id("allowance");
		$data['nssf_table']  = $this->cc_payment_model->get_table_id("nssf");
		$data['nhif_table']  = $this->cc_payment_model->get_table_id("nhif");
		$data['paye_table'] = $this->cc_payment_model->get_table_id("paye");
		$data['relief_table'] = $this->cc_payment_model->get_table_id("relief");
		$data['insurance_amount_table'] = $this->cc_payment_model->get_table_id("insurance_amount");
		$data['insurance_relief_table'] = $this->cc_payment_model->get_table_id("insurance_relief");

		
		$data['cc_payment'] = $this->cc_payment_model->get_cc_payment($cc_payment_id);
		$data['cc_payment_items'] = $this->cc_payment_model->get_cc_payment_items($cc_payment_id);
		/*$data['total_basic_pay'] = $this->cc_payment_model->get_total_basic_pay($cc_payment_id,$branch_id);
		$data['total_benefits'] = $this->cc_payment_model->get_total_benefits($cc_payment_id,$branch_id);
		$data['total_allowances'] = $this->cc_payment_model->get_total_allowances($cc_payment_id,$branch_id);
		$data['helb'] = $this->cc_payment_model->get_total_helb($cc_payment_id,$branch_id);
		$data['paye'] = $this->cc_payment_model->get_total_paye($cc_payment_id,$branch_id);*/
	
		$this->load->view('cc_payment/branch_month_summary', $data);
	}
	
	//overtime
	public function import_overtime()
	{
		$v_data['title'] = $data['title'] = $this->site_model->display_page_title();
		
		$data['content'] = $this->load->view('cc_payment/import_overtime', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	//overtime templates
	function import_overtime_template()
	{
		//export products template in excel 
		$this->cc_payment_model->import_overtime_template();
	}
	//import the cc_payment data
	function do_overtime_import()
	{
		if(isset($_FILES['import_csv']))
		{
			if(is_uploaded_file($_FILES['import_csv']['tmp_name']))
			{
				//import products from excel 
				$response = $this->cc_payment_model->import_csv_overtime($this->csv_path);
				
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
		
		$data['content'] = $this->load->view('cc_payment/import_overtime', $v_data, true);
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function send_monthly_payslips($cc_payment_id)
	{
		$where = 'cc_payment_item.personnel_id = personnel.personnel_id AND cc_payment_item.cc_payment_id = '.$cc_payment_id;
		
		$data['cc_payment_id'] = $cc_payment_id;
		$data['cc_payment'] = $this->cc_payment_model->get_cc_payment($cc_payment_id);
		$data['query'] = $this->personnel_model->retrieve_cc_payment_ccpersonnel($where);
		
		$this->db->where('cc_payment.branch_id = branch.branch_id AND cc_payment.cc_payment_id = '.$cc_payment_id);
		$branches = $this->db->get('cc_payment, branch');
			
		if($branches->num_rows() > 0)
		{
			$row = $branches->result();
			$branch_id = $row[0]->branch_id;
			$branch_name = $row[0]->branch_name;
			$month_id = $row[0]->month_id;
			$branch_image_name = $row[0]->branch_image_name;
			$branch_address = $row[0]->branch_address;
			$branch_post_code = $row[0]->branch_post_code;
			$branch_city = $row[0]->branch_city;
			$branch_phone = $row[0]->branch_phone;
			$branch_email = $row[0]->branch_email;
			$branch_location = $row[0]->branch_location;
			$month_id = $row[0]->month_id;
			$cc_payment_year = $row[0]->cc_payment_year;
			$file_data = $row[0]->file_data;
			if(empty($file_data))
			{
				echo 'Please generate the payroll again to view the bank report';
				die();
			}
			$this->load->helper('file');
			$cc_payment_path = realpath(APPPATH . '../assets/cc_payment/');
			$file = $cc_payment_path.'\\'.$file_data.'.txt';
			$data['cc_payment_data'] = json_decode(read_file($file));
			$where .= ' AND branch_id = '.$branch_id;
		}
		$personnel_emails = $this->personnel_model->retrieve_cc_payment_personnel($where);
		
		if($personnel_emails->num_rows() > 0)                                                                                      
		{
			//Create pdf files for personnel
			foreach($personnel_emails->result() as $email)
			{
				$personnel_email = $email->personnel_email;
				$personnel_id = $email->personnel_id;
				$branch_id = $email->branch_id;
				$personnel_fname = $email->personnel_fname;
				$personnel_onames = $email->personnel_onames;
				
				//check if payslip has been downloaded
				$personnel_payslip_name = $this->cc_payment_model->is_payslip_downloaded($personnel_id, $cc_payment_id);
				if($personnel_payslip_name == FALSE)
				{
					//create payslip if not created
					$personnel_payslip_name = $this->cc_payment_model->download_payslip($cc_payment_id, $personnel_id, $branches, $this->payslip_path);
					if($personnel_payslip_name != FALSE)
					{
						$message['subject'] = $month_id.' '.$cc_payment_year.' Payslip';
						$message['text'] = ' <p>Dear '.$personnel_fname.'</p>
						<p>Please find your payslip attached</p>';
						$sender_email = $branch_email;
						$shopping = "";
						$from = 'insight.nairobi@insightmanagement.co.ke'; 
						
						$button = '';
						$sender['email'] = 'insight.nairobi@insightmanagement.co.ke'; 
						$sender['name'] = 'Insight Management Consultants LTD';
						$receiver['email'] = $personnel_email;
						$receiver['name'] = $personnel_fname.' '.$personnel_onames;
						$payslip = $this->payslip_path.'/'.$personnel_payslip_name;
						
						$response = $this->email_model->send_sendgrid_mail($receiver, $sender, $message, $payslip);
						
						$this->session->set_userdata("success_message", "Email Sent successfully");
					}
				}
			}
		}
		else
		{
			$this->session->set_userdata("error_message","Could not send emails. Please try again");
		}
		redirect('accounts/cc_payment');
	}
	
	//send payslips via email
	public function _send_monthly_payslips($cc_payment_id)
	{
		//get personnel email addresses
		$personnel_emails = $this->cc_payment_model->get_personnel_emails($cc_payment_id);
		
		if($personnel_emails->num_rows() > 0)                                                                                      
		{
			//retieve each personnel_id for each email
			foreach($personnel_emails->result() as $email)
			{
				$personnel_email = $email->personnel_email;
				$personnel_id = $email->personnel_id;
				$branch_id = $email->branch_id;
				$first_name = $email->personnel_fname;
				
				$branch_email = $this->cc_payment_model->get_branch_email($branch_id);
				
				//create a link
				$link = site_url().'accounts/cc_payment/access-payslip/'.$cc_payment_id.'/'.$personnel_id;
				//pass link to the message
				
				//create the message to send 
				$message_all  = $link;
				
				$message['subject'] = 'PAYSLIP FOR THE ENDING MONTH';
				$message['text'] = ' <p>Dear '.$first_name.'</p>
				<p>Please click on the provided hyperlink below to access your payslip</p>
				<p>'.$message_all.'</p>
				';
				$sender_email = $branch_email;
				$shopping = "";
				$from = $branch_email; 
				
				$button = '';
				$sender['email'] = $sender_email; 
				$sender['name'] = $sender_email;
				$receiver['email'] = $personnel_email;
				
				
				//$response = $this->email_model->send_sendgrid_mail($receiver, $sender, $message);
				
				$this->session->set_userdata("success_message", "Email Sent successfully");
			}
	
		}
		else
		{
			$this->session->set_userdata("error_message","Could not send emails. Please try again");
		}
		redirect('accounts/cc_payment/access-payslip/'.$cc_payment_id.'/'.$personnel_id);
	}
	public function access_payslip($cc_payment_id,$personnel_id)
	{
		$where = 'cc_payment_item.personnel_id ='.$personnel_id.' AND cc_payment_item.cc_payment_id = '.$cc_payment_id;
		
		$this->db->where('cc_payment.branch_id = branch.branch_id AND cc_payment.cc_payment_id = '.$cc_payment_id);
		$branches = $this->db->get('cc_payment, branch');
		
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
			$file_data = $row[0]->file_data;
			$this->load->helper('file');
			$cc_payment_path = realpath(APPPATH . '../assets/cc_payment/');
			$file = $cc_payment_path.'\\'.$file_data.'.txt';
			$data['cc_payment_data'] = json_decode(read_file($file));
		}
		
		
		$row = $v_data['individual']->row();

		$individual_lname = $row->individual_lname;
		$individual_mname = $row->individual_mname;
		$individual_fname = $row->individual_fname;
		$individual_number = $row->individual_number;
	
 

		$data['branch_name'] = $branch_name;
		$data['branch_image_name'] = $branch_image_name;
		$data['branch_id'] = $branch_id;
		$data['branch_address'] = $branch_address;
		$data['branch_post_code'] = $branch_post_code;
		$data['branch_city'] = $branch_city;
		$data['branch_phone'] = $branch_phone;
		$data['branch_email'] = $branch_email;
		$data['branch_location'] = $branch_location;
		
		$data['cc_payment_id'] = $cc_payment_id; 
		$data['cc_payment'] = $this->cc_payment_model->get_cc_payment($cc_payment_id);
		//var_dump($where);die();
		$data['query'] = $this->personnel_model->retrieve_cc_payment_personnel($where);
			
		$data['payments'] = $this->cc_payment_model->get_all_payments();
		
		$data['cc_payment_items'] = $this->cc_payment_model->get_cc_payment_items($cc_payment_id);
		$data['benefits'] = $this->cc_payment_model->get_all_benefits();
		$data['allowances'] = $this->cc_payment_model->get_all_allowances();
		$data['deductions'] = $this->cc_payment_model->get_all_deductions();
		$data['savings'] = $this->cc_payment_model->get_all_savings();
		$data['loan_schemes'] = $this->cc_payment_model->get_all_loan_schemes();
		$data['other_deductions'] = $this->cc_payment_model->get_all_other_deductions();
		
		$data['basic_pay_table']  = $this->cc_payment_model->get_table_id("basic_pay");
		$data['payment_table'] = $this->cc_payment_model->get_table_id("payment");
		$data['benefit_table'] =  $this->cc_payment_model->get_table_id("benefit");
		$data['other_deduction_table'] = $this->cc_payment_model->get_table_id("other_deduction");
		$data['deduction_table'] = $this->cc_payment_model->get_table_id("deduction");
		$data['allowance_table'] = $this->cc_payment_model->get_table_id("allowance");
		$data['nssf_table']  = $this->cc_payment_model->get_table_id("nssf");
		$data['savings_table'] = $this->cc_payment_model->get_table_id("savings");
		$data['loan_scheme_table'] = $this->cc_payment_model->get_table_id("loan_scheme");
		$data['nhif_table']  = $this->cc_payment_model->get_table_id("nhif");
		$data['paye_table'] = $this->cc_payment_model->get_table_id("paye");
		$data['relief_table'] = $this->cc_payment_model->get_table_id("relief");
		$data['insurance_amount_table'] = $this->cc_payment_model->get_table_id("insurance_amount");
		$data['insurance_relief_table'] = $this->cc_payment_model->get_table_id("insurance_relief");
		
		$html=$this->load->view("cc_payment/access_payslip", $data, TRUE);
		//this the the PDF filename that user will get to download
        $pdfFilePath ="  Payslip statement.pdf";
		
        $this->load->library('mpdf');
		
		$this->mpdf->WriteHTML($html);
		$this->mpdf->Output();;
	}

}
?>