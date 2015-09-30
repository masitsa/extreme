<?php

class Suppliers_model extends CI_Model 
{	
	/*
	*	Retrieve all suppliers
	*
	*/
	public function all_suppliers()
	{
		$this->db->where('supplier_status = 1');
		$this->db->order_by('supplier_name');
		$query = $this->db->get('supplier');
		
		return $query;
	}
	/*
	*	Retrieve latest supplier
	*
	*/
	public function latest_supplier()
	{
		$this->db->limit(1);
		$this->db->order_by('created', 'DESC');
		$query = $this->db->get('supplier');
		
		return $query;
	}
	
	
	/*
	*	Retrieve all suppliers
	*	@param string $table
	* 	@param string $where
	*
	*/
	public function get_all_suppliers($table, $where, $per_page, $page)
	{
		//retrieve all users
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by('supplier_name');
		$query = $this->db->get('', $per_page, $page);
		
		return $query;
	}
	
	/*
	*	Add a new supplier
	*	@param string $image_name
	*
	*/
	public function add_supplier()
	{
		$data = array(
				'supplier_name'=>ucwords(strtolower($this->input->post('supplier_name'))),
				'supplier_phone'=>$this->input->post('supplier_phone'),
				'supplier_email'=>$this->input->post('supplier_email'),
				'supplier_physical_address'=>$this->input->post('supplier_physical_address'),
				'supplier_contact_person'=>$this->input->post('supplier_contact_person'),
				'supplier_status'=>$this->input->post('supplier_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'branch_code'=>$this->session->userdata('branch_code')
			);
			
		if($this->db->insert('supplier', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Update an existing supplier
	*	@param string $image_name
	*	@param int $supplier_id
	*
	*/
	public function update_supplier($supplier_id)
	{
		$data = array(
				'supplier_name'=>ucwords(strtolower($this->input->post('supplier_name'))),
				'supplier_phone'=>$this->input->post('supplier_phone'),
				'supplier_email'=>$this->input->post('supplier_email'),
				'supplier_physical_address'=>$this->input->post('supplier_physical_address'),
				'supplier_contact_person'=>$this->input->post('supplier_contact_person'),
				'supplier_status'=>$this->input->post('supplier_status'),
				'created'=>date('Y-m-d H:i:s'),
				'created_by'=>$this->session->userdata('personnel_id'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'branch_code'=>$this->session->userdata('branch_code')
			);
			
		$this->db->where('branch_code = "'.$this->session->userdata('branch_code').'"AND supplier_id = '.$supplier_id);
		if($this->db->update('supplier', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	
	/*
	*	get a single supplier's details
	*	@param int $supplier_id
	*
	*/
	public function get_supplier($supplier_id)
	{
		//retrieve all users
		$this->db->from('supplier');
		$this->db->select('*');
		$this->db->where('supplier_id = '.$supplier_id);
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single supplier's details
	*	@param int $supplier_id
	*
	*/
	public function get_supplier_by_name($supplier_name)
	{
		//retrieve all users
		$this->db->from('supplier');
		$this->db->select('*');
		$this->db->where('supplier_name = \''.$supplier_name.'\'');
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	Delete an existing supplier
	*	@param int $supplier_id
	*
	*/
	public function delete_supplier($supplier_id)
	{
		if($this->db->delete('supplier', array('supplier_id' => $supplier_id)))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Activate a deactivated supplier
	*	@param int $supplier_id
	*
	*/
	public function activate_supplier($supplier_id)
	{
		$data = array(
				'supplier_status' => 1
			);
		$this->db->where('supplier_id', $supplier_id);
		
		if($this->db->update('supplier', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated supplier
	*	@param int $supplier_id
	*
	*/
	public function deactivate_supplier($supplier_id)
	{
		$data = array(
				'supplier_status' => 0
			);
		$this->db->where('supplier_id', $supplier_id);
		
		if($this->db->update('supplier', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
}
?>