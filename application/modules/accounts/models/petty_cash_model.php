<?php

class Petty_cash_model extends CI_Model 
{
	public function get_petty_cash($where, $table)
	{
		$this->db->select('*');
		$this->db->join('account', 'petty_cash.account_id = account.account_id', 'left');
		$this->db->where($where);
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
			'created' => date('Y-m-d H:i:s'),
			"created_by" => $this->session->userdata('personnel_id'),
			"modified_by" => $this->session->userdata('personnel_id')
		);
		
		$transaction_type_id = $this->input->post('transaction_type_id');
		
		if($transaction_type_id == 1)
		{
			$array['account_id'] = $this->input->post('account_id');
		}
		
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