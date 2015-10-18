<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/accounts/controllers/accounts.php";

class Hospital_accounts extends accounts 
{
	function __construct()
	{
		parent:: __construct();
	}
	
	public function index()
	{
		$branch_code = $this->session->userdata('search_branch_code');
		
		if(empty($branch_code))
		{
			$branch_code = $this->session->userdata('branch_code');
		}
		
		$this->db->where('branch_code', $branch_code);
		$query = $this->db->get('branch');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$branch_name = $row->branch_name;
		}
		
		else
		{
			$branch_name = '';
		}
		$where = 'account.branch_code = \''.$branch_code.'\'';
		$search = $this->session->userdata('search_hospital_accounts');
		
		$where .= $search;
		
		$table = 'account';
		$segment = 3;
		//pagination
		$this->load->library('pagination');
		$config['base_url'] = site_url().'accounts/hospital-accounts';
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
        $v_data["page"] = $page;
        $v_data["links"] = $this->pagination->create_links();
		$v_data['query'] = $this->hospital_accounts_model->get_hospital_accounts($table, $where, $config["per_page"], $page);
		$data['title'] = $v_data['title'] = 'Accounts';
		$data['content'] = $this->load->view('hospital_accounts/accounts', $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function search_hospital_accounts()
	{
		$account_name = $this->input->post('account_name');
		
		if(!empty($account_name))
		{
			$this->session->set_userdata('search_hospital_accounts', ' AND account.account_name LIKE \'%'.$account_name.'%\'');
		}
		
		redirect('accounts/hospital-accounts');
	}
	
	public function close_search_hospital_accounts()
	{
		$this->session->unset_userdata('search_hospital_accounts');
		
		redirect('accounts/hospital-accounts');
	}
	
	public function add_account()
	{
		//form validation rules
		$this->form_validation->set_rules('account_name', 'Account', 'required|xss_clean');
		$this->form_validation->set_rules('account_status', 'Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->hospital_accounts_model->add_account())
			{
				$this->session->set_userdata('success_message', 'Account added successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not add account. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('accounts/hospital-accounts');
	}
	
	public function edit_account($account_id)
	{
		//form validation rules
		$this->form_validation->set_rules('account_name', 'Account', 'required|xss_clean');
		$this->form_validation->set_rules('account_status', 'Status', 'required|xss_clean');
		
		//if form has been submitted
		if ($this->form_validation->run())
		{
			if($this->hospital_accounts_model->edit_account($account_id))
			{
				$this->session->set_userdata('success_message', 'Account edited successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Could not edit account. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('accounts/hospital-accounts');
	}
	
	public function statement($account_id, $date_from = NULL, $date_to = NULL)
	{
		$where = 'petty_cash.transaction_type_id = transaction_type.transaction_type_id AND petty_cash.account_id = '.$account_id;
		$table = 'petty_cash, transaction_type';
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where .= ' AND (petty_cash.petty_cash_date >= \''.$date_from.'\' OR \'petty_cash.petty_cash_date <= '.$date_to.'\')';
			$search_title = 'Petty cash from '.date('jS M Y', strtotime($date_from)).' to '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else if(!empty($date_from))
		{
			$where .= ' AND petty_cash.petty_cash_date = \''.$date_from.'\'';
			$search_title = 'Petty cash of '.date('jS M Y', strtotime($date_from)).' ';
		}
		
		else if(!empty($date_to))
		{
			$where .= ' AND petty_cash.petty_cash_date = \''.$date_to.'\'';
			$search_title = 'Petty cash of '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else
		{
			$where .= ' AND DATE_FORMAT(petty_cash.petty_cash_date, \'%m\') = \''.date('m').'\' AND DATE_FORMAT(petty_cash.petty_cash_date, \'%Y\') = \''.date('Y').'\'';
			$search_title = 'Petty cash for the month of '.date('M Y').' ';
		}
		
		$v_data['module'] = 1;
		$v_data['account_id'] = $account_id;
		$v_data['date_from'] = $date_from;
		$v_data['date_to'] = $date_to;
		$v_data['accounts'] = $this->petty_cash_model->get_accounts();
		$v_data['query'] = $this->petty_cash_model->get_petty_cash($where, $table);
		$v_data['title'] = $search_title;
		$data['title'] = 'Petty cash';
		$data['content'] = $this->load->view('hospital_accounts/statement', $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function record_petty_cash($account_id)
	{
		$this->form_validation->set_rules('transaction_type_id', 'Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('petty_cash_description', 'Description', 'trim|required|xss_clean');
		$this->form_validation->set_rules('petty_cash_amount', 'Amount', 'trim|required|xss_clean');
		
		// credit or debit
		$transaction_type_id = $this->input->post('transaction_type_id');
		
		if($transaction_type_id == 1)
		{
			$this->form_validation->set_rules('account_id', 'Account', 'required|xss_clean');
		}
		else
		{
			$this->form_validation->set_rules('account_id', 'Account', 'xss_clean');
		}
		
		if ($this->form_validation->run())
		{
			if($this->petty_cash_model->record_petty_cash())
			{
				$this->session->set_userdata('success_message', 'Record saved successfully');
			}
			
			else
			{
				$this->session->set_userdata('error_message', 'Unable to save. Please try again');
			}
		}
		
		else
		{
			$this->session->set_userdata('error_message', validation_errors());
		}
		
		redirect('accounts/hospital_accounts/statement/'.$account_id.'');
	}
	
	public function search_petty_cash($account_id)
	{
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
		
		if(!empty($date_from) && !empty($date_to))
		{
			redirect('accounts/hospital_accounts/statement/'.$account_id.'/'.$date_from.'/'.$date_to);
		}
		
		else if(!empty($date_from))
		{
			redirect('accounts/hospital_accounts/statement/'.$account_id.'/'.$date_from);
		}
		
		else if(!empty($date_to))
		{
			redirect('accounts/hospital_accounts/statement/'.$account_id.'/'.$date_to);
		}
		
		else
		{
			redirect('accounts/petty-cash/'.$account_id);
		}
	}
	
	public function print_petty_cash($account_id, $date_from = NULL, $date_to = NULL)
	{
		$where = 'petty_cash.transaction_type_id = transaction_type.transaction_type_id AND petty_cash.account_id = '.$account_id;
		$table = 'petty_cash, transaction_type';
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where .= ' AND (petty_cash.petty_cash_date >= \''.$date_from.'\' OR \'petty_cash.petty_cash_date <= '.$date_to.'\')';
			$search_title = 'Petty cash from '.date('jS M Y', strtotime($date_from)).' to '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else if(!empty($date_from))
		{
			$where .= ' AND petty_cash.petty_cash_date = \''.$date_from.'\'';
			$search_title = 'Petty cash of '.date('jS M Y', strtotime($date_from)).' ';
		}
		
		else if(!empty($date_to))
		{
			$where .= ' AND petty_cash.petty_cash_date = \''.$date_to.'\'';
			$search_title = 'Petty cash of '.date('jS M Y', strtotime($date_to)).' ';
		}
		
		else
		{
			$where .= ' AND DATE_FORMAT(petty_cash.petty_cash_date, \'%m\') = \''.date('m').'\' AND DATE_FORMAT(petty_cash.petty_cash_date, \'%Y\') = \''.date('Y').'\'';
			$search_title = 'Petty cash for the month of '.date('M Y').' ';
		}
		
		$v_data['contacts'] = $this->site_model->get_contacts();
		$v_data['date_from'] = $date_from;
		$v_data['date_to'] = $date_to;
		$v_data['query'] = $this->petty_cash_model->get_petty_cash($where, $table);
		$v_data['title'] = $search_title;
		$this->load->view('petty_cash/print_petty_cash', $v_data);
	}
}
?>