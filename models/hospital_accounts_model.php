<?php

class Hospital_accounts_model extends CI_Model 
{
	public function get_hospital_accounts($table, $where, $per_page, $page, $order = NULL)
	{
		//retrieve all users
		$this->db->select('SUM(petty_cash.petty_cash_amount) AS total_expenditure, account.*');
		$this->db->from($table);
		$this->db->join('petty_cash', 'petty_cash.account_id = account.account_id', 'left');
		$this->db->where($where);
		$this->db->order_by('account.account_name','ASC');
		$this->db->group_by('account.account_name');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	public function add_account()
	{
		$account_name = $this->input->post('account_name');
		$data = array(
				'account_name'=>$account_name,
				'account_status'=>$this->input->post('account_status'),
				'branch_code'=>$this->session->userdata('branch_code')
			);
			
		if($this->db->insert('account', $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	public function edit_account($account_id)
	{
		$account_name = $this->input->post('account_name');
		$data = array(
				'account_name'=>$account_name,
				'account_status'=>$this->input->post('account_status'),
				'branch_code'=>$this->session->userdata('branch_code')
			);
		$this->db->where('account_id', $account_id);
		if($this->db->update('account', $data))
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