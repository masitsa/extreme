<?php   if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "./application/modules/accounts/controllers/accounts.php";

class Petty_cash extends accounts 
{
	function __construct()
	{
		parent:: __construct();
	}
	
	public function index($date_from = NULL, $date_to = NULL)
	{
		$where = 'petty_cash.transaction_type_id = transaction_type.transaction_type_id';
		$table = 'petty_cash, transaction_type';
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where .= ' AND (petty_cash.petty_cash_date >= \''.$date_from.'\' AND petty_cash.petty_cash_date <= \''.$date_to.'\')';
			//$where .= ' AND petty_cash.petty_cash_date BETWEEN \''.$date_from.'\' AND \'petty_cash.petty_cash_date <= '.$date_to.'\')';
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
			$date_from = date('Y-m-01');
			$where .= ' AND DATE_FORMAT(petty_cash.petty_cash_date, \'%m\') = \''.date('m').'\' AND DATE_FORMAT(petty_cash.petty_cash_date, \'%Y\') = \''.date('Y').'\'';
			$search_title = 'Petty cash for the month of '.date('M Y').' ';
		}
		
		$v_data['balance_brought_forward'] = $this->petty_cash_model->calculate_balance_brought_forward($date_from);
		
		$v_data['date_from'] = $date_from;
		$v_data['date_to'] = $date_to;
		$v_data['accounts'] = $this->petty_cash_model->get_accounts();
		$v_data['query'] = $this->petty_cash_model->get_petty_cash($where, $table);
		$v_data['title'] = $search_title;
		$data['title'] = 'Petty cash';
		$data['content'] = $this->load->view('petty_cash/statement', $v_data, TRUE);
		
		$this->load->view('admin/templates/general_page', $data);
	}
	
	public function record_petty_cash()
	{
		$this->form_validation->set_rules('transaction_type_id', 'Type', 'trim|required|xss_clean');
		$this->form_validation->set_rules('account_id', 'Account', 'required|xss_clean');
		$this->form_validation->set_rules('petty_cash_description', 'Description', 'trim|required|xss_clean');
		$this->form_validation->set_rules('petty_cash_amount', 'Amount', 'trim|required|xss_clean');
		$this->form_validation->set_rules('petty_cash_date', 'Transaction date', 'required|xss_clean');
		
		// credit or debit
		$transaction_type_id = $this->input->post('transaction_type_id');
		
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
		
		redirect('accounts/petty-cash');
	}
	
	public function search_petty_cash()
	{
		$date_from = $this->input->post('date_from');
		$date_to = $this->input->post('date_to');
		
		if(!empty($date_from) && !empty($date_to))
		{
			redirect('accounts/petty-cash/'.$date_from.'/'.$date_to);
		}
		
		else if(!empty($date_from))
		{
			redirect('accounts/petty-cash/'.$date_from);
		}
		
		else if(!empty($date_to))
		{
			redirect('accounts/petty-cash/'.$date_to);
		}
		
		else
		{
			redirect('accounts/petty-cash');
		}
	}
	
	public function print_petty_cash($date_from = NULL, $date_to = NULL)
	{
		$where = 'petty_cash.transaction_type_id = transaction_type.transaction_type_id';
		$table = 'petty_cash, transaction_type';
		
		if(!empty($date_from) && !empty($date_to))
		{
			$where .= ' AND (petty_cash.petty_cash_date >= \''.$date_from.'\' AND petty_cash.petty_cash_date <= \''.$date_to.'\')';
			//$where .= ' AND petty_cash.petty_cash_date BETWEEN \''.$date_from.'\' AND \'petty_cash.petty_cash_date <= '.$date_to.'\')';
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
			$date_from = date('Y-m-01');
			$where .= ' AND DATE_FORMAT(petty_cash.petty_cash_date, \'%m\') = \''.date('m').'\' AND DATE_FORMAT(petty_cash.petty_cash_date, \'%Y\') = \''.date('Y').'\'';
			$search_title = 'Petty cash for the month of '.date('M Y').' ';
		}
		
		$v_data['balance_brought_forward'] = $this->petty_cash_model->calculate_balance_brought_forward($date_from);
		
		$v_data['contacts'] = $this->site_model->get_contacts();
		$v_data['date_from'] = $date_from;
		$v_data['date_to'] = $date_to;
		$v_data['query'] = $this->petty_cash_model->get_petty_cash($where, $table);
		$v_data['title'] = $search_title;
		$this->load->view('petty_cash/print_petty_cash', $v_data);
	}
}
?>