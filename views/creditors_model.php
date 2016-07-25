<?php

class Creditors_model extends CI_Model 
{	
	
	/*
	*	Add a new creditor
	*
	*/
	public function add_creditor()
	{
		$data = array(
			'creditor_name'=>$this->input->post('creditor_name'),
			'creditor_email'=>$this->input->post('creditor_email'),
			'creditor_phone'=>$this->input->post('creditor_phone'),
			'creditor_location'=>$this->input->post('creditor_location'),
			'creditor_building'=>$this->input->post('creditor_building'),
			'creditor_floor'=>$this->input->post('creditor_floor'),
			'creditor_address'=>$this->input->post('creditor_address'),
			'creditor_post_code'=>$this->input->post('creditor_post_code'),
			'creditor_city'=>$this->input->post('creditor_city'),
			'creditor_contact_person_name'=>$this->input->post('creditor_contact_person_name'),
			'creditor_contact_person_onames'=>$this->input->post('creditor_contact_person_onames'),
			'creditor_contact_person_phone1'=>$this->input->post('creditor_contact_person_phone1'),
			'creditor_contact_person_phone2'=>$this->input->post('creditor_contact_person_phone2'),
			'creditor_contact_person_email'=>$this->input->post('creditor_contact_person_email'),
			'creditor_description'=>$this->input->post('creditor_description'),
			'branch_code'=>$this->session->userdata('branch_code'),
			'created_by'=>$this->session->userdata('creditor_id'),
			'modified_by'=>$this->session->userdata('creditor_id'),
			'created'=>date('Y-m-d H:i:s')
		);
		
		if($this->db->insert('creditor', $data))
		{
			return $this->db->insert_id();
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing creditor
	*	@param string $image_name
	*	@param int $creditor_id
	*
	*/
	public function edit_creditor($creditor_id)
	{
		$data = array(
			'creditor_name'=>$this->input->post('creditor_name'),
			'creditor_email'=>$this->input->post('creditor_email'),
			'creditor_phone'=>$this->input->post('creditor_phone'),
			'creditor_location'=>$this->input->post('creditor_location'),
			'creditor_building'=>$this->input->post('creditor_building'),
			'creditor_floor'=>$this->input->post('creditor_floor'),
			'creditor_address'=>$this->input->post('creditor_address'),
			'creditor_post_code'=>$this->input->post('creditor_post_code'),
			'creditor_city'=>$this->input->post('creditor_city'),
			'creditor_contact_person_name'=>$this->input->post('creditor_contact_person_name'),
			'creditor_contact_person_onames'=>$this->input->post('creditor_contact_person_onames'),
			'creditor_contact_person_phone1'=>$this->input->post('creditor_contact_person_phone1'),
			'creditor_contact_person_phone2'=>$this->input->post('creditor_contact_person_phone2'),
			'creditor_contact_person_email'=>$this->input->post('creditor_contact_person_email'),
			'creditor_description'=>$this->input->post('creditor_description'),
			'modified_by'=>$this->session->userdata('creditor_id'),
		);
		
		$this->db->where('creditor_id', $creditor_id);
		if($this->db->update('creditor', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	get a single creditor's details
	*	@param int $creditor_id
	*
	*/
	public function get_creditor($creditor_id)
	{
		//retrieve all users
		$this->db->from('creditor');
		$this->db->select('*');
		$this->db->where('creditor_id = '.$creditor_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Retrieve all creditor
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_creditors($table, $where, $per_page, $page, $order = 'creditor_name', $order_method = 'ASC')
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	public function calculate_balance_brought_forward($date_from)
	{
		$this->db->select('(
(SELECT SUM(creditor_account_amount) FROM creditor_account WHERE creditor_account_status = 1 AND transaction_type_id = 1 AND creditor_account_date < \''.$date_from.'\')
-
(SELECT SUM(creditor_account_amount) FROM creditor_account WHERE creditor_account_status = 1 AND transaction_type_id = 2 AND creditor_account_date < \''.$date_from.'\')
) AS balance_brought_forward', FALSE); 
		$this->db->where('creditor_account_date < \''.$date_from.'\'');
		$this->db->group_by('balance_brought_forward');
		$query = $this->db->get('creditor_account');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			return $row->balance_brought_forward;
		}
		
		else
		{
			return 0;
		}
	}
	
	public function get_creditor_account($where, $table)
	{
		$this->db->select('*');
		//$this->db->join('account', 'creditor_account.account_id = account.account_id', 'left');
		$this->db->where($where);
		$this->db->order_by('creditor_account_date', 'ASC');
		$query = $this->db->get($table);
		
		return $query;
	}
	
	public function record_creditor_account($creditor_id)
	{
		$array = array(
			"creditor_account_date" => $this->input->post('creditor_account_date'),
			"transaction_type_id" => $this->input->post('transaction_type_id'),
			"creditor_account_description" => $this->input->post('creditor_account_description'),
			"creditor_account_amount" => $this->input->post('creditor_account_amount'),
			"creditor_id" => $this->input->post('creditor_id'),
			'created' => date('Y-m-d H:i:s'),
			"created_by" => $this->session->userdata('personnel_id'),
			"modified_by" => $this->session->userdata('personnel_id')
		);
		
		$transaction_type_id = $this->input->post('transaction_type_id');
		
		if($this->db->insert('creditor_account', $array))
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
}