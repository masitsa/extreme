<?php

class Petty_cash_model extends CI_Model 
{
	public function calculate_balance_brought_forward($date_from)
	{
		$this->db->select('(
(SELECT SUM(petty_cash_amount) FROM petty_cash WHERE petty_cash_status = 1 AND transaction_type_id = 1 AND petty_cash_date < \''.$date_from.'\')
-
(SELECT SUM(petty_cash_amount) FROM petty_cash WHERE petty_cash_status = 1 AND transaction_type_id = 2 AND petty_cash_date < \''.$date_from.'\')
) AS balance_brought_forward', FALSE); 
		$this->db->where('petty_cash_date < \''.$date_from.'\'');
		$this->db->group_by('balance_brought_forward');
		$query = $this->db->get('petty_cash');
		$row = $query->row();
		return $row->balance_brought_forward;
	}
	
	public function get_petty_cash($where, $table)
	{
		$this->db->select('*');
		$this->db->join('account', 'petty_cash.account_id = account.account_id', 'left');
		$this->db->where($where);
		$this->db->order_by('petty_cash_date', 'ASC');
		$query = $this->db->get($table);
		
		return $query;
	}
	
	public function get_accounts()
	{
		$this->db->where('account_status = 1');
		$this->db->order_by('account_name');
		$query = $this->db->get('account');
		
		return $query;
	}
	
	public function record_petty_cash()
	{
		$array = array(
			"petty_cash_date" => $this->input->post('petty_cash_date'),
			"transaction_type_id" => $this->input->post('transaction_type_id'),
			"petty_cash_description" => $this->input->post('petty_cash_description'),
			"petty_cash_amount" => $this->input->post('petty_cash_amount'),
			"account_id" => $this->input->post('account_id'),
			'created' => date('Y-m-d H:i:s'),
			"created_by" => $this->session->userdata('personnel_id'),
			"modified_by" => $this->session->userdata('personnel_id')
		);
		
		$transaction_type_id = $this->input->post('transaction_type_id');
		
		if($this->db->insert('petty_cash', $array))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
}
?>